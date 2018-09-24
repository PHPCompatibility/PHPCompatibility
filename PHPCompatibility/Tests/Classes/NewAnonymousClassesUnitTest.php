<?php
/**
 * New Anonymous Classes Sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Classes;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New Anonymous Classes Sniff tests
 *
 * @group newAnonymousClasses
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewAnonymousClassesSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Wim Godden <wim@cu.be>
 */
class NewAnonymousClassesUnitTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/new_anonymous_classes.php';

    /**
     * Test anonymous classes
     *
     * @return void
     */
    public function testAnonymousClasses()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertError($file, 4, 'Anonymous classes are not supported in PHP 5.6 or earlier');
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file, 3);
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file);
    }

}
