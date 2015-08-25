<?php
/**
 * PHPCompatibility_Sniffs_PHP_DefaultTimeZoneRequiredSniff.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_DefaultTimeZoneRequiredSniff.
 *
 * Discourages the use of deprecated INI directives through ini_set() or ini_get().
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_DefaultTimeZoneRequiredSniff extends PHPCompatibility_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     * Maybe not ideal to do this on each open tag. But I don't feel like digging further into PHP_CodeSniffer right now
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG);

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
        if ($this->supportsAbove('5.4')) {
            if (ini_get('date.timezone') == false) {
                $error = 'Default timezone is required since PHP 5.4';
                $phpcsFile->addError($error, $stackPtr);
            }
        }

    }//end process()


}//end class
