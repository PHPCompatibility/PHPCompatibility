<?php
/**
 * Removed new reference sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Syntax;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Removed new reference sniff tests
 *
 * @group removedNewReference
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\RemovedNewReferenceSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Jansen Price <jansen.price@gmail.com>
 */
class RemovedNewReferenceUnitTest extends BaseSniffTest
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
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertWarning($file, $line, 'Assigning the return value of new by reference is deprecated in PHP 5.3');

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, 'Assigning the return value of new by reference is deprecated in PHP 5.3 and has been removed in PHP 7.0');

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
            array(11),
            array(12),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, 8);
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertNoViolation($file);
    }

}
