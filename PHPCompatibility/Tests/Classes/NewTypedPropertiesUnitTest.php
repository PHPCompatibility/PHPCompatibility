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

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewTypedProperties sniff.
 *
 * @group newTypedProperties
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewTypedPropertiesSniff
 *
 * @since 9.2.0
 */
class NewTypedPropertiesUnitTest extends BaseSniffTest
{

    /**
     * testNewTypedProperties
     *
     * @dataProvider dataNewTypedProperties
     *
     * @param array $line            The line number on which the error should occur.
     * @param bool  $testNoViolation Whether or not to test noViolation for PHP 7.4.
     *
     * @return void
     */
    public function testNewTypedProperties($line, $testNoViolation = false)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertError($file, $line, 'Typed properties are not supported in PHP 7.3 or earlier');

        if ($testNoViolation === true) {
            $file = $this->sniffFile(__FILE__, '7.4');
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewTypedProperties()
     *
     * @return array
     */
    public function dataNewTypedProperties()
    {
        return [
            [23, true],
            [24, true],
            [25, true],
            [28, true],
            [31, true],
            [34, true],
            [35, true],
            [38, true],
            [41, true],
            [49, true],
            [51, true],
            [54, true],
            [57, true],
            [62],
            [63],
            [64],
            [65],
            [66],
        ];
    }


    /**
     * Verify the sniff doesn't throw false positives for non-typed properties.
     *
     * @return void
     */
    public function testNoFalsePositivesNewTypedProperties()
    {
        $file = $this->sniffFile(__FILE__, '7.3');

        for ($line = 1; $line < 19; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }


    /**
     * testInvalidPropertyType
     *
     * @dataProvider dataInvalidPropertyType
     *
     * @param array  $line The line number on which the error should occur.
     * @param string $type The invalid type which should be detected.
     *
     * @return void
     */
    public function testInvalidPropertyType($line, $type)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, "$type is not supported as a type declaration for properties");
    }

    /**
     * Data provider.
     *
     * @see testInvalidPropertyType()
     *
     * @return array
     */
    public function dataInvalidPropertyType()
    {
        return [
            [62, 'void'],
            [63, 'callable'],
            [64, 'callable'],
            [65, 'boolean'],
            [66, 'integer'],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will also throw warnings/errors
     * about invalid typed properties.
     */
}
