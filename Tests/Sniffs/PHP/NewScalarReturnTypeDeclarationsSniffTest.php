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
            $file = $this->sniffFile('sniff-examples/new_scalar_return_type_declarations.php', '7.0');
            $this->assertNoViolation($file, 3);
            $this->assertNoViolation($file, 5);
            $this->assertNoViolation($file, 7);
            $this->assertNoViolation($file, 9);
        }
    }
}

