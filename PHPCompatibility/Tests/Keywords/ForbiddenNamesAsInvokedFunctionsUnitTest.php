<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Keywords;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the ForbiddenNamesAsInvokedFunctions sniff.
 *
 * @group forbiddenNamesAsInvokedFunctions
 * @group keywords
 *
 * @covers \PHPCompatibility\Sniffs\Keywords\ForbiddenNamesAsInvokedFunctionsSniff
 *
 * @since 5.5
 */
class ForbiddenNamesAsInvokedFunctionsUnitTest extends BaseSniffTest
{

    /**
     * testReservedKeyword
     *
     * @dataProvider dataReservedKeyword
     *
     * @param string $keyword       Reserved keyword.
     * @param array  $linesFunction The line numbers in the test file which apply to this keyword as a function call.
     * @param array  $linesMethod   The line numbers in the test file which apply to this keyword as a method call.
     * @param string $introducedIn  The PHP version in which the keyword became a reserved word.
     * @param string $okVersion     A PHP version in which the keyword was not yet reserved.
     *
     * @return void
     */
    public function testReservedKeyword($keyword, $linesFunction, $linesMethod, $introducedIn, $okVersion)
    {
        $file  = $this->sniffFile(__FILE__, $introducedIn);
        $error = "'{$keyword}' is a reserved keyword introduced in PHP version {$introducedIn} and cannot be invoked as a function";
        $lines = \array_merge($linesFunction, $linesMethod);
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (empty($linesMethod) === true) {
            return;
        }

        // Test that method calls do not throw an error for PHP 7.0+.
        $file = $this->sniffFile(__FILE__, '7.0-');
        foreach ($linesMethod as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testReservedKeyword()
     *
     * @return array
     */
    public function dataReservedKeyword()
    {
        return [
            ['abstract', [6], [53], '5.0', '4.4'],
            ['callable', [7], [54], '5.4', '5.3'],
            ['catch', [8], [55], '5.0', '4.4'],
            ['final', [10], [56], '5.0', '4.4'],
            ['finally', [11], [57], '5.5', '5.4'],
            ['goto', [12], [58], '5.3', '5.2'],
            ['implements', [13], [59], '5.0', '4.4'],
            ['interface', [14], [60], '5.0', '4.4'],
            ['instanceof', [15], [61], '5.0', '4.4'],
            ['insteadof', [16], [62], '5.4', '5.3'],
            ['namespace', [17], [63], '5.3', '5.2'],
            ['private', [18], [64], '5.0', '4.4'],
            ['protected', [19], [65], '5.0', '4.4'],
            ['public', [20], [66], '5.0', '4.4'],
            ['trait', [22], [67], '5.4', '5.3'],
            ['try', [23], [68], '5.0', '4.4'],
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number where no error should occur.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High number beyond any newly introduced keywords.
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoFalsePositives
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return [
            [34],
            [35],
            [36],
            [37],
            [38],
            [39],
            [40],
            [41],
            [42],
            [43],
            [44],
            [45],
            [46],
            [47],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '4.4'); // Low version below the first introduced reserved word.
        $this->assertNoViolation($file);
    }
}
