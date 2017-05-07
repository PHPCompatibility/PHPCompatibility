<?php
/**
 * PHPCompatibility_Sniffs_PHP_ConstantArraysUsingConstSniff.
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_ConstantArraysUsingConstSniff.
 *
 * Constant arrays using the constant keyword in PHP 5.6
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_ConstantArraysUsingConstSniff extends PHPCompatibility_Sniff
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
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.5') !== true) {
            return;
        }

        $find = array(
            T_ARRAY             => T_ARRAY,
            T_OPEN_SHORT_ARRAY  => T_OPEN_SHORT_ARRAY,
            T_CLOSE_SHORT_ARRAY => T_CLOSE_SHORT_ARRAY,
        );

        $hasArray = $phpcsFile->findNext($find, ($stackPtr + 1), null, false, null, true);
        if ($hasArray !== false) {
            $phpcsFile->addError(
                'Constant arrays using the "const" keyword are not allowed in PHP 5.5 or earlier',
                $hasArray,
                'Found'
            );
        }
    }
}
