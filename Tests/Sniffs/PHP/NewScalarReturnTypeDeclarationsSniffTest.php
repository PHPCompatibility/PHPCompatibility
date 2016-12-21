<?php
/**
 * New return types test file
 *
 * @package PHPCompatibility
 */


/**
 * New return types test file
 *
 * @group newScalarReturnTypeDeclarations
 * @group typeDeclarations
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewScalarReturnTypeDeclarationsSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewScalarReturnTypeDeclarationsSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_scalar_return_type_declarations.php';

    /**
     * Set up: skip these tests if the PHPCS version isn't high enough.
     */
    protected function setUp()
    {
        if (version_compare(PHP_CodeSniffer::VERSION, '2.3.4', '<')) {
            $this->markTestSkipped();
        }
        else {
            parent::setUp();
        }
    }

    /**
     * testScalarReturnType
     *
     * @dataProvider dataScalarReturnType
     *
     * @param string $returnType        The return type.
     * @param string $lastVersionBefore The PHP version just *before* the type was introduced.
     * @param array  $line              The line number in the test file where the error should occur.
     * @param string $okVersion         A PHP version in which the return type was ok to be used.
     *
     * @return void
     */
    public function testScalarReturnType($returnType, $lastVersionBefore, $line, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        $this->assertError($file, $line, "{$returnType} return type is not present in PHP version {$lastVersionBefore} or earlier");

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testScalarReturnType()
     *
     * @return array
     */
    public function dataScalarReturnType()
    {
        return array(
            array('bool', '5.6', 3, '7.0'),
            array('int', '5.6', 5, '7.0'),
            array('float', '5.6', 7, '7.0'),
            array('string', '5.6', 9, '7.0'),
            array('void', '7.0', 11, '7.1'),
        );
    }
}

