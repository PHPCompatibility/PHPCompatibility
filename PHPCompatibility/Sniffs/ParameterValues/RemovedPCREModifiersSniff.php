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
use PHPCompatibility\Helpers\PCRERegexTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Check for the use of deprecated and removed regex modifiers for PCRE regex functions.
 *
 * Initially just checks for the `e` modifier, which was deprecated since PHP 5.5
 * and removed as of PHP 7.0.
 *
 * {@internal If and when this sniff would need to start checking for other modifiers, a minor
 * refactor will be needed as all references to the `e` modifier are currently hard-coded
 * and the target functions are limited to the ones which supported the `e` modifier.}
 *
 * PHP version 5.5
 * PHP version 7.0
 *
 * @link https://wiki.php.net/rfc/remove_preg_replace_eval_modifier
 * @link https://wiki.php.net/rfc/remove_deprecated_functionality_in_php7
 * @link https://www.php.net/manual/en/reference.pcre.pattern.modifiers.php
 *
 * @since 5.6
 * @since 7.0.8  This sniff now throws a warning (deprecated) or an error (removed) depending
 *               on the `testVersion` set. Previously it would always throw an error.
 * @since 8.2.0  Now extends the `AbstractFunctionCallParameterSniff` instead of the base `Sniff` class.
 * @since 9.0.0  Renamed from `PregReplaceEModifierSniff` to `RemovedPCREModifiersSniff`.
 * @since 10.0.0 Now uses the new `PCRERegexTrait`.
 */
class RemovedPCREModifiersSniff extends AbstractFunctionCallParameterSniff
{
    use PCRERegexTrait;

    /**
     * Functions to check for.
     *
     * @since 7.0.1
     * @since 8.2.0  Renamed from `$functions` to `$targetFunctions`.
     * @since 10.0.0 Value changed from an irrelevant value to an array.
     *
     * @var array<string, array<string, int|string>> Key is the function name, value an array containing
     *                                               the 1-based parameter position and the official name of the parameter.
     */
    protected $targetFunctions = [
        'preg_replace' => [
            'position' => 1,
            'name'     => 'pattern',
        ],
        'preg_filter' => [
            'position' => 1,
            'name'     => 'pattern',
        ],
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
        return (ScannedCode::shouldRunOnOrAbove('5.5') === false);
    }

    /**
     * Process the parameters of a matched function.
     *
     * @since 5.6
     * @since 8.2.0 Renamed from `process()` to `processParameters()` and removed
     *              logic superfluous now the sniff extends the abstract.
     * @since 10.0.0 Most logic has been moved to the new `PCRERegexTrait`.
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
        $functionLC  = \strtolower($functionName);
        $paramInfo   = $this->targetFunctions[$functionLC];
        $targetParam = PassedParameters::getParameterFromStack($parameters, $paramInfo['position'], $paramInfo['name']);
        if ($targetParam === false) {
            return;
        }

        $patterns = $this->getRegexPatternsFromParameter($phpcsFile, $functionName, $targetParam);
        if (empty($patterns) === true) {
            return;
        }

        foreach ($patterns as $pattern) {
            $modifiers = $this->getRegexModifiers($phpcsFile, $pattern);
            if ($modifiers === '') {
                continue;
            }

            $this->examineModifiers($phpcsFile, $pattern['end'], $functionName, $modifiers);
        }
    }

    /**
     * Examine the regex modifier string.
     *
     * @since 8.2.0 Split off from the `processRegexPattern()` method.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the
     *                                                  stack passed in $tokens.
     * @param string                      $functionName The function which contained the pattern.
     * @param string                      $modifiers    The regex modifiers found.
     *
     * @return void
     */
    protected function examineModifiers(File $phpcsFile, $stackPtr, $functionName, $modifiers)
    {
        if (\strpos($modifiers, 'e') !== false) {
            $error     = '%s() - /e modifier is deprecated since PHP 5.5';
            $isError   = false;
            $errorCode = 'Deprecated';
            $data      = [$functionName];

            if (ScannedCode::shouldRunOnOrAbove('7.0') === true) {
                $error    .= ' and removed since PHP 7.0';
                $isError   = true;
                $errorCode = 'Removed';
            }

            MessageHelper::addMessage($phpcsFile, $error, $stackPtr, $isError, $errorCode, $data);
        }
    }
}
