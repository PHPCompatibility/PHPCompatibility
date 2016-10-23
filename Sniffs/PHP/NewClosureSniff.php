<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewClosure.
 *
 * PHP version 5.3
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewClosure.
 *
 * Closures are available since PHP 5.3
 *
 * PHP version 5.3
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_NewClosureSniff extends PHPCompatibility_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CLOSURE);

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
        if ($this->supportsBelow('5.2')) {
            $phpcsFile->addError(
                'Closures / anonymous functions are not available in PHP 5.2 or earlier',
                $stackPtr,
                'Found'
            );
        }
    }//end process()

}//end class
