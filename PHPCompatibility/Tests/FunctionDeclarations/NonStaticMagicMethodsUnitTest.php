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
        return array(
            // Class.
            array('__get', 'public', 'private', 32),
            array('__set', 'public', 'protected', 33),
            array('__isset', 'public', 'private', 34),
            array('__unset', 'public', 'protected', 35),
            array('__call', 'public', 'private', 36),
            array('__callStatic', 'public', 'protected', 37),
            array('__sleep', 'public', 'private', 38),
            array('__toString', 'public', 'protected', 39),

            // Alternative property order & stacked.
            array('__set', 'public', 'protected', 56),
            array('__isset', 'public', 'private', 57),
            array('__get', 'public', 'private', 65),

            // Interface.
            array('__get', 'public', 'protected', 98),
            array('__set', 'public', 'private', 99),
            array('__isset', 'public', 'protected', 100),
            array('__unset', 'public', 'private', 101),
            array('__call', 'public', 'protected', 102),
            array('__callStatic', 'public', 'private', 103),
            array('__sleep', 'public', 'protected', 104),
            array('__toString', 'public', 'private', 105),

            // Anonymous class.
            array('__get', 'public', 'private', 149),
            array('__set', 'public', 'protected', 150),
            array('__isset', 'public', 'private', 151),
            array('__unset', 'public', 'protected', 152),
            array('__call', 'public', 'private', 153),
            array('__callStatic', 'public', 'protected', 154),
            array('__sleep', 'public', 'private', 155),
            array('__toString', 'public', 'protected', 156),

            // PHP 7.4: __(un)serialize()
            array('__serialize', 'public', 'protected', 179),
            array('__unserialize', 'public', 'private', 180),

            // More magic methods.
            array('__destruct', 'public', 'private', 201),
            array('__debugInfo', 'public', 'protected', 202),
            array('__invoke', 'public', 'private', 203),
            array('__set_state', 'public', 'protected', 204),

            // Trait.
            array('__get', 'public', 'private', 250),
            array('__set', 'public', 'protected', 251),
            array('__isset', 'public', 'private', 252),
            array('__unset', 'public', 'protected', 253),
            array('__call', 'public', 'private', 254),
            array('__callStatic', 'public', 'protected', 255),
            array('__sleep', 'public', 'private', 256),
            array('__toString', 'public', 'protected', 257),
            array('__serialize', 'public', 'private', 258),
            array('__unserialize', 'public', 'protected', 259),
        );
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
        return array(
            // Class.
            array('__get', 44),
            array('__set', 45),
            array('__isset', 46),
            array('__unset', 47),
            array('__call', 48),

            // Alternative property order & stacked.
            array('__get', 55),
            array('__set', 56),
            array('__isset', 57),
            array('__get', 65),

            // Interface.
            array('__get', 110),
            array('__set', 111),
            array('__isset', 112),
            array('__unset', 113),
            array('__call', 114),

            // Anonymous class.
            array('__get', 161),
            array('__set', 162),
            array('__isset', 163),
            array('__unset', 164),
            array('__call', 165),

            // PHP 7.4: __(un)serialize()
            array('__serialize', 185),
            array('__unserialize', 186),

            // More magic methods.
            array('__construct', 209),
            array('__destruct', 210),
            array('__clone', 211),
            array('__debugInfo', 212),
            array('__invoke', 213),

            // Trait.
            array('__get', 264),
            array('__set', 265),
            array('__isset', 266),
            array('__unset', 267),
            array('__call', 268),
            array('__serialize', 271),
            array('__unserialize', 272),
        );
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
        return array(
            // Class.
            array('__callStatic', 49),
            array('__set_state', 50),

            // Interface.
            array('__callStatic', 115),
            array('__set_state', 116),

            // Anonymous class.
            array('__callStatic', 166),
            array('__set_state', 167),

            // Trait.
            array('__callStatic', 269),
            array('__set_state', 270),

        );
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
        return array(
            // Plain class.
            array(5),
            array(6),
            array(7),
            array(8),
            array(9),
            array(10),
            array(11),
            array(12),
            array(13),
            // Normal class.
            array(18),
            array(19),
            array(20),
            array(21),
            array(22),
            array(23),
            array(24),
            array(25),
            array(26),
            array(27),

            // Alternative property order & stacked.
            array(58),

            // Plain interface.
            array(71),
            array(72),
            array(73),
            array(74),
            array(75),
            array(76),
            array(77),
            array(78),
            array(79),
            // Normal interface.
            array(84),
            array(85),
            array(86),
            array(87),
            array(88),
            array(89),
            array(90),
            array(91),
            array(92),
            array(93),

            // Plain anonymous class.
            array(122),
            array(123),
            array(124),
            array(125),
            array(126),
            array(127),
            array(128),
            array(129),
            array(130),
            // Normal anonymous class.
            array(135),
            array(136),
            array(137),
            array(138),
            array(139),
            array(140),
            array(141),
            array(142),
            array(143),
            array(144),

            // PHP 7.4: __(un)serialize()
            array(173),
            array(174),

            // More magic methods.
            array(192),
            array(193),
            array(194),
            array(195),
            array(196),

            // Plain trait.
            array(219),
            array(220),
            array(221),
            array(222),
            array(223),
            array(224),
            array(225),
            array(226),
            array(227),
            array(228),
            array(229),

            // Normal trait.
            array(234),
            array(235),
            array(236),
            array(237),
            array(238),
            array(239),
            array(240),
            array(241),
            array(242),
            array(243),
            array(244),
            array(245),
        );
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
