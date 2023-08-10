<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCSUtils\Utils\Conditions;
use PHPCSUtils\Utils\Scopes;
use PHP_CodeSniffer\Files\File;

/**
 * Detect: Calling get_[called_]class() without arguments from outside a class will throw an Error since PHP 8.0.
 *
 * Previously, an `E_WARNING` was raised and the functions returned `false`.
 *
 * PHP version 8.0
 *
 * @link https://www.php.net/manual/en/function.get-class.php#refsect1-function.get-class-changelog
 * @link https://www.php.net/manual/en/function.get-called-class.php#refsect1-function.get-called-class-changelog
 *
 * @since 10.0.0
 */
final class ForbiddenGetClassNoArgsOutsideOOSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array
     */
    protected $targetFunctions = [
        'get_class'        => 'without an argument, ',
        'get_called_class' => '',
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
        return (ScannedCode::shouldRunOnOrAbove('8.0') === false);
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
     * @return void
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        // Nothing to do. Function called with one or more parameters.
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
        $lastCondition = Conditions::getLastCondition($phpcsFile, $stackPtr, \T_FUNCTION);
        if ($lastCondition !== false && Scopes::isOOMethod($phpcsFile, $lastCondition) === true) {
            // This function call will be executed within an OO context.
            return;
        }

        $functionLc = \strtolower($functionName);
        $phpcsFile->addError(
            'Calling %s() %sfrom outside of an OO scope, will throw an Error since PHP 8.0.',
            $stackPtr,
            'Found',
            [$functionLc, $this->targetFunctions[$functionLc]]
        );
    }
}
