<?php
/**
 * Removed alternative PHP tags sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Removed alternative PHP tags sniff test file
 *
 * @group removedAlternativePHPTags
 *
 * @covers PHPCompatibility_Sniffs_PHP_RemovedAlternativePHPTagsSniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class RemovedAlternativePHPTagsSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/removed_alternative_phptags.php';

    /**
     * Whether or not ASP tags are on.
     *
     * @var bool
     */
    protected static $aspTags = false;


    /**
     * Set up skip condition.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        if (version_compare(phpversion(), '7.0', '<')) {
            self::$aspTags = (boolean) ini_get('asp_tags');
        }

        parent::setUpBeforeClass();
    }


    /**
     * testAlternativePHPTags
     *
     * @dataProvider dataAlternativePHPTags
     *
     * @param string $type    The type of opening tags, either ASP or Script.
     * @param string $snippet The text string found.
     * @param int    $line    The line number.
     *
     * @return void
     */
    public function testAlternativePHPTags($type, $snippet, $line)
    {
        if ($type === 'ASP' && self::$aspTags === false) {
            $this->markTestSkipped();
            return;
        }

        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertError($file, $line, $type . ' style opening tags have been removed in PHP 7.0. Found "' . $snippet . '"');
    }

    /**
     * Data provider.
     *
     * @see testAlternativePHPTags()
     *
     * @return array
     */
    public function dataAlternativePHPTags()
    {
        return array(
            array('Script', '<script language="php">', 7),
            array('Script', "<script language='php'>", 10),
            array('Script', '<script type="text/php" language="php">', 13),
            array('Script', "<script language='PHP' type='text/php'>", 16),
            array('ASP', '<%', 21),
            array('ASP', '<%', 22),
            array('ASP', '<%=', 23),
            array('ASP', '<%=', 24),
        );
    }


    /**
     * testMaybeASPOpenTag
     *
     * @dataProvider dataMaybeASPOpenTag
     *
     * @param int    $line    The line number.
     * @param string $snippet Part of the text string found.
     *
     * @return void
     */
    public function testMaybeASPOpenTag($line, $snippet)
    {
        if (self::$aspTags === true) {
            $this->markTestSkipped();
            return;
        }

        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertNoViolation($file, $line);

        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        if (version_compare(phpversion(), '5.3', '<')) {
            // PHP 5.2 does not generate the snippet correctly.
            $warning = 'Possible use of ASP style opening tags detected. ASP style opening tags have been removed in PHP 7.0. Found: <%';
        } else {
            $warning = 'Possible use of ASP style opening tags detected. ASP style opening tags have been removed in PHP 7.0. Found: ' . $snippet;
        }
        $this->assertWarning($file, $line, $warning);
    }

    /**
     * Data provider.
     *
     * @see testMaybeASPOpenTag()
     *
     * @return array
     */
    public function dataMaybeASPOpenTag()
    {
        return array(
            array(21, '<% echo $var; %>'),
            array(22, '<% echo $var; %> and some m...'),
            array(23, '<%= $var . \' and some more ...'),
            array(24, '<%= $var %> and some more t...'),
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
            array(3),
        );
    }
}
