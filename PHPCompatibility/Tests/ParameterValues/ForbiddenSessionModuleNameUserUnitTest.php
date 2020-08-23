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
 * Test the ForbiddenSessionModuleNameUser sniff.
 *
 * @group forbiddenSessionModuleNameUser
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\ForbiddenSessionModuleNameUserSniff
 *
 * @since 10.0.0
 */
class ForbiddenSessionModuleNameUserUnitTest extends BaseSniffTest
{

    /**
     * testForbiddenSessionModuleNameUser
     *
     * @dataProvider dataForbiddenSessionModuleNameUser
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testForbiddenSessionModuleNameUser($line)
    {
        $file = $this->sniffFile(__FILE__, '7.2');
        $this->assertError($file, $line, 'Passing "user" as the $module to session_module_name() is not allowed since PHP 7.2.');
    }

    /**
     * Data provider.
     *
     * @see testForbiddenSessionModuleNameUser()
     *
     * @return array
     */
    public function dataForbiddenSessionModuleNameUser()
    {
        return [
            [16],
            [17],
            [18],
        ];
    }


    /**
     * Verify there are no false positives on valid code.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '7.2');

        // No errors expected on the first 14 lines.
        for ($line = 1; $line <= 14; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertNoViolation($file);
    }
}
