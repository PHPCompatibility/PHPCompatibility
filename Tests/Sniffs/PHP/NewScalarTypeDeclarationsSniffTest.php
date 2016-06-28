<?php
/**
 * New types test file
 *
 * @package PHPCompatibility
 */


/**
 * New types test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewScalarTypeDeclarationsSniffTest extends BaseSniffTest
{
    /**
     * testSettingTestVersion
     *
     * @return void
     */
    public function testSettingTestVersion()
    {
        $file = $this->sniffFile('sniff-examples/new_scalar_type_declarations.php', '5.6');
        $this->assertError($file, 3, 'bool type is not present in PHP version 5.6 or earlier');
        $this->assertError($file, 5, 'int type is not present in PHP version 5.6 or earlier');
        $this->assertError($file, 7, 'float type is not present in PHP version 5.6 or earlier');
        $this->assertError($file, 9, 'string type is not present in PHP version 5.6 or earlier');

        $file = $this->sniffFile('sniff-examples/new_scalar_type_declarations.php', '7.0');
        $this->assertNoViolation($file, 3);
        $this->assertNoViolation($file, 5);
        $this->assertNoViolation($file, 7);
        $this->assertNoViolation($file, 9);
    }
}

