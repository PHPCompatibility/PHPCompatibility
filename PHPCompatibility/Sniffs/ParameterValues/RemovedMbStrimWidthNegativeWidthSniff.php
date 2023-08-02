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
use PHPCompatibility\Helpers\TokenGroup;
use PHPCSUtils\Utils\PassedParameters;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Passing a negative $width to mb_strimwidth() is deprecated since PHP 8.3.
 * Support will be removed in PHP 9.0.
 *
 * Note: this was introduced in PHP 7.1. Also {@see NewNegativeStringOffsetSniff}.
 *
 * PHP version 8.3
 * PHP version 9.0
 *
 * @link https://wiki.php.net/rfc/deprecations_php_8_3#passing_negative_widths_to_mb_strimwidth
 * @link https://www.php.net/manual/en/function.mb-strimwidth.php
 *
 * @since 10.0.0
 */
final class RemovedMbStrimWidthNegativeWidthSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, bool>
     */
    protected $targetFunctions = [
        'mb_strimwidth' => true,
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
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $targetParam = PassedParameters::getParameterFromStack($parameters, 3, 'width');
        if ($targetParam === false) {
            return;
        }

        if (TokenGroup::isNegativeNumber($phpcsFile, $targetParam['start'], $targetParam['end']) === false) {
            return;
        }

        $firstNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $targetParam['start'], ($targetParam['end'] + 1), true);

        $phpcsFile->addWarning(
            'Passing a negative $width to mb_strimwidth() is deprecated since PHP 8.3. Found: %s',
            $firstNonEmpty,
            'Deprecated',
            [$targetParam['clean']]
        );
    }
}
