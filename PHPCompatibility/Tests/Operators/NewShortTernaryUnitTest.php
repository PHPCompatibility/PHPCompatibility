<?php
/**
 * New Short Ternary Sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Operators;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New Short Ternary Sniff tests
 *
 * @group newShortTernary
 * @group operators
 *
 * @covers \PHPCompatibility\Sniffs\Operators\NewShortTernarySniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Jansen Price <jansen.price@gmail.com>
 */
class NewShortTernaryUnitTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/ternary_operator.php';

    /**
     * testElvisOperator
     *
     * @dataProvider dataElvisOperator
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testElvisOperator($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, $line, 'Middle may not be omitted from ternary operators in PHP < 5.3');
    }


    /**
     * dataElvisOperator
     *
     * @see testElvisOperator()
     *
     * @return array
     */
    public function dataElvisOperator()
    {
        return array(
            array(8),
            array(10),
        );
    }


    /**
     * Test ternary operators that are acceptable in all PHP versions.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertNoViolation($file, 5);
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file);
    }

}
