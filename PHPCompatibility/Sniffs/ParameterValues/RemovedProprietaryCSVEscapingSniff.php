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
 * Detect code affected by the various steps taken to deprecate (and eventually remove) the proprietary CSV escaping mechanism.
 *
 * PHP 7.4: Passing an empty string for the `$escape` parameter is allowed and will effectively deactivate the escaping.
 *          For the fputcsv() and the fgetcsv() functions, passing an empty string was previously not allowed.
 *          For the str_getcsv() function, this constitutes a behavioural change as an empty string would previously fall through
 *          to the default value.
 * PHP 8.4: Deprecated not passing the `$escape` parameter to prevent future problems when the default parameter value of the
 *          `$escape` parameter will change from `"\\"` to an empty string.
 *          In practice, this means the parameter has become a required parameter, even though there are two optional
 *          parameters before it.
 *
 * PHP version 7.4
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/kill-csv-escaping
 * @link https://wiki.php.net/rfc/deprecations_php_8_4#deprecate_proprietary_csv_escaping_mechanism
 *
 * @since 10.0.0
 */
final class RemovedProprietaryCSVEscapingSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, array<string, int|string>>
     */
    protected $targetFunctions = [
        'fputcsv' => [
            'position' => 5,
            'name'     => 'escape',
        ],
        'fgetcsv' => [
            'position' => 5,
            'name'     => 'escape',
        ],
        'str_getcsv' => [
            'position' => 4,
            'name'     => 'escape',
        ],
    ];

    /**
     * Determine if this sniff needs to run at all.
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return false;
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
     * @return void
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        if (ScannedCode::shouldRunOnOrBelow('7.3') === true) {
            $this->checkParameterValueIsEmptyString($phpcsFile, $stackPtr, $functionName, $parameters);
        }

        if (ScannedCode::shouldRunOnOrAbove('8.4') === true) {
            $this->checkIfParameterIsPassed($phpcsFile, $stackPtr, $functionName, $parameters);
        }
    }

    /**
     * Process the function if no parameters were found.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     *
     * @return void
     */
    public function processNoParameters(File $phpcsFile, $stackPtr, $functionName)
    {
        if (ScannedCode::shouldRunOnOrAbove('8.4') === true) {
            $this->checkIfParameterIsPassed($phpcsFile, $stackPtr, $functionName, []);
        }
    }


    /**
     * PHP < 7.4: Process a passed `$escape` parameter to check if the value is an empty string.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File                  $phpcsFile    The file being scanned.
     * @param int                                          $stackPtr     The position of the current token in the stack.
     * @param string                                       $functionName The token content (function name) which was matched.
     * @param array<int|string, array<string, int|string>> $parameters   Array with information about the parameters.
     *
     * @return void
     */
    private function checkParameterValueIsEmptyString(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $functionLC  = \strtolower($functionName);
        $paramInfo   = $this->targetFunctions[$functionLC];
        $targetParam = PassedParameters::getParameterFromStack($parameters, $paramInfo['position'], $paramInfo['name']);
        if ($targetParam === false) {
            return;
        }

        if ($targetParam['clean'] !== '""' && $targetParam['clean'] !== "''") {
            // Not a hard-coded empty string. Bow out, either valid non-empty string or undetermined.
            return;
        }

        $firstNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $targetParam['start'], ($targetParam['end'] + 1), true);

        // Special case the changed behaviour for str_getcsv().
        if ($functionLC === 'str_getcsv') {
            if (ScannedCode::shouldRunOnOrAbove('7.4') === false) {
                // Only report the error when the code needs to run on both PHP <= 7.3 as well as PHP >= 7.4.
                return;
            }

            $msg  = 'Passing an empty string as the $%s parameter to %s() would fall through to the default value ("\\") in PHP 7.3 and earlier, while in PHP 7.4+ it will disable the proprietary CSV escaping mechanism.';
            $code = 'ChangedBehaviour';
            $data = [
                $paramInfo['name'],
                $functionLC,
            ];

            $phpcsFile->addError($msg, $firstNonEmpty, $code, $data);
            return;
        }

        $msg  = 'Passing an empty string as the $%s parameter to %s() to disable the proprietary CSV escaping mechanism was not supported in PHP 7.3 or earlier.';
        $code = 'EmptyStringNotAllowed';
        $data = [
            $paramInfo['name'],
            $functionLC,
        ];

        $phpcsFile->addError($msg, $firstNonEmpty, $code, $data);
    }

    /**
     * PHP 8.4+: Verify the `$escape` parameter is passed.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File                  $phpcsFile    The file being scanned.
     * @param int                                          $stackPtr     The position of the current token in the stack.
     * @param string                                       $functionName The token content (function name) which was matched.
     * @param array<int|string, array<string, int|string>> $parameters   Array with information about the parameters.
     *
     * @return void
     */
    private function checkIfParameterIsPassed(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $functionLC  = \strtolower($functionName);
        $paramInfo   = $this->targetFunctions[$functionLC];
        $targetParam = PassedParameters::getParameterFromStack($parameters, $paramInfo['position'], $paramInfo['name']);
        if ($targetParam !== false && $targetParam['clean'] !== '') {
            return;
        }

        $msg  = 'The $%s parameter must be passed when calling %s() as its default value will change in a future PHP version.';
        $code = 'DeprecatedParamNotPassed';
        $data = [
            $paramInfo['name'],
            $functionLC,
        ];

        $phpcsFile->addWarning($msg, $stackPtr, $code, $data);
    }
}
