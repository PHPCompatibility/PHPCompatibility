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

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewConstantScalarExpressions sniff.
 *
 * @group newConstantScalarExpressions
 * @group initialValue
 *
 * @covers \PHPCompatibility\AbstractInitialValueSniff
 * @covers \PHPCompatibility\Sniffs\InitialValue\NewConstantScalarExpressionsSniff
 *
 * @since 8.2.0
 */
class NewConstantScalarExpressionsUnitTest extends BaseSniffTest
{

    /**
     * Error phrases.
     *
     * @var array
     */
    private $errorPhrases = [
        'const'    => 'when defining constants using the const keyword',
        'property' => 'in property declarations',
        'static'   => 'in static variable declarations',
        'default'  => 'in default function arguments',
    ];


    /**
     * testNewConstantScalarExpressions
     *
     * @dataProvider dataNewConstantScalarExpressions
     *
     * @param int    $line  The line number.
     * @param string $type  Error type.
     * @param string $extra Extra snippet which will be part of the error message.
     *                      Only needed when testing several errors on the same line.
     *
     * @return void
     */
    public function testNewConstantScalarExpressions($line, $type, $extra = '')
    {
        $file    = $this->sniffFile(__FILE__, '5.5');
        $snippet = '';
        if (isset($this->errorPhrases[$type]) === true) {
            $snippet = $this->errorPhrases[$type];
        }

        $error = "Constant scalar expressions are not allowed {$snippet} in PHP 5.5 or earlier.";
        if ($extra !== '') {
            $error .= ' Found: ' . $extra;
        }

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider dataNewConstantScalarExpressions.
     *
     * @see testNewConstantScalarExpressions()
     *
     * @return array
     */
    public function dataNewConstantScalarExpressions()
    {
        return [
            [122, 'const'],
            [123, 'const'],
            [124, 'const'],
            [125, 'const'],
            [126, 'const'],
            [127, 'const'],
            [128, 'const'],
            [129, 'const'],
            [130, 'const'],
            [131, 'const'],
            [132, 'const'],
            [133, 'const'],
            [134, 'const'],
            [135, 'const'],
            [136, 'const'],
            [137, 'const'],
            [138, 'const'],
            [139, 'const'],
            [140, 'const'],
            [141, 'const'],
            [142, 'const'],
            [143, 'const'],
            [144, 'const'],
            [145, 'const'],
            [146, 'const'],
            [147, 'const'],
            [148, 'const'],
            [149, 'const'],
            [150, 'const'],

            [153, 'const'],

            [156, 'const'],
            [157, 'const'],

            [161, 'const'],
            [162, 'const'],
            [163, 'const'],
            [165, 'property'],
            [166, 'property'],
            [171, 'property'],
            [173, 'default'],

            [180, 'const'],
            [181, 'const'],
            [182, 'const'],
            [184, 'property'],
            [185, 'property'],
            [193, 'property'],
            [195, 'default'],

            [202, 'property'],
            [203, 'property'],
            [208, 'property'],
            [210, 'default'],

            [216, 'default', '$a = 5 * MINUTEINSECONDS'],
            [216, 'default', '$b = [ \'a\', 1 + 2 ]'],
            [220, 'default', '$a = 30 / HALF'],
            [220, 'default', '$b = array( 1, THREE, \'string\'.\'concat\')'],
            [224, 'default', '$a = (1 + 1)'],
            [224, 'default', '$b = 2 << 3'],
            [224, 'default', '$c = ((BAR)?10:100)'],
            [224, 'default', '$f = 10 * 5'],

            [227, 'default'],
            [228, 'default'],
            [229, 'default'],

            [233, 'static'],
            [234, 'static'],
            [235, 'static'],
            [236, 'static', '$h = (24 and 2)'],
            [236, 'static', '$i = ONE * 2'],
            [236, 'static', '$j = \'a\' . \'b\''],
            [238, 'static'],

            [241, 'static'],
            [242, 'const'],
            [244, 'property'],
            [246, 'const'],
            [247, 'const'],
            [250, 'property'],

            [258, 'const'],
            [259, 'const'],

            [262, 'static'],
            [263, 'static'],
            [264, 'static'],
            [265, 'static'],

            [287, 'const'],
            [288, 'const'],
            [289, 'const'],

            [293, 'static'],

            [297, 'property'],
            [301, 'property'],

            [309, 'default'],
        ];
    }


    /**
     * Test the sniff doesn't throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '5.5');
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

        // No errors expected on the first 120 lines.
        for ($line = 1; $line <= 120; $line++) {
            $data[] = [$line];
        }

        // ... nor on line 267 - 282.
        for ($line = 267; $line <= 280; $line++) {
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
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file);
    }
}
