<?php
/**
 * preg_replace() /e modifier sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * preg_replace() /e modifier sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class PregReplaceEModifierSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/preg_replace_e_modifier.php';

    /**
     * testDeprecatedEModifier
     *
     * @group pregReplaceEModifier
     *
     * @dataProvider dataDeprecatedEModifier
     *
     * @param int    $line         Line number where the error should occur.
     * @param string $functionName Function name.
     *
     * @return void
     */
    public function testDeprecatedEModifier($line, $functionName = 'preg_replace')
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertError($file, $line, "{$functionName}() - /e modifier is deprecated since PHP 5.5");

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, "{$functionName}() - /e modifier is forbidden since PHP 7.0");
    }

    /**
     * dataDeprecatedEModifier
     *
     * @see testDeprecatedEModifier()
     *
     * @return array
     */
    public function dataDeprecatedEModifier() {
        return array(
            // preg_replace()
            array(50),
            array(51),
            array(54),
            array(55),
            array(58),
            array(59),
            array(60),
            array(63),
            array(78),
            array(84),

            // Bracket delimiters.
            array(99),
            array(100),
            array(104),
            array(106),
            array(108),

            // preg_filter()
            array(114, 'preg_filter'),
            array(115, 'preg_filter'),
            array(118, 'preg_filter'),
            array(119, 'preg_filter'),
            array(122, 'preg_filter'),
            array(123, 'preg_filter'),
            array(124, 'preg_filter'),
            array(127, 'preg_filter'),
            array(142, 'preg_filter'),
            array(148, 'preg_filter'),
        );
    }


    /**
     * testNoViolation
     *
     * @group pregReplaceEModifier
     *
     * @dataProvider dataNoViolation
     *
     * @param int $line Line number where no error should occur.
     *
     * @return void
     */
    public function testNoViolation($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoViolation
     *
     * @see testNoViolation()
     *
     * @return array
     */
    public function dataNoViolation() {
        return array(
            // No or only valid modifiers.
            array(9),
            array(10),
            array(13),
            array(14),
            array(17),
            array(18),
            array(21),
            array(24),
            array(39),
            array(45),

            // Untestable regex (variable, constant, function call).
            array(94),
            array(95),
            array(96),

            // Bracket delimiters.
            array(101),
            array(102),
            array(103),
            array(105),
            array(107),
            array(109),

        );
    }

}
