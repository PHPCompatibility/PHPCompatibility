<?php
/**
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 *
 * @package PHPCompatibility
 */


/**
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 *
 * @group forbiddenNegativeBitshift
 *
 * @covers PHPCompatibility_Sniffs_PHP_ForbiddenNegativeBitshiftSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class ForbiddenNegativeBitshiftSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/forbidden_negative_bitshift.php';

    /**
     * testForbiddenNegativeBitshift
     *
     * @dataProvider dataForbiddenNegativeBitshift
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testForbiddenNegativeBitshift($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, 'Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0');
    }

    /**
     * dataForbiddenNegativeBitshift
     *
     * @see testForbiddenNegativeBitshift()
     *
     * @return array
     */
    public function dataForbiddenNegativeBitshift()
    {
        return array(
            array(3),
            array(4),
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
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
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
            array(6),
            array(7),
            array(8),
            //array(9), - currently gives a false positive, @todo: uncomment when fixed.
            array(12),
        );
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
