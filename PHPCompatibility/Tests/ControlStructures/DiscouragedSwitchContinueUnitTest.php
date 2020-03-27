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
        $data = [
            [16],
            [24],
            [28],
            [30],
            [40],
            [44],
            [59],
            [77],
            [87],
            [95],
            [100],
            [102],
            [114],
            [120],
            [149],
            [174],
            [210],

            /*
            @todo: False negatives. Unscoped control structure within case.
            array(133),
            array(145),
            array(156),
            array(184),
            */
        ];

        if (\version_compare(Helper::getVersion(), '3.5.3', '!=')) {
            $data[] = [212];
            $data[] = [218];
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
        return [
            [6],
            [8],
            [18],
            [26],
            [32],
            [34],
            [36],
            [38],
            [42],
            [49],
            [51],
            [63],
            [67],
            [79],
            [85],
            [93],
            [104],
            [122],
            [129],
            [137],
            [143],
            [147],
            [160],
            [164],
            [176],
            [188],
            [188],
            [202],
            [204],
            [206],
            [208],
            [214],
            [216],
            [220],
            [222],
        ];
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
