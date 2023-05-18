<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Generators;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewGeneratorReturn sniff.
 *
 * @group newGeneratorReturn
 * @group generators
 *
 * @covers \PHPCompatibility\Sniffs\Generators\NewGeneratorReturnSniff
 *
 * @since 8.2.0
 */
class NewGeneratorReturnUnitTest extends BaseSniffTest
{

    /**
     * Test detection of final return in generators.
     *
     * @dataProvider dataNewGeneratorReturn
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewGeneratorReturn($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertError($file, $line, 'Returning a final expression from a generator was not supported in PHP 5.6 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testNewGeneratorReturn()
     *
     * @return array
     */
    public function dataNewGeneratorReturn()
    {
        return [
            [30],
            [35],
            [39],
            [64],
            [83],
            [101],
        ];
    }


    /**
     * Test the sniff doesn't throw false positives for valid code.
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
        $data = [];

        // No errors expected on the first 24 lines.
        for ($line = 1; $line <= 24; $line++) {
            $data[] = [$line];
        }

        for ($line = 44; $line <= 55; $line++) {
            $data[] = [$line];
        }

        $data[] = [67];

        for ($line = 105; $line <= 130; $line++) {
            $data[] = [$line];
        }

        return $data;
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
