<?php
/**
 * New Magic Methods Sniff test file.
 *
 * @package PHPCompatibility
 */


/**
 * New Magic Methods Sniff tests.
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewMagicMethodsSniffTest extends BaseSniffTest
{
    /**
     * Whether or not traits will be recognized in PHPCS.
     *
     * @var bool
     */
    protected static $recognizesTraits = true;


    /**
     * Set up skip condition.
     */
    public static function setUpBeforeClass()
    {
        // When using PHPCS 1.x combined with PHP 5.3 or lower, traits are not recognized.
        if (version_compare(PHP_CodeSniffer::VERSION, '2.0', '<') && version_compare(phpversion(), '5.4', '<')) {
            self::$recognizesTraits = false;
        }
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
    protected function getTestFile($isTrait, $testVersion = null) {
        if ($isTrait === false) {
            return $this->sniffFile('sniff-examples/new_magic_methods.php', $testVersion);
        }
        else {
            return $this->sniffFile('sniff-examples/new_magic_methods_traits.php', $testVersion);
        }
    }


    /**
     * Test magic methods that shouldn't be flagged by this sniff.
     *
     * @group newMagicMethods
     *
     * @dataProvider dataMagicMethodsThatShouldntBeFlagged
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testMagicMethodsThatShouldntBeFlagged($line)
    {
        $file = $this->getTestFile(false, '5.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testMagicMethodsThatShouldntBeFlagged()
     *
     * @return array
     */
    public function dataMagicMethodsThatShouldntBeFlagged() {
        return array(
            array(8),
            array(9),
            array(10),
            array(11),
            array(12),
            array(13),
            array(14),
        );
    }


    /**
     * testNewMagicMethod
     *
     * @group newMagicMethods
     *
     * @dataProvider dataNewMagicMethod
     *
     * @param string $methodName        Name of the method.
     * @param string $lastVersionBefore The PHP version just *before* the method became magic.
     * @param array  $lines             The line numbers in the test file which apply to this method.
     * @param string $okVersion         A PHP version in which the method was magic.
     * @param bool   $isTrait           Whether the test relates to a method in a trait.
     *
     * @return void
     */
    public function testNewMagicMethod($methodName, $lastVersionBefore, $lines, $okVersion, $isTrait = false)
    {
        if ($isTrait === true && self::$recognizesTraits === false) {
            $this->markTestSkipped();
            return;
        }

        $file = $this->getTestFile($isTrait, $lastVersionBefore);
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, "The method {$methodName}() was not magical in PHP version {$lastVersionBefore} and earlier. The associated magic functionality will not be invoked.");
        }

        $file = $this->getTestFile($isTrait, $okVersion);
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
    public function dataNewMagicMethod() {
        return array(
            // new_magic_methods.php
            array('__get', '4.4', array(22, 34), '5.0'),
            array('__isset', '5.0', array(23, 35), '5.1'),
            array('__unset', '5.0', array(24, 36), '5.1'),
            array('__set_state', '5.0', array(25, 37), '5.1'),
            array('__callStatic', '5.2', array(27, 39), '5.3'),
            array('__invoke', '5.2', array(28, 40), '5.3'),

            // new_magic_methods_traits.php
            array('__get', '4.4', array(5), '5.0', true),
            array('__isset', '5.0', array(6), '5.1', true),
            array('__unset', '5.0', array(7), '5.1', true),
            array('__set_state', '5.0', array(8), '5.1', true),
            array('__callStatic', '5.2', array(10), '5.3', true),
            array('__invoke', '5.2', array(11), '5.3', true),
        );
    }


    /**
     * testNewDebugInfo
     *
     * {@internal Separate test for __debugInfo() as the noViolation check needs a wrapper
	 * for PHPCS 2.5.1 as the Naming Convention sniff in PHPCS < 2.5.1 does not recognize
	 * __debugInfo() yet, causing the noViolation sniff to fail.
     *
     * @group newMagicMethods
     *
     * @dataProvider dataNewDebugInfo
     *
     * @param string $methodName        Name of the method.
     * @param string $lastVersionBefore The PHP version just *before* the method became magic.
     * @param array  $lines             The line numbers in the test file which apply to this method.
     * @param string $okVersion         A PHP version in which the method was magic.
     * @param bool   $isTrait           Whether the test relates to a method in a trait.
     *
     * @return void
     */
    public function testNewDebugInfo($methodName, $lastVersionBefore, $lines, $okVersion, $isTrait = false)
    {
        if ($isTrait === true && self::$recognizesTraits === false) {
            $this->markTestSkipped();
            return;
        }

        $file = $this->getTestFile($isTrait, $lastVersionBefore);
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, "The method {$methodName}() was not magical in PHP version {$lastVersionBefore} and earlier. The associated magic functionality will not be invoked.");
        }

		if (version_compare(PHP_CodeSniffer::VERSION, '2.5.1', '>=')) {
	        $file = $this->getTestFile($isTrait, $okVersion);
	        foreach ($lines as $line) {
	            $this->assertNoViolation($file, $line);
	        }
		}
    }

    /**
     * Data provider.
     *
     * @see testNewDebugInfo()
     *
     * @return array
     */
    public function dataNewDebugInfo() {
        return array(
            // new_magic_methods.php
            array('__debugInfo', '5.5', array(29, 41), '5.6'),

            // new_magic_methods_traits.php
            array('__debugInfo', '5.5', array(12), '5.6', true),
        );
    }


    /**
     * testChangedToStringMethod
     *
     * @group newMagicMethods
     *
     * @dataProvider dataChangedToStringMethod
     *
     * @param int  $line    The line number.
     * @param bool $isTrait Whether the test relates to a method in a trait.
     *
     * @return void
     */
    public function testChangedToStringMethod($line, $isTrait = false)
    {
        if ($isTrait === true && self::$recognizesTraits === false) {
            $this->markTestSkipped();
            return;
        }

        $file = $this->getTestFile($isTrait, '5.1');
        $this->assertWarning($file, $line, "The method __toString() was not truly magical in PHP version 5.1 and earlier. The associated magic functionality will only be called when directly combined with echo or print.");

        $file = $this->getTestFile($isTrait, '5.2');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testChangedToStringMethod()
     *
     * @return array
     */
    public function dataChangedToStringMethod() {
        return array(
            // new_magic_methods.php
            array(26),
            array(38),

            // new_magic_methods_traits.php
            array(9, true),
        );
    }

}
