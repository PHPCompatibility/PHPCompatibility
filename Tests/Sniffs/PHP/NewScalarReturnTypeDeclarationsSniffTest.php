<?php
/**
 * New return types test file
 *
 * @package PHPCompatibility
 */


/**
 * New return types test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewScalarReturnTypeDeclarationsSniffTest extends BaseSniffTest
{
    /**
     * testSettingTestVersion
     *
     * @return void
     */
    public function testSettingTestVersion()
    {
        if (version_compare(PHP_CodeSniffer::VERSION, '2.3.4') >= 0) {
            $file = $this->sniffFile('sniff-examples/new_scalar_return_type_declarations.php', '5.6');
            $this->assertError($file, 3, 'bool return type is not present in PHP version 5.6 or earlier');
            $this->assertError($file, 5, 'int return type is not present in PHP version 5.6 or earlier');
            $this->assertError($file, 7, 'float return type is not present in PHP version 5.6 or earlier');
            $this->assertError($file, 9, 'string return type is not present in PHP version 5.6 or earlier');
    
            $file = $this->sniffFile('sniff-examples/new_scalar_return_type_declarations.php', '7.0');
            $this->assertNoViolation($file, 3);
            $this->assertNoViolation($file, 5);
            $this->assertNoViolation($file, 7);
            $this->assertNoViolation($file, 9);
        }
    }
}

