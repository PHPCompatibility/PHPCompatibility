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
 * Test the RemovedPCREModifiers sniff.
 *
 * @group removedPCREModifiers
 * @group parameterValues
 * @group regexModifiers
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedPCREModifiersSniff
 * @covers \PHPCompatibility\Helpers\PCRERegexTrait
 *
 * @since 5.6
 */
class RemovedPCREModifiersUnitTest extends BaseSniffTest
{

    /**
     * testDeprecatedEModifier
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
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertWarning($file, $line, "{$functionName}() - /e modifier is deprecated since PHP 5.5");

        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertError($file, $line, "{$functionName}() - /e modifier is deprecated since PHP 5.5 and removed since PHP 7.0");
    }

    /**
     * dataDeprecatedEModifier
     *
     * @see testDeprecatedEModifier()
     *
     * @return array
     */
    public function dataDeprecatedEModifier()
    {
        return [
            // Function preg_replace().
            [50],
            [51],
            [54],
            [55],
            [58],
            [59],
            [60],
            [72],
            [80],
            [84],

            // Bracket delimiters.
            [99],
            [100],
            [104],
            [106],
            [108],

            // Function preg_filter().
            [114, 'preg_filter'],
            [115, 'preg_filter'],
            [118, 'preg_filter'],
            [119, 'preg_filter'],
            [122, 'preg_filter'],
            [123, 'preg_filter'],
            [124, 'preg_filter'],
            [136, 'preg_filter'],
            [144, 'preg_filter'],
            [148, 'preg_filter'],

            // Regex build up of multiple tokens.
            [153],
            [154],

            // Array of patterns.
            [162],
            [163],
            [164],
            [165],
            [166],

            [173],
            [174],
            [175],
            [176],
            [177],

            [182], // Three errors.

            // Interpolated variables.
            [204],
            [205],

            // Quote as a delimiter.
            [211],

            // Heredoc/Nowdoc.
            [228, 'preg_filter'],
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number where no error should occur.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoFalsePositives
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return [
            // No or only valid modifiers.
            [9],
            [10],
            [13],
            [14],
            [17],
            [18],
            [21],
            [24],
            [39],
            [45],

            // Untestable regex (variable, constant, function call).
            [94],
            [95],
            [96],

            // Bracket delimiters.
            [101],
            [102],
            [103],
            [105],
            [107],
            [109],

            // Issue 265 - mixed string quotes.
            [157],

            // Issues https://wordpress.org/support/topic/wrong-error-preg_replace-e-modifier-is-forbidden-since-php-7-0/
            [167],
            [178],
            [187],
            [201],

            // Interpolated variables.
            [206],

            // Quote as a delimiter.
            [210],

            // Heredoc/Nowdoc.
            [220],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertNoViolation($file);
    }
}
