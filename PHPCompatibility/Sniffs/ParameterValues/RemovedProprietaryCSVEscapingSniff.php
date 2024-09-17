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
 *
 * PHP version 7.4
 *
 * @link https://wiki.php.net/rfc/kill-csv-escaping
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
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrBelow('7.3') === false);
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
}
