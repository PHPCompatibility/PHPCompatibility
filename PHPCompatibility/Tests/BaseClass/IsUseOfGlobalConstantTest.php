<?php
/**
 * Is use of global constant ? test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\BaseClass;

/**
 * isUseOfGlobalConstant() function tests
 *
 * @group utilityIsUseOfGlobalConstant
 * @group utilityFunctions
 *
 * @uses    \PHPCompatibility\Tests\BaseClass\MethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class IsUseOfGlobalConstantTest extends MethodTestFrame
{

    /**
     * The file name for the file containing the test cases within the
     * `sniff-examples/utility-functions/` directory.
     *
     * @var string
     */
    protected $filename = 'is_use_of_global_constant.php';

    /**
     * testIsUseOfGlobalConstant
     *
     * @dataProvider dataIsUseOfGlobalConstant
     *
     * @covers \PHPCompatibility\Sniff::isUseOfGlobalConstant
     *
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param string $expected      The expected boolean return value.
     *
     * @return void
     */
    public function testIsUseOfGlobalConstant($commentString, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, T_STRING, 'PHP_VERSION_ID');
        $result   = $this->helperClass->isUseOfGlobalConstant($this->phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataIsUseOfGlobalConstant
     *
     * @see testIsUseOfGlobalConstant()
     *
     * @return array
     */
    public function dataIsUseOfGlobalConstant()
    {
        return array(
            array('/* Case 1 */', false),
            array('/* Case 2 */', false),
            array('/* Case 3 */', false),
            array('/* Case 4 */', false),
            array('/* Case 5 */', false),
            array('/* Case 6 */', false),
            array('/* Case 7 */', false),
            array('/* Case 8 */', false),
            array('/* Case 9 */', false),
            array('/* Case 10 */', false),
            array('/* Case 11 */', false),
            array('/* Case 12 */', false),
            array('/* Case 13 */', false),
            array('/* Case 14 */', false),
            array('/* Case 15 */', false),
            array('/* Case 16 */', false),
            array('/* Case 17 */', false),
            array('/* Case 18 */', false),
            array('/* Case 19 */', false),
            array('/* Case 20 */', false),
            array('/* Case 21 */', false),
            array('/* Case 22 */', false),
            array('/* Case 23 */', false),
            array('/* Case 24 */', false),
            array('/* Case 25 */', false),
            array('/* Case 26 */', false),
            array('/* Case 27 */', false),
            array('/* Case 28 */', false),
            array('/* Case 29 */', false),
            array('/* Case 30 */', false),
            array('/* Case 31 */', false),

            array('/* Case A1 */', true),
            array('/* Case A2 */', true),
            array('/* Case A3 */', true),
            array('/* Case A4 */', true),
            array('/* Case A5 */', true),
            array('/* Case A6 */', true),
            array('/* Case A7 */', true),
            array('/* Case A8 */', true),
            array('/* Case A9 */', true),
        );
    }
}
