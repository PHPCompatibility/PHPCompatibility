<?php
/**
 * New Closure Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Closure Sniff tests
 *
 * @group newClosure
 * @group closures
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewClosureSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewClosureSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_closure.php';

    /**
     * Test closures
     *
     * @return void
     */
    public function testClosure()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, 3, 'Closures / anonymous functions are not available in PHP 5.2 or earlier');
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertNoViolation($file, 6);
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file);
    }

}
