<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\LanguageConstructs;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedLanguageConstructs sniff.
 *
 * @group removedLanguageConstructs
 * @group languageConstructs
 *
 * @covers \PHPCompatibility\Sniffs\LanguageConstructs\RemovedLanguageConstructsSniff
 *
 * @since 10.0.0
 */
final class RemovedLanguageConstructsUnitTest extends BaseSniffTestCase
{

    /**
     * PHP 8.5: backtick operator as alias for shell_exec().
     *
     * @dataProvider dataBacktickOperator
     *
     * @param int $line The line number where an error is expected.
     *
     * @return void
     */
    public function testBacktickOperator($line)
    {
        $file = $this->sniffFile(__FILE__, '8.5');
        // Warning will appear twice, once for the opener backtick, once for the closer.
        $this->assertWarning($file, $line, 'The backtick operator is deprecated since PHP 8.5; Use shell_exec() instead');

        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     */
    public static function dataBacktickOperator()
    {
        return [
            [9],
            [10],
        ];
    }


    /**
     * Test against false positives for the backtick operator.
     *
     * @dataProvider dataBacktickOperatorNoFalsePositives
     *
     * @param int $line The line number where an error is expected.
     *
     * @return void
     */
    public function testBacktickOperatorNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.5');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @return array<array<int>>
     */
    public static function dataBacktickOperatorNoFalsePositives()
    {
        return [
            [6],
            [7],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.4'); // Low version before first deprecation.
        $this->assertNoViolation($file);
    }
}
