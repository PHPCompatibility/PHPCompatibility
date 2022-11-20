<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ControlStructures;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewExecutionDirectives sniff.
 *
 * @group newExecutionDirectives
 * @group controlStructures
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\NewExecutionDirectivesSniff
 *
 * @since 7.0.3
 */
class NewExecutionDirectivesUnitTest extends BaseSniffTest
{

    /**
     * Sniffed file
     *
     * @var \PHP_CodeSniffer\Files\File
     */
    protected $sniffResult;

    /**
     * Set up the test file for some of these unit tests.
     *
     * @before
     *
     * @return void
     */
    protected function setUpPHPCS()
    {
        // Sniff file without testVersion for testing the version independent sniff features.
        $this->sniffResult = $this->sniffFile(__FILE__);
    }

    /**
     * Test that execution directives which are not supported in certain PHP versions are correctly flagged.
     *
     * @dataProvider dataNewExecutionDirective
     *
     * @param string $directive          Name of the execution directive.
     * @param string $lastVersionBefore  The PHP version just *before* the directive was introduced.
     * @param array  $lines              The line numbers in the test file where the error should occur.
     * @param string $okVersion          A PHP version in which the directive was ok to be used.
     * @param string $conditionalVersion Optional. A PHP version in which the directive was conditionaly available.
     * @param string $condition          The availability condition.
     *
     * @return void
     */
    public function testNewExecutionDirective($directive, $lastVersionBefore, $lines, $okVersion, $conditionalVersion = null, $condition = null)
    {
        $file  = $this->sniffFile(__FILE__, $lastVersionBefore);
        $error = "Directive {$directive} is not present in PHP version {$lastVersionBefore} or earlier";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }

        if (isset($conditionalVersion, $condition)) {
            $file  = $this->sniffFile(__FILE__, $conditionalVersion);
            $error = "Directive {$directive} is present in PHP version {$conditionalVersion} but will be disregarded unless PHP is compiled with {$condition}";
            foreach ($lines as $line) {
                $this->assertWarning($file, $line, $error);
            }
        }

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewExecutionDirectivs()
     *
     * @return array
     */
    public function dataNewExecutionDirective()
    {
        return [
            ['ticks', '3.1', [6, 7], '4.0'],
            ['encoding', '5.2', [8], '5.4', '5.3', '--enable-zend-multibyte'],
            ['strict_types', '5.6', [9], '7.0'],
        ];
    }


    /**
     * Verify that execution directives with an invalid value are flagged.
     *
     * @dataProvider dataInvalidDirectiveValue
     *
     * @param string $directive Name of the execution directive.
     * @param string $value     The value.
     * @param int    $line      Line number where the error should occur.
     *
     * @return void
     */
    public function testInvalidDirectiveValue($directive, $value, $line)
    {
        // Message will be shown independently of testVersion.
        $this->assertWarning($this->sniffResult, $line, "The execution directive {$directive} does not seem to have a valid value. Please review. Found: {$value}");
    }

    /**
     * Data provider.
     *
     * @see testInvalidDirectiveValue()
     *
     * @return array
     */
    public function dataInvalidDirectiveValue()
    {
        return [
            ['ticks', 'TICK_VALUE', 16],
            ['strict_types', 'false', 18],
        ];
    }


    /**
     * Verify that "encoding" execution directives with an invalid value are flagged.
     *
     * @requires function mb_list_encodings
     *
     * @dataProvider dataInvalidEncodingDirectiveValue
     *
     * @param string $directive Name of the execution directive.
     * @param string $value     The value.
     * @param int    $line      Line number where the error should occur.
     *
     * @return void
     */
    public function testInvalidEncodingDirectiveValue($directive, $value, $line)
    {
        // Message will be shown independently of testVersion.
        $this->assertWarning($this->sniffResult, $line, "The execution directive {$directive} does not seem to have a valid value. Please review. Found: {$value}");
    }

    /**
     * Data provider.
     *
     * @see testInvalidEncodingDirectiveValue()
     *
     * @return array
     */
    public function dataInvalidEncodingDirectiveValue()
    {
        return [
            ['encoding', 'invalid', 17],
        ];
    }


    /**
     * Verify that directives which are not supported by PHP are flagged as such.
     *
     * @return void
     */
    public function testInvalidDirective()
    {
        // Message will be shown independently of testVersion.
        $this->assertError($this->sniffResult, 22, 'Declare can only be used with the directives ticks, encoding, strict_types. Found: invalid');
    }


    /**
     * Verify that the sniff stays silent for incomplete declare statements (live coding/parse error).
     *
     * @return void
     */
    public function testIncompleteDirective()
    {
        $this->assertNoViolation($this->sniffResult, 25);
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as the directive value checks are version independent.
     */
}
