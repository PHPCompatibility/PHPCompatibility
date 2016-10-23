<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenNegativeBitshift.
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenNegativeBitshift.
 *
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenNegativeBitshiftSniff extends PHPCompatibility_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_SR);

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.0') === false) {
            return;
        }

        $nextNumber = $phpcsFile->findNext(T_LNUMBER, $stackPtr + 1, null, false, null, true);
        if($nextNumber === false || ($stackPtr + 1) === $nextNumber) {
            return;
        }

        $hasMinusSign = $phpcsFile->findNext(T_MINUS, $stackPtr + 1, $nextNumber, false, null, true);
        if($hasMinusSign === false) {
            return;
        }

        $phpcsFile->addError(
            'Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0',
            $hasMinusSign,
            'Found'
        );

    }//end process()

}//end class
