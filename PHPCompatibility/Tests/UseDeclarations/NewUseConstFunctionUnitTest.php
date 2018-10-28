<?php
/**
 * New use const function sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\UseDeclarations;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New use const function in PHP 5.6 sniff test file
 *
 * @group newUseConstFunction
 * @group useDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\UseDeclarations\NewUseConstFunctionSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewUseConstFunctionUnitTest extends BaseSniffTest
{

    /**
     * testNewUseConstFunction
     *
     * @dataProvider dataNewUseConstFunction
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewUseConstFunction($line)
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertError($file, $line, 'Importing functions and constants through a "use" statement is not supported in PHP 5.5 or lower.');
    }

    /**
     * Data provider dataNewUseConstFunction.
     *
     * @see testNewUseConstFunction()
     *
     * @return array
     */
    public function dataNewUseConstFunction()
    {
        return array(
            array(48),
            array(49),
            array(50),
            array(51),
            array(54),
            array(58),
            array(62),
            array(66),
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
        $file = $this->sniffFile(__FILE__, '5.5');
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
            array(7),
            array(8),
            array(9),
            array(12),
            array(16),
            array(22),
            array(28),
            array(34),
            array(40),
            array(72),
            array(73),
            array(74),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file);
    }
}
