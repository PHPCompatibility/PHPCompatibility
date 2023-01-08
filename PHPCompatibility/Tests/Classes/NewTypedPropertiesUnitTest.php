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
     * Verify that all type declarations are flagged when the minimum supported PHP version < 7.4.
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
            [71],
            [74],
            [79],
            [80],
            [81],
            [84],
            [87],
            [90],
            [93],
            [96],
            [99],
            [102],
            [105],
            [108],
            [114],
            [115, true],
            [116],
            [117],
            [131],
            [132],
            [135],
            [138],
            [139],
            [142],
        ];
    }


    /**
     * Verify the sniff doesn't throw false positives for non-typed properties.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesNewTypedProperties($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3');
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
        $cases = [];
        // No errors expected on the first 19 lines.
        for ($line = 1; $line <= 19; $line++) {
            $cases[] = [$line];
        }

        // Don't throw errors for normal constructor/function parameters.
        $cases[] = [118];
        $cases[] = [119];
        $cases[] = [123];
        $cases[] = [127];

        return $cases;
    }

    /**
     * Verify that invalid type declarations are flagged correctly.
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
            [117, 'callable'],
        ];
    }


    /**
     * Test correctly throwing an error when types are used which were not available on a particular PHP version.
     *
     * @dataProvider dataNewTypedPropertyTypes
     *
     * @param string $type              The declared type.
     * @param string $lastVersionBefore The PHP version just *before* the type hint was introduced.
     * @param array  $line              The line number where the error is expected.
     * @param string $okVersion         A PHP version in which the type hint was ok to be used.
     * @param bool   $testNoViolation   Whether or not to test noViolation.
     *                                  Defaults to true.
     *
     * @return void
     */
    public function testNewTypedPropertyTypes($type, $lastVersionBefore, $line, $okVersion, $testNoViolation = true)
    {
        $file = $this->sniffFile(__FILE__, $lastVersionBefore);
        $this->assertError($file, $line, "'{$type}' property type is not present in PHP version {$lastVersionBefore} or earlier");

        if ($testNoViolation === true) {
            $file = $this->sniffFile(__FILE__, $okVersion);
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewTypedPropertyTypes()
     *
     * @return array
     */
    public function dataNewTypedPropertyTypes()
    {
        return [
            ['mixed', '7.4', 71, '8.0'],
            ['mixed', '7.4', 74, '8.0', false],
            ['null', '7.4', 81, '8.0'],
            ['false', '7.4', 84, '8.0'],
            ['mixed', '7.4', 84, '8.0'],
            ['null', '7.4', 93, '8.0', false],
            ['false', '7.4', 96, '8.0', false],
            ['false', '7.4', 99, '8.0'],
            ['mixed', '7.4', 116, '8.0', false],
        ];
    }


    /**
     * Verify an error is thrown for nullable mixed types.
     *
     * @return void
     */
    public function testInvalidNullableMixed()
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, 74, 'Mixed types cannot be nullable, null is already part of the mixed type');
    }


    /**
     * Test no false positives for non-nullable "mixed" type.
     *
     * @return void
     */
    public function testInvalidNullableMixedNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file, 71);
    }


    /**
     * Verify that an error is thrown for union types.
     *
     * @dataProvider dataNewUnionTypes
     *
     * @param string $type            The declared type.
     * @param array  $line            The line number where the error is expected.
     * @param bool   $testNoViolation Whether or not to test noViolation.
     *                                Defaults to true.
     *
     * @return void
     */
    public function testNewUnionTypes($type, $line, $testNoViolation = true)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, "Union types are not present in PHP version 7.4 or earlier. Found: $type");

        if ($testNoViolation === true) {
            $file = $this->sniffFile(__FILE__, '8.0');
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewUnionTypes()
     *
     * @return array
     */
    public function dataNewUnionTypes()
    {
        return [
            ['int|float', 79],
            ['MyClassA|\Package\MyClassB', 80],
            ['array|bool|int|float|NULL|object|string', 81],
            ['false|mixed|self|parent|iterable|Resource', 84],
            ['callable||void', 87, false],
            ['?int|float', 90],
            ['bool|FALSE', 99],
            ['object|ClassName', 102],
            ['iterable|array|Traversable', 105],
            ['int|string|INT', 108],
            ['float|int', 114],
        ];
    }


    /**
     * Verify an error is thrown when `false` or `null` is used while not a union type.
     *
     * @dataProvider dataInvalidNonUnionNullFalseType
     *
     * @param int    $line Line number on which to expect an error.
     * @param string $type The invalid type which should be expected.
     *
     * @return void
     */
    public function testInvalidNonUnionNullFalseType($line, $type)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, "'$type' type can only be used as part of a union type");
    }

    /**
     * Data provider.
     *
     * @see testInvalidNonUnionNullFalseType()
     *
     * @return array
     */
    public function dataInvalidNonUnionNullFalseType()
    {
        return [
            [93, 'null'],
            [96, 'false'],
        ];
    }


    /**
     * Verify that no error is thrown when `false` or `null` is used within a union type.
     *
     * @dataProvider dataInvalidNonUnionNullFalseTypeNoFalsePositives
     *
     * @param int $line Line number on which to expect an error.
     *
     * @return void
     */
    public function testInvalidNonUnionNullFalseTypeNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testInvalidNonUnionNullFalseTypeNoFalsePositives()
     *
     * @return array
     */
    public function dataInvalidNonUnionNullFalseTypeNoFalsePositives()
    {
        return [
            [81],
            [84],
            [99],
        ];
    }


    /**
     * Verify that an error is thrown for intersection types.
     *
     * @dataProvider dataNewIntersectionTypes
     *
     * @param string $type            The declared type.
     * @param array  $line            The line number where the error is expected.
     * @param bool   $testNoViolation Whether or not to test noViolation.
     *                                Defaults to true.
     *
     * @return void
     */
    public function testNewIntersectionTypes($type, $line, $testNoViolation = true)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertError($file, $line, "Intersection types are not present in PHP version 8.0 or earlier. Found: $type");

        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNewIntersectionTypes()
     *
     * @return array
     */
    public function dataNewIntersectionTypes()
    {
        return [
            ['MyClassA&\Package\MyClassB', 131],
            ['Traversable&\Countable', 132],
            ['int&string', 135],
            ['self&\Fully\Qualified\SomeInterface', 138],
            ['Qualified\SomeInterface&parent', 139],
            ['A&B&A', 142],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will also throw warnings/errors
     * about invalid typed properties.
     */
}
