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
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param int    $targetType    The token type for the target token.
     * @param string $expected The expected boolean return value.
     */
    public function testTokenHasScope($commentString, $targetType, $expected, $validTokens = null)
    {
        $stackPtr = $this->getTargetToken($commentString, $targetType);
        $result   = $this->helperClass->tokenHasScope($this->_phpcsFile, $stackPtr, $validTokens);
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
            array('/* Case 1 */', T_VARIABLE, false), // $var

            // Various scopes.
            array('/* Case 2 */', T_ECHO, true), // echo within if
            array('/* Case 2 */', T_ECHO, true, T_IF), // echo within if
            array('/* Case 2 */', T_ECHO, false, array(T_SWITCH) ), // echo within if

            array('/* Case 3 */', T_ECHO, true), // echo within else-if
            array('/* Case 3 */', T_ECHO, true, array(T_ELSEIF)), // echo within else-if
            array('/* Case 3 */', T_ECHO, false, array(T_IF)), // echo within else-if

            array('/* Case 4 */', T_ECHO, true), // echo within else
            array('/* Case 5 */', T_ECHO, true), // echo within for
            array('/* Case 6 */', T_ECHO, true), // echo within foreach

            array('/* Case 7 */', T_CASE, true), // case within switch
            array('/* Case 7 */', T_CASE, true, array(T_SWITCH)), // case within switch
            array('/* Case 7 */', T_CASE, false, array(T_CASE)), // case within switch

            array('/* Case 8 */', T_ECHO, true), // echo within case within switch
            array('/* Case 8 */', T_ECHO, true, array(T_SWITCH)), // echo within case within switch
            array('/* Case 8 */', T_ECHO, true, T_CASE), // echo within case within switch
            array('/* Case 8 */', T_ECHO, true, array(T_SWITCH, T_CASE)), // echo within case within switch
            array('/* Case 8 */', T_ECHO, true, array(T_SWITCH, T_IF)), // echo within case within switch
            array('/* Case 8 */', T_ECHO, false, array(T_ELSEIF, T_IF)), // echo within case within switch

            array('/* Case 9 */', T_DEFAULT, true), // default within switch
            array('/* Case 10 */', T_ECHO, true), // echo within default within switch

            array('/* Case 11 */', T_ECHO, true), // echo within function
            array('/* Case 11 */', T_ECHO, true, array(T_FUNCTION)), // echo within function
        );
    }

    /**
     * testInClassScope
     *
     * @dataProvider dataInClassScope
     *
     * @covers PHPCompatibility_Sniff::inClassScope
     *
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param int    $targetType    The token type for the target token.
     * @param string $expected      The expected boolean return value.
     */
    public function testInClassScope($commentString, $targetType, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, $targetType);
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
            array('/* Case C1 */', T_VARIABLE, true), // $property
            array('/* Case C2 */', T_FUNCTION, true), // function in class
            array('/* Case C3 */', T_FUNCTION, false), // global function
            array('/* Case C4 */', T_FUNCTION, true), // function in namespaced class
            array('/* Case C5 */', T_FUNCTION, true), // function in anon class
        );
    }


    /**
     * testInUseScope
     *
     * @dataProvider dataInUseScope
     *
     * @covers PHPCompatibility_Sniff::inUseScope
     *
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param int    $targetType    The token type for the target token.
     * @param string $expected      The expected boolean return value.
     */
    public function testInUseScope($commentString, $targetType, $expected)
    {
        $stackPtr = $this->getTargetToken($commentString, $targetType);
        $result   = $this->helperClass->inUseScope($this->_phpcsFile, $stackPtr);
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
            array('/* Case U1 */', T_STRING, false),
            array('/* Case U2 */', T_AS, false),
            array('/* Case U3 */', T_STRING, false),
            array('/* Case U4 */', T_STRING, false),
            array('/* Case U5 */', T_STRING, false),
            array('/* Case U6 */', T_AS, true),
            array('/* Case U7 */', T_PUBLIC, true),
            array('/* Case U8 */', T_PROTECTED, true),
            array('/* Case U9 */', T_PRIVATE, true),
        );
    }

}
