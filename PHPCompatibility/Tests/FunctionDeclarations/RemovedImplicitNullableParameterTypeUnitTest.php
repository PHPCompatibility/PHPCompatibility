<?php

/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2024 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionDeclarations;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedOptionalBeforeRequiredParam sniff.
 *
 * @group removedOptionalBeforeRequiredParam
 * @group functiondeclarations
 *
 * @covers \PHPCompatibility\Sniffs\FunctionDeclarations\RemovedOptionalBeforeRequiredParamSniff
 *
 * @since 10.0.0
 */
class RemovedImplicitNullableParameterTypeUnitTest extends BaseSniffTestCase
{

    /**
     * Verify that the sniff throws a warning for optional parameters before required.
     *
     * @dataProvider dataImplicitNullableParam
     *
     * @param int    $line  The line number where a warning is expected.
     * @param string $param The parameter name used in the message.
     *
     * @return void
     */
    public function testImplicitNullableParam($line, $param)
    {
        $error = 'Implicitly marking parameter %s as nullable is deprecated since PHP 8.4';
        $file  = $this->sniffFile(__FILE__, '8.4');
        $this->assertWarning($file, $line, sprintf($error, $param));

        $error .= ', and removed in PHP 9.0';
        $file   = $this->sniffFile(__FILE__, '9.0');
        $this->assertError($file, $line, sprintf($error, $param));
    }

    /**
     * Data provider.
     *
     * @see testImplicitNullableParam()
     *
     * @return array
     */
    public static function dataImplicitNullableParam()
    {
        return [
            'class' => [23, '$thing'],
            'scalar' => [24, '$number'],
            'union1' => [26, '$number'],
            'union2' => [27, '$numberOrBool'],
            'union3' => [29, '$person'],
            'intersection1' => [31, '$one'],
            'intersection2' => [32, '$two'],
            'disjunctive normal form 1' => [35, '$one'],
            'disjunctive normal form 2' => [36, '$two'],
        ];
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
        $file = $this->sniffFile(__FILE__, '8.4');
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
        return [
            'no type' => [4],
            'no type and no default value' => [5],
            'no default value' => [6],
            'default not null' => [7],
            'scalar with question 1' => [9],
            'scalar with question 2' => [10],
            'union with null 1' => [13],
            'union with null 2' => [14],
            'union with null 3' => [15],
            'disjunctive normal form 1' => [18],
            'disjunctive normal form 2' => [19],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file);
    }

    /**
     * Ensure that the fixer produces expected output
     *
     * @return void
     */
    public function testFixer()
    {
        $this->assertFixerOutputMatches(str_replace('.php', '.inc', __FILE__));
    }
}
