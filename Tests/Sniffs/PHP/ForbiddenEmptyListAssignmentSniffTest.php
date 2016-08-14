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
     * testEmptyListAssignment
     *
     * @group forbiddenEmptyListAssignment
     *
     * @dataProvider dataEmptyListAssignment
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testEmptyListAssignment($line)
    {
        $file = $this->sniffFile('sniff-examples/forbidden_empty_list_assignment.php', '5.6');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile('sniff-examples/forbidden_empty_list_assignment.php', '7.0');
        $this->assertError($file, $line, "Empty list() assignments are not allowed since PHP 7.0");
    }

    /**
     * dataEmptyListAssignment
     *
     * @see testEmptyListAssignment()
     *
     * @return array
     */
    public function dataEmptyListAssignment() {
        return array(
            array(3),
            array(4),
            array(5),
            array(6),
            array(7),
        );
    }


    /**
     * testValidListAssignment
     *
     * @group forbiddenEmptyListAssignment
     *
     * @dataProvider dataValidListAssignment
     *
     * @param int $line Line number with a valid list assignment.
     *
     * @return void
     */
    public function testValidListAssignment($line)
    {
        $file = $this->sniffFile('sniff-examples/forbidden_empty_list_assignment.php');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataValidListAssignment
     *
     * @see testValidListAssignment()
     *
     * @return array
     */
    public function dataValidListAssignment() {
        return array(
            array(13),
            array(14),
            array(15),
            array(16),
            array(17),
            array(20),
        );
    }

}
