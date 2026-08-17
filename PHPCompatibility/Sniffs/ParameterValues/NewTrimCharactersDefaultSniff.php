<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2026 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHP_CodeSniffer\Files\File;
use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCSUtils\Utils\PassedParameters;

/**
 * The default value for the $characters parameters for `[lr]trim()` includes the form feed character as of PHP 8.6.
 *
 * PHP version 8.6
 *
 * @link https://wiki.php.net/rfc/trim_form_feed
 * @link https://www.php.net/trim
 * @link https://www.php.net/ltrim
 * @link https://www.php.net/rtrim
 *
 * @since 10.0.0
 */
final class NewTrimCharactersDefaultSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * Key is the function name, value an array containing the 1-based parameter position
     * and the official name of the parameter.
     *
     * @since 10.0.0
     *
     * @var array<string, array<string, int|string>>
     */
    protected $targetFunctions = [
        'trim'  => [
            'position' => 2,
            'name'     => 'characters',
        ],
        'ltrim' => [
            'position' => 2,
            'name'     => 'characters',
        ],
        'rtrim' => [
            'position' => 2,
            'name'     => 'characters',
        ],
    ];


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * Note: This sniff should only trigger errors when both PHP 8.5 or lower,
     * as well as PHP 8.6 or higher needs to be supported within the application.
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrBelow('8.5') === false || ScannedCode::shouldRunOnOrAbove('8.6') === false);
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
        $functionLC  = \strtolower($functionName);
        $paramInfo   = $this->targetFunctions[$functionLC];
        $targetParam = PassedParameters::getParameterFromStack($parameters, $paramInfo['position'], $paramInfo['name']);
        if ($targetParam !== false) {
            // Parameter is set, not an issue.
            return;
        }

        $phpcsFile->addError(
            'The default value of the $characters parameter for %s() includes the form feed character as of PHP 8.6. It was changed from " \n\r\t\v\x00" to " \f\n\r\t\v\x00". For cross-version compatibility, the $characters parameter should be explicitly set.',
            $stackPtr,
            'NotSet',
            [$functionName]
        );
    }
}
