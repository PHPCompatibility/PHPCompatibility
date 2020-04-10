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
 * Test the ForbiddenVariableNamesInClosureUse sniff.
 *
 * @group forbiddenVariableNamesInClosureUse
 * @group closures
 * @group functionDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\ForbiddenVariableNamesInClosureUseSniff
 *
 * @since 7.1.4
 */
class ForbiddenVariableNamesInClosureUseUnitTest extends BaseSniffTest
{

    /**
     * testForbiddenVariableNamesInClosureUse
     *
     * @dataProvider dataForbiddenVariableNamesInClosureUse
     *
     * @param int    $line    The line number.
     * @param string $varName Variable name which should be found.
     *
     * @return void
     */
    public function testForbiddenVariableNamesInClosureUse($line, $varName)
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertError($file, $line, 'Variables bound to a closure via the use construct cannot use the same name as superglobals, $this, or a declared parameter since PHP 7.1. Found: ' . $varName);
    }

    /**
     * Data provider.
     *
     * @see testForbiddenVariableNamesInClosureUse()
     *
     * @return array
     */
    public function dataForbiddenVariableNamesInClosureUse()
    {
        return array(
            array(4, '$_SERVER'),
            array(5, '$_REQUEST'),
            array(6, '$GLOBALS'),
            array(7, '$this'),
            array(8, '$param'),
            array(9, '$param'),
            array(10, '$c'),
            array(11, '$b'),
            array(11, '$d'),
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
        $file = $this->sniffFile(__FILE__, '7.1');
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
            array(18),
            array(19),
            array(22),
            array(23),
            array(24),
            array(27),
            array(31),
            array(32),
            array(33),
            array(36),
            array(41),
            array(44),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.0');
        $this->assertNoViolation($file);
    }
}
