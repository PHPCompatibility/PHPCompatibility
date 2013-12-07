<?php
/**
 * New Functions Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Functions Sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class NewFunctionsSniffTest extends BaseSniffTest
{
    /**
     * Test array_fill_keys
     *
     * @return void
     */
    public function testArrayFillKeys()
    {
        $file = $this->sniffFile('sniff-examples/new_functions.php', '5.1');
        $this->assertError($file, 3, "The function array_fill_keys is not present in PHP version 5.1 or earlier");

        // Verify that sniff doesn't throw an error in version 5.2
        $file = $this->sniffFile('sniff-examples/new_functions.php', '5.2');
        $this->assertNoViolation($file, 3);

        // Verify that sniff doesn't catch static functions called with a class
        $file = $this->sniffFile('sniff-examples/new_functions.php', '5.1');
        $this->assertNoViolation($file, 4);
    }
}
