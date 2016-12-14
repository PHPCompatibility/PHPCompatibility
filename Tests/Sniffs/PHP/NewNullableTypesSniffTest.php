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
        // Skip this test for low PHPCS versions.
        if (version_compare(PHP_CodeSniffer::VERSION, '2.3.4', '<')) {
            $this->markTestSkipped();
        }

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
            array(20),
            array(21),
            array(22),
            array(23),
            array(24),
            array(25),
            array(26),
            array(27),
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
            array(45),
            array(46),
            array(47),
            array(48),
            array(49),
            array(50),
            array(51),
            array(52),

            array(55), // Three errors of the same.
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

            array(33),
            array(34),
            array(35),
            array(36),
            array(37),
            array(38),
            array(39),
            array(40),
        );
    }
}
