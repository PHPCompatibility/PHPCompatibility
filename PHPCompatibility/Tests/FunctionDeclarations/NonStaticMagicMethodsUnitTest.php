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
 * Test the NonStaticMagicMethods sniff.
 *
 * @group nonStaticMagicMethods
 * @group functionDeclarations
 * @group magicMethods
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\NonStaticMagicMethodsSniff
 *
 * @since 5.5
 */
class NonStaticMagicMethodsUnitTest extends BaseSniffTest
{

    /**
     * testWrongMethodVisibility
     *
     * @dataProvider dataWrongMethodVisibility
     *
     * @param string $methodName        Method name.
     * @param string $desiredVisibility The visibility the method should have.
     * @param string $testVisibility    The visibility the method actually has in the test.
     * @param int    $line              The line number.
     *
     * @return void
     */
    public function testWrongMethodVisibility($methodName, $desiredVisibility, $testVisibility, $line)
    {
        $file = $this->sniffFile(__FILE__, '5.3-99.0');
        $this->assertError($file, $line, "Visibility for magic method {$methodName} must be {$desiredVisibility}. Found: {$testVisibility}");
    }

    /**
     * Data provider.
     *
     * @see testWrongMethodVisibility()
     *
     * @return array
     */
    public function dataWrongMethodVisibility()
    {
        return [
            // Class.
            ['__get', 'public', 'private', 32],
            ['__set', 'public', 'protected', 33],
            ['__isset', 'public', 'private', 34],
            ['__unset', 'public', 'protected', 35],
            ['__call', 'public', 'private', 36],
            ['__callStatic', 'public', 'protected', 37],
            ['__sleep', 'public', 'private', 38],
            ['__toString', 'public', 'protected', 39],

            // Alternative property order & stacked.
            ['__set', 'public', 'protected', 56],
            ['__isset', 'public', 'private', 57],
            ['__get', 'public', 'private', 65],

            // Interface.
            ['__get', 'public', 'protected', 98],
            ['__set', 'public', 'private', 99],
            ['__isset', 'public', 'protected', 100],
            ['__unset', 'public', 'private', 101],
            ['__call', 'public', 'protected', 102],
            ['__callStatic', 'public', 'private', 103],
            ['__sleep', 'public', 'protected', 104],
            ['__toString', 'public', 'private', 105],

            // Anonymous class.
            ['__get', 'public', 'private', 149],
            ['__set', 'public', 'protected', 150],
            ['__isset', 'public', 'private', 151],
            ['__unset', 'public', 'protected', 152],
            ['__call', 'public', 'private', 153],
            ['__callStatic', 'public', 'protected', 154],
            ['__sleep', 'public', 'private', 155],
            ['__toString', 'public', 'protected', 156],

            // PHP 7.4: __(un)serialize()
            ['__serialize', 'public', 'protected', 179],
            ['__unserialize', 'public', 'private', 180],

            // More magic methods.
            ['__debugInfo', 'public', 'protected', 202],
            ['__invoke', 'public', 'private', 203],
            ['__set_state', 'public', 'protected', 204],

            // Trait.
            ['__get', 'public', 'private', 250],
            ['__set', 'public', 'protected', 251],
            ['__isset', 'public', 'private', 252],
            ['__unset', 'public', 'protected', 253],
            ['__call', 'public', 'private', 254],
            ['__callStatic', 'public', 'protected', 255],
            ['__sleep', 'public', 'private', 256],
            ['__toString', 'public', 'protected', 257],
            ['__serialize', 'public', 'private', 258],
            ['__unserialize', 'public', 'protected', 259],
        ];
    }


    /**
     * testWrongStaticMethod
     *
     * @dataProvider dataWrongStaticMethod
     *
     * @param string $methodName Method name.
     * @param int    $line       The line number.
     *
     * @return void
     */
    public function testWrongStaticMethod($methodName, $line)
    {
        $file = $this->sniffFile(__FILE__, '5.3-99.0');
        $this->assertError($file, $line, "Magic method {$methodName} cannot be defined as static.");
    }

    /**
     * Data provider.
     *
     * @see testWrongStaticMethod()
     *
     * @return array
     */
    public function dataWrongStaticMethod()
    {
        return [
            // Class.
            ['__get', 44],
            ['__set', 45],
            ['__isset', 46],
            ['__unset', 47],
            ['__call', 48],

            // Alternative property order & stacked.
            ['__get', 55],
            ['__set', 56],
            ['__isset', 57],
            ['__get', 65],

            // Interface.
            ['__get', 110],
            ['__set', 111],
            ['__isset', 112],
            ['__unset', 113],
            ['__call', 114],

            // Anonymous class.
            ['__get', 161],
            ['__set', 162],
            ['__isset', 163],
            ['__unset', 164],
            ['__call', 165],

            // PHP 7.4: __(un)serialize()
            ['__serialize', 185],
            ['__unserialize', 186],

            // More magic methods.
            ['__construct', 209],
            ['__destruct', 210],
            ['__clone', 211],
            ['__debugInfo', 212],
            ['__invoke', 213],

            // Trait.
            ['__get', 264],
            ['__set', 265],
            ['__isset', 266],
            ['__unset', 267],
            ['__call', 268],
            ['__serialize', 271],
            ['__unserialize', 272],
        ];
    }


    /**
     * testWrongNonStaticMethod
     *
     * @dataProvider dataWrongNonStaticMethod
     *
     * @param string $methodName Method name.
     * @param int    $line       The line number.
     *
     * @return void
     */
    public function testWrongNonStaticMethod($methodName, $line)
    {
        $file = $this->sniffFile(__FILE__, '5.3-99.0');
        $this->assertError($file, $line, "Magic method {$methodName} must be defined as static.");
    }

    /**
     * Data provider.
     *
     * @see testWrongNonStaticMethod()
     *
     * @return array
     */
    public function dataWrongNonStaticMethod()
    {
        return [
            // Class.
            ['__callStatic', 49],
            ['__set_state', 50],

            // Interface.
            ['__callStatic', 115],
            ['__set_state', 116],

            // Anonymous class.
            ['__callStatic', 166],
            ['__set_state', 167],

            // Trait.
            ['__callStatic', 269],
            ['__set_state', 270],

        ];
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.3-99.0');
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
            // Plain class.
            [5],
            [6],
            [7],
            [8],
            [9],
            [10],
            [11],
            [12],
            [13],
            // Normal class.
            [18],
            [19],
            [20],
            [21],
            [22],
            [23],
            [24],
            [25],
            [26],
            [27],

            // Alternative property order & stacked.
            [58],

            // Plain interface.
            [71],
            [72],
            [73],
            [74],
            [75],
            [76],
            [77],
            [78],
            [79],
            // Normal interface.
            [84],
            [85],
            [86],
            [87],
            [88],
            [89],
            [90],
            [91],
            [92],
            [93],

            // Plain anonymous class.
            [122],
            [123],
            [124],
            [125],
            [126],
            [127],
            [128],
            [129],
            [130],
            // Normal anonymous class.
            [135],
            [136],
            [137],
            [138],
            [139],
            [140],
            [141],
            [142],
            [143],
            [144],

            // PHP 7.4: __(un)serialize()
            [173],
            [174],

            // More magic methods.
            [192],
            [193],
            [194],
            [195],
            [196],
            [201],

            // Plain trait.
            [219],
            [220],
            [221],
            [222],
            [223],
            [224],
            [225],
            [226],
            [227],
            [228],
            [229],

            // Normal trait.
            [234],
            [235],
            [236],
            [237],
            [238],
            [239],
            [240],
            [241],
            [242],
            [243],
            [244],
            [245],

            // Nested function.
            [277],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertNoViolation($file);
    }
}
