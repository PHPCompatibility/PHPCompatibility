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
     * @dataProvider dataClosure
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testClosure($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, $line, 'Closures / anonymous functions are not available in PHP 5.2 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testClosure()
     *
     * @return array
     */
    public function dataClosure()
    {
        return array(
            array(3),
            array(14),
            array(22),
            array(30),
        );
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
     * Test static closures
     *
     * @dataProvider dataStaticClosure
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testStaticClosure($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertError($file, $line, 'Closures / anonymous functions could not be declared as static in PHP 5.3 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testStaticClosure()
     *
     * @return array
     */
    public function dataStaticClosure()
    {
        return array(
            array(14),
            array(30),
        );
    }


    /**
     * Test using $this in closures
     *
     * @dataProvider dataThisInClosure
     *
     * @param int  $line            The line number.
     * @param bool $testNoViolation Whether or not to run the noViolation test.
     *
     * @return void
     */
    public function testThisInClosure($line, $testNoViolation = true)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertError($file, $line, 'Closures / anonymous functions did not have access to $this in PHP 5.3 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testThisInClosure()
     *
     * @return array
     */
    public function dataThisInClosure()
    {
        return array(
            array(23),
            array(24),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertNoViolation($file);
    }

}
