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
 * @author Wim Godden <wim.godden@cu.be>
 */
class DeprecatedMagicAutoloadSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/deprecated_magic_autoload.php';

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
            array(8),
            array(14),
            array(18),
            array(24),
        );
    }
}
