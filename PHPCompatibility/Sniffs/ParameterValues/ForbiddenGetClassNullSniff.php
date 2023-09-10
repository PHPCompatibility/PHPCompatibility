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
 * Detect: Passing `null` to `get_class()` is no longer allowed as of PHP 7.2.
 * This will now result in an `E_WARNING` being thrown.
 *
 * PHP version 7.2
 *
 * @link https://wiki.php.net/rfc/get_class_disallow_null_parameter
 * @link https://www.php.net/manual/en/function.get-class.php#refsect1-function.get-class-changelog
 *
 * @since 9.0.0
 */
class ForbiddenGetClassNullSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 9.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'get_class' => true,
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
        return (ScannedCode::shouldRunOnOrAbove('7.2') === false);
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
        $target = PassedParameters::getParameterFromStack($parameters, 1, 'object');
        if ($target === false) {
            return;
        }

        if ($target['clean'] !== 'null') {
            return;
        }

        $phpcsFile->addError(
            'Passing "null" as the $object to get_class() is not allowed since PHP 7.2.',
            $target['start'],
            'Found'
        );
    }
}
