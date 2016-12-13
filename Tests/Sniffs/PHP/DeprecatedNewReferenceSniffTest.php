<?php
/**
 * Deprecated new reference sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Deprecated new reference sniff tests
 *
 * @group deprecatedNewReference
 * @group references
 *
 * @covers PHPCompatibility_Sniffs_PHP_DeprecatedNewReferenceSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class DeprecatedNewReferenceSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/deprecated_new_reference.php';

    /**
     * testDeprecatedNewReference
     *
     * @dataProvider dataDeprecatedNewReference
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testDeprecatedNewReference($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertWarning($file, $line, 'Assigning the return value of new by reference is deprecated in PHP 5.3');

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, 'Assigning the return value of new by reference is deprecated in PHP 5.3 and forbidden in PHP 7.0');

    }

    /**
     * Data provider.
     *
     * @see testDeprecatedNewReference()
     *
     * @return array
     */
    public function dataDeprecatedNewReference()
    {
        return array(
            array(9),
            array(10),
        );
    }

    /**
     * testNoReference
     *
     * @return void
     */
    public function testNoReference()
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, 8);
    }

}
