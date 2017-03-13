<?php
/**
 * PHP4 style constructors sniff test file
 *
 * @package PHPCompatibility
 */

/**
 * PHP4 style constructors sniff test
 *
 * @group deprecatedPHP4StyleConstructors
 *
 * @covers PHPCompatibility_Sniffs_PHP_DeprecatedPHP4StyleConstructorsSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Koen Eelen <koen.eelen@cu.be>
 */
class DeprecatedPHP4StyleConstructorsSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/deprecated_php4style_constructors.php';

    /**
     * Test PHP4 style constructors.
     *
     * @dataProvider dataIsDeprecated
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testIsDeprecated($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertWarning($file, $line, 'Use of deprecated PHP4 style class constructor is not supported since PHP 7');
    }

    /**
     * dataIsDeprecated
     *
     * @see testIsDeprecated()
     *
     * @return array
     */
    public function dataIsDeprecated()
    {
        return array(
            array(3),
            array(18),
        );
    }


    /**
     * Test valid methods with the same name as the class.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoFalsePositives
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        $testCases = array(
            array(9),
        );

        // Add an additional testcase which will only be 'no violation' if namespaces are recognized.
        if (version_compare(phpversion(), '5.3', '>=')) {
            $testCases[] = array(26);
        }

        return $testCases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file);
    }

}
