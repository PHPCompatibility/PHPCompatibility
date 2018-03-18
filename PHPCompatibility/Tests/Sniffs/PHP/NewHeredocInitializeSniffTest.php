<?php
/**
 * New initialize with heredoc sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;
use PHPCompatibility\PHPCSHelper;

/**
 * New initialize with heredoc in PHP 5.3 sniff test file
 *
 * @group newHeredocInitialize
 * @group constants
 * @group variables
 *
 * @covers \PHPCompatibility\Sniffs\PHP\NewHeredocInitializeSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewHeredocInitializeSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_heredoc_initialize.php';

    /**
     * Whether or not traits will be recognized in PHPCS.
     *
     * @var bool
     */
    protected static $recognizesTraits = true;


    /**
     * Set up skip condition.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        // When using PHPCS 2.3.4 or lower combined with PHP 5.3 or lower, traits are not recognized.
        if (version_compare(PHPCSHelper::getVersion(), '2.4.0', '<') && version_compare(PHP_VERSION_ID, '50400', '<')) {
            self::$recognizesTraits = false;
        }

        parent::setUpBeforeClass();
    }


    /**
     * testHeredocInitialize
     *
     * @dataProvider dataHeredocInitialize
     *
     * @param int    $line    The line number.
     * @param string $type    Error type.
     * @param bool   $isTrait Whether the test relates to a method in a trait.
     *
     * @return void
     */
    public function testHeredocInitialize($line, $type, $isTrait = false)
    {
        if ($isTrait === true && self::$recognizesTraits === false) {
            $this->markTestSkipped('Traits are not recognized on PHPCS < 2.4.0 in combination with PHP < 5.4');
            return;
        }

        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, $line, "Initializing {$type} using the Heredoc syntax was not supported in PHP 5.2 or earlier");
    }

    /**
     * Data provider dataHeredocInitialize.
     *
     * @see testHeredocInitialize()
     *
     * @return array
     */
    public function dataHeredocInitialize()
    {
        return array(
            array(5, 'static variables'),
            array(13, 'constants'),
            array(19, 'class properties'),
            array(27, 'constants'),
            array(31, 'class properties'),
            array(39, 'constants'),
            array(47, 'class properties', true),
            array(52, 'constants'),
            array(60, 'constants'),
            array(87, 'static variables'),
            array(90, 'static variables'),
            array(97, 'constants'),
            array(100, 'constants'),
            array(104, 'class properties'),
            array(107, 'class properties'),
            array(115, 'default parameter values'),
            array(121, 'default parameter values'),
            array(124, 'default parameter values'),
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
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
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
            array(70),
            array(75),
            array(79),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file);
    }

}
