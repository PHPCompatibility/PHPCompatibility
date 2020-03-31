<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ControlStructures;

use PHPCompatibility\Tests\BaseSniffTest;
use PHPCSUtils\BackCompat\Helper;

/**
 * Test the DiscouragedSwitchContinue sniff.
 *
 * @group discouragedSwitchContinue
 * @group controlStructures
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\DiscouragedSwitchContinueSniff
 *
 * @since 8.2.0
 */
class DiscouragedSwitchContinueUnitTest extends BaseSniffTest
{

    /**
     * testDiscouragedSwitchContinue
     *
     * @dataProvider dataDiscouragedSwitchContinue
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testDiscouragedSwitchContinue($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertWarning($file, $line, "Targeting a 'switch' control structure with a 'continue' statement is strongly discouraged and will throw a warning as of PHP 7.3.");
    }

    /**
     * Data provider.
     *
     * @see testDiscouragedSwitchContinue()
     *
     * @return array
     */
    public function dataDiscouragedSwitchContinue()
    {
        $data = array(
            array(16),
            array(24),
            array(28),
            array(30),
            array(40),
            array(44),
            array(59),
            array(77),
            array(87),
            array(95),
            array(100),
            array(102),
            array(114),
            array(120),
            array(149),
            array(174),
            array(210),

            /*
            @todo: False negatives. Unscoped control structure within case.
            array(133),
            array(145),
            array(156),
            array(184),
            */
        );

        if (version_compare(Helper::getVersion(), '3.5.3', '!=')) {
            $data[] = array(212);
            $data[] = array(218);
        }

        return $data;
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
        $file = $this->sniffFile(__FILE__, '7.3');
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
            array(8),
            array(18),
            array(26),
            array(32),
            array(34),
            array(36),
            array(38),
            array(42),
            array(49),
            array(51),
            array(63),
            array(67),
            array(79),
            array(85),
            array(93),
            array(104),
            array(122),
            array(129),
            array(137),
            array(143),
            array(147),
            array(160),
            array(164),
            array(176),
            array(188),
            array(188),
            array(202),
            array(204),
            array(206),
            array(208),
            array(214),
            array(216),
            array(220),
            array(222),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.2');
        $this->assertNoViolation($file);
    }
}
