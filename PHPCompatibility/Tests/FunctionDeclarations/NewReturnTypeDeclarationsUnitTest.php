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
 * Test the NewReturnTypeDeclarations sniff.
 *
 * @group newReturnTypeDeclarations
 * @group functionDeclarations
 * @group typeDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\NewReturnTypeDeclarationsSniff
 *
 * @since 7.0.0
 */
class NewReturnTypeDeclarationsUnitTest extends BaseSniffTestCase
{

    /**
     * Verify that type declarations not supported in certain PHP versions are flagged correctly.
     *
     * @dataProvider dataReturnType
     *
     * @param string $returnType        The return type.
     * @param string $lastVersionBefore The PHP version just *before* the type was introduced.
     * @param int    $line              The line number in the test file where the error should occur.
     * @param string $okVersion         A PHP version in which the return type was ok to be used.
     * @param bool   $testNoViolation   Whether or not to test noViolation.
     *                                  Defaults to true.
     *
     * @return void
     */
    public function testReturnType($returnType, $lastVersionBefore, $line, $okVersion, $testNoViolation = true)
    {
        $file = $this->sniffFile(__FILE__, $lastVersionBefore);
        $this->assertError($file, $line, "'{$returnType}' return type is not present in PHP version {$lastVersionBefore} or earlier");

        if ($testNoViolation === true) {
            $file = $this->sniffFile(__FILE__, $okVersion);
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testReturnType()
     *
     * @return array
     */
    public static function dataReturnType()
    {
        return [
            ['bool', '5.6', 4, '7.0'],
            ['int', '5.6', 5, '7.0'],
            ['float', '5.6', 6, '7.0'],
            ['string', '5.6', 7, '7.0'],
            ['array', '5.6', 8, '7.0'],
            ['callable', '5.6', 9, '7.0'],
            ['self', '5.6', 10, '7.0'],
            ['parent', '5.6', 11, '7.0'],
            ['Class name', '5.6', 12, '7.0'],
            ['Class name', '5.6', 13, '7.0'],
            ['Class name', '5.6', 14, '7.0'],
            ['Class name', '5.6', 15, '7.0'],
            ['Class name', '5.6', 35, '7.0'],
            ['int', '5.6', 43, '7.0'],

            ['iterable', '7.0', 18, '7.1'],
            ['void', '7.0', 19, '7.1'],

            ['callable', '5.6', 22, '7.0'],

            ['object', '7.1', 29, '7.2'],

            ['static', '7.4', 47, '8.0'],
            ['static', '7.4', 48, '8.0'],
            ['mixed', '7.4', 53, '8.0'],
            ['mixed', '7.4', 56, '8.0', false],

            // Union types - OK version is 8.0.
            ['int', '5.6', 59, '8.0'],
            ['float', '5.6', 59, '8.0'],
            ['Class name', '5.6', 60, '8.0'],
            ['Class name', '5.6', 60, '8.0'],
            ['array', '5.6', 61, '8.0'],
            ['bool', '5.6', 61, '8.0'],
            ['callable', '5.6', 61, '8.0'],
            ['int', '5.6', 61, '8.0'],
            ['float', '5.6', 61, '8.0'],
            ['null', '7.4', 61, '8.0'],
            ['object', '7.1', 61, '8.0'],
            ['string', '5.6', 61, '8.0'],
            ['false', '7.4', 64, '8.0'],
            ['self', '5.6', 64, '8.0'],
            ['parent', '5.6', 64, '8.0'],
            ['static', '7.4', 64, '8.0'],
            ['iterable', '7.0', 64, '8.0'],
            ['Class name', '5.6', 64, '8.0'],
            ['int', '5.6', 67, '8.0'],
            ['float', '5.6', 67, '8.0'],
            ['null', '7.4', 70, '8.0', false],
            ['false', '7.4', 73, '8.0', false],
            ['bool', '5.6', 76, '8.0'],
            ['false', '7.4', 76, '8.0'],
            ['object', '7.1', 79, '8.0'],
            ['Class name', '5.6', 79, '8.0'],
            ['iterable', '7.0', 83, '8.0'],
            ['array', '5.6', 83, '8.0'],
            ['Class name', '5.6', 83, '8.0'],
            ['int', '5.6', 87, '8.0'],
            ['string', '5.6', 87, '8.0'],
            ['int', '5.6', 87, '8.0'],
            ['never', '8.0', 90, '8.1'],
            ['never', '8.0', 94, '8.1'],

            // Intersection types - OK version is 8.1.
            ['int', '5.6', 118, '8.1'],
            ['string', '5.6', 118, '8.1'],
            ['self', '5.6', 122, '8.1'],
            ['parent', '5.6', 123, '8.1'],
            ['static', '7.4', 124, '8.1'],

            ['true', '8.1', 131, '8.2'],

            ['string', '5.6', 136, '7.0'],
            ['mixed', '7.4', 137, '8.0'],
            ['Class name', '5.6', 138, '8.0'],
        ];
    }


    /**
     * Verify that no false positives are thrown for function declarations without types.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6'); // Low version below the first addition.
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
        return [
            [25],
            [26],
            [42],
        ];
    }


    /**
     * Verify an error is thrown for combining stand-alone types with the nullability operator or using them in a union type.
     *
     * @dataProvider dataInvalidStandAloneType
     *
     * @param string $type        The declared type.
     * @param int    $line        The line number where the error is expected.
     * @param string $testVersion The PHP version in which the type was first allowed to be used.
     *
     * @return void
     */
    public function testInvalidStandAloneType($type, $line, $testVersion)
    {
        $file = $this->sniffFile(__FILE__, $testVersion);
        $this->assertError($file, $line, "The '$type' type can only be used as a standalone type");
    }

    /**
     * Data provider.
     *
     * @see testInvalidStandAloneType()
     *
     * @return array
     */
    public static function dataInvalidStandAloneType()
    {
        return [
            ['mixed', 56, '8.0'],
            ['void', 100, '7.1'],
            ['void', 101, '7.1'],
            ['mixed', 104, '8.0'],
            ['never', 107, '8.1'],
            ['never', 108, '8.1'],
        ];
    }


    /**
     * Test no false positives for non-nullable "mixed" type.
     *
     * @return void
     */
    public function testInvalidNullableMixedNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file, 53);
    }


    /**
     * Verify that an error is thrown for union types.
     *
     * @dataProvider dataNewUnionTypes
     *
     * @param string $type The declared type.
     * @param int    $line The line number where the error is expected.
     *
     * @return void
     */
    public function testNewUnionTypes($type, $line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, "Union types are not present in PHP version 7.4 or earlier. Found: $type");

        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNewUnionTypes()
     *
     * @return array
     */
    public static function dataNewUnionTypes()
    {
        return [
            ['int|float', 59],
            ['MyClassA|\Package\MyClassB', 60],
            ['array|bool|callable|int|float|null|Object|string', 61],
            ['false|self|parent|static|iterable|Resource', 64],
            ['?int|float', 67],
            ['bool|false', 76],
            ['object|ClassName', 79],
            ['iterable|array|Traversable', 83],
            ['int|string|INT', 87],
            ['A|B', 138],
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
        $this->assertError($file, $line, "'$type' type can only be used as part of a union type in PHP 8.1 or earlier");

        $file = $this->sniffFile(__FILE__, '8.2');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testInvalidNonUnionNullFalseType()
     *
     * @return array
     */
    public static function dataInvalidNonUnionNullFalseType()
    {
        return [
            [70, 'null'],
            [73, 'false'],
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
    public static function dataInvalidNonUnionNullFalseTypeNoFalsePositives()
    {
        return [
            [61],
            [64],
            [76],
        ];
    }


    /**
     * Verify that an error is thrown for intersection types.
     *
     * @dataProvider dataNewIntersectionTypes
     *
     * @param string $type The declared type.
     * @param int    $line The line number where the error is expected.
     *
     * @return void
     */
    public function testNewIntersectionTypes($type, $line)
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
    public static function dataNewIntersectionTypes()
    {
        return [
            ['MyClassA&\Package\MyClassB', 112],
            ['Traversable&\Countable', 114],
            ['int&string', 118],
            ['self&\Fully\Qualified\SomeInterface', 122],
            ['Qualified\SomeInterface&parent', 123],
            ['static&SomeInterface', 124],
            ['A&B&A', 128],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw errors
     * for invalid type hints and incorrect usage.
     */
}
