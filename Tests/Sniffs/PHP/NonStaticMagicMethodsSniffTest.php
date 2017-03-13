<?php
/**
 * Non Static Magic Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Non Static Magic Sniff tests
 *
 * @group nonStaticMagicMethods
 * @group magicMethods
 *
 * @covers PHPCompatibility_Sniffs_PHP_NonStaticMagicMethodsSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class NonStaticMagicMethodsSniffTest extends BaseSniffTest
{
    /**
     * Whether or not traits will be recognized in PHPCS.
     *
     * @var bool
     */
    protected static $recognizesTraits = true;


    /**
     * Set up skip condition.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        // When using PHPCS 1.x combined with PHP 5.3 or lower, traits are not recognized.
        if (version_compare(PHP_CodeSniffer::VERSION, '2.0', '<') && version_compare(phpversion(), '5.4', '<')) {
            self::$recognizesTraits = false;
        }

        parent::setUpBeforeClass();
    }


    /**
     * Get the correct test file.
     *
     * (@internal
     * The test file has been split into two:
     * - one covering classes and interfaces
     * - one covering traits
     *
     * This is to avoid test failing because PHPCS 1.x gets confused about the scope
     * openers/closers when run on PHP 5.3 or lower.
     * In a 'normal' situation you won't often find classes, interfaces and traits all
     * mixed in one file anyway, so this issue for which this is a work-around,
     * should not cause real world issues anyway.}}
     *
     * @param bool   $isTrait     Whether to load the class/interface test file or the trait test file.
     * @param string $testVersion Value of 'testVersion' to set on PHPCS object.
     *
     * @return PHP_CodeSniffer_File File object|false
     */
    protected function getTestFile($isTrait, $testVersion = null)
    {
        if ($isTrait === false) {
            return $this->sniffFile('sniff-examples/nonstatic_magic_methods.php', $testVersion);
        } else {
            return $this->sniffFile('sniff-examples/nonstatic_magic_methods_traits.php', $testVersion);
        }
    }


    /**
     * testWrongMethodVisibility
     *
     * @dataProvider dataWrongMethodVisibility
     *
     * @param string $methodName        Method name.
     * @param string $desiredVisibility The visibility the method should have.
     * @param string $testVisibility    The visibility the method actually has in the test.
     * @param int    $line              The line number.
     * @param bool   $isTrait           Whether the test relates to method in a trait.
     *
     * @return void
     */
    public function testWrongMethodVisibility($methodName, $desiredVisibility, $testVisibility, $line, $isTrait = false)
    {
        if ($isTrait === true && self::$recognizesTraits === false) {
            $this->markTestSkipped();
            return;
        }

        $file = $this->getTestFile($isTrait, '5.3-99.0');
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
            /*
             * nonstatic_magic_methods.php
             */
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

            /*
             * nonstatic_magic_methods_traits.php
             */
            // Trait.
            array('__get', 'public', 'private', 32, true),
            array('__set', 'public', 'protected', 33, true),
            array('__isset', 'public', 'private', 34, true),
            array('__unset', 'public', 'protected', 35, true),
            array('__call', 'public', 'private', 36, true),
            array('__callStatic', 'public', 'protected', 37, true),
            array('__sleep', 'public', 'private', 38, true),
            array('__toString', 'public', 'protected', 39, true),

        );
    }


    /**
     * testWrongStaticMethod
     *
     * @dataProvider dataWrongStaticMethod
     *
     * @param string $methodName Method name.
     * @param int    $line       The line number.
     * @param bool   $isTrait    Whether the test relates to a method in a trait.
     *
     * @return void
     */
    public function testWrongStaticMethod($methodName, $line, $isTrait = false)
    {
        if ($isTrait === true && self::$recognizesTraits === false) {
            $this->markTestSkipped();
            return;
        }

        $file = $this->getTestFile($isTrait, '5.3-99.0');
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
            /*
             * nonstatic_magic_methods.php
             */
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

            /*
             * nonstatic_magic_methods_traits.php
             */
            // Trait.
            array('__get', 44, true),
            array('__set', 45, true),
            array('__isset', 46, true),
            array('__unset', 47, true),
            array('__call', 48, true),

        );
    }


    /**
     * testWrongNonStaticMethod
     *
     * @dataProvider dataWrongNonStaticMethod
     *
     * @param string $methodName Method name.
     * @param int    $line       The line number.
     * @param bool   $isTrait    Whether the test relates to a method in a trait.
     *
     * @return void
     */
    public function testWrongNonStaticMethod($methodName, $line, $isTrait = false)
    {
        if ($isTrait === true && self::$recognizesTraits === false) {
            $this->markTestSkipped();
            return;
        }

        $file = $this->getTestFile($isTrait, '5.3-99.0');
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
            /*
             * nonstatic_magic_methods.php
             */
            // Class.
            array('__callStatic', 49),
            array('__set_state', 50),

            // Interface.
            array('__callStatic', 115),
            array('__set_state', 116),

            /*
             * nonstatic_magic_methods_traits.php
             */
            // Trait.
            array('__callStatic', 49, true),
            array('__set_state', 50, true),

        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int  $line    The line number.
     * @param bool $isTrait Whether to load the class/interface test file or the trait test file.
     *
     * @return void
     */
    public function testNoFalsePositives($line, $isTrait = false)
    {
        if ($isTrait === true && self::$recognizesTraits === false) {
            $this->markTestSkipped();
            return;
        }

        $file = $this->getTestFile($isTrait, '5.3-99.0');
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
            /*
             * nonstatic_magic_methods.php
             */
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

            /*
             * nonstatic_magic_methods_traits.php
             */
            // Plain trait.
            array(5, true),
            array(6, true),
            array(7, true),
            array(8, true),
            array(9, true),
            array(10, true),
            array(11, true),
            array(12, true),
            array(13, true),
            // Normal trait.
            array(18, true),
            array(19, true),
            array(20, true),
            array(21, true),
            array(22, true),
            array(23, true),
            array(24, true),
            array(25, true),
            array(26, true),
            array(27, true),

        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        // nonstatic_magic_methods.php
        $file = $this->getTestFile(false, '5.2');
        $this->assertNoViolation($file);

        // nonstatic_magic_methods_traits.php
        $file = $this->getTestFile(true, '5.2');
        $this->assertNoViolation($file);
    }

}
