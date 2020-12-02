<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewStripTagsAllowableTagsArray sniff.
 *
 * @group newStripTagsAllowableTagsArray
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewStripTagsAllowableTagsArraySniff
 *
 * @since 9.3.0
 */
class NewStripTagsAllowableTagsArrayUnitTest extends BaseSniffTest
{

    /**
     * testNewStripTagsAllowableTagsArray
     *
     * @dataProvider dataNewStripTagsAllowableTagsArray
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testNewStripTagsAllowableTagsArray($line)
    {
        $file  = $this->sniffFile(__FILE__, '7.3');
        $error = 'The strip_tags() function did not accept $allowed_tags to be passed in array format in PHP 7.3 and earlier.';

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testNewStripTagsAllowableTagsArray()
     *
     * @return array
     */
    public function dataNewStripTagsAllowableTagsArray()
    {
        return [
            [13],
            [16],
            [23],
            [26],
            [33],
            [34],
        ];
    }


    /**
     * testInvalidStripTagsAllowableTagsArray
     *
     * @dataProvider dataInvalidStripTagsAllowableTagsArray
     *
     * @param int  $line       Line number where the error should occur.
     * @param bool $paramValue The parameter value detected.
     *
     * @return void
     */
    public function testInvalidStripTagsAllowableTagsArray($line, $paramValue)
    {
        $file  = $this->sniffFile(__FILE__, '7.4');
        $error = 'When passing strip_tags() the $allowed_tags parameter as an array, the tags should not be enclosed in <> brackets. Found: ' . $paramValue;

        $this->assertWarning($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testInvalidStripTagsAllowableTagsArray()
     *
     * @return array
     */
    public function dataInvalidStripTagsAllowableTagsArray()
    {
        return [
            [23, "'<a>'"],
            [23, "'<p>'"],
            [27, "'<a>'"],
            [28, "'<p>'"],
        ];
    }

    /**
     * testNoFalsePositivesInvalidStripTagsAllowableTagsArray
     *
     * @dataProvider dataNoFalsePositivesInvalidStripTagsAllowableTagsArray
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesInvalidStripTagsAllowableTagsArray($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesInvalidStripTagsAllowableTagsArray()
     *
     * @return array
     */
    public function dataNoFalsePositivesInvalidStripTagsAllowableTagsArray()
    {
        return [
            [33],
            [34],
            [36],
            [37],
            [38],
            [39],
            [40],
            [41],
        ];
    }

    /**
     * Test the sniff does not throw false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param string $testVersion The testVersion to use.
     *
     * @return void
     */
    public function testNoFalsePositives($testVersion)
    {
        $file = $this->sniffFile(__FILE__, $testVersion);

        // No errors expected on the first 11 lines.
        for ($line = 1; $line <= 11; $line++) {
            $this->assertNoViolation($file, $line);
        }
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
        return [
            ['7.3'],
            ['7.4'],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff will throw warnings/errors
     * about independently of the testVersion.
     */
}
