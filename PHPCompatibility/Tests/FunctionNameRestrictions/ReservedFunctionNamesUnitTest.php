<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionNameRestrictions;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the ReservedFunctionNames sniff.
 *
 * @group reservedFunctionNames
 * @group functionNameRestrictions
 *
 * @covers \PHPCompatibility\Sniffs\FunctionNameRestrictions\ReservedFunctionNamesSniff
 *
 * @since 8.2.0
 */
class ReservedFunctionNamesUnitTest extends BaseSniffTest
{

    /**
     * Test that double underscore prefixed functions/methods which aren't reserved names trigger an error.
     *
     * @dataProvider dataReservedFunctionNames
     *
     * @param int    $line The line number.
     * @param string $type Either 'function' or 'method'.
     *
     * @return void
     */
    public function testReservedFunctionNames($line, $type)
    {
        $file = $this->sniffFile(__FILE__);
        $this->assertWarning($file, $line, " is discouraged; PHP has reserved all $type names with a double underscore prefix for future use.");
    }

    /**
     * Data provider.
     *
     * @see testReservedFunctionNames()
     *
     * @return array
     */
    public function dataReservedFunctionNames()
    {
        return array(
            array(20, 'method'),
            array(21, 'method'),
            array(22, 'method'),

            array(25, 'function'),
            array(26, 'function'),
            array(27, 'function'),
            array(28, 'function'),
            array(29, 'function'),
            array(30, 'function'),
            array(31, 'function'),
            array(32, 'function'),
            array(33, 'function'),
            array(34, 'function'),
            array(35, 'function'),
            array(37, 'function'),
            array(38, 'function'),
            array(39, 'function'),
            array(41, 'function'),
            array(42, 'function'),

            array(92, 'method'),
            array(93, 'method'),
            array(94, 'method'),

            array(107, 'method'),
            array(109, 'method'),

            array(139, 'function'),
            array(142, 'method'),

            array(149, 'function'),
            array(150, 'function'),

            array(160, 'function'),
            array(161, 'function'),
        );
    }


    /**
     * Verify the sniff doesn't throw false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__);
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
            array(5),
            array(6),
            array(7),
            array(8),
            array(9),
            array(10),
            array(11),
            array(12),
            array(13),
            array(14),
            array(15),
            array(16),
            array(17),
            array(18),
            array(19),

            array(40),
            array(46),
            array(50),
            array(51),
            array(52),
            array(54),

            array(58),
            array(63),
            array(66),
            array(69),
            array(72),

            array(77),
            array(78),
            array(79),
            array(80),
            array(81),
            array(82),
            array(83),
            array(84),
            array(85),
            array(86),
            array(87),
            array(88),
            array(89),
            array(90),
            array(91),

            array(98),
            array(101),
            array(102),

            array(124),
            array(135),

            array(148),

            array(156),
            array(157),
        );
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff operates
     *  independently of the testVersion.
     */
}
