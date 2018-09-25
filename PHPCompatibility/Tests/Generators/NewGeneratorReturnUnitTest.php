<?php
/**
 * New generator return expressions sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Generators;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New generator return expressions in PHP 7.0 sniff test file
 *
 * @group newGeneratorReturn
 * @group generators
 *
 * @covers \PHPCompatibility\Sniffs\Generators\NewGeneratorReturnSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewGeneratorReturnUnitTest extends BaseSniffTest
{

    /**
     * testNewGeneratorReturn
     *
     * @dataProvider dataNewGeneratorReturn
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewGeneratorReturn($line)
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertError($file, $line, 'Returning a final expression from a generator was not supported in PHP 5.6 or earlier');
    }

    /**
     * Data provider dataNewGeneratorReturn.
     *
     * @see testNewGeneratorReturn()
     *
     * @return array
     */
    public function dataNewGeneratorReturn()
    {
        return array(
            array(30),
            array(35),
            array(39),
            array(64),
            array(83),
            array(101),
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
        $file = $this->sniffFile(__FILE__, '5.6');
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
            array(6),
            array(15),
            array(21),
            array(53),
            array(107),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file);
    }

}
