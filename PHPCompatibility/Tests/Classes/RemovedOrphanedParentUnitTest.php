<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Classes;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedOrphanedParent sniff.
 *
 * @group newRemovedOrphanedParent
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\RemovedOrphanedParentSniff
 *
 * @since 9.2.0
 */
class RemovedOrphanedParentUnitTest extends BaseSniffTest
{

    /**
     * testRemovedOrphanedParent.
     *
     * @dataProvider dataRemovedOrphanedParent
     *
     * @param int $line The line number where a warning is expected.
     *
     * @return void
     */
    public function testRemovedOrphanedParent($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertWarning($file, $line, 'Using "parent" inside a class without parent is deprecated since PHP 7.4');

        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, 'Using "parent" inside a class without parent is deprecated since PHP 7.4 and removed since PHP 8.0');
    }

    /**
     * Data provider.
     *
     * @see testRemovedOrphanedParent()
     *
     * @return array
     */
    public function dataRemovedOrphanedParent()
    {
        return [
            [35],
            [36],
            [37],
            [38],
            [44],
            [45],
            [46],
            [47],
            [55],
            [56],
            [57],
            [58],
            [72],
            [73],
        ];
    }


    /**
     * testNoFalsePositives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
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
        $cases = [];
        // No errors expected on the first 31 lines.
        for ($line = 1; $line <= 31; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [66];
        $cases[] = [67];
        $cases[] = [68];

        // Add parse error test case.
        $cases[] = [80];

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertNoViolation($file);
    }
}
