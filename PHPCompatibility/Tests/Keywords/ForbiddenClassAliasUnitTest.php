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

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the ForbiddenClassAlias sniff.
 *
 * @group forbiddenClassAlias
 * @group keywords
 *
 * @covers \PHPCompatibility\Sniffs\Keywords\ForbiddenClassAliasSniff
 *
 * @since 10.0.0
 */
final class ForbiddenClassAliasUnitTest extends BaseSniffTestCase
{

    /**
     * Verify that type keywords used as class alias names are detected correctly.
     *
     * @dataProvider dataForbiddenClassAlias
     *
     * @param int    $line         Line number where the error should occur.
     * @param string $type         The type keyword to detect.
     * @param string $okVersion    The last PHP version before this became an error.
     * @param string $errorVersion The PHP version on which this became an error.
     *
     * @return void
     */
    public function testForbiddenClassAlias($line, $type, $okVersion, $errorVersion)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(__FILE__, $errorVersion);
        $this->assertError($file, $line, "Type keyword \"{$type}\" is not allowed as a class alias name since PHP {$errorVersion}.");
    }

    /**
     * Data provider.
     *
     * @see testForbiddenClassAlias()
     *
     * @return array<array<int|string>>
     */
    public static function dataForbiddenClassAlias()
    {
        return [
            [51, 'bool', '5.6', '7.0'],
            [52, 'false', '5.6', '7.0'],
            [53, 'float', '5.6', '7.0'],
            [54, 'int', '5.6', '7.0'],
            [58, 'null', '5.6', '7.0'],
            [65, 'parent', '5.6', '7.0'],
            [68, 'self', '5.6', '7.0'],
            [69, 'static', '5.6', '7.0'],
            [70, 'string', '5.6', '7.0'],
            [74, 'true', '5.6', '7.0'],

            [77, 'void', '7.0', '7.1'],
            [78, 'iterable', '7.0', '7.1'],
            [81, 'object', '7.1', '7.2'],
            [84, 'mixed', '7.4', '8.0'],
            [87, 'never', '8.0', '8.1'],
            [90, 'array', '8.4', '8.5'],
            [91, 'callable', '8.4', '8.5'],
        ];
    }


    /**
     * Verify there are no false positives on valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '99.9'); // High version beyond that last change.
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
        $cases = [];

        // No errors expected on the first 45 lines.
        for ($line = 1; $line <= 45; $line++) {
            $cases[] = [$line];
        }

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.6'); // Low version before the first change.
        $this->assertNoViolation($file);
    }
}
