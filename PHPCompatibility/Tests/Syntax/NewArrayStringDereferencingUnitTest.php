<?php
/**
 * NewArrayStringDereferencingSniff test file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Syntax;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New array and string literal dereferencing sniff test.
 *
 * @group newArrayStringDereferencing
 * @group syntax
 *
 * @covers \PHPCompatibility\Sniffs\Syntax\NewArrayStringDereferencingSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewArrayStringDereferencingUnitTest extends BaseSniffTest
{

    /**
     * testArrayStringDereferencing
     *
     * @dataProvider dataArrayStringDereferencing
     *
     * @param int    $line The line number.
     * @param string $type Whether this is an array or string dereferencing.
     *
     * @return void
     */
    public function testArrayStringDereferencing($line, $type)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertError($file, $line, "Direct array dereferencing of {$type} is not present in PHP version 5.4 or earlier");
    }

    /**
     * Data provider dataArrayStringDereferencing.
     *
     * @see testArrayStringDereferencing()
     *
     * @return array
     */
    public function dataArrayStringDereferencing()
    {
        return array(
            array(4, 'arrays'),
            array(5, 'arrays'),
            array(6, 'arrays'),
            array(7, 'string literals'),
            array(8, 'string literals'),
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
        $file = $this->sniffFile(__FILE__, '5.4');
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
            array(11),
            array(12),
            array(13),
            array(14),
            array(15),
            array(16),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertNoViolation($file);
    }
}
