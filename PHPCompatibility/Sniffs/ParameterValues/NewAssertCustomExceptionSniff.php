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
use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;

/**
 * Assert() supports custom exceptions as $description since PHP 7.0.
 *
 * > assert() is now a language construct and not a function. Assertion can now be
 * > an expression. The second parameter is now interpreted either as an exception
 * > (if a Throwable object is given), or as the description supported from PHP 5.4.8 onwards.
 *
 * PHP version 7.0
 *
 * @link https://wiki.php.net/rfc/expectations
 * @link https://www.php.net/manual/en/function.assert.php#refsect1-function.assert-changelog
 *
 * @since 10.0.0
 */
class NewAssertCustomExceptionSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'assert' => true,
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
        return (ScannedCode::shouldRunOnOrBelow('5.6') === false);
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
        if (isset($parameters[2]) === false) {
            return;
        }

        /*
         * Examine the description parameter.
         *
         * Note: this could still generate false positives if a non-Throwable object is instantiated which
         * has a __toString() method to generate the $description, but chances of code like that being used
         * are slim.
         */
        $targetParam = $parameters[2];
        $hasNew      = $phpcsFile->findNext(\T_NEW, $targetParam['start'], ($targetParam['end'] + 1));
        if ($hasNew === false) {
            // Undetermined. Bow out.
            return;
        }

        $hasStringCast = $phpcsFile->findNext(\T_STRING_CAST, $targetParam['start'], ($targetParam['end'] + 1));
        if ($hasStringCast !== false && $hasStringCast < $hasNew) {
            // Cast to string, not an issue.
            return;
        }

        $error = 'Passing a Throwable object as the second parameter to assert() is not supported in PHP 5.6 or earlier. Found: %s';
        $data  = [$targetParam['clean']];

        $phpcsFile->addError($error, $targetParam['start'], 'CustomExceptionFound', $data);
    }
}
