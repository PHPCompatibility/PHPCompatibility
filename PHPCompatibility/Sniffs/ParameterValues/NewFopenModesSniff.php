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
use PHPCSUtils\Utils\PassedParameters;

/**
 * Check for valid values for the `fopen()` `$mode` parameter.
 *
 * PHP version 5.2+
 *
 * @link https://www.php.net/manual/en/function.fopen.php#refsect1-function.fopen-changelog
 *
 * @since 9.0.0
 */
class NewFopenModesSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 9.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'fopen' => true,
    ];


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 9.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        // Version used here should be (above) the highest version from the `newModes` control,
        // structure below, i.e. the last PHP version in which a new mode was introduced.
        return (ScannedCode::shouldRunOnOrBelow('7.1') === false);
    }


    /**
     * Process the parameters of a matched function.
     *
     * @since 9.0.0
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
        $targetParam = PassedParameters::getParameterFromStack($parameters, 2, 'mode');
        if ($targetParam === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $errors = [];

        for ($i = $targetParam['start']; $i <= $targetParam['end']; $i++) {
            if ($tokens[$i]['code'] === \T_STRING
                || $tokens[$i]['code'] === \T_VARIABLE
            ) {
                // Variable, constant, function call. Ignore as undetermined.
                return;
            }

            if ($tokens[$i]['code'] !== \T_CONSTANT_ENCAPSED_STRING) {
                continue;
            }

            if (\strpos($tokens[$i]['content'], 'c+') !== false && ScannedCode::shouldRunOnOrBelow('5.2.5') === true) {
                $errors['cplusFound'] = [
                    'c+',
                    '5.2.5',
                    $targetParam['clean'],
                ];
            } elseif (\strpos($tokens[$i]['content'], 'c') !== false && ScannedCode::shouldRunOnOrBelow('5.2.5') === true) {
                $errors['cFound'] = [
                    'c',
                    '5.2.5',
                    $targetParam['clean'],
                ];
            }

            if (\strpos($tokens[$i]['content'], 'e') !== false && ScannedCode::shouldRunOnOrBelow('7.0.15') === true) {
                $errors['eFound'] = [
                    'e',
                    '7.0.15',
                    $targetParam['clean'],
                ];
            }
        }

        if (empty($errors) === true) {
            return;
        }

        foreach ($errors as $errorCode => $errorData) {
            $phpcsFile->addError(
                'Passing "%s" as the $mode to fopen() is not supported in PHP %s or lower. Found: %s',
                $targetParam['start'],
                $errorCode,
                $errorData
            );
        }
    }
}
