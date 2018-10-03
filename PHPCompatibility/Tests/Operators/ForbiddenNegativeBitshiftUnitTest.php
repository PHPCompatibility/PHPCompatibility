<?php
/**
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Operators;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 *
 * @group forbiddenNegativeBitshift
 * @group operators
 *
 * @covers \PHPCompatibility\Sniffs\Operators\ForbiddenNegativeBitshiftSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Wim Godden <wim@cu.be>
 */
class ForbiddenNegativeBitshiftUnitTest extends BaseSniffTest
{

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
        $file = $this->sniffFile(__FILE__, '7.0');
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
        $file = $this->sniffFile(__FILE__, '7.0');
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
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file);
    }

}
