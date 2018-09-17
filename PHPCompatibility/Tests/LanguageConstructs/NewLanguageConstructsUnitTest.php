<?php
/**
 * New language constructs sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New language constructs sniff tests
 *
 * @group newLanguageConstructs
 *
 * @covers \PHPCompatibility\Sniffs\PHP\NewLanguageConstructsSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Jansen Price <jansen.price@gmail.com>
 */
class NewLanguageConstructsSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_language_constructs.php';

    /**
     * testNamespaceSeparator
     *
     * @return void
     */
    public function testNamespaceSeparator()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, 3, 'the \ operator (for namespaces) is not present in PHP version 5.2 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file, 3);
    }

    /**
     * Variadic functions using ...
     *
     * @return void
     */
    public function testEllipsis()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertError($file, 5, 'variadic functions using ... is not present in PHP version 5.5 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file, 5);
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '99.0'); // High version beyond newest addition.
        $this->assertNoViolation($file);
    }

}
