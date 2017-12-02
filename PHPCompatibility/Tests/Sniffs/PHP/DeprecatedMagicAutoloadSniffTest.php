<?php
/**
 * __autoload deprecation for PHP 7.2 sniff test
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * __autoload deprecation for PHP 7.2 sniff test
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
        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertNoViolation($file, $line);
        
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
     *
     * @return void
     */
    public function testIsNotAffected($testFile, $line)
    {
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
            array(self::TEST_FILE, 14),
            array(self::TEST_FILE, 18),
            array(self::TEST_FILE, 24),
            array(self::TEST_FILE_NAMESPACED, 6),
            array(self::TEST_FILE_NAMESPACED, 11),
            array(self::TEST_FILE_NAMESPACED, 16),
            array(self::TEST_FILE_NAMESPACED, 21),
            array(self::TEST_FILE_NAMESPACED, 27),
        );
    }
    
}
