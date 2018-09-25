<?php
/**
 * New catching multiple exception types sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\ControlStructures;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New catching multiple exception types sniff test file
 *
 * @group newMultiCatch
 * @group controlStructures
 * @group exceptions
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\NewMultiCatchSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewMultiCatchUnitTest extends BaseSniffTest
{

    /**
     * testNewMultiCatch
     *
     * @dataProvider dataNewMultiCatch
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewMultiCatch($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, 'Catching multiple exceptions within one statement is not supported in PHP 7.0 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testNewMultiCatch()
     *
     * @return array
     */
    public function dataNewMultiCatch()
    {
        return array(
            array(21),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return array(
            array(8),
            array(10),
            array(12),
            array(23),
            array(30), // Live coding.
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertNoViolation($file);
    }

}
