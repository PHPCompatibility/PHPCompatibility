<?php
/**
 * New return types test file
 *
 * @package PHPCompatibility
 */


/**
 * New return types test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewScalarReturnTypeDeclarationsSniffTest extends BaseSniffTest
{

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
     * @param int $line The line number.
     *
     * @return void
     */
    public function testScalarReturnType($line)
    {
        $file = $this->sniffFile('sniff-examples/new_scalar_return_type_declarations.php', '7.0');
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
            array(3),
            array(5),
            array(7),
            array(9),
        );
    }
}

