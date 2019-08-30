<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2019 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\InitialValue;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;

/**
 * Constant arrays using the const keyword in PHP 5.6
 *
 * PHP version 5.6
 */
class NewConstantArraysUsingConstSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(\T_CONST);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.5') !== true) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $find   = array(
            \T_ARRAY            => \T_ARRAY,
            \T_OPEN_SHORT_ARRAY => \T_OPEN_SHORT_ARRAY,
        );

        while (($hasArray = $phpcsFile->findNext($find, ($stackPtr + 1), null, false, null, true)) !== false) {
            $phpcsFile->addError(
                'Constant arrays using the "const" keyword are not allowed in PHP 5.5 or earlier',
                $hasArray,
                'Found'
            );

            // Skip past the content of the array.
            $stackPtr = $hasArray;
            if ($tokens[$hasArray]['code'] === \T_OPEN_SHORT_ARRAY && isset($tokens[$hasArray]['bracket_closer'])) {
                $stackPtr = $tokens[$hasArray]['bracket_closer'];
            } elseif ($tokens[$hasArray]['code'] === \T_ARRAY && isset($tokens[$hasArray]['parenthesis_closer'])) {
                $stackPtr = $tokens[$hasArray]['parenthesis_closer'];
            }
        }
    }
}
