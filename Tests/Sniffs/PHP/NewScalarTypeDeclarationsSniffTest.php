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
     * testNewTypeDeclaration
     *
     * @group TypeDeclarations
     *
     * @dataProvider dataNewTypeDeclaration
     *
     * @param string $type The scalar type.
     * @param int    $line Line number on which to expect an error.
     *
     * @return void
     */
    public function testNewTypeDeclaration($type, $lastVersionBefore, $line, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        $this->assertError($file, $line, "'{$type}' type declaration is not present in PHP version {$lastVersionBefore} or earlier");

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNewTypeDeclaration()
     *
     * @return array
     */
    public function dataNewTypeDeclaration()
    {
        return array(
            array('array', '5.0', 4, '5.1'),
            array('array', '5.0', 5, '5.1'),
            array('callable', '5.3', 9, '5.4'),
            array('bool', '5.6', 13, '7.0'),
            array('int', '5.6', 14, '7.0'),
            array('float', '5.6', 15, '7.0'),
            array('string', '5.6', 16, '7.0'),
        );
    }


    /**
     * testInvalidTypeDeclaration
     *
     * @group TypeDeclarations
     *
     * @dataProvider dataInvalidTypeDeclaration
     *
     * @param string $type The scalar type.
     * @param int    $line Line number on which to expect an error.
     *
     * @return void
     */
    public function testInvalidTypeDeclaration($type, $alternative, $line)
    {
        $file = $this->sniffFile(self::TEST_FILE);
        $this->assertError($file, $line, "'{$type}' is not a valid type declaration. Did you mean {$alternative} ?");
    }

    /**
     * Data provider.
     *
     * @see testInvalidTypeDeclaration()
     *
     * @return array
     */
    public function dataInvalidTypeDeclaration()
    {
        return array(
            array('boolean', 'bool', 20),
            array('integer', 'int', 21),
            array('parent', 'self', 24),
            array('static', 'self', 25),
        );
    }


    /**
     * testInvalidSelfTypeDeclaration
     *
     * @group TypeDeclarations
     *
     * @dataProvider dataInvalidSelfTypeDeclaration
     *
     * @param int $line Line number on which to expect an error.
     *
     * @return void
     */
    public function testInvalidSelfTypeDeclaration($line)
    {
        $file = $this->sniffFile(self::TEST_FILE);
        $this->assertError($file, $line, "'self' type cannot be used outside of class scope");
    }

    /**
     * Data provider.
     *
     * @see testInvalidSelfTypeDeclaration()
     *
     * @return array
     */
    public function dataInvalidSelfTypeDeclaration()
    {
        return array(
            array(37),
            array(44),
        );
    }


    /**
     * testTypeDeclaration
     *
     * @group TypeDeclarations
     *
     * @dataProvider dataTypeDeclaration
     *
     * @param int  $line            Line number on which to expect an error.
     * @param bool $testNoViolation Whether or not to test noViolation for PHP 5.0.
     *                              This covers the remaining few cases not covered
     *                              by the above tests.
     *
     * @return void
     */
    public function testTypeDeclaration($line, $testNoViolation = false)
    {
        $file = $this->sniffFile(self::TEST_FILE, '4.4');
        $this->assertError($file, $line, 'Type hints were not present in PHP 4.4 or earlier');

        if ($testNoViolation === true) {
            $file = $this->sniffFile(self::TEST_FILE, '5.0');
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testTypeDeclaration()
     *
     * @return array
     */
    public function dataTypeDeclaration()
    {
        return array(
            array(4),
            array(5),
            array(9),
            array(13),
            array(14),
            array(15),
            array(16),
            array(20),
            array(21),
            array(24),
            array(25),
            array(29, true),
            array(34, true),
            array(37),
            array(41, true),
            array(44),
        );
    }
}

