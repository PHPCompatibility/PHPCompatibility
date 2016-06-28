<?php
/**
 * Empty list() assignments have been removed in PHP 7.0 sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Empty list() assignments have been removed in PHP 7.0 sniff test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class ForbiddenEmptyListAssignmentSniffTest extends BaseSniffTest
{
    /**
     * Verify that checking for a specific version works
     *
     * @return void
     */
    public function testEmptyListAssignment()
    {
        $file = $this->sniffFile('sniff-examples/forbidden_empty_list_assignment.php', '5.6');
        $this->assertNoViolation($file, 3);
        $this->assertNoViolation($file, 5);
        
        $file = $this->sniffFile('sniff-examples/forbidden_empty_list_assignment.php', '7.0');
        $this->assertError($file, 3, "Empty list() assignments are not allowed since PHP 7.0");
        $this->assertError($file, 5, "Empty list() assignments are not allowed since PHP 7.0");
    }
}
