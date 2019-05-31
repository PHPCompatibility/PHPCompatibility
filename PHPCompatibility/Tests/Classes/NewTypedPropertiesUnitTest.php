<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2019 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Classes;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New Typed Properties Sniff tests
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
     * @param array $line The line number on which the error should occur.
     *
     * @return void
     */
    public function testNewTypedProperties($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
        $this->assertError($file, $line, 'Typed properties are not supported in PHP 7.3 or earlier');
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
        return array(
            array(22),
            array(23),
            array(24),
            array(27),
            array(30),
            array(33),
            array(34),
            array(37),
            array(40),
            array(48),
            array(50),
            array(54),
            array(59),
            array(60),
            array(61),
        );
    }


    /**
     * Verify the sniff doesn't throw false positives for non-typed properties.
     *
     * @return void
     */
    public function testNoFalsePositivesNewTypedProperties()
    {
        $file = $this->sniffFile(__FILE__, '7.3');

        for ($line = 1; $line < 18; $line++) {
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
        return array(
            array(59, 'void'),
            array(60, 'callable'),
            array(61, 'callable'),
        );
    }


    /**
     * Verify the sniff doesn't throw false positives.
     *
     * @return void
     */
    public function testNoFalsePositivesInvalidPropertyType()
    {
        $file = $this->sniffFile(__FILE__, '7.4');

        for ($line = 1; $line < 57; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will also throw warnings/errors
     * about invalid typed properties.
     */
}
