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
        $file = $this->sniffFile($filename, '5.5');

        $this->assertNoViolation($file, 2);

        $lineCount = \count(\file($filename));
        // Each line of the use case files (starting at line 3) exhibits an
        // error.
        for ($i = 3; $i < $lineCount; $i++) {
            $this->assertError($file, $i, 'Function name, class name, namespace name or constant name can not be reserved keyword');
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
            'function-declare-reference',
            'method-declare',
            'const',
            'class-const',
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
            'class-use-trait-alias-public-method',
            'class-use-trait-alias-protected-method',
            'class-use-trait-alias-private-method',
            'class-use-trait-alias-final-method',
        ];

        $data = [];
        foreach ($testCaseTypes as $type) {
            $data[$type] = [$type];
        }

        return $data;
    }


    /**
     * testNotForbiddenInPHP7
     *
     * @dataProvider usecaseProviderPHP7
     *
     * @param string $usecase Partial filename of the test case file covering
     *                        a specific use case.
     *
     * @return void
     */
    public function testNotForbiddenInPHP7($usecase)
    {
        $file = $this->sniffFile(__DIR__ . "/ForbiddenNames/$usecase.inc", '7.0');
        $this->assertNoViolation($file);
    }

    /**
     * Provides use cases to test with each keyword.
     *
     * @return array
     */
    public function usecaseProviderPHP7()
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
            [24, 'as'],
            [25, 'finally'],
            [30, 'class'],
            [34, 'exit'],
            [39, 'interface'],
            [51, 'switch'],
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
        $file = $this->sniffFile($path, '5.5');
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
