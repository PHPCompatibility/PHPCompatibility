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

use PHPCompatibility\Tests\BaseSniffTest;

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
class NewReturnTypeDeclarationsUnitTest extends BaseSniffTest
{

    /**
     * Verify that type declarations not supported in certain PHP versions are flagged correctly.
     *
     * @dataProvider dataReturnType
     *
     * @param string $returnType        The return type.
     * @param string $lastVersionBefore The PHP version just *before* the type was introduced.
     * @param array  $line              The line number in the test file where the error should occur.
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
    public function dataReturnType()
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
            ['mixed', '7.4', 64, '8.0'],
            ['self', '5.6', 64, '8.0'],
            ['parent', '5.6', 64, '8.0'],
            ['static', '7.4', 64, '8.0'],
            ['iterable', '7.0', 64, '8.0'],
            ['Class name', '5.6', 64, '8.0'],
            ['void', '7.0', 64, '8.0'],
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
    public function dataNoFalsePositives()
    {
        return [
            [25],
            [26],
            [42],
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
        $this->assertError($file, 56, 'Mixed types cannot be nullable, null is already part of the mixed type');
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


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw errors
     * for invalid type hints and incorrect usage.
     */
}
