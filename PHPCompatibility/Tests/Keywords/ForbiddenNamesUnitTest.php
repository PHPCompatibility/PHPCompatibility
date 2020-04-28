<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Keywords;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the ForbiddenNames sniff.
 *
 * @group forbiddenNames
 * @group keywords
 *
 * @covers \PHPCompatibility\Sniffs\Keywords\ForbiddenNamesSniff
 *
 * @since 5.5
 */
class ForbiddenNamesUnitTest extends BaseSniffTest
{

    /**
     * Test case files containing tests for the "other" reserved keywords.
     *
     * This array should be kept in sync with the same in the "generate-forbidden-names-test-files" script.
     *
     * @var array
     */
    private $testsForOtherInvalidNames = [
        // Declarations.
        'namespace'        => true,
        'nested-namespace' => true,
        'class'            => true,
        'interface'        => true,
        'trait'            => true,

        // Aliases.
        'use-as'           => true,
        'multi-use-as'     => true,
        'group-use-as'     => true,
    ];

    /**
     * testForbiddenNames
     *
     * @dataProvider usecaseProvider
     *
     * @param string $usecase Partial filename of the test case file covering
     *                        a specific use case.
     *
     * @return void
     */
    public function testForbiddenNames($usecase)
    {
        // These use cases were generated using the PHP script
        // `generate-forbidden-names-test-files` in sniff-examples.
        $filename = __DIR__ . "/ForbiddenNames/$usecase.inc";

        // Set the testVersion to the highest PHP version encountered in the
        // \PHPCompatibility\Sniffs\Keywords\ForbiddenNamesSniff::$invalidNames
        // list to catch all errors.
        $file = $this->sniffFile($filename, '7.4');

        $this->assertNoViolation($file, 2);

        $lineCount            = \count(\file($filename));
        $fullReservedLineEnd  = $lineCount;
        $otherReservedLineEnd = $lineCount;
        if (isset($this->testsForOtherInvalidNames[$usecase]) === true) {
            $fullReservedLineEnd  = ($lineCount - 13);
            $otherReservedLineEnd = ($lineCount - 3);
        }

        // Each line of the use case files (starting at line 3) exhibits an error.
        for ($i = 3; $i <= $fullReservedLineEnd; $i++) {
            $this->assertError($file, $i, 'Function name, class name, namespace name or constant name can not be reserved keyword');
        }

        if (isset($this->testsForOtherInvalidNames[$usecase]) === true) {
            // The error message for "other" reserved keywords is slightly different.
            for ($i = ($fullReservedLineEnd + 1); $i <= $otherReservedLineEnd; $i++) {
                $this->assertError($file, $i, ' and should not be used to name a class, interface or trait or as part of a namespace');
            }

            // Other "soft" reserved are a warning.
            for ($i = ($otherReservedLineEnd + 1); $i <= $lineCount; $i++) {
                $this->assertWarning($file, $i, ' and should not be used to name a class, interface or trait or as part of a namespace');
            }
        }
    }

    /**
     * Provides use cases to test with each keyword.
     *
     * @return array
     */
    public function usecaseProvider()
    {
        $testCaseTypes = [
            // Declarations.
            'namespace',
            'nested-namespace',
            'class',
            'interface',
            'trait',
            'function-declare',
            'const',
            'define',

            // Aliases.
            'use-as',
            'use-function-as',
            'use-const-as',
            'multi-use-as',
            'multi-use-function-as',
            'multi-use-const-as',
            'group-use-as',
            'group-use-function-as',
            'group-use-const-as',
            'group-use-function-as-in-group',
            'group-use-const-as-in-group',
            'class-use-trait-alias-method',
        ];

        $data = [];
        foreach ($testCaseTypes as $type) {
            $data[$type] = [$type];
        }

        return $data;
    }


    /**
     * Test that reserved names trigger an error when used as method/class const name
     * in combination with PHP 5.
     *
     * @dataProvider usecaseProviderPHP5vs7
     *
     * @param string $usecase Partial filename of the test case file covering
     *                        a specific use case.
     *
     * @return void
     */
    public function testForbiddenInPHP5($usecase)
    {
        $filename = __DIR__ . "/ForbiddenNames/$usecase.inc";
        $file     = $this->sniffFile($filename, '5.6-');

        $this->assertNoViolation($file, 2);

        $lineCount = \count(\file($filename));
        // Each line of the use case files (starting at line 3) exhibits an
        // error.
        for ($i = 3; $i < $lineCount; $i++) {
            $this->assertError($file, $i, 'Function name, class name, namespace name or constant name can not be reserved keyword');
        }
    }

    /**
     * Test that reserved names do NOT trigger an error when used as method/class const name
     * in combination with PHP 7.
     *
     * @dataProvider usecaseProviderPHP5vs7
     *
     * @param string $usecase Partial filename of the test case file covering
     *                        a specific use case.
     *
     * @return void
     */
    public function testNotForbiddenInPHP7($usecase)
    {
        $file = $this->sniffFile(__DIR__ . "/ForbiddenNames/$usecase.inc", '7.0-');
        $this->assertNoViolation($file);
    }

    /**
     * Provides use cases to test with each keyword.
     *
     * @return array
     */
    public function usecaseProviderPHP5vs7()
    {
        return [
            'method-declare' => ['method-declare'],
            'class-const'    => ['class-const'],
        ];
    }


    /**
     * Test some specific code samples trigger the correct errors on the correct lines.
     *
     * @dataProvider dataSpecificCodeSamples
     *
     * @param int    $line    Line number of which to expect an error.
     * @param string $keyword Keyword which triggered the error.
     *
     * @return void
     */
    public function testSpecificCodeSamples($line, $keyword)
    {
        $file = $this->sniffFile(__DIR__ . '/ForbiddenNamesUnitTest.3.inc');
        $this->assertError($file, $line, "Function name, class name, namespace name or constant name can not be reserved keyword '$keyword'");
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function dataSpecificCodeSamples()
    {
        return [
            [8, 'for'],
            [8, 'list'],
            [8, 'foreach'],
            [11, 'as'],
            [12, 'private'],
            [16, 'or'],
            [17, 'list'],
            [18, 'die'],
            [25, 'endwhile'],
            [26, 'public'],
            [29, 'endfor'],
            [30, 'protected'],
            [33, 'endswitch'],
            [34, 'private'],
            [37, 'enddeclare'],
            [38, 'final'],
            [43, 'as'],
            [44, 'finally'],
            [46, 'as'],
            [50, 'class'],
            [54, 'exit'],
            [59, 'interface'],
            [71, 'switch'],
            [76, 'trait'],
        ];
    }


    /**
     * Test that no false positives are thrown for a keyword on a version in which the keyword wasn't reserved.
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__DIR__ . '/ForbiddenNames/class.inc', '4.4'); // Version number specific to the lines being tested.
        // Just a sample of the lines which should give no violation.
        $this->assertNoViolation($file, 3); // Keyword: abstract.
        $this->assertNoViolation($file, 8); // Keyword: callable.
        $this->assertNoViolation($file, 10); // Keyword: catch.
    }


    /**
     * Test that no false positives are thrown for one of the "other" keywords on a version
     * in which the keyword wasn't reserved.
     *
     * @return void
     */
    public function testNoFalsePositivesOtherReserved()
    {
        $file = $this->sniffFile(__DIR__ . '/ForbiddenNames/class.inc', '-5.6');

        // Some "other" reserved keywords.
        $this->assertNoViolation($file, 81); // Keyword: bool.
        $this->assertNoViolation($file, 86); // Keyword: void.
    }


    /**
     * Test that correct use of the keywords doesn't trigger false positives, as well as
     * use of incorrectly named constructs.
     *
     * @dataProvider dataCorrectUsageOfKeywords
     *
     * @param string $path Path to the test case file which shouldn't trigger any errors.
     *
     * @return void
     */
    public function testCorrectUsageOfKeywords($path)
    {
        $file = $this->sniffFile($path, '7.4');
        $this->assertNoViolation($file);
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function dataCorrectUsageOfKeywords()
    {
        return [
            'correct-use'                   => [__DIR__ . '/ForbiddenNamesUnitTest.1.inc'],
            'incorrect-use-but-not-checked' => [__DIR__ . '/ForbiddenNamesUnitTest.2.inc'],
        ];
    }
}
