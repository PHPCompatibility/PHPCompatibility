<?php
/**
 * Forbidden names as function invocations sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Forbidden names as function invocations sniff test file
 *
 * @group forbiddenNamesAsInvokedFunctions
 * @group reservedKeywords
 *
 * @covers PHPCompatibility_Sniffs_PHP_ForbiddenNamesAsInvokedFunctionsSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class ForbiddenNamesAsInvokedFunctionsSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/forbidden_names_function_invocation.php';

    /**
     * testReservedKeyword
     *
     * @dataProvider dataReservedKeyword
     *
     * @param string $keyword      Reserved keyword.
     * @param array  $lines        The line numbers in the test file which apply to this keyword.
     * @param string $introducedIn The PHP version in which the keyword became a reserved word.
     * @param string $okVersion    A PHP version in which the keyword was not yet reserved.
     *
     * @return void
     */
    public function testReservedKeyword($keyword, $lines, $introducedIn, $okVersion)
    {
        $file  = $this->sniffFile(self::TEST_FILE, $introducedIn);
        $error = "'{$keyword}' is a reserved keyword introduced in PHP version {$introducedIn} and cannot be invoked as a function";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testReservedKeyword()
     *
     * @return array
     */
    public function dataReservedKeyword()
    {
        return array(
            array('abstract', array(6), '5.0', '4.4'),
            array('callable', array(7), '5.4', '5.3'),
            array('catch', array(8), '5.0', '4.4'),
            array('final', array(10), '5.0', '4.4'),
            array('finally', array(11), '5.5', '5.4'),
            array('goto', array(12), '5.3', '5.2'),
            array('implements', array(13), '5.0', '4.4'),
            array('interface', array(14), '5.0', '4.4'),
            array('instanceof', array(15), '5.0', '4.4'),
            array('insteadof', array(16), '5.4', '5.3'),
            array('namespace', array(17), '5.3', '5.2'),
            array('private', array(18), '5.0', '4.4'),
            array('protected', array(19), '5.0', '4.4'),
            array('public', array(20), '5.0', '4.4'),
            array('trait', array(22), '5.4', '5.3'),
            array('try', array(23), '5.0', '4.4'),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number where no error should occur.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '99.0'); // High number beyond any newly introduced keywords.
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
            array(34),
            array(35),
            array(36),
            array(37),
            array(38),
            array(39),
            array(40),
            array(41),
            array(42),
            array(43),
            array(44),
            array(45),
            array(46),
            array(47),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '4.4'); // Low version below the first introduced reserved word.
        $this->assertNoViolation($file);
    }

}
