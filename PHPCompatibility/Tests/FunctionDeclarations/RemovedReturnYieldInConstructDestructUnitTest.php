<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionDeclarations;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedReturnYieldInConstructDestruct sniff.
 *
 * @group removedReturnYieldInConstructDestruct
 * @group functionDeclarations
 * @group magicMethods
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\RemovedReturnYieldInConstructDestructSniff
 *
 * @since 10.0.0
 */
final class RemovedReturnYieldInConstructDestructUnitTest extends BaseSniffTestCase
{

    /**
     * Verify return statements in constructors and destructors are flagged for PHP 8.6 and up.
     *
     * @dataProvider dataRemovedReturnInConstructDestruct
     *
     * @param int    $line         The line number.
     * @param string $functionType Either 'constructor' or 'destructor'.
     *
     * @return void
     */
    public function testRemovedReturnInConstructDestruct($line, $functionType)
    {
        $file  = $this->sniffFile(__FILE__, '8.6');
        $error = \sprintf('Returning a value from a %s is deprecated since PHP 8.6.', $functionType);
        $this->assertWarning($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @return array<array<int|string>>
     */
    public static function dataRemovedReturnInConstructDestruct()
    {
        return [
            [180, 'constructor'],
            [183, 'constructor'],
            [190, 'destructor'],
            [193, 'destructor'],
        ];
    }


    /**
     * Verify yield (from) statements in constructors and destructors are flagged for PHP 8.6 and up.
     *
     * @dataProvider dataRemovedYieldInConstructDestruct
     *
     * @param int    $line         The line number.
     * @param string $functionType Either 'constructor' or 'destructor'.
     *
     * @return void
     */
    public function testRemovedYieldInConstructDestruct($line, $functionType)
    {
        $file  = $this->sniffFile(__FILE__, '8.6');
        $error = \sprintf('Using a %s as a generator is deprecated since PHP 8.6.', $functionType);
        $this->assertWarning($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @return array<array<int|string>>
     */
    public static function dataRemovedYieldInConstructDestruct()
    {
        return [
            [201, 'constructor'],
            [202, 'constructor'],
            [207, 'destructor'],
            [208, 'destructor'],
            [216, 'constructor'],
            [217, 'constructor'],
            [222, 'destructor'],
            [223, 'destructor'],
        ];
    }


    /**
     * Test that there are no false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.6');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     */
    public static function dataNoFalsePositives()
    {
        $data = [];

        // No errors expected on the first 171 lines.
        for ($line = 1; $line <= 171; $line++) {
            $data[] = [$line];
        }

        // Make sure there are no duplicate errors for multi-token "yield from".
        $data[] = [224];
        $data[] = [225];

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.5');
        $this->assertNoViolation($file);
    }
}
