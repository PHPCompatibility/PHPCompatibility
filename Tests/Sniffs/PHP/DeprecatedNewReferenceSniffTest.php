<?php
/**
 * Deprecated new reference sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Deprecated new reference sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class DeprecatedNewReferenceSniffTest extends BaseSniffTest
{
    /**
     * Test new reference
     *
     * @return void
     */
    public function testNewReference()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_new_reference.php');

        $this->assertNoViolation($file, 8);

        $this->assertError($file, 9, 'Assigning the return value of new by reference is deprecated in PHP 5.3');

        $this->assertError($file, 10, 'Assigning the return value of new by reference is deprecated in PHP 5.3');
    }

    /**
     * testSettingTestVersion
     *
     * @return void
     */
    public function testSettingTestVersion()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_new_reference.php', '5.2');

        $this->assertNoViolation($file, 8);
        $this->assertNoViolation($file, 9);
        $this->assertNoViolation($file, 10);
    }
}
