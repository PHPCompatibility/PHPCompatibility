<?php
/**
 * Forbidden names as function invocations sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Forbidden names as function invocations sniff test file
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class ForbiddenNamesAsInvokedFunctionsSniffTest extends BaseSniffTest
{
    /**
     * Sniffed file
     *
     * @var PHP_CodeSniffer_File
     */
    protected $_sniffFile;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->_sniffFile = $this->sniffFile('sniff-examples/forbidden_names_function_invocation.php');
    }

    /**
     * TestAbstract
     *
     * @return void
     */
    public function testAbstract()
    {
        $this->assertError($this->_sniffFile, 6, "'abstract' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testCallable
     *
     * @return void
     */
    public function testCallable()
    {
        $this->assertError($this->_sniffFile, 7, "'callable' is a reserved keyword introduced in PHP version 5.4 and cannot be invoked as a function");
    }

    /**
     * testCatch
     *
     * @return void
     */
    public function testCatch()
    {
        $this->assertError($this->_sniffFile, 8, "'catch' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testClone
     *
     * @return void
     */
    public function testClone()
    {
        $this->assertError($this->_sniffFile, 9, "'clone' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testFinal
     *
     * @return void
     */
    public function testFinal()
    {
        $this->assertError($this->_sniffFile, 10, "'final' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testFinally
     *
     * TODO: Test 'finally' (PHP 5.5)
     *
     * @return void
     */
    public function testFinally()
    {
        $this->assertError($this->_sniffFile, 11, "'finally' is a reserved keyword introduced in PHP version 5.5 and cannot be invoked as a function");
    }

    /**
     * testGoto
     *
     * @return void
     */
    public function testGoto()
    {
        $this->assertError($this->_sniffFile, 12, "'goto' is a reserved keyword introduced in PHP version 5.3 and cannot be invoked as a function");
    }

    /**
     * testImplements
     *
     * @return void
     */
    public function testImplements()
    {
        $this->assertError($this->_sniffFile, 13, "'implements' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testInterface
     *
     * @return void
     */
    public function testInterface()
    {
        $this->assertError($this->_sniffFile, 14, "'interface' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testInstanceOf
     *
     * @return void
     */
    public function testInstanceOf()
    {
        $this->assertError($this->_sniffFile, 15, "'instanceof' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testInsteadOf
     *
     * @return void
     */
    public function testInsteadOf()
    {
        $this->assertError($this->_sniffFile, 16, "'insteadof' is a reserved keyword introduced in PHP version 5.4 and cannot be invoked as a function");
    }

    /**
     * testNamespace
     *
     * @return void
     */
    public function testNamespace()
    {
        $this->assertError($this->_sniffFile, 17, "'namespace' is a reserved keyword introduced in PHP version 5.3 and cannot be invoked as a function");
    }

    /**
     * testPrivate
     *
     * @return void
     */
    public function testPrivate()
    {
        $this->assertError($this->_sniffFile, 18, "'private' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testProteced
     *
     * @return void
     */
    public function testProteced()
    {
        $this->assertError($this->_sniffFile, 19, "'protected' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testPublic
     *
     * @return void
     */
    public function testPublic()
    {
        $this->assertError($this->_sniffFile, 20, "'public' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testThrow
     *
     * @return void
     */
    public function testThrow()
    {
        $this->assertError($this->_sniffFile, 21, "'throw' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testTrait
     *
     * @return void
     */
    public function testTrait()
    {
        $this->assertError($this->_sniffFile, 22, "'trait' is a reserved keyword introduced in PHP version 5.4 and cannot be invoked as a function");
    }

    /**
     * testTry
     *
     * @return void
     */
    public function testTry()
    {
        $this->assertError($this->_sniffFile, 23, "'try' is a reserved keyword introduced in PHP version 5.0 and cannot be invoked as a function");
    }

    /**
     * testBool
     *
     * @return void
     */
    public function testBool()
    {
        $this->assertError($this->_sniffFile, 32, "'bool' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
    }

    /**
     * testInt
     *
     * @return void
     */
    public function testInt()
    {
        $this->assertError($this->_sniffFile, 33, "'int' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
    }
    
    /**
     * testFloat
     *
     * @return void
     */
    public function testFloat()
    {
        $this->assertError($this->_sniffFile, 34, "'float' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
    }
    
    /**
     * testString
     *
     * @return void
     */
    public function testString()
    {
        $this->assertError($this->_sniffFile, 35, "'string' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
    }
    
    /**
     * testNull
     *
     * @return void
     */
    public function testNull()
    {
        $this->assertError($this->_sniffFile, 36, "'null' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
        $this->assertError($this->_sniffFile, 37, "'null' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
    }
    
    /**
     * testTrue
     *
     * @return void
     */
    public function testTrue()
    {
        $this->assertError($this->_sniffFile, 38, "'true' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
        $this->assertError($this->_sniffFile, 39, "'true' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
    }
    
    /**
     * testFalse
     *
     * @return void
     */
    public function testFalse()
    {
        $this->assertError($this->_sniffFile, 40, "'false' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
        $this->assertError($this->_sniffFile, 41, "'false' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
    }
    
    /**
     * testResource
     *
     * @return void
     */
    public function testResource()
    {
        $this->assertError($this->_sniffFile, 42, "'resource' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
    }
    
    /**
     * testObject
     *
     * @return void
     */
    public function testObject()
    {
        $this->assertError($this->_sniffFile, 43, "'object' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
    }
    
    /**
     * testMixed
     *
     * @return void
     */
    public function testMixed()
    {
        $this->assertError($this->_sniffFile, 44, "'mixed' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
    }

    /**
     * testNumberic
     *
     * @return void
     */
    public function testNumeric()
    {
        $this->assertError($this->_sniffFile, 45, "'numeric' is a reserved keyword introduced in PHP version 7.0 and cannot be invoked as a function");
    }
    
    /**
     * Verify that checking for a specific version works
     *
     * @return void
     */
    public function testGotoInPreviousVersion()
    {
        $this->_sniffFile = $this->sniffFile('sniff-examples/forbidden_names_function_invocation.php', '5.2');

        $this->assertNoViolation($this->_sniffFile, 12);
    }
}
