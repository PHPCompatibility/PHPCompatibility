<?php
/**
 * Deprecated Mbstring regex replace e modifier sniff test file.
 *
 * @package PHPCompatibility
 */


/**
 * Deprecated Mbstring regex replace e modifier test file.
 *
 * @group mbstringReplaceEModifier
 * @group regexEModifier
 *
 * @covers PHPCompatibility_Sniffs_PHP_MbstringReplaceEModifierSniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class MbstringReplaceEModifierSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/mbstring_replace_e_modifier.php';

    /**
     * testMbstringEModifier
     *
     * @dataProvider dataMbstringEModifier
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testMbstringEModifier($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, '5.3-7.1');
        $this->assertWarning($file, $line, 'The Mbstring regex "e" modifier is deprecated since PHP 7.1.');

        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertWarning($file, $line, 'The Mbstring regex "e" modifier is deprecated since PHP 7.1. Use mb_ereg_replace_callback() instead (PHP 5.4.1+).');
    }

    /**
     * Data provider.
     *
     * @see testMbstringEModifier()
     *
     * @return array
     */
    public function dataMbstringEModifier()
    {
        return array(
            array(14),
            array(15),
            array(16),
            array(24),
            array(25),
            array(26),
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
        $file = $this->sniffFile(self::TEST_FILE, '7.1');
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
            array(4),
            array(5),
            array(6),
            array(9),
            array(10),
            array(11),
            array(19),
            array(20),
            array(21),
        );
    }
}
