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
 * Test the ForbiddenParameterShadowSuperGlobals sniff.
 *
 * @group forbiddenParameterShadowSuperGlobals
 * @group functionDeclarations
 * @group superglobals
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\ForbiddenParameterShadowSuperGlobalsSniff
 *
 * @since 7.0.3
 */
class ForbiddenParameterShadowSuperGlobalsUnitTest extends BaseSniffTest
{

    /**
     * testParameterShadowSuperGlobals
     *
     * @dataProvider dataParameterShadowSuperGlobals
     *
     * @param string $superglobal Parameter name.
     * @param int    $line        Line number where the error should occur.
     *
     * @return void
     */
    public function testParameterShadowSuperGlobal($superglobal, $line)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertError($file, $line, "Parameter shadowing super global ({$superglobal}) causes a fatal error since PHP 5.4");
    }

    /**
     * dataParameterShadowSuperGlobals
     *
     * @see testParameterShadowSuperGlobals()
     *
     * @return array
     */
    public function dataParameterShadowSuperGlobals()
    {
        return [
            ['$GLOBALS', 4],
            ['$_SERVER', 5],
            ['$_GET', 6],
            ['$_POST', 7],
            ['$_FILES', 8],
            ['$_COOKIE', 9],
            ['$_SESSION', 10],
            ['$_REQUEST', 11],
            ['$_ENV', 12],
            ['$GLOBALS', 20],
            ['$_SERVER', 21],
            ['$_GET', 22],
            ['$_ENV', 25],
        ];
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
        $file = $this->sniffFile(__FILE__, '5.4');
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
        return [
            [15],
            [16],
            [17],
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
