<?php
/**
 * New type casts sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\TypeCasts;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New type casts sniff tests
 *
 * @group newTypeCasts
 * @group typeCasts
 *
 * @covers \PHPCompatibility\Sniffs\TypeCasts\NewTypeCastsSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewTypeCastsUnitTest extends BaseSniffTest
{

    /**
     * testNewTypeCasts
     *
     * @dataProvider dataNewFunction
     *
     * @param string $castDescription   The type of type cast.
     * @param string $lastVersionBefore The PHP version just *before* the type cast was introduced.
     * @param array  $lines             The line numbers in the test file which apply to this type cast.
     * @param string $okVersion         A PHP version in which the type cast was valid.
     * @param string $testVersion       Optional. A PHP version in which to test for the error if different
     *                                  from the $lastVersionBefore.
     *
     * @return void
     */
    public function testNewTypeCasts($castDescription, $lastVersionBefore, $lines, $okVersion, $testVersion = null)
    {
        $errorVersion = (isset($testVersion)) ? $testVersion : $lastVersionBefore;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "{$castDescription} is not present in PHP version {$lastVersionBefore} or earlier";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewFunction()
     *
     * @return array
     */
    public function dataNewFunction()
    {
        return array(
            array('The unset cast', '4.4', array(8, 13, 15), '5.0'),
            array('The binary cast', '5.2.0', array(9, 10, 14, 16), '5.3', '5.2'), // Test (global) namespaced function.
        );
    }


    /**
     * Test functions that shouldn't be flagged by this sniff.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '4.4'); // Low version below the first addition.
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
            array(4),
            array(5),
            array(19),
            array(20),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond newest addition.
        $this->assertNoViolation($file);
    }
}
