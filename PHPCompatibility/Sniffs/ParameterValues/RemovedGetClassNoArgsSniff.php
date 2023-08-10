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
use PHPCSUtils\Utils\PassedParameters;
use PHP_CodeSniffer\Files\File;

/**
 * Detect: Calling get_class() and get_parent_class() without arguments is deprecated as of PHP 8.3.
 * This will become a fatal error as of PHP 9.0.
 *
 * PHP version 8.3
 * PHP version 9.0
 *
 * @link https://wiki.php.net/rfc/deprecate_functions_with_overloaded_signatures#get_class_and_get_parent_class
 * @link https://www.php.net/manual/en/function.get-class.php
 * @link https://www.php.net/manual/en/function.get-parent-class.php
 *
 * @since 10.0.0
 */
final class RemovedGetClassNoArgsSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * The error message.
     *
     * @since 10.0.0
     *
     * @var string
     */
    const ERROR_MSG = 'Calling %s() without the $%s argument is deprecated since PHP 8.3.';

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array
     */
    protected $targetFunctions = [
        'get_class'        => 'object',
        'get_parent_class' => 'object_or_class',
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
        return (ScannedCode::shouldRunOnOrAbove('8.3') === false);
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
        $functionLc = \strtolower($functionName);
        $target     = PassedParameters::getParameterFromStack($parameters, 1, $this->targetFunctions[\strtolower($functionLc)]);
        if ($target !== false) {
            return;
        }

        // Uh oh.. function call must be using named params and passing an incorrect name.
        $phpcsFile->addWarning(
            self::ERROR_MSG,
            $stackPtr,
            'ArgIncorrect',
            [$functionLc, $this->targetFunctions[$functionLc]]
        );
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
        $functionLc = \strtolower($functionName);
        $phpcsFile->addWarning(
            self::ERROR_MSG,
            $stackPtr,
            'ArgMissing',
            [$functionLc, $this->targetFunctions[$functionLc]]
        );
    }
}
