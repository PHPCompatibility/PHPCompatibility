<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\InitialValue;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewHeredoc sniff.
 *
 * @group newHeredoc
 * @group initialValue
 *
 * @covers \PHPCompatibility\AbstractInitialValueSniff
 * @covers \PHPCompatibility\Sniffs\InitialValue\NewHeredocSniff
 *
 * @since 7.1.4
 */
class NewHeredocUnitTest extends BaseSniffTestCase
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
    public static function dataHeredocInitialize()
    {
        $data = [
            [5, 'static variables'],
            [13, 'constants'],
            [19, 'class properties'],
            [27, 'constants'],
            [31, 'class properties'],
            [39, 'constants'],
            [47, 'class properties'],
            [52, 'constants'],
            [60, 'constants'],
            [87, 'static variables'],
            [90, 'static variables'],
            [97, 'constants'],
            [100, 'constants'],
            [104, 'class properties'],
            [107, 'class properties'],
            [115, 'default parameter values'],
            [121, 'default parameter values'],
            [124, 'default parameter values'],
            [132, 'default parameter values'],
            [136, 'default parameter values'],
            [146, 'constants'],
            [156, 'constants'],
            [164, 'static variables'],
            [172, 'class properties'],
        ];

        if (\PHP_VERSION_ID >= 70300) {
            $data[] = [192, 'static variables'];
        }

        return $data;
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
    public static function dataNoFalsePositives()
    {
        return [
            [70],
            [75],
            [79],
        ];
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
