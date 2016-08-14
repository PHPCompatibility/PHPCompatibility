<?php
/**
 * PHP4 style constructors sniff test file
 *
 * @package PHPCompatibility
 */

/**
 * PHP4 style constructors sniff test
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Koen Eelen <koen.eelen@cu.be>
 */
class DeprecatedPHP4StyleConstructorsSniffTest extends BaseSniffTest
{
    /**
     * Test PHP4 style constructors.
     *
     * @group deprecatedPHP4Constructors
     *
     * @return void
     */
    public function testIsDeprecated()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_php4style_constructors.php', '5.6');
        $this->assertNoViolation($file, 3);

        $file = $this->sniffFile('sniff-examples/deprecated_php4style_constructors.php', '7.0');
        $this->assertError($file, 3, 'Deprecated PHP4 style constructor are not supported since PHP7');
    }

    /**
     * Test valid methods with the same name as the class.
     *
     * @group deprecatedPHP4Constructors
     *
     * @return void
     */
    public function testValidMethods()
    {
        $file = $this->sniffFile('sniff-examples/deprecated_php4style_constructors.php', '7.0');
        $this->assertNoViolation($file, 9);
        $this->assertNoViolation($file, 20);
    }
}
