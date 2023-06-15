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
 * Test the NewClosure sniff.
 *
 * @group newClosure
 * @group functionDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\NewClosureSniff
 *
 * @since 7.0.0
 */
class NewClosureUnitTest extends BaseSniffTest
{

    /**
     * Test closures
     *
     * @dataProvider dataClosure
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testClosure($line)
    {
        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertError($file, $line, 'Closures / anonymous functions are not available in PHP 5.2 or earlier');

        $file = $this->sniffFile(__FILE__, '5.4'); // Testing against 5.4 to get past 5.3/static notices.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testClosure()
     *
     * @return array
     */
    public static function dataClosure()
    {
        return [
            [3],
            [14],
            [22],
            [31],
            [40],
            [47],
            [52],
            [59],
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertNoViolation($file, 6);
    }


    /**
     * Test static closures
     *
     * @dataProvider dataStaticClosure
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testStaticClosure($line)
    {
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertError($file, $line, 'Closures / anonymous functions could not be declared as static in PHP 5.3 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testStaticClosure()
     *
     * @return array
     */
    public static function dataStaticClosure()
    {
        return [
            [14],
            [31],
            [40],
            [47],
        ];
    }


    /**
     * Test using $this in closures
     *
     * @dataProvider dataThisInClosure
     *
     * @param int  $line            The line number.
     * @param bool $testNoViolation Whether or not to run the noViolation test.
     *
     * @return void
     */
    public function testThisInClosure($line, $testNoViolation = true)
    {
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertError($file, $line, 'Closures / anonymous functions did not have access to $this in PHP 5.3 or earlier');

        if ($testNoViolation === true) {
            $file = $this->sniffFile(__FILE__, '5.4');
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testThisInClosure()
     *
     * @return array
     */
    public static function dataThisInClosure()
    {
        return [
            [23],
            [24],
            [32, false],
            [33, false],
            [53, false],
            [68],
        ];
    }


    /**
     * Test using $this in static closures
     *
     * @dataProvider dataThisInStaticClosure
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testThisInStaticClosure($line)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertError($file, $line, 'Closures / anonymous functions declared as static do not have access to $this');
    }

    /**
     * Data provider.
     *
     * @see testThisInStaticClosure()
     *
     * @return array
     */
    public static function dataThisInStaticClosure()
    {
        return [
            [32],
            [33],
        ];
    }


    /**
     * Test no false positives for using $this in static closures
     *
     * @return void
     */
    public function testNoFalsePositivesThisInStaticClosure()
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertNoViolation($file, 41);
    }


    /**
     * Test using $this in closure outside class context
     *
     * @return void
     */
    public function testThisInClosureOutsideClass()
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertWarning($file, 53, 'Closures / anonymous functions only have access to $this if used within a class or when bound to an object using bindTo(). Please verify.');
    }


    /**
     * Test no false positives for using $this in static closures
     *
     * @dataProvider dataNoFalsePositivesThisInClosureOutsideClass
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesThisInClosureOutsideClass($line)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesThisInClosureOutsideClass()
     *
     * @return array
     */
    public static function dataNoFalsePositivesThisInClosureOutsideClass()
    {
        return [
            [48],
            [60],
        ];
    }


    /**
     * Test using self/parent/static in closures.
     *
     * @dataProvider dataClassRefInClosure
     *
     * @param int    $line The line number.
     * @param string $ref  The class reference encountered.
     *
     * @return void
     */
    public function testClassRefInClosure($line, $ref)
    {
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertError($file, $line, 'Closures / anonymous functions could not use "' . $ref . '::" in PHP 5.3 or earlier');

        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testClassRefInClosure()
     *
     * @return array
     */
    public static function dataClassRefInClosure()
    {
        return [
            [83, 'self'],
            [84, 'self'],
            [85, 'parent'],
            [86, 'static'],
        ];
    }


    /**
     * Test no false positives for other uses of static within closures.
     *
     * @dataProvider dataNoFalsePositivesClassRefInClosure
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesClassRefInClosure($line)
    {
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesClassRefInClosure()
     *
     * @return array
     */
    public static function dataNoFalsePositivesClassRefInClosure()
    {
        return [
            [88],
            [90],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw warnings/errors
     * about the use of closures in PHP < 5.3 and about invalid usage of $this in closures for PHP 5.4+.
     */
}
