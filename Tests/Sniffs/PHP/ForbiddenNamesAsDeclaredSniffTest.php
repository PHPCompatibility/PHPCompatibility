<?php
/**
 * Forbidden names as declared name for class, interface, trait or namespace sniff test file.
 *
 * @package PHPCompatibility
 */


/**
 * Forbidden names as declared name for class, interface, trait or namespace.
 *
 * @group forbiddenNamesAsDeclared
 * @group reservedKeywords
 *
 * @covers PHPCompatibility_Sniffs_PHP_ForbiddenNamesAsDeclaredSniff
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
            array('null', array(22, 36, 50, 64, 79, 94), '7.0', '5.6'),
            array('true', array(23, 37, 51, 65, 80, 95), '7.0', '5.6'),
            array('false', array(24, 38, 52, 66, 81, 96), '7.0', '5.6'),
            array('bool', array(25, 39, 53, 67, 82, 97), '7.0', '5.6'),
            array('int', array(26, 40, 54, 68, 83, 98), '7.0', '5.6'),
            array('float', array(27, 41, 55, 69, 84, 99), '7.0', '5.6'),
            array('string', array(28, 42, 56, 70, 85, 100), '7.0', '5.6'),
            array('resource', array(29, 43, 57, 71, 86, 101), '7.0', '5.6'),
            array('object', array(30, 44, 58, 72, 87, 102), '7.0', '5.6'),
            array('mixed', array(31, 45, 59, 73, 88, 103), '7.0', '5.6'),
            array('numeric', array(32, 46, 60, 74, 89, 104), '7.0', '5.6'),
            array('iterable', array(33, 47, 61, 75, 90, 105), '7.1', '7.0'),
            array('void', array(34, 48, 62, 76, 91, 106), '7.1', '7.0'),
        );
    }


    /**
     * testNoViolation
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
