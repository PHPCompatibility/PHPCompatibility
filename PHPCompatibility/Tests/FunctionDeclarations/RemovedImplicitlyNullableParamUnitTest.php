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
 * Test the RemovedImplicitlyNullableParam sniff.
 *
 * @group removedImplicitlyNullableParam
 * @group functiondeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\RemovedImplicitlyNullableParamSniff
 *
 * @since 10.0.0
 */
final class RemovedImplicitlyNullableParamUnitTest extends BaseSniffTestCase
{

    /**
     * Verify that the sniff throws a warning for implicitly nullable parameters.
     *
     * @dataProvider dataRemovedImplicitlyNullableParam
     *
     * @param int $line The line number where a warning is expected.
     *
     * @return void
     */
    public function testRemovedImplicitlyNullableParam($line)
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertWarning($file, $line, 'Implicitly marking a parameter as nullable is deprecated since PHP 8.4. Update the type to be explicitly nullable instead.');
    }

    /**
     * Data provider.
     *
     * @see testRemovedImplicitlyNullableParam()
     *
     * @return array
     */
    public static function dataRemovedImplicitlyNullableParam()
    {
        return [
            [42],
            [45],
            [46],
            [47],
            [48],
            [49],
            [50],
            [54],
        ];
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        $cases = [];
        // No errors expected on the first 38 lines.
        for ($line = 1; $line <= 38; $line++) {
            $cases[] = [$line];
        }

        $cases[] = [51];

        // Parse error test case.
        $cases[] = [57];

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file);
    }
}
