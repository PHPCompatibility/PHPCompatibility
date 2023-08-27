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
 * The default value for the `$variant` parameter has changed from `INTL_IDNA_VARIANT_2003`
 * to `INTL_IDNA_VARIANT_UTS46` in PHP 7.4.
 *
 * PHP version 7.4
 *
 * @link https://www.php.net/manual/en/migration74.incompatible.php#migration74.incompatible.intl
 * @link https://wiki.php.net/rfc/deprecate-and-remove-intl_idna_variant_2003
 * @link https://www.php.net/manual/en/function.idn-to-ascii.php
 * @link https://www.php.net/manual/en/function.idn-to-utf8.php
 *
 * @since 9.3.0
 */
class NewIDNVariantDefaultSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * Key is the function name, value an array containing the 1-based parameter position
     * and the official name of the parameter.
     *
     * @since 9.3.0
     *
     * @var array<string, array<string, int|string>>
     */
    protected $targetFunctions = [
        'idn_to_ascii' => [
            'position' => 3,
            'name'     => 'variant',
        ],
        'idn_to_utf8' => [
            'position' => 3,
            'name'     => 'variant',
        ],
    ];

    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * Note: This sniff should only trigger errors when both PHP 7.3 or lower,
     * as well as PHP 7.4 or higher need to be supported within the application.
     *
     * @since 9.3.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrBelow('7.3') === false || ScannedCode::shouldRunOnOrAbove('7.4') === false);
    }


    /**
     * Process the parameters of a matched function.
     *
     * @since 9.3.0
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
        if ($targetParam !== false) {
            return;
        }

        $error = 'The default value of the %1$s() $%2$s parameter has changed from INTL_IDNA_VARIANT_2003 to INTL_IDNA_VARIANT_UTS46 in PHP 7.4. For optimal cross-version compatibility, the $%2$s parameter should be explicitly set.';
        $phpcsFile->addError(
            $error,
            $stackPtr,
            'NotSet',
            [$functionName, $paramInfo['name']]
        );
    }
}
