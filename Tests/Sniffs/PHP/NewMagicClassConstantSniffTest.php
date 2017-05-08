<?php
/**
 * New magic ::class constant sniff test file.
 *
 * @package PHPCompatibility
 */


/**
 * New magic ::class constant sniff test file.
 *
 * @group newMagicClassConstant
 * @group constants
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewMagicClassConstantSniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewMagicClassConstantSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_magic_class_constant.php';

    /**
     * testNewMagicClassConstant
     *
     * @dataProvider dataNewMagicClassConstant
     *
     * @param int  $line            The line number.
     * @param bool $testNoViolation Whether or not to run the noViolation test.
     *
     * @return void
     */
    public function testNewMagicClassConstant($line, $testNoViolation = true)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertError($file, $line, 'The magic class constant ClassName::class was not available in PHP 5.4 or earlier');

        if ($testNoViolation === true) {
            // Namespace detection does not work on PHP 5.2.
            if (version_compare(phpversion(), '5.3.0', '>=')) {
                $file = $this->sniffFile(self::TEST_FILE, '5.5');
                $this->assertNoViolation($file, $line);
            }
        }
    }

    /**
     * Data provider dataNewMagicClassConstant.
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
            array(24, false), // Line which also tests the incorrect use warnings.
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
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
     * testInvalidUse
     *
     * @return void
     */
    public function testInvalidUse()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertWarning($file, 24, 'Using the magic class constant ClassName::class is only useful in combination with a namespaced class');
    }


    /**
     * testNoFalsePositivesInvalidUse
     *
     * @dataProvider dataNoFalsePositivesInvalidUse
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesInvalidUse($line)
    {
        if (version_compare(phpversion(), '5.3.0', '<') === true) {
            $this->markTestSkipped('PHP 5.2 does not recognize namespaces.');
            return;
        }

        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesInvalidUse()
     *
     * @return array
     */
    public function dataNoFalsePositivesInvalidUse()
    {
        return array(
            array(6),
            array(12),
        );
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw warnings
     * on invalid use of the constant in PHP 5.5+ versions.
     */

}
