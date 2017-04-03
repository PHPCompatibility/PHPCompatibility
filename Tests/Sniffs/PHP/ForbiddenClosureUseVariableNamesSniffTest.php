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
            array(41), // Live coding.
            array(44), // Live coding.
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
