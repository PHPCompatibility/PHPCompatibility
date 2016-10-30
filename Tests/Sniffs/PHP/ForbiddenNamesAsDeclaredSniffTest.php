<?php
/**
 * Forbidden names as declared name for class, interface, trait or namespace sniff test file.
 *
 * @package PHPCompatibility
 */


/**
 * Forbidden names as declared name for class, interface, trait or namespace.
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class ForbiddenNamesAsDeclaredSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/forbidden_names_as_declared.php';

    /**
     * testReservedKeyword
     *
     * @group forbiddenNamesAsDeclared
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
        $file = $this->sniffFile(self::TEST_FILE, $introducedIn);
        foreach ($lines as $line) {
            $this->assertError($file, $line, "'{$keyword}' is a reserved keyword as of PHP version {$introducedIn} and cannot be used to name a class, interface or trait or as part of a namespace");
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
            array('null', array(22, 34, 46, 58, 71, 84), '7.0', '5.6'),
            array('true', array(23, 35, 47, 59, 72, 85), '7.0', '5.6'),
            array('false', array(24, 36, 48, 60, 73, 86), '7.0', '5.6'),
            array('bool', array(25, 37, 49, 61, 74, 87), '7.0', '5.6'),
            array('int', array(26, 38, 50, 62, 75, 88), '7.0', '5.6'),
            array('float', array(27, 39, 51, 63, 76, 89), '7.0', '5.6'),
            array('string', array(28, 40, 52, 64, 77, 90), '7.0', '5.6'),
            array('resource', array(29, 41, 53, 65, 78, 91), '7.0', '5.6'),
            array('object', array(30, 42, 54, 66, 79, 92), '7.0', '5.6'),
            array('mixed', array(31, 43, 55, 67, 80, 93), '7.0', '5.6'),
            array('numeric', array(32, 44, 56, 68, 81, 94), '7.0', '5.6'),
        );
    }


    /**
     * testNoViolation
     *
     * @group forbiddenNamesAsDeclared
     *
     * @dataProvider dataNoViolation
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoViolation($line)
    {
        $file = $this->sniffFile(self::TEST_FILE);
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoViolation()
     *
     * @return array
     */
    public function dataNoViolation()
    {
        return array(
            array(6),
            array(7),
            array(8),
            array(9),
            array(10),
            array(11),
            array(12),
            array(13),
            array(14),
            array(15),
            array(16),
        );
    }
}
