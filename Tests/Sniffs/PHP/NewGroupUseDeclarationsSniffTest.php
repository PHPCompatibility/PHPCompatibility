<?php
/**
 * New use group declaration sniff tests
 *
 * @package PHPCompatibility
 */


/**
 * New use group declaration sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewGroupUseDeclarationsSniffTest extends BaseSniffTest
{
    /**
     * Test use group declaration
     *
     * @return void
     */
    public function testUseGroupDeclaration()
    {
        if (version_compare(PHP_CodeSniffer::VERSION, '2.3.4') >= 0) {
            $file = $this->sniffFile('sniff-examples/new_group_use_declarations.php', '5.6');
            $this->assertNoViolation($file, 4);
            $this->assertNoViolation($file, 5);
            $this->assertNoViolation($file, 6);
            $this->assertNoViolation($file, 8);
            $this->assertNoViolation($file, 9);
            $this->assertNoViolation($file, 10);
            $this->assertError($file, 13, "Group use declarations are not allowed in PHP 5.6 or earlier");
            $this->assertError($file, 14, "Group use declarations are not allowed in PHP 5.6 or earlier");
    
            $file = $this->sniffFile('sniff-examples/new_group_use_declarations.php', '7.0');
            $this->assertNoViolation($file, 4);
            $this->assertNoViolation($file, 5);
            $this->assertNoViolation($file, 6);
            $this->assertNoViolation($file, 8);
            $this->assertNoViolation($file, 9);
            $this->assertNoViolation($file, 10);
            $this->assertNoViolation($file, 13);
            $this->assertNoViolation($file, 14);
        }
    }
}