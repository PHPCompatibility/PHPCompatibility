<?php
/**
 * New Closure Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Closure Sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewClosureSniffTest extends BaseSniffTest
{
    /**
     * Test closures
     *
     * @return void
     */
    public function testClosure()
    {
        $file = $this->sniffFile('sniff-examples/new_closure.php', '5.2');
        $this->assertError($file, 3, "Closures / anonymous functions are not available in PHP 5.2 or earlier");

        $file = $this->sniffFile('sniff-examples/new_closure.php', '5.3');
        $this->assertNoViolation($file, 3);
    }
}
