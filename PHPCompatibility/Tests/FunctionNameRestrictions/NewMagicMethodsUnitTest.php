<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionNameRestrictions;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewMagicMethods sniff.
 *
 * @group newMagicMethods
 * @group functionNameRestrictions
 * @group magicMethods
 *
 * @covers \PHPCompatibility\Sniffs\FunctionNameRestrictions\NewMagicMethodsSniff
 *
 * @since 7.0.4
 */
class NewMagicMethodsUnitTest extends BaseSniffTest
{

    /**
     * testNewMagicMethod
     *
     * @dataProvider dataNewMagicMethod
     *
     * @param string $methodName        Name of the method.
     * @param string $lastVersionBefore The PHP version just *before* the method became magic.
     * @param array  $lines             The line numbers in the test file which apply to this method.
     * @param string $okVersion         A PHP version in which the method was magic.
     *
     * @return void
     */
    public function testNewMagicMethod($methodName, $lastVersionBefore, $lines, $okVersion)
    {
        $file  = $this->sniffFile(__FILE__, $lastVersionBefore);
        $error = "The method {$methodName}() was not magical in PHP version {$lastVersionBefore} and earlier. The associated magic functionality will not be invoked.";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewMagicMethod()
     *
     * @return array
     */
    public function dataNewMagicMethod()
    {
        return [
            ['__construct', '4.4', [20], '5.0'],
            ['__destruct', '4.4', [21], '5.0'],
            ['__get', '4.4', [22, 34, 61], '5.0'],
            ['__isset', '5.0', [23, 35, 62], '5.1'],
            ['__unset', '5.0', [24, 36, 63], '5.1'],
            ['__set_state', '5.0', [25, 37, 64], '5.1'],
            ['__callStatic', '5.2', [27, 39, 66], '5.3'],
            ['__invoke', '5.2', [28, 40, 67], '5.3'],
            ['__debugInfo', '5.5', [29, 41, 68], '5.6'],
            ['__serialize', '7.3', [78], '7.4'],
            ['__unserialize', '7.3', [79], '7.4'],

            // Traits.
            ['__get', '4.4', [87], '5.0'],
            ['__isset', '5.0', [88], '5.1'],
            ['__unset', '5.0', [89], '5.1'],
            ['__set_state', '5.0', [90], '5.1'],
            ['__callStatic', '5.2', [92], '5.3'],
            ['__invoke', '5.2', [93], '5.3'],
            ['__debugInfo', '5.5', [94], '5.6'],
            ['__serialize', '7.3', [95], '7.4'],
            ['__unserialize', '7.3', [96], '7.4'],
            ['__construct', '4.4', [97], '5.0'],
            ['__destruct', '4.4', [98], '5.0'],

            ['__serialize', '7.3', [126], '7.4'],
            ['__unserialize', '7.3', [127], '7.4'],
        ];
    }


    /**
     * testChangedToStringMethod
     *
     * @dataProvider dataChangedToStringMethod
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testChangedToStringMethod($line)
    {
        $file = $this->sniffFile(__FILE__, '5.1');
        $this->assertWarning($file, $line, 'The method __toString() was not truly magical in PHP version 5.1 and earlier. The associated magic functionality will only be called when directly combined with echo or print.');

        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testChangedToStringMethod()
     *
     * @return array
     */
    public function dataChangedToStringMethod()
    {
        return [
            [26],
            [38],
            [65],
            [91],
        ];
    }


    /**
     * Test magic methods that shouldn't be flagged by this sniff.
     *
     * @dataProvider dataMagicMethodsThatShouldntBeFlagged
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testMagicMethodsThatShouldntBeFlagged($line)
    {
        $file = $this->sniffFile(__FILE__, '4.4'); // Low version below the first addition.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testMagicMethodsThatShouldntBeFlagged()
     *
     * @return array
     */
    public function dataMagicMethodsThatShouldntBeFlagged()
    {
        return [
            [8],
            [9],
            [10],
            [11],
            [12],
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
        $file = $this->sniffFile(__FILE__, '4.4'); // Low version below the first addition.
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
            // Functions of same name outside class context.
            [47],
            [48],
            [49],
            [50],
            [51],
            [52],
            [53],
            [54],
            [74],
            [75],

            // Magic serialization methods in a class implementing Serializable.
            [112],
            [115],
            [121],
            [122],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond newest addition.
        $this->assertNoViolation($file);
    }
}
