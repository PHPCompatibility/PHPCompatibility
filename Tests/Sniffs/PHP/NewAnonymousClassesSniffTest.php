<?php
/**
 * New Anonymous Classes Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Anonymous Classes Sniff tests
 *
 * @group newAnonymousClasses
 * @group closures
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewAnonymousClassesSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewAnonymousClassesSniffTest extends BaseSniffTest
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

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, 4);
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

}
