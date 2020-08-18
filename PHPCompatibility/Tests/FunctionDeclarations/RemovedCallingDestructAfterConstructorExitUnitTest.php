<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionDeclarations;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedCallingDestructAfterConstructorExit sniff.
 *
 * @group removedCallingDestructAfterConstructorExit
 * @group functiondeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\RemovedCallingDestructAfterConstructorExitSniff
 *
 * @since 10.0.0
 */
class RemovedCallingDestructAfterConstructorExitUnitTest extends BaseSniffTest
{

    /**
     * Verify that the sniff throws an error when an exit/die is encountered in a class constructor for PHP 8.0+.
     *
     * @dataProvider dataRemovedCallingDestructAfterConstructorExit
     *
     * @param int    $line The line number where an error is expected.
     * @param string $name The name of the exit type used.
     *
     * @return void
     */
    public function testRemovedCallingDestructAfterConstructorExit($line, $name)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, "When $name() is called within an object constructor, the object destructor will no longer be called since PHP 8.0");
    }

    /**
     * Data provider.
     *
     * @see testRemovedCallingDestructAfterConstructorExit()
     *
     * @return array
     */
    public function dataRemovedCallingDestructAfterConstructorExit()
    {
        return array(
            array(33, 'exit'),
            array(44, 'die'),
            array(46, 'exit'),
        );
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        $cases = array();
        // No errors expected on the first 26 lines.
        for ($line = 1; $line <= 26; $line++) {
            $cases[] = array($line);
        }

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file);
    }
}
