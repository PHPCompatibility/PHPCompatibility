<?php
/**
 * New generator return expressions sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New generator return expressions in PHP 7.0 sniff test file
 *
 * @group newGeneratorReturn
 *
 * @covers \PHPCompatibility\Sniffs\PHP\NewGeneratorReturnSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewGeneratorReturnSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_generator_return.php';

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
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
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
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
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
        );
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
