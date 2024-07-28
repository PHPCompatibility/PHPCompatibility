<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Attributes;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the NewAttributes sniff.
 *
 * @group newAttributes
 * @group attributes
 *
 * @covers \PHPCompatibility\Sniffs\Attributes\NewAttributesSniff
 *
 * @since 10.0.0
 */
class NewAttributesUnitTest extends BaseSniffTestCase
{

    /**
     * The name of the primary test case file.
     *
     * @var string
     */
    const TEST_FILE = 'NewAttributesUnitTest.1.inc';

    /**
     * The name of a secondary test case file containing tests with a PHP close tag in a text string.
     *
     * @var string
     */
    const TEST_CLOSE_TAG = 'NewAttributesUnitTest.2.inc';

    /**
     * Verify that multi-line attributes are identified and flagged correctly.
     *
     * @dataProvider dataMultilineAttributes
     *
     * @param int    $line     The line number.
     * @param string $testFile File name for the test case file to use.
     *
     * @return void
     */
    public function testMultilineAttribute($line, $testFile = self::TEST_FILE)
    {
        $file  = $this->sniffFile(__DIR__ . '/' . $testFile, '7.4');
        $error = 'Multi-line attributes will result in a parse error in PHP 7.4 and earlier.';

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testMultilineAttributes()
     *
     * @return array
     */
    public static function dataMultilineAttributes()
    {
        $data = [
            [67],
            [73],
            [86],
            [96],
            [104],

            [4, self::TEST_CLOSE_TAG],
            [9, self::TEST_CLOSE_TAG],
        ];

        return $data;
    }


    /**
     * Verify that inline attributes are identified and flagged correctly.
     *
     * @dataProvider dataInlineAttributes
     *
     * @param int    $line     The line number.
     * @param string $testFile File name for the test case file to use.
     *
     * @return void
     */
    public function testInlineAttribute($line, $testFile = self::TEST_FILE)
    {
        $file  = $this->sniffFile(__DIR__ . '/' . $testFile, '7.4');
        $error = 'Code after an inline attribute on the same line will be ignored in PHP 7.4 and earlier and is likely to cause a parse error or functional error.';

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testInlineAttributes()
     *
     * @return array
     */
    public static function dataInlineAttributes()
    {
        $data = [
            [38],
            [46],
            [48],
            [83],
            [105],

            [5, self::TEST_CLOSE_TAG],
        ];

        return $data;
    }


    /**
     * Verify that a text string containing something which looks like a PHP close tag, within an attribute,
     * is identified and flagged correctly.
     *
     * @dataProvider dataAttributeContainingStringCloseTag
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testAttributeContainingStringCloseTag($line)
    {
        $file  = $this->sniffFile(__DIR__ . '/' . self::TEST_CLOSE_TAG, '7.4');
        $error = 'Text string containing text which looks like a PHP close tag found on the same line as an attribute opener. This will cause PHP to switch to inline HTML in PHP 7.4 and earlier, which may lead to code exposure and will break functionality.';

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testAttributeContainingStringCloseTag()
     *
     * @return array
     */
    public static function dataAttributeContainingStringCloseTag()
    {
        $data = [
            [4],
        ];

        return $data;
    }


    /**
     * Verify that cross-version compatible attributes are flagged with a warning.
     *
     * @dataProvider dataNewAttributes
     *
     * @param int    $line     The line number.
     * @param string $found    Optional. Found attribute contents.
     * @param string $testFile File name for the test case file to use.
     *
     * @return void
     */
    public function testNewAttributes($line, $found = '', $testFile = self::TEST_FILE)
    {
        $file  = $this->sniffFile(__DIR__ . '/' . $testFile, '7.4');
        $error = 'Attributes are not supported in PHP 7.4 or earlier. They will be ignored and the application may not work as expected.';
        if ($found !== '') {
            $error .= ' Found: ' . $found;
        }

        $this->assertWarning($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testNewAttributes()
     *
     * @return array
     */
    public static function dataNewAttributes()
    {
        $data = [
            [17],
            [20],
            [23],
            [24],
            [25],
            [26],
            [30],
            [31],
            [32],
            [35],
            [38],
            [40],
            [41],
            [42],
            [43],
            [46],
            [48],
            [50, '#[WithoutArgument]'],
            [50, '#[SingleArgument(0)]'],
            [50, "#[FewArguments('Hello', 'World')]"],
            [53],
            [56, '#[ORM\Id]'],
            [56, '#[ORM\Column("integer")]'],
            [56, '#[ORM\GeneratedValue]'],
            [59],
            [60],
            [64],
            [67, '#[Attr2("foo"), Attr2("bar")]'],
            [73, '#[ Attr1("foo"), Attr2("bar"), ]'],
            [83, '#[MyAttr([1, 2])]'],
            [86, '#[ ORM\Entity, ORM\Table("user") ]'],
            [92],
            [95],
            [96, '#[Assert\Email(["message" => "text"])]'],
            [96, '#[Assert\Text(["message" => "text"]), Assert\Domain(["message" => "text"]), Assert\Id(Assert\Id::REGEX[10]), ]'],
            [104],

            [4, '#[DeprecationReason(\'reason: <https://some-website/reason?>\') ]', self::TEST_CLOSE_TAG],
            [9, '#[DeprecationReason( \'reason: <https://some-website/reason?>\' )]', self::TEST_CLOSE_TAG],
        ];

        return $data;
    }


    /**
     * Verify the sniff does not throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__DIR__ . '/' . self::TEST_FILE, '7.4');
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
        $data = [];

        // No errors expected on the first 12 lines.
        for ($line = 1; $line <= 12; $line++) {
            $data[] = [$line];
        }

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @dataProvider dataNoViolationsInFileOnValidVersion
     *
     * @param string $testFile File name for the test case file to use.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion($testFile)
    {
        $file = $this->sniffFile(__DIR__ . '/' . $testFile, '8.0');
        $this->assertNoViolation($file);
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public static function dataNoViolationsInFileOnValidVersion()
    {
        return [
            [self::TEST_FILE],
            [self::TEST_CLOSE_TAG],
        ];
    }
}
