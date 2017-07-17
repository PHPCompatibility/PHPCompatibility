<?php
/**
 * Test Helper file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\BaseClass;

use PHPCompatibility\Sniff;

/**
 * Helper class to facilitate testing of the methods within the abstract \PHPCompatibility\Sniff class.
 *
 * @uses    \PHPCompatibility\Sniff
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class TestHelperPHPCompatibility extends Sniff
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
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
    }
}
