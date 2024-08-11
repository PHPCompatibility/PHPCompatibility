<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;

/**
 * Detect calls to xml_set_*_handler() functions which try to unset the handler by passing an empty string.
 *
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/deprecations_php_8_4#xml_set_object_and_xml_set_handler_with_string_method_names
 *
 * @since 10.0.0
 */
final class RemovedXmlSetHandlerCallbackUnsetSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, array<int, string>>
     */
    protected $targetFunctions = [
        'xml_set_character_data_handler' => [
            2 => 'handler',
        ],
        'xml_set_default_handler' => [
            2 => 'handler',
        ],
        'xml_set_element_handler' => [
            2 => 'start_handler',
            3 => 'end_handler',
        ],
        'xml_set_end_namespace_decl_handler' => [
            2 => 'handler',
        ],
        'xml_set_external_entity_ref_handler' => [
            2 => 'handler',
        ],
        'xml_set_notation_decl_handler' => [
            2 => 'handler',
        ],
        'xml_set_processing_instruction_handler' => [
            2 => 'handler',
        ],
        'xml_set_start_namespace_decl_handler' => [
            2 => 'handler',
        ],
        'xml_set_unparsed_entity_decl_handler' => [
            2 => 'handler',
        ],
    ];

    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrAbove('8.4') === false);
    }

    /**
     * Process the parameters of a matched function.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File                  $phpcsFile    The file being scanned.
     * @param int                                          $stackPtr     The position of the current token in the stack.
     * @param string                                       $functionName The token content (function name) which was matched.
     * @param array<int|string, array<string, int|string>> $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $tokens       = $phpcsFile->getTokens();
        $functionLC   = \strtolower($functionName);
        $functionInfo = $this->targetFunctions[$functionLC];

        foreach ($functionInfo as $offset => $paramName) {
            $targetParam = PassedParameters::getParameterFromStack($parameters, $offset, $paramName);
            if ($targetParam === false) {
                continue;
            }

            $callback = '';
            for ($i = $targetParam['start']; $i <= $targetParam['end']; $i++) {
                if (isset(Tokens::$emptyTokens[$tokens[$i]['code']])) {
                    continue;
                }

                if ($tokens[$i]['code'] === \T_STRING_CONCAT
                    || $tokens[$i]['code'] === \T_START_HEREDOC
                    || $tokens[$i]['code'] === \T_END_HEREDOC
                    || $tokens[$i]['code'] === \T_START_NOWDOC
                    || $tokens[$i]['code'] === \T_END_NOWDOC
                ) {
                    // Ignore.
                    continue;
                }

                if ($tokens[$i]['code'] === \T_CONSTANT_ENCAPSED_STRING) {
                    $callback .= TextStrings::stripQuotes($tokens[$i]['content']);
                    continue;
                }

                if ($tokens[$i]['code'] === \T_HEREDOC || $tokens[$i]['code'] === \T_NOWDOC) {
                    $callback .= $tokens[$i]['content'];
                    continue;
                }

                // Anything else, variable/function call etc. Ignore. Either valid callback or undetermined.
                continue 2;
            }

            // Allow for multi-line empty strings.
            $callback = TextStrings::stripQuotes($callback);
            $callback = \trim($callback);
            if ($callback !== '') {
                continue;
            }

            $firstNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $targetParam['start'], ($targetParam['end'] + 1), true);

            $msg  = 'Passing an empty string to reset the $%s for %s() is deprecated since PHP 8.4. Pass `null` instead.';
            $code = 'Deprecated';
            $data = [
                $paramName,
                $functionLC,
            ];

            $phpcsFile->addWarning($msg, $firstNonEmpty, $code, $data);
        }
    }
}
