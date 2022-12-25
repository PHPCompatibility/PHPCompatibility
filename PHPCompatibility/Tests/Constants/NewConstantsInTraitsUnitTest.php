<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Constants;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewConstantsInTraits sniff.
 *
 * @group newConstantsInTraits
 * @group constants
 *
 * @covers \PHPCompatibility\Sniffs\Constants\NewConstantsInTraitsSniff
 *
 * @since 10.0.0
 */
final class NewConstantsInTraitsUnitTest extends BaseSniffTest
{

    /**
     * Test that an error is thrown for class constants declared with visibility.
     *
     * @dataProvider dataConstantInTrait
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testConstantInTrait($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertError($file, $line, 'Declaring constants in traits is not supported in PHP 8.1 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testConstantInTrait()
     *
     * @return array
     */
    public function dataConstantInTrait()
    {
        return [
            [41],
            [42],
            [43],
        ];
    }


    /**
     * Verify that there are no false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
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
        for ($line = 1; $line <= 35; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.2');
        $this->assertNoViolation($file);
    }
}
