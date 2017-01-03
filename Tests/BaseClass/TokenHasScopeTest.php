<?php
/**
 * Token scope test file
 *
 * @package PHPCompatibility
 */


/**
 * Token scope function tests
 *
 * @group utilityTokenScope
 * @group utilityFunctions
 *
 * @uses    BaseClass_MethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class BaseClass_TokenScopeTest extends BaseClass_MethodTestFrame
{

    /**
     * The file name for the file containing the test cases within the
     * `sniff-examples/utility-functions/` directory.
     *
     * @var string
     */
    protected $filename = 'token_has_scope.php';

    /**
     * testTokenHasScope
     *
     * @dataProvider dataTokenHasScope
     *
     * @covers PHPCompatibility_Sniff::tokenHasScope
     *
     * @param int    $stackPtr Stack pointer for an arbitrary token in the test file.
     * @param string $expected The expected boolean return value.
     */
    public function testTokenHasScope($stackPtr, $expected, $validTokens = null)
    {
        $result = $this->helperClass->tokenHasScope($this->_phpcsFile, $stackPtr, $validTokens);
        $this->assertSame($expected, $result);
    }

    /**
     * dataTokenHasScope
     *
     * @see testTokenHasScope()
     *
     * @return array
     */
    public function dataTokenHasScope()
    {
        return array(
            // No scope.
            array(2, false), // $var

            // Various scopes.
            array(23, true), // echo within if
            array(23, true, T_IF), // echo within if
            array(23, false, array(T_SWITCH) ), // echo within if

            array(45, true), // echo within else-if
            array(45, true, array(T_ELSEIF)), // echo within else-if
            array(45, false, array(T_IF)), // echo within else-if

            array(57, true), // echo within else
            array(86, true), // echo within for
            array(107, true), // echo within foreach

            array(123, true), // case within switch
            array(123, true, array(T_SWITCH)), // case within switch
            array(123, false, array(T_CASE)), // case within switch

            array(129, true), // echo within case within switch
            array(129, true, array(T_SWITCH)), // echo within case within switch
            array(129, true, T_CASE), // echo within case within switch
            array(129, true, array(T_SWITCH, T_CASE)), // echo within case within switch
            array(129, true, array(T_SWITCH, T_IF)), // echo within case within switch
            array(129, false, array(T_ELSEIF, T_IF)), // echo within case within switch

            array(139, true), // default within switch
            array(143, true), // echo within default within switch

            array(164, true), // echo within function
            array(164, true, array(T_FUNCTION)), // echo within function
        );
    }

    /**
     * testInClassScope
     *
     * @dataProvider dataInClassScope
     *
     * @covers PHPCompatibility_Sniff::inClassScope
     *
     * @param int    $stackPtr Stack pointer for an arbitrary token in the test file.
     * @param string $expected The expected boolean return value.
     */
    public function testInClassScope($stackPtr, $expected)
    {
        $result = $this->helperClass->inClassScope($this->_phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataInClassScope
     *
     * @see testInClassScope()
     *
     * @return array
     */
    public function dataInClassScope()
    {
        return array(
            array(181, true), // $property
            array(185, true), // function in class
            array(202, false), // global function
            array(220, true), // function in namespaced class
            array(391, true), // function in anon class
        );
    }


    /**
     * testInUseScope
     *
     * @dataProvider dataInUseScope
     *
     * @covers PHPCompatibility_Sniff::inUseScope
     *
     * @param int    $stackPtr Stack pointer for an arbitrary token in the test file.
     * @param string $expected The expected boolean return value.
     */
    public function testInUseScope($stackPtr, $expected)
    {
        $result = $this->helperClass->inUseScope($this->_phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataInClassScope
     *
     * @see testInClassScope()
     *
     * @return array
     */
    public function dataInUseScope()
    {
        return array(
            array(235, false),
            array(244, false),
            array(255, false),
            array(269, false),
            array(283, false),
            array(303, true),
            array(327, true),
            array(351, true),
            array(375, true),
        );
    }

}
