<?php
/**
 * __autoload deprecation for PHP 7.2 sniff test
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;
use PHPCompatibility\PHPCSHelper;

/**
 * __autoload deprecation for PHP 7.2 sniff test
 *
 * @group deprecatedMagicAutoload
 * @group functionDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\PHP\DeprecatedMagicAutoloadSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Wim Godden <wim.godden@cu.be>
 */
class DeprecatedMagicAutoloadSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/deprecated_magic_autoload.php';
    const TEST_FILE_NAMESPACED = 'sniff-examples/deprecated_magic_autoload_namespaced.php';

    /**
     * Whether or not traits and interfaces will be recognized in PHPCS.
     *
     * @var bool
     */
    protected static $recognizesTraitsOrInterfaces = true;

    /**
     * Set up skip condition.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        // When using PHPCS 2.3.4 or lower combined with PHP 5.3 or lower, traits are not recognized.
        if (version_compare(PHPCSHelper::getVersion(), '2.4.0', '<') && version_compare(PHP_VERSION_ID, '50400', '<')) {
            self::$recognizesTraitsOrInterfaces = false;
        }
        parent::setUpBeforeClass();
    }

    /**
     * Test __autoload deprecation not causing issue in 7.1.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertNoViolation($file);
    }

    /**
     * Test __autoload deprecation.
     *
     * @dataProvider dataIsDeprecated
     *
     * @param int $line The line number where the error should occur.
     *
     * @return void
     */
    public function testIsDeprecated($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.2');
        $this->assertWarning($file, $line, 'Use of __autoload() function is deprecated since PHP 7.2');
    }

    /**
     * dataIsDeprecated
     *
     * @see testIsDeprecated()
     *
     * @return array
     */
    public function dataIsDeprecated()
    {
        return array(
            array(3),
        );
    }

    /**
     * Test not affected __autoload declarations.
     *
     * @dataProvider dataIsNotAffected
     *
     * @param string $testFile The file to test
     * @param int    $line     The line number where the error should occur.
     * @param bool   $isTrait  Whether the test relates to a method in a trait.
     *
     * @return void
     */
    public function testIsNotAffected($testFile, $line, $isTrait = false)
    {
        if ($isTrait === true && self::$recognizesTraitsOrInterfaces === false) {
            $this->markTestSkipped('Traits are not recognized on PHPCS < 2.4.0 in combination with PHP < 5.4');
            return;
        }

        $file = $this->sniffFile($testFile, '7.2');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataIsDeprecated
     *
     * @see testIsDeprecated()
     *
     * @return array
     */
    public function dataIsNotAffected()
    {
        return array(
            array(self::TEST_FILE, 8),
            array(self::TEST_FILE, 14, true),
            array(self::TEST_FILE, 18, true),
            array(self::TEST_FILE, 24),
            array(self::TEST_FILE_NAMESPACED, 5),
            array(self::TEST_FILE_NAMESPACED, 10),
            array(self::TEST_FILE_NAMESPACED, 16),
            array(self::TEST_FILE_NAMESPACED, 20),
            array(self::TEST_FILE_NAMESPACED, 26),
        );
    }
}
