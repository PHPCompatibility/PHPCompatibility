<?php
/**
 * Forbidden names sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Forbidden names sniff test
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class ForbiddenNamesSniffTest extends BaseSniffTest
{
    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

    }

    /**
     * testNamespace
     *
     * @dataProvider usecaseProvider
     */
    public function testForbiddenNames($usecase)
    {
        // These use cases were generated using the PHP script 
        // `generate-forbidden-names-test-files` in sniff-examples
        $filename = "sniff-examples/forbidden-names/$usecase.php";
        $file = $this->sniffFile($filename);

        $this->assertNoViolation($file, 2);

        $lineCount = count(file($file->getFilename()));

        // Each line of the use case files (starting at line 3) exhibits an 
        // error.
        for ($i = 3; $i < $lineCount; $i++) {
            $this->assertError($file, $i, "Function name, class name, namespace name or constant name can not be reserved keyword");
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
            array('class-use-trait-alias-method'),
            array('trait'),
            array('function-declare'),
            array('const'),
            array('define'),
        );
    }

    /**
     * testCorrectUsageOfKeywords
     *
     * @return void
     */
    public function testCorrectUsageOfKeywords()
    {
        if (ini_get('date.timezone') == false) {
            ini_set('date.timezone', 'America/Chicago');
        }
        $file = $this->sniffFile("sniff-examples/forbidden_names_correct_usage.php");

        $this->assertNoViolation($file);
    }

    /**
     * Test setting test version option
     *
     * @return void
     */
    public function testSettingTestVersion()
    {
        $file = $this->sniffFile("sniff-examples/forbidden-names/class.php", "5.2");

        $this->assertNoViolation($file, 8);
    }
}
