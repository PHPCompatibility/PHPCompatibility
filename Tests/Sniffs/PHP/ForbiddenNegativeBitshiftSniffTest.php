<?php
/**
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0
 *
 * @package PHPCompatibility
 */


/**
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class ForbiddenNegativeBitshiftSniffTest extends BaseSniffTest
{
    /**
     * testSettingTestVersion
     *
     * @return void
     */
    public function testSettingTestVersion()
    {
        $file = $this->sniffFile('sniff-examples/forbidden_negative_bitshift.php', '5.6');
        $this->assertNoViolation($file, 3);
        $this->assertNoViolation($file, 5);
        
        $file = $this->sniffFile('sniff-examples/forbidden_negative_bitshift.php', '7.0');
        $this->assertError($file, 3, 'Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0');
        $this->assertNoViolation($file, 5);
    }
}

