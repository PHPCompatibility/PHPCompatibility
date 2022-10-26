<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Core;

use PHPUnit\Framework\TestCase;
use PHPCompatibility\Util\Tests\TestHelperPHPCompatibility;

/**
 * Tests for various stand-alone utility functions.
 *
 * @group utilityMiscFunctions
 * @group utilityFunctions
 *
 * @since 7.0.6
 */
class FunctionsUnitTest extends TestCase
{

    /**
     * A wrapper for the abstract PHPCompatibility sniff.
     *
     * @var \PHPCompatibility\Sniff
     */
    protected $helperClass;

    /**
     * Sets up this unit test.
     *
     * @before
     *
     * @return void
     */
    protected function setUpHelper()
    {
        $this->helperClass = new TestHelperPHPCompatibility();
    }

    /**
     * Clean up after finished test.
     *
     * @after
     *
     * @return void
     */
    protected function destroyHelper()
    {
        $this->helperClass = null;
    }

    /**
     * testStripVariables
     *
     * @dataProvider dataStripVariables
     *
     * @covers \PHPCompatibility\Sniff::stripVariables
     *
     * @param string $input    The input string.
     * @param string $expected The expected function output.
     *
     * @return void
     */
    public function testStripVariables($input, $expected)
    {
        $this->assertSame($expected, $this->helperClass->stripVariables($input));
    }

    /**
     * dataStripVariables
     *
     * @see testStripVariables()
     *
     * @return array
     */
    public function dataStripVariables()
    {
        return [
            // These would need to be matched when testing double quoted strings for variables.
            ['"He drank some $juice juice."', '"He drank some  juice."'],
            ['"He drank some juice made of $juices."', '"He drank some juice made of ."'],
            ['"He drank some juice made of ${juice}s."', '"He drank some juice made of s."'],
            ['"He drank some $juices[0] juice."', '"He drank some  juice."'],
            ['"He drank some $juices[1] juice."', '"He drank some  juice."'],
            ['"He drank some $juices[koolaid1] juice."', '"He drank some  juice."'],
            ['"$people->john drank some $juices[0] juice."', '" drank some  juice."'],
            ['"$people->john then said hello to $people->jane."', '" then said hello to ."'],
            ['"$people->john\'s wife greeted $people->robert."', '"\'s wife greeted ."'],
            ['"The element at index -3 is $array[-3]."', '"The element at index -3 is ."'],
            ['"This is {$great}"', '"This is "'],
            ['"This square is {$square->width}00 centimeters broad."', '"This square is 00 centimeters broad."'],
            ['"This works: {$arr[\'key\']}"', '"This works: "'],
            ['"This works: {$arr[4][3]}"', '"This works: "'],
            ['"This is wrong: {$arr[foo][3]}"', '"This is wrong: "'],
            ['"This works: {$arr[\'foo\'][3]}"', '"This works: "'],
            ['"This works too: {$obj->values[3]->name}"', '"This works too: "'],

            ['"This is the value of the var named $name: {${$name}}"', '"This is the value of the var named : "'],
            ['"This is the value of the var named \$name: {${$name}}"', '"This is the value of the var named \$name: "'],
            ['"This is the value of the var named by the return value of getName(): {${getName()}}"', '"This is the value of the var named by the return value of getName(): "'],
            ['"This is the value of the var named by the return value of getName(): {${getName( $test )}}"', '"This is the value of the var named by the return value of getName(): "'],
            ['"This is the value of the var named by the return value of getName(): {${getName( \'abc\' )}}"', '"This is the value of the var named by the return value of getName(): "'],
            ['"This is the value of the var named by the return value of \$object->getName(): {${$object->getName()}}"', '"This is the value of the var named by the return value of \$object->getName(): "'],
            ['"{$foo->$bar}\n"', '"\n"'],
            ['"I\'d like an {${beers::softdrink}}\n"', '"I\'d like an \n"'],
            ['"I\'d like an {${beers::$ale}}\n"', '"I\'d like an \n"'],

            ['"{$foo->{$baz[1]}}\n"', '"{->}\n"'], // Problem var, only one I haven't managed to work out properly.

            // These shouldn't match and should be returned as is.
            ['"He drank some \\\\$juice juice."', '"He drank some \\\\$juice juice."'],
            ['"This is { $great}"', '"This is { }"'],
            ['"This is the return value of getName(): {getName()}"', '"This is the return value of getName(): {getName()}"'],
        ];
    }
}
