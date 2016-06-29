<?php
/**
 * New function array dereferencing sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New function array dereferencing sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewFunctionArrayDereferencingSniffTest extends BaseSniffTest
{
    /**
     * testArrayDereferencing
     *
     * @return void
     */
    public function testArrayDereferencing()
    {
        $file = $this->sniffFile('sniff-examples/new_function_array_dereferencing.php', '5.3');
        $this->assertError($file, 3, 'Function array dereferencing is not present in PHP version 5.3 or earlier');
        
        $file = $this->sniffFile('sniff-examples/new_function_array_dereferencing.php', '5.4');
        $this->assertNoViolation($file, 3);
    }
}
