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

/**
 * Detect calls to dba_key_split() passing `false` or `null` as the key.
 *
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/deprecations_php_8_4#deprecate_passing_null_and_false_to_dba_key_split
 * @link https://www.php.net/dba_key_split
 *
 * @since 10.0.0
 */
final class RemovedDbaKeySplitNullFalseSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'dba_key_split' => true,
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
        $targetParam = PassedParameters::getParameterFromStack($parameters, 1, 'key');
        if ($targetParam === false) {
            return;
        }

        /*
         * First try to determine if the parameter contents matches a function call to a dba_*key() function.
         * We do this by basically getting a string representation of the parameter, but without
         * whatever tokens are between parentheses.
         */
        $tokens                       = $phpcsFile->getTokens();
        $contentToMatchAgainstPattern = '';
        $firstNonEmpty                = null;

        for ($i = $targetParam['start']; $i <= $targetParam['end']; $i++) {
            if (isset(Tokens::$emptyTokens[$tokens[$i]['code']])) {
                continue;
            }

            if (isset($firstNonEmpty) === false) {
                $firstNonEmpty = $i;
            }

            $contentToMatchAgainstPattern .= $tokens[$i]['content'];

            if ($tokens[$i]['code'] === \T_OPEN_PARENTHESIS
                && isset($tokens[$i]['parenthesis_closer'])
            ) {
                $i = $tokens[$i]['parenthesis_closer'];
                $contentToMatchAgainstPattern .= $tokens[$i]['content'];
            }
        }

        if (\preg_match('`^[\\\\]?dba_(first|next)key\(\)$`i', $contentToMatchAgainstPattern) !== 1) {
            /*
             * The parameter value didn't match a call to one of the typical dba_*key() functions.
             * Check if a hard-coded null or false was passed.
             */
            $contentLc = \strtolower($targetParam['clean']);
            if ($contentLc !== 'null' && $contentLc !== 'false') {
                return;
            }
        }

        /*
         * If we're still here, it means the parameter contained either a function call to a dba_*key() function
         * or a hard-coded false/null value.
         * In both cases, throwing the deprecation is warranted.
         */
        $msg  = 'Passing false or null as the $key to dba_key_split() is deprecated since PHP 8.4. Found: %s';
        $code = 'Deprecated';
        $data = [$targetParam['clean']];

        $phpcsFile->addWarning($msg, $firstNonEmpty, $code, $data);
    }
}
