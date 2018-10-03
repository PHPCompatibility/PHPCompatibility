<?php
/**
 * PHP 5.5 referencing expressions in foreach sniff test file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\ControlStructures;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * PHP 5.5 referencing expressions in foreach sniff test file.
 *
 * @group newForeachExpressionReferencing
 * @group controlStructures
 *
 * @covers \PHPCompatibility\Sniffs\ControlStructures\NewForeachExpressionReferencingSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewForeachExpressionReferencingUnitTest extends BaseSniffTest
{

    /**
     * testNewForeachExpressionReferencing
     *
     * @dataProvider dataNewForeachExpressionReferencing
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNewForeachExpressionReferencing($line)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertError($file, $line, 'Referencing $value is only possible if the iterated array is a variable in PHP 5.4 or earlier.');
    }

    /**
     * dataNewForeachExpressionReferencing
     *
     * @see testNewForeachExpressionReferencing()
     *
     * @return array
     */
    public function dataNewForeachExpressionReferencing()
    {
        return array(
            array(17),
            array(18),
            array(20),
            array(21),
            array(23),
            array(24),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number with a valid list assignment.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoFalsePositives
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return array(
            array(6),
            array(7),
            array(8),
            array(9),
            array(10),
            array(11),
            array(12),
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
