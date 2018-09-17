<?php
/**
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 *
 * @group forbiddenNegativeBitshift
 *
 * @covers \PHPCompatibility\Sniffs\PHP\ForbiddenNegativeBitshiftSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Wim Godden <wim@cu.be>
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
            array(5),
            array(7),
            array(8),
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
            array(10),
            array(11),
            array(12),
            array(13),
            array(16),
            array(19),
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
