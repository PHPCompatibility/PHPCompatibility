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

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\TextStrings;

/**
 * Check for use of deprecated and removed regex modifiers for MbString regex functions.
 *
 * Initially just checks for the PHP 7.1 deprecated `e` modifier.
 *
 * PHP version 7.1+
 *
 * @link https://wiki.php.net/rfc/deprecate_mb_ereg_replace_eval_option
 * @link https://www.php.net/manual/en/function.mb-regex-set-options.php
 *
 * @since 7.0.5
 * @since 7.0.8 This sniff now throws a warning instead of an error as the functionality is
 *              only deprecated (for now).
 * @since 8.2.0 Now extends the `AbstractFunctionCallParameterSniff` instead of the base `Sniff` class.
 * @since 9.0.0 Renamed from `MbstringReplaceEModifierSniff` to `RemovedMbstringModifiersSniff`.
 */
class RemovedMbstringModifiersSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * Key is the function name, value the parameter position of the options parameter.
     *
     * @since 7.0.5
     * @since 8.2.0 Renamed from `$functions` to `$targetFunctions`.
     *
     * @var array<string, int>
     */
    protected $targetFunctions = [
        'mb_ereg_replace'      => 4,
        'mb_eregi_replace'     => 4,
        'mb_regex_set_options' => 1,
        'mbereg_replace'       => 4, // Undocumented, but valid function alias.
        'mberegi_replace'      => 4, // Undocumented, but valid function alias.
    ];


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 8.2.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        // Version used here should be the lowest version, i.e the version in which the
        // first modifier being detected by this sniff was removed.
        return (ScannedCode::shouldRunOnOrAbove('7.1') === false);
    }


    /**
     * Process the parameters of a matched function.
     *
     * @since 7.0.5
     * @since 8.2.0 Renamed from `process()` to `processParameters()` and removed
     *              logic superfluous now the sniff extends the abstract.
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
        $tokens         = $phpcsFile->getTokens();
        $functionNameLc = \strtolower($functionName);

        // Check whether the options parameter in the function call is passed.
        if (isset($parameters[$this->targetFunctions[$functionNameLc]]) === false) {
            return;
        }

        $optionsParam = $parameters[$this->targetFunctions[$functionNameLc]];
        $options      = '';

        /*
         * Get the content of any string tokens in the options parameter and remove the quotes and variables.
         */
        for ($i = $optionsParam['start']; $i <= $optionsParam['end']; $i++) {
            if ($tokens[$i]['code'] === \T_STRING
                || $tokens[$i]['code'] === \T_VARIABLE
            ) {
                // Variable, constant, function call. Ignore as undetermined.
                return;
            }

            if (isset(Tokens::$stringTokens[$tokens[$i]['code']]) === false) {
                continue;
            }

            $content = TextStrings::stripQuotes($tokens[$i]['content']);
            if ($tokens[$i]['code'] === \T_DOUBLE_QUOTED_STRING) {
                $content = TextStrings::stripEmbeds($content);
            }
            $content = \trim($content);

            if (empty($content) === false) {
                $options .= $content;
            }
        }

        if (\strpos($options, 'e') !== false) {
            $error   = 'The Mbstring regex "e" modifier is deprecated since PHP 7.1';
            $code    = 'Deprecated';
            $isError = false;

            if (ScannedCode::shouldRunOnOrAbove('8.0') === true) {
                $error  .= ' and removed since PHP 8.0';
                $code    = 'Removed';
                $isError = true;
            }

            $error .= '.';

            // The alternative mb_ereg_replace_callback() function is only available since 5.4.1.
            if (ScannedCode::shouldRunOnOrBelow('5.4.1') === false) {
                $error .= ' Use mb_ereg_replace_callback() instead (PHP 5.4.1+).';
            }

            MessageHelper::addMessage($phpcsFile, $error, $stackPtr, $isError, $code);
        }
    }
}
