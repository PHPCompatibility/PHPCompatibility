<?php
/**
 * New return types test file
 *
 * @package PHPCompatibility
 */


/**
 * New return types test file
 *
 * @group newReturnTypeDeclarations
 * @group typeDeclarations
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewReturnTypeDeclarationsSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewReturnTypeDeclarationsSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_return_type_declarations.php';

    /**
     * testReturnType
     *
     * @dataProvider dataReturnType
     *
     * @param string $returnType        The return type.
     * @param string $lastVersionBefore The PHP version just *before* the type was introduced.
     * @param array  $line              The line number in the test file where the error should occur.
     * @param string $okVersion         A PHP version in which the return type was ok to be used.
     *
     * @return void
     */
    public function testReturnType($returnType, $lastVersionBefore, $line, $okVersion)
    {
        $file = $this->sniffFile(self::TEST_FILE, $lastVersionBefore);
        $this->assertError($file, $line, "{$returnType} return type is not present in PHP version {$lastVersionBefore} or earlier");

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testReturnType()
     *
     * @return array
     */
    public function dataReturnType()
    {
        return array(
            array('bool', '5.6', 4, '7.0'),
            array('int', '5.6', 5, '7.0'),
            array('float', '5.6', 6, '7.0'),
            array('string', '5.6', 7, '7.0'),
            array('array', '5.6', 8, '7.0'),
            array('callable', '5.6', 9, '7.0'),
            array('self', '5.6', 10, '7.0'),
            array('Class name', '5.6', 11, '7.0'),
            array('Class name', '5.6', 12, '7.0'),
            array('Class name', '5.6', 13, '7.0'),
            array('Class name', '5.6', 14, '7.0'),

            array('void', '7.0', 17, '7.1'),

            array('callable', '5.6', 20, '7.0'),
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
        $file = $this->sniffFile(self::TEST_FILE, '5.6'); // Low version below the first addition.
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
            array(23),
            array(24),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '99.0'); // High version beyond newest addition.
        $this->assertNoViolation($file);
    }

}
