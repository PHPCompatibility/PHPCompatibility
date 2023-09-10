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
 * Detect: Passing multi-byte separators to the `$decimal_separator` and `$thousands_separator` parameters
 * for `number_format()` which was not supported prior to PHP 5.4.
 *
 * Previously, only the first byte of each separator was used.
 *
 * PHP version 5.4
 *
 * @link https://www.php.net/manual/en/function.number-format.php#refsect1-function.number-format-changelog
 *
 * @since 10.0.0
 */
class NewNumberFormatMultibyteSeparatorsSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for and the parameters to check.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'number_format' => true,
    ];

    /**
     * Tokens which we are looking for in the parameter.
     *
     * This property is set in the register() method.
     *
     * @since 10.0.0
     *
     * @var array<int|string, int|string>
     */
    private $targetTokens = [];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        // Only set the $targetTokens property once.
        $this->targetTokens  = Tokens::$emptyTokens;
        $this->targetTokens += Tokens::$heredocTokens;
        $this->targetTokens += Tokens::$stringTokens;

        return parent::register();
    }


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrBelow('5.3') === false);
    }


    /**
     * Process the parameters of a matched function.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     * @param array                       $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $targetParam = PassedParameters::getParameterFromStack($parameters, 3, 'decimal_separator');
        if ($targetParam !== false) {
            $this->examineParameter($phpcsFile, $targetParam, 'decimal_separator');
        }

        $targetParam = PassedParameters::getParameterFromStack($parameters, 4, 'thousands_separator');
        if ($targetParam !== false) {
            $this->examineParameter($phpcsFile, $targetParam, 'thousands_separator');
        }
    }

    /**
     * Examine the contents of an individual parameter.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param array                       $param     Array with information about the current parameter.
     * @param string                      $paramName The name of the current parameter.
     *
     * @return void
     */
    protected function examineParameter(File $phpcsFile, $param, $paramName)
    {
        $firstNonEmpty   = $phpcsFile->findNext(Tokens::$emptyTokens, $param['start'], ($param['end'] + 1), true);
        $hasNonTextToken = $phpcsFile->findNext($this->targetTokens, $firstNonEmpty, ($param['end'] + 1), true);
        if ($hasNonTextToken !== false) {
            // Non text string token found.
            return;
        }

        $tokens  = $phpcsFile->getTokens();
        $content = TextStrings::getCompleteTextString($phpcsFile, $firstNonEmpty);
        $length  = \strlen($content);

        if ($tokens[$firstNonEmpty]['code'] === \T_DOUBLE_QUOTED_STRING
            || $tokens[$firstNonEmpty]['code'] === \T_START_HEREDOC
        ) {
            $embedInfo = TextStrings::getStripEmbeds($content);
            $length    = \strlen($embedInfo['remaining']);
            if ($embedInfo['remaining'] !== $content) {
                // Add 1 character to the count for each variable stripped.
                $length += \count($embedInfo['embeds']);
            }
        }

        if ($length === 1) {
            // Single-byte, we're good.
            return;
        }

        $phpcsFile->addError(
            'Passing a multi-byte separator as the $%s to number_format() is not supported in PHP 5.3 or earlier. Found: "%s"',
            $firstNonEmpty,
            'In' . \ucfirst($paramName),
            [$paramName, $content]
        );
    }
}
