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
     * testNewAttributes
     *
     * @dataProvider dataNewAttributes
     *
     * @param int    $line  The line number.
     * @param string $found Optional. Found attribute contents.
     *
     * @return void
     */
    public function testNewAttributes($line, $found = '')
    {
        $file  = $this->sniffFile(__FILE__, '7.4');
        $error = 'Attributes are not supported in PHP 7.4 or earlier.';
        if ($found !== '') {
            $error .= ' Found: ' . $found;
        }

        $this->assertError($file, $line, $error);
    }

    /**
     * testNewAttributes
     *
     * @dataProvider dataBackwardsCompatibleAttributes
     *
     * @param int    $line  The line number.
     * @param string $found Optional. Found attribute contents.
     *
     * @return void
     */
    public function testBackwardsCompatibleNewAttributes($line, $found = '')
    {
        $file    = $this->sniffFile(__FILE__, '7.4');
        $warning = 'Backwards compatible attribute detected. This may not cause parse errors in PHP < 8.0:';
        if ($found !== '') {
            $warning .= ' Found: ' . $found;
        }

        $this->assertWarning($file, $line, $warning);
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
            [38],
            [46],
            [48],
            // There are backwards compatible but the sniffer can't parse multiple arguments in one line.
            [50, '#[WithoutArgument]'],
            [50, '#[SingleArgument(0)]'],
            [50, "#[FewArguments('Hello', 'World')]"],
            [56, '#[ORM\Id]'],
            [56, '#[ORM\Column("integer")]'],
            [56, '#[ORM\GeneratedValue]'],
            // End multiple Attributes in one line.
            [67, '#[Attr2("foo"), Attr2("bar")]'],
            [73, '#[ Attr1("foo"), Attr2("bar"), ]'],
            [83, '#[MyAttr([1, 2])]'],
            [86, '#[ ORM\Entity, ORM\Table("user") ]'],
            [96, '#[Assert\Email(["message" => "text"])]'],
            [96, '#[Assert\Text(["message" => "text"]), Assert\Domain(["message" => "text"]), Assert\Id(Assert\Id::REGEX[10]), ]'],
            [104],
            [109],
        ];

        return $data;
    }

    /**
     * Data provider.
     *
     * @see testBackwardsCompatibleNewAttributes
     *
     * @return array
     */
    public static function dataBackwardsCompatibleAttributes()
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
            [40],
            [41],
            [42],
            [43],
            [53],
            [59],
            [60],
            [64],
            [92],
            [95],
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
        $file = $this->sniffFile(__FILE__, '7.4');
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
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file);
    }
}
