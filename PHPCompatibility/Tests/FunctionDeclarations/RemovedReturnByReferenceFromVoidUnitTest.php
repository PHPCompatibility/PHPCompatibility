<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionDeclarations;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedReturnByReferenceFromVoid sniff.
 *
 * @group removedReturnByReferenceFromVoid
 * @group functiondeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\RemovedReturnByReferenceFromVoidSniff
 *
 * @since 10.0.0
 */
class RemovedReturnByReferenceFromVoidUnitTest extends BaseSniffTest
{

    /**
     * Test that returning by reference from a void function is detected correctly.
     *
     * @dataProvider dataReturnByReferenceFromVoid
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testReturnByReferenceFromVoid($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertWarning($file, $line, 'Returning by reference from a void function is deprecated since PHP 8.1.');
    }

    /**
     * Data provider.
     *
     * @see testReturnByReferenceFromVoid()
     *
     * @return array
     */
    public function dataReturnByReferenceFromVoid()
    {
        return [
            [54],
            [56],
            [59],
            [63],
            [67],
            [71],
            [75],
        ];
    }


    /**
     * Verify that there are no false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number.
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

        // No errors expected on the first 50 lines.
        for ($line = 1; $line <= 50; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file);
    }
}
