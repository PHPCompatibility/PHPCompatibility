<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2018 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\MethodUse;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New direct calls to __clone() sniff tests.
 *
 * @group newDirectCallsToClone
 * @group methodUse
 *
 * @covers \PHPCompatibility\Sniffs\MethodUse\NewDirectCallsToCloneSniff
 *
 * @since 9.1.0
 */
class NewDirectCallsToCloneUnitTest extends BaseSniffTest
{

    /**
     * Test detecting direct calls to clone.
     *
     * @dataProvider dataDirectCallToClone
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testDirectCallToClone($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertError($file, $line, 'Direct calls to the __clone() magic method are not allowed in PHP 5.6 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testDirectCallToClone()
     *
     * @return array
     */
    public function dataDirectCallToClone()
    {
        return array(
            array(23),
            array(24),
            array(25),
            array(29),
            array(30),
        );
    }


    /**
     * Test against false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6');
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
            array(9),
            array(10),
            array(11),
            array(14),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file);
    }
}
