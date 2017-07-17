<?php
/**
 * New nowdoc and quoted heredoc sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New nowdoc and quoted heredoc sniff tests
 *
 * @group newNowdocQuotedHeredoc
 * @group reservedKeywords
 *
 * @covers \PHPCompatibility\Sniffs\PHP\NewNowdocQuotedHeredocSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewNowdocQuotedHeredocSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_nowdoc_quoted_heredoc.php';


    /**
     * testNowdoc
     *
     * @dataProvider dataNowdoc
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNowdoc($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, $line, 'Nowdocs are not present in PHP version 5.2 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testNowdoc()
     *
     * @return array
     */
    public function dataNowdoc()
    {
        return array(
            array(15),
            array(19),
            array(21),
            array(25),
            array(29),
            array(32),
            array(34),
            array(37),
            array(40),
            array(48),
        );
    }


    /**
     * testQuotedHeredoc
     *
     * @dataProvider dataQuotedHeredoc
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testQuotedHeredoc($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, $line, 'The Heredoc identifier may not be enclosed in (double) quotes in PHP version 5.2 or earlier.');
    }

    /**
     * Data provider.
     *
     * @see testQuotedHeredoc()
     *
     * @return array
     */
    public function dataQuotedHeredoc()
    {
        return array(
            array(55),
            array(61),
            array(69),
            array(74),
            array(80),
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
        $data = array(
            array(4),
            array(8),
            array(30),
            array(36),
            array(70),
            array(76),
        );

        // PHPCS 1.x does not support skipping forward.
        if (version_compare(\PHP_CodeSniffer::VERSION, '2.0', '>=')) {
            $data[] = array(42);
            $data[] = array(46);
            $data[] = array(82);
            $data[] = array(86);
        }

        return $data;
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
