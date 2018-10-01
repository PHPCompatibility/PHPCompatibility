<?php
/**
 * \PHPCompatibility\Sniffs\InitialValue\NewConstantArraysUsingConstSniff.
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\InitialValue;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\InitialValue\NewConstantArraysUsingConstSniff.
 *
 * Constant arrays using the const keyword in PHP 5.6
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
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
        return array(T_CONST);
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
            T_ARRAY             => T_ARRAY,
            T_OPEN_SHORT_ARRAY  => T_OPEN_SHORT_ARRAY,
        );

        while (($hasArray = $phpcsFile->findNext($find, ($stackPtr + 1), null, false, null, true)) !== false) {
            $phpcsFile->addError(
                'Constant arrays using the "const" keyword are not allowed in PHP 5.5 or earlier',
                $hasArray,
                'Found'
            );

            // Skip past the content of the array.
            $stackPtr = $hasArray;
            if ($tokens[$hasArray]['code'] === T_OPEN_SHORT_ARRAY && isset($tokens[$hasArray]['bracket_closer'])) {
                $stackPtr = $tokens[$hasArray]['bracket_closer'];
            } elseif ($tokens[$hasArray]['code'] === T_ARRAY && isset($tokens[$hasArray]['parenthesis_closer'])) {
                $stackPtr = $tokens[$hasArray]['parenthesis_closer'];
            }
        }
    }
}
