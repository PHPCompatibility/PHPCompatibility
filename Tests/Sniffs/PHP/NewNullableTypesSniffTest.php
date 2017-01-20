<?php
/**
 * New nullable type hints / return types sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New nullable type hints / return types sniff test file
 *
 * @group nullableTypes
 * @group typeDeclarations
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewNullableTypesSniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewNullableTypesSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_nullable_types.php';

    /**
     * testNewNullableReturnTypes
     *
     * @dataProvider dataNewNullableReturnTypes
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewNullableReturnTypes($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, 'Nullable return types are not supported in PHP 7.0 or earlier.');

        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNewNullableReturnTypes()
     *
     * @return array
     */
    public function dataNewNullableReturnTypes()
    {
        return array(
            array(21),
            array(22),
            array(23),
            array(24),
            array(25),
            array(26),
            array(27),
            array(28),
            array(29),

            array(63),
        );
    }


    /**
     * testNewNullableTypeHints
     *
     * @dataProvider dataNewNullableTypeHints
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNewNullableTypeHints($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, 'Nullable type declarations are not supported in PHP 7.0 or earlier.');

        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNewNullableTypeHints()
     *
     * @return array
     */
    public function dataNewNullableTypeHints()
    {
        return array(
            array(48),
            array(49),
            array(50),
            array(51),
            array(52),
            array(53),
            array(54),
            array(55),
            array(56),

            array(59), // Three errors of the same.

            array(64),
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
        $file = $this->sniffFile(self::TEST_FILE, '7.0'); // Arbitrary pre-PHP 7.1 version.
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
            array(8),
            array(9),
            array(10),
            array(11),
            array(12),
            array(13),
            array(14),
            array(15),
            array(16),

            array(35),
            array(36),
            array(37),
            array(38),
            array(39),
            array(40),
            array(41),
            array(42),
            array(43),
        );
    }
}
