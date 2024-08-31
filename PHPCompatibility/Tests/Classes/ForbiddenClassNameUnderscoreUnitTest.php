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

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the ForbiddenClassNameUnderscore sniff.
 *
 * @group forbiddenClassNameUnderscore
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\ForbiddenClassNameUnderscoreSniff
 *
 * @since 10.0.0
 */
final class ForbiddenClassNameUnderscoreUnitTest extends BaseSniffTestCase
{

    /**
     * Test the sniff correctly detects OO structures named "_".
     *
     * @dataProvider dataForbiddenClassNameUnderscore
     *
     * @param int    $line Line number where the error should occur.
     * @param string $type Type of OO structure.
     *
     * @return void
     */
    public function testForbiddenClassNameUnderscore($line, $type)
    {
        $file  = $this->sniffFile(__FILE__, '8.4');
        $error = \sprintf('Using a single underscore, "_", as %s name is deprecated since PHP 8.4.', $type);

        $this->assertWarning($file, $line, $error);

        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testForbiddenClassNameUnderscore()
     *
     * @return array<array<int|string>>
     */
    public static function dataForbiddenClassNameUnderscore()
    {
        return [
            [18, 'class'],
            [19, 'interface'],
            [20, 'trait'],
            [21, 'enum'],
            [26, 'class'],
            [27, 'interface'],
            [28, 'trait'],
            [29, 'enum'],
        ];
    }

    /**
     * Verify there are no false positives on code this sniff should ignore.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number.
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
     * @return array<array<int>>
     */
    public static function dataNoFalsePositives()
    {
        $data = [];

        // No errors expected on the first 16 lines.
        for ($line = 1; $line <= 16; $line++) {
            $data[] = [$line];
        }

        $data[] = [32];

        return $data;
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
