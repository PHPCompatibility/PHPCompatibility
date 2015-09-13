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

        $this->assertError($file, 3, "the \ operator (for namespaces) is not present in PHP version 5.2 or earlier");
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
    }

}
