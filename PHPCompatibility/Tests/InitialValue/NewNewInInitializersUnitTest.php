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
 * Test the NewNewInInitializers sniff.
 *
 * @group newNewInInitializers
 * @group initialValue
 *
 * @covers \PHPCompatibility\Sniffs\InitialValue\NewNewInInitializersSniff
 *
 * @since 10.0.0
 */
final class NewNewInInitializersUnitTest extends BaseSniffTest
{

    /**
     * Partial error phrases to be used in combination with the error message constant.
     *
     * @since 10.0.0.
     *
     * @var array
     */
    protected $initialValueTypes = [
        'const'     => 'global/namespaced constants declared using the const keyword',
        'property'  => '', // Not supported.
        'staticvar' => 'static variables',
        'default'   => 'default parameter values',
    ];

    /**
     * Verify that new in initial values is correctly detected.
     *
     * @dataProvider dataNewInInitializer
     *
     * @param int    $line The line number.
     * @param string $type Error type.
     *
     * @return void
     */
    public function testNewInInitializer($line, $type)
    {
        $file        = $this->sniffFile(__FILE__, '8.0');
        $replacement = $this->initialValueTypes[$type];
        $this->assertError($file, $line, "New in initializers is not supported in PHP 8.0 or earlier for {$replacement}.");
    }

    /**
     * Data provider.
     *
     * @see testNewInInitializer()
     *
     * @return array
     */
    public function dataNewInInitializer()
    {
        return [
            [38, 'const'],
            [39, 'const'],
            [40, 'const'],
            [44, 'staticvar'], // x2.
            [45, 'staticvar'],
            [49, 'default'],
            [51, 'default'],
            [52, 'default'],
            [53, 'default'],
            [54, 'default'],
            [56, 'default'],
            [60, 'default'],
            [61, 'default'],
            [64, 'default'],
            [69, 'default'],
            [70, 'default'],
            [73, 'default'],
            [78, 'default'],
            [81, 'default'],
            [86, 'default'],
            [87, 'default'],
            [90, 'default'],
            [95, 'default'],

            // Still not supported, but due to non-static parameters being passed to the constructor. Flag these anyway.
            [102, 'default'],
            [103, 'default'],
            [106, 'default'],
            [107, 'default'],
        ];
    }


    /**
     * Verify the sniff doesn't throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0');
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
        for ($line = 1; $line <= 32; $line++) {
            $data[] = [$line];
        }

        for ($line = 110; $line <= 148; $line++) {
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
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertNoViolation($file);
    }
}
