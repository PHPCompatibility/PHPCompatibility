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
     * @param int    $line    The line number where an error is expected.
     * @param string $name    The name of the exit type used.
     * @param bool   $isError Whether to expect an error or a warning. Defaults to true (= error).
     *
     * @return void
     */
    public function testRemovedCallingDestructAfterConstructorExit($line, $name, $isError = true)
    {
        $file  = $this->sniffFile(__FILE__, '8.0');
        $error = "When $name() is called within an object constructor, the object destructor will no longer be called since PHP 8.0";

        if ($isError === true) {
            $this->assertError($file, $line, $error);
        } else {
            $this->assertWarning($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedCallingDestructAfterConstructorExit()
     *
     * @return array
     */
    public static function dataRemovedCallingDestructAfterConstructorExit()
    {
        return [
            [33, 'exit'],
            [44, 'die', false],
            [46, 'exit', false],
            [69, 'die'],
            [85, 'exit'],
            [97, 'die', false],
        ];
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
    public static function dataNoFalsePositives()
    {
        $cases = [];
        // No errors expected on the first 26 lines.
        for ($line = 1; $line <= 26; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [56];
        $cases[] = [61];
        $cases[] = [66];

        for ($line = 101; $line <= 130; $line++) {
            $cases[] = [$line];
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
