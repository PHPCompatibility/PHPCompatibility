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

use PHPCompatibility\Tests\BaseSniffTest;
use PHPCSUtils\BackCompat\Helper;

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
class NewAttributesUnitTest extends BaseSniffTest
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
     * Data provider.
     *
     * @see testNewAttributes()
     *
     * @return array
     */
    public function dataNewAttributes()
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
        ];

        /*
         * In PHP < 8.0 in combination with PHPCS < 3.6.1, anything after the test case on
         * line 104 will be tokenized as `T_INLINE_HTML` and undetectable.
         */
        if (version_compare(Helper::getVersion(), '3.6.1', '>=') === true
            || version_compare(PHP_VERSION_ID, '80000', '>=') === true
        ) {
            $data[] = [109];
        }

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
    public function dataNoFalsePositives()
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
