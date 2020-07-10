<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Constants;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewMagicClassConstant sniff.
 *
 * @group newMagicClassConstant
 * @group constants
 *
 * @covers \PHPCompatibility\Sniffs\Constants\NewMagicClassConstantSniff
 *
 * @since 7.1.4
 */
class NewMagicClassConstantUnitTest extends BaseSniffTest
{

    /**
     * Test correctly identifying the magic class constant.
     *
     * @dataProvider dataNewMagicClassConstant
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewMagicClassConstant($line)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertError($file, $line, 'The magic class constant ClassName::class was not available in PHP 5.4 or earlier');

        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNewMagicClassConstant()
     *
     * @return array
     */
    public function dataNewMagicClassConstant()
    {
        return array(
            array(6),
            array(12),
            array(27),
            array(28),
            array(29),
            array(30),
            array(31),
            array(32),
        );
    }


    /**
     * Verify that no false positives are thrown for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
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
        return array(
            array(4),
            array(10),
            array(18),
            array(19),
        );
    }


    /**
     * Test correctly identifying the magic class constant when used on instantiated objects.
     *
     * @dataProvider dataNewMagicClassConstantOnObject
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewMagicClassConstantOnObject($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, 'Using the magic class constant ::class with dynamic class names is not supported in PHP 7.4 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testNewMagicClassConstantOnObject()
     *
     * @return array
     */
    public function dataNewMagicClassConstantOnObject()
    {
        return array(
            array(35),
            array(36),
            array(37),
            array(38),
            array(39),

            // Still not supported, but throwing an error anyhow.
            array(49),
            array(50),
            array(51),
            array(52),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file);
    }
}
