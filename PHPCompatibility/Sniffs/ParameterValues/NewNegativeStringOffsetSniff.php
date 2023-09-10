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
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect negative string offsets as parameters passed to functions where this
 * was not allowed prior to PHP 7.1.
 *
 * PHP version 7.1
 *
 * @link https://wiki.php.net/rfc/negative-string-offsets
 *
 * @since 9.0.0
 */
class NewNegativeStringOffsetSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 9.0.0
     *
     * @var array<string, array<int, string>> Function name => 1-based parameter offset of the affected parameters => parameter name.
     */
    protected $targetFunctions = [
        'file_get_contents'     => [
            4 => 'offset',
        ],
        'grapheme_extract'      => [
            4 => 'offset',
        ],
        'grapheme_stripos'      => [
            3 => 'offset',
        ],
        'grapheme_strpos'       => [
            3 => 'offset',
        ],
        'iconv_strpos'          => [
            3 => 'offset',
        ],
        'mb_ereg_search_setpos' => [
            1 => 'offset',
        ],
        'mb_strimwidth'         => [
            2 => 'start',
            3 => 'width',
        ],
        'mb_stripos'            => [
            3 => 'offset',
        ],
        'mb_strpos'             => [
            3 => 'offset',
        ],
        'stripos'               => [
            3 => 'offset',
        ],
        'strpos'                => [
            3 => 'offset',
        ],
        'substr_count'          => [
            3 => 'offset',
            4 => 'length',
        ],
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
        return (ScannedCode::shouldRunOnOrBelow('7.0') === false);
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
        $functionLC = \strtolower($functionName);
        foreach ($this->targetFunctions[$functionLC] as $pos => $name) {
            $targetParam = PassedParameters::getParameterFromStack($parameters, $pos, $name);
            if ($targetParam === false) {
                continue;
            }

            if (TokenGroup::isNegativeNumber($phpcsFile, $targetParam['start'], $targetParam['end']) === false) {
                continue;
            }

            $phpcsFile->addError(
                'Negative string offsets were not supported for the $%s parameter in %s() in PHP 7.0 or lower. Found: %s',
                $targetParam['start'],
                'Found',
                [
                    $name,
                    $functionName,
                    $targetParam['clean'],
                ]
            );
        }
    }
}
