<?php
/**
 * Test Helper file.
 *
 * @package PHPCompatibility
 */

if (class_exists('PHPCompatibility_Sniff', true) === false) {
    require_once dirname(dirname(dirname(__FILE__))) . '/Sniff.php';
}

/**
 * Helper class to facilitate testing of the methods within the abstract PHPCompatibility_Sniff class.
 *
 * @uses    PHPCompatibility_Sniff
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class BaseClass_TestHelperPHPCompatibility extends PHPCompatibility_Sniff
{
    /**
     * Dummy method to bypass the abstract method implementation requirements.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Dummy method to bypass the abstract method implementation requirements.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
    }
}
