<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewAnonymousClasses.
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewAnonymousClasses.
 *
 * Anonymous classes are supported in PHP 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_NewAnonymousClassesSniff extends PHPCompatibility_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        if (version_compare(PHP_CodeSniffer::VERSION, '2.3.4') >= 0) {
            return array(T_NEW);
        } else {
            return array();
        }
    }//end register()


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
        if ($this->supportsBelow('5.6') === false) {
            return;
        }

        $whitespace = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, $stackPtr + 2);
        $class      = $phpcsFile->findNext(T_ANON_CLASS, $stackPtr + 2, $stackPtr + 3);
        if ($whitespace === false || $class === false) {
            return;
        }

        $phpcsFile->addError('Anonymous classes are not supported in PHP 5.6 or earlier', $stackPtr);

    }//end process()


}//end class
