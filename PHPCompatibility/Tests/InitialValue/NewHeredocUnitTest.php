<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2019 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\InitialValue;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewHeredoc sniff.
 *
 * @group newHeredoc
 * @group initialValue
 *
 * @covers \PHPCompatibility\Sniffs\InitialValue\NewHeredocSniff
 *
 * @since 7.1.4
 */
class NewHeredocUnitTest extends BaseSniffTest
{

    /**
     * testHeredocInitialize
     *
     * @dataProvider dataHeredocInitialize
     *
     * @param int    $line The line number.
     * @param string $type Error type.
     *
     * @return void
     */
    public function testHeredocInitialize($line, $type)
    {
        $file = $this->sniffFile(__FILE__, '5.2');
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
            array(47, 'class properties'),
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
        $file = $this->sniffFile(__FILE__, '5.2');
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
        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertNoViolation($file);
    }
}
