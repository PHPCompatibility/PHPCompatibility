<?php
/**
 * Forbidden names sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Forbidden names sniff test
 *
 * @group forbiddenNames
 * @group reservedKeywords
 *
 * @covers PHPCompatibility_Sniffs_PHP_ForbiddenNamesSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class ForbiddenNamesSniffTest extends BaseSniffTest
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
        // `generate-forbidden-names-test-files` in sniff-examples
        $filename = "sniff-examples/forbidden-names/$usecase.php";

        // Set the testVersion to the highest PHP version encountered in the
        // PHPCompatibility_Sniffs_PHP_ForbiddenNamesSniff::$invalidNames list
        // to catch all errors + beyond `use function/const` exception.
        $file = $this->sniffFile($filename, '5.6');

        $this->assertNoViolation($file, 2);

        $lineCount = count(file($file->getFilename()));
        // Each line of the use case files (starting at line 3) exhibits an
        // error.
        for ($i = 3; $i < $lineCount; $i++) {
            $this->assertError($file, $i, 'Function name, class name, namespace name or constant name can not be reserved keyword');
        }

    }

    /**
     * Provides use cases to test with each keyword
     *
     * @return array
     */
    public function usecaseProvider()
    {
        return array(
            array('namespace'),
            array('use'),
            array('use-as'),
            array('class'),
            array('class-extends'),
            array('class-use-trait'),
            array('class-use-trait-const'),
            array('class-use-trait-function'),
            array('class-use-trait-alias-method'),
            array('class-use-trait-alias-public-method'),
            array('class-use-trait-alias-protected-method'),
            array('class-use-trait-alias-private-method'),
            array('trait'),
            array('function-declare'),
            array('const'),
            array('define'),
            array('interface'),
            array('interface-extends'),
        );
    }


    /**
     * testCorrectUsageOfKeywords
     *
     * @return void
     */
    public function testCorrectUsageOfKeywords()
    {
        $file = $this->sniffFile('sniff-examples/forbidden_names_correct_usage.php', '5.5');
        $this->assertNoViolation($file);
    }

    /**
     * testCorrectUsageUseFunctionConst
     *
     * @return void
     */
    public function testCorrectUsageUseFunctionConst()
    {
        $file = $this->sniffFile('sniff-examples/forbidden_names_correct_usage_use.php', '5.6');
        $this->assertNoViolation($file);
    }

    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile('sniff-examples/forbidden-names/class.php', '4.4'); // Version number specific to the line being tested.
        $this->assertNoViolation($file, 3);
    }
}
