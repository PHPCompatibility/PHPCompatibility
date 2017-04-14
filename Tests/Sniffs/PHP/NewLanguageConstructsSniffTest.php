<?php
/**
 * New language constructs sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New language constructs sniff tests
 *
 * @group newLanguageConstructs
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewLanguageConstructsSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class NewLanguageConstructsSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_language_constructs.php';

    /**
     * testNamespaceSeparator
     *
     * @requires PHP 5.3
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
     * testPow
     *
     * @return void
     */
    public function testPow()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertError($file, 5, 'power operator (**) is not present in PHP version 5.5 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file, 5);
    }

    /**
     * testPowEquals
     *
     * @return void
     */
    public function testPowEquals()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertError($file, 6, 'power assignment operator (**=) is not present in PHP version 5.5 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file, 6);
    }

    /**
     * testSpaceship
     *
     * @return void
     */
    public function testSpaceship()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertError($file, 12, 'spaceship operator (<=>) is not present in PHP version 5.6 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, 12);
    }

    /**
     * Coalescing operator
     *
     * @return void
     */
    public function testCoalescing()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertError($file, 8, 'null coalescing operator (??) is not present in PHP version 5.6 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, 8);
    }

    /**
     * Coalesce equal operator
     *
     * @return void
     */
    public function testCoalesceEquals()
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertError($file, 10, 'null coalesce equal operator (??=) is not present in PHP version 7.1 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '7.2');
        $this->assertNoViolation($file, 10);
    }

    /**
     * Variadic functions using ...
     *
     * @return void
     */
    public function testEllipsis()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertError($file, 14, 'variadic functions using ... is not present in PHP version 5.5 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file, 14);
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
