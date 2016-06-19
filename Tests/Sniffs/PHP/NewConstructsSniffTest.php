<?php
/**
 * New constructs sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New constructs sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class NewConstructsSniffTest extends BaseSniffTest
{
    /**
     * testNamespaceSeparator
     *
     * @return void
     */
    public function testNamespaceSeparator()
    {
        $file = $this->sniffFile('sniff-examples/new_constructs.php', '5.2');
        $this->assertError($file, 3, 'the \ operator (for namespaces) is not present in PHP version 5.2 or earlier');
        
        $file = $this->sniffFile('sniff-examples/new_constructs.php', '5.3');
        $this->assertNoViolation($file, 3);
    }

    /**
     * testPow
     *
     * @requires PHP 5.6
     * @return void
     */
    public function testPow()
    {
        $file = $this->sniffFile('sniff-examples/new_constructs.php', '5.5');
        $this->assertError($file, 5, "power operator (**) is not present in PHP version 5.5 or earlier");
        
        $file = $this->sniffFile('sniff-examples/new_constructs.php', '5.6');
        $this->assertNoViolation($file, 5);
    }

    /**
     * testPowEquals
     *
     * @requires PHP 5.6
     * @return void
     */
    public function testPowEquals()
    {
        $file = $this->sniffFile('sniff-examples/new_constructs.php', '5.5');
        $this->assertError($file, 6, "power assignment operator (**=) is not present in PHP version 5.5 or earlier");
        
        $file = $this->sniffFile('sniff-examples/new_constructs.php', '5.6');
        $this->assertNoViolation($file, 6);
    }

    /**
     * testSpaceship
     *
     * @return void
     */
    public function testSpaceship()
    {
        $file = $this->sniffFile('sniff-examples/new_constructs.php', '5.6');
        $this->assertError($file, 12, "spaceship operator (<=>) is not present in PHP version 5.6 or earlier");
        
        $file = $this->sniffFile('sniff-examples/new_constructs.php', '7.0');
        $this->assertNoViolation($file, 12);
    }

    /**
     * Coalescing operator
     *
     * @return void
     */
    public function testCoalescing()
    {
        $file = $this->sniffFile('sniff-examples/new_constructs.php', '5.6');
        $this->assertError($file, 8, "null coalescing operator (??) is not present in PHP version 5.6 or earlier");
        $this->assertError($file, 10, "null coalescing operator (??) is not present in PHP version 5.6 or earlier");
        
        $file = $this->sniffFile('sniff-examples/new_constructs.php', '7.0');
        $this->assertNoViolation($file, 8);
        $this->assertNoViolation($file, 10);
    }
}
