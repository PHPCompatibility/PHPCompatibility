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
 * @group deprecatedPHP4Constructors
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim.godden@cu.be>
 */
class DeprecatedMagicAutoloadSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/deprecated_magic_autoload.php';

    /**
     * Test PHP4 style constructors.
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
        );
    }


    /**
     * Test valid methods with the same name as the class.
     *
     * @group deprecatedPHP4Constructors
     *
     * @dataProvider dataValidMethods
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testValidMethods($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataValidMethods
     *
     * @see testValidMethods()
     *
     * @return array
     */
    public function dataValidMethods()
	{
        $testCases = array(
            array(9),
        );

        // Add an additional testscase which will only be 'no violation' if namespaces are recognized.
        if (version_compare(phpversion(), '5.3', '>=')) {
            $testCases[] = array(26);
        }

        return $testCases;
    }
}
