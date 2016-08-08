<?php
/**
 * New type declarations test file
 *
 * @package PHPCompatibility
 */


/**
 * New type declarations test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewScalarTypeDeclarationsSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_scalar_type_declarations.php';

    /**
     * testScalarTypeDeclaration
     *
     * @dataProvider dataScalarTypeDeclaration
     *
     * @param string $type The scalar type.
     * @param int    $line The line number.
     *
     * @return void
     */
    public function testScalarTypeDeclaration($type, $line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertError($file, $line, $type . ' type is not present in PHP version 5.6 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, $line);

    }

    /**
     * Data provider.
     *
     * @see testScalarTypeDeclaration()
     *
     * @return array
     */
    public function dataScalarTypeDeclaration()
    {
        return array(
            array('bool', 3),
            array('int', 5),
            array('float', 7),
            array('string', 9),
        );
    }
}

