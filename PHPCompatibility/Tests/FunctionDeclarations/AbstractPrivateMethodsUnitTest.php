<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionDeclarations;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the AbstractPrivateMethods sniff.
 *
 * @group abstractPrivateMethods
 * @group functiondeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\AbstractPrivateMethodsSniff
 *
 * @since 9.2.0
 * @since 10.0.0 Moved from `Classes` to `FunctionDeclarations`.
 */
class AbstractPrivateMethodsUnitTest extends BaseSniffTest
{

    /**
     * Verify that the sniff throws an error for non-trait abstract private methods for PHP 5.1+.
     *
     * @dataProvider dataForbiddenAbstractPrivateMethods
     *
     * @param int $line The line number where an error is expected.
     *
     * @return void
     */
    public function testForbiddenAbstractPrivateMethods($line)
    {
        $file = $this->sniffFile(__FILE__, '5.1');
        $this->assertError($file, $line, 'Abstract methods cannot be declared as private since PHP 5.1');
    }

    /**
     * Data provider.
     *
     * @see testForbiddenAbstractPrivateMethods()
     *
     * @return array
     */
    public function dataForbiddenAbstractPrivateMethods()
    {
        return array(
            array(28),
            array(29),
            array(33),
            array(34),
        );
    }


    /**
     * Verify that the sniff throws an error for abstract private methods in traits for PHP 7.4
     * and doesn't for PHP 8.0.
     *
     * @dataProvider dataNewTraitAbstractPrivateMethods
     *
     * @param int $line The line number where a warning is expected.
     *
     * @return void
     */
    public function testNewTraitAbstractPrivateMethods($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4');
        $this->assertError($file, $line, 'Traits cannot declare "abstract private" methods in PHP 7.4 or below');

        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNewTraitAbstractPrivateMethods()
     *
     * @return array
     */
    public function dataNewTraitAbstractPrivateMethods()
    {
        return array(
            array(42),
            array(43),
        );
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
        $file = $this->sniffFile(__FILE__, '5.1');
        $this->assertNoViolation($file, $line);

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
        $cases = array();
        // No errors expected on the first 24 lines.
        for ($line = 1; $line <= 24; $line++) {
            $cases[] = array($line);
        }

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.0');
        $this->assertNoViolation($file);
    }
}
