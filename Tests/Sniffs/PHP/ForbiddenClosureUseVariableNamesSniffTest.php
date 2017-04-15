<?php
/**
 * PHP 7.1 Forbidden variable names in closure use statements tests.
 *
 * @package PHPCompatibility
 */


/**
 * PHP 7.1 Forbidden variable names in closure use statements tests.
 *
 * @group forbiddenClosureUseVariableNames
 * @group closures
 *
 * @covers PHPCompatibility_Sniffs_PHP_ForbiddenClosureUseVariableNamesSniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class ForbiddenClosureUseVariableNamesSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/forbidden_closure_use_variable_names.php';

    const TEST_FILE_LIVE_CODING = 'sniff-examples/forbidden_closure_use_variable_names.2.php';

    /**
     * testForbiddenClosureUseVariableNames
     *
     * @dataProvider dataForbiddenClosureUseVariableNames
     *
     * @param int    $line    The line number.
     * @param string $varName Variable name which should be found.
     *
     * @return void
     */
    public function testForbiddenClosureUseVariableNames($line, $varName)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertError($file, $line, 'Variables bound to a closure via the use construct cannot use the same name as superglobals, $this, or a declared parameter since PHP 7.1. Found: ' . $varName);
    }

    /**
     * Data provider.
     *
     * @see testForbiddenClosureUseVariableNames()
     *
     * @return array
     */
    public function dataForbiddenClosureUseVariableNames()
    {
        return array(
            array(4, '$_SERVER'),
            array(5, '$_REQUEST'),
            array(6, '$GLOBALS'),
            array(7, '$this'),
            array(8, '$param'),
            array(9, '$param'),
            array(10, '$c'),
            array(11, '$b'),
            array(11, '$d'),
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
        $file = $this->sniffFile(self::TEST_FILE, '7.1');
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
            array(18),
            array(19),
            array(22),
            array(23),
            array(24),
            array(27),
            array(31),
            array(32),
            array(33),
            array(36),
        );
    }


    /**
     * testNoFalsePositivesLiveCoding
     *
     * @dataProvider dataNoFalsePositivesLiveCoding
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesLiveCoding($line)
    {
        if (strpos(PHP_CodeSniffer::VERSION, '2.5.1') !== false) {
            $this->markTestSkipped('PHPCS 2.5.1 has a bug in the tokenizer which affects this test.');
            return;
        }

        $file = $this->sniffFile(self::TEST_FILE_LIVE_CODING, '7.1');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesLiveCoding()
     *
     * @return array
     */
    public function dataNoFalsePositivesLiveCoding()
    {
        return array(
            array(41),
            array(44),
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
