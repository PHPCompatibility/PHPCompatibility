<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;

/**
 * Detect undocumented operator abbreviations being passed to `version_compare()`.
 *
 * Support for these undocumented operator abbreviation has been removed in PHP 8.1.
 *
 * PHP version 8.1
 *
 * @link https://github.com/php/php-src/blob/28a1a6be0873a109cb02ba32784bf046b87a02e4/UPGRADING#L148
 * @link https://github.com/php/php-src/commit/2b7eb0e26a1816a3c5ddb28dd53f98ae0ecef047
 *
 * @since 10.0.0
 */
class RemovedVersionCompareOperatorsSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'version_compare' => true,
    ];

    /**
     * The operators which are no longer supported.
     *
     * Note: operators are case-sensitive, values should be lowercase.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    private $unsupportedOperators = [
        ''  => true,
        'l' => true,
        'g' => true,
        'e' => true,
        '!' => true,
        'n' => true,
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
        return (ScannedCode::shouldRunOnOrAbove('8.1') === false);
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
        $targetParam = PassedParameters::getParameterFromStack($parameters, 3, 'operator');
        if ($targetParam === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $textStartTokens  = Tokens::$stringTokens;
        $textStartTokens += Tokens::$heredocTokens;

        $accepted  = $textStartTokens;
        $accepted += Tokens::$emptyTokens;

        $hasNonText = $phpcsFile->findNext($accepted, $targetParam['start'], ($targetParam['end'] + 1), true);
        if ($hasNonText !== false) {
            // Found a non text-string token. Ignore as undetermined.
            return;
        }

        $textStringStart = $phpcsFile->findNext($textStartTokens, $targetParam['start'], ($targetParam['end'] + 1));
        if ($textStringStart === false) {
            // Shouldn't be able to happen, but just in case.
            return; // @codeCoverageIgnore
        }

        $contents = TextStrings::getCompleteTextString($phpcsFile, $textStringStart);
        if (isset($this->unsupportedOperators[$contents]) === false) {
            // Either never supported or still supported.
            return;
        }

        $phpcsFile->addError(
            'version_compare() no longer supports operator abbreviations since PHP 8.1. Found: %s',
            $textStringStart,
            'Found',
            [$targetParam['clean']]
        );
    }
}
