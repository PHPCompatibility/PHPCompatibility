<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Numbers;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the ValidIntegers sniff.
 *
 * @group validIntegers
 * @group numbers
 *
 * @covers \PHPCompatibility\Sniffs\Numbers\ValidIntegersSniff
 *
 * @since 7.0.3
 */
class ValidIntegersUnitTest extends BaseSniffTest
{

    /**
     * testBinaryInteger
     *
     * @dataProvider dataBinaryInteger
     *
     * @param int    $line            Line number where the error should occur.
     * @param string $binary          (Start of) Binary number as a string.
     * @param bool   $testNoViolation Whether or not to test for noViolation.
     *                                Defaults to true. Set to false if another error is
     *                                expected on the same line (invalid binary).
     *
     * @return void
     */
    public function testBinaryInteger($line, $binary, $testNoViolation = true)
    {
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertError($file, $line, "Binary integer literals were not present in PHP version 5.3 or earlier. Found: {$binary}");

        if ($testNoViolation === true) {
            $file = $this->sniffFile(__FILE__, '5.4');
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data Provider.
     *
     * @see testBinaryInteger()
     *
     * @return array
     */
    public function dataBinaryInteger()
    {
        return array(
            array(3, '0b001001101', true),
            array(4, '0b01', false),
            array(11, '0B10001', true),
            array(14, '0b00100_1101', true),
            array(19, '0b01', false),
        );
    }


    /**
     * testInvalidBinaryInteger
     *
     * @dataProvider dataInvalidBinaryInteger
     *
     * @param int    $line   Line number where the error should occur.
     * @param string $binary (Start of) Binary number as a string.
     *
     * @return void
     */
    public function testInvalidBinaryInteger($line, $binary)
    {
        $file = $this->sniffFile(__FILE__); // Message will be shown independently of testVersion.
        $this->assertWarning($file, $line, "Invalid binary integer detected. Found: {$binary}");
    }

    /**
     * Data Provider.
     *
     * @see testInvalidBinaryInteger()
     *
     * @return array
     */
    public function dataInvalidBinaryInteger()
    {
        return array(
            array(4, '0b0123456'),

            // Depending on PHP version the message will show the complete number or just the first part.
            array(19, '0b012'),
        );
    }


    /**
     * testInvalidOctalInteger
     *
     * @dataProvider dataInvalidOctalInteger
     *
     * @param int    $line  Line number where the error should occur.
     * @param string $octal Octal number as a string.
     *
     * @return void
     */
    public function testInvalidOctalInteger($line, $octal)
    {
        $error = "Invalid octal integer detected. Prior to PHP 7 this would lead to a truncated number. From PHP 7 onwards this causes a parse error. Found: {$octal}";

        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertWarning($file, $line, $error);

        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, $error);
    }

    /**
     * Data Provider.
     *
     * @see testInvalidOctalInteger()
     *
     * @return array
     */
    public function dataInvalidOctalInteger()
    {
        return array(
            array(7, '08'),
            array(8, '038'),
            array(9, '091'),
            array(16, '03_8'),
        );
    }


    /**
     * testValidOctalInteger
     *
     * @dataProvider dataValidOctalInteger
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testValidOctalInteger($line)
    {
        $file = $this->sniffFile(__FILE__, '4.0-99.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data Provider.
     *
     * @see testValidOctalInteger()
     *
     * @return array
     */
    public function dataValidOctalInteger()
    {
        return array(
            array(6),
            array(15),
        );
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw warnings/errors
     * about invalid integers independently of the testVersion.
     */
}
