<?php
/**
 * Non Static Magic Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Non Static Magic Sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class NonStaticMagicMethodsSniffTest extends BaseSniffTest
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

        $this->_sniffFile = $this->sniffFile('sniff-examples/nonstatic_magic_methods.php');
    }

    /**
     * testCorrectImplementation
     *
     * @return void
     */
    public function testCorrectImplementation()
    {
        $this->assertNoViolation($this->_sniffFile, 5);
        $this->assertNoViolation($this->_sniffFile, 14);
        $this->assertNoViolation($this->_sniffFile, 15);
    }

    /**
     * testClassWithPrivateMagicMethods
     *
     * @return void
     */
    public function testClassWithPrivateMagicMethods()
    {
        $this->assertError($this->_sniffFile, 24, "Magic methods must be public (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 25, "Magic methods must be public (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 26, "Magic methods must be public (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 27, "Magic methods must be public (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 28, "Magic methods must be public (since PHP 5.3)");
    }

    /**
     * testClassWithProtectedMagicMethods
     *
     * @return void
     */
    public function testClassWithProtectedMagicMethods()
    {
        $this->assertError($this->_sniffFile, 33, "Magic methods must be public (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 34, "Magic methods must be public (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 35, "Magic methods must be public (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 36, "Magic methods must be public (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 37, "Magic methods must be public (since PHP 5.3)");
    }

    /**
     * testClassWithStaticMagicMethods
     *
     * @return void
     */
    public function testClassWithStaticMagicMethods()
    {
        $this->assertError($this->_sniffFile, 42, "Magic methods cannot be static (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 43, "Magic methods cannot be static (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 44, "Magic methods cannot be static (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 45, "Magic methods cannot be static (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 46, "Magic methods cannot be static (since PHP 5.3)");
    }

    /**
     * testClassWithStaticPublicMagicMethod
     *
     * @return void
     */
    public function testClassWithStaticPublicMagicMethod()
    {
        $this->assertError($this->_sniffFile, 51, "Magic methods cannot be static (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 56, "Magic methods cannot be static (since PHP 5.3)");
    }

    /**
     * testClassWithPrivateStaticMagicMethod
     *
     * @return void
     */
    public function testClassWithPrivateStaticMagicMethod()
    {
        $this->assertError($this->_sniffFile, 61, "Magic methods must be public and cannot be static (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 66, "Magic methods must be public and cannot be static (since PHP 5.3)");
    }

    /**
     * testClassWithProtectedStaticMagicMethod
     *
     * @return void
     */
    public function testClassWithProtectedStaticMagicMethod()
    {
        $this->assertError($this->_sniffFile, 71, "Magic methods must be public and cannot be static (since PHP 5.3)");
        $this->assertError($this->_sniffFile, 76, "Magic methods must be public and cannot be static (since PHP 5.3)");
    }

    /**
     * testClassWithPrivateStaticMagicMethodAndWhitespace
     *
     * @return void
     */
    public function testClassWithPrivateStaticMagicMethodAndWhitespace()
    {
        $this->assertError($this->_sniffFile, 83, "Magic methods must be public and cannot be static (since PHP 5.3)");
    }

    /**
     * testCorrectInterface
     *
     * @return void
     */
    public function testCorrectInterface()
    {
        $this->assertNoViolation($this->_sniffFile, 89);
        $this->assertNoViolation($this->_sniffFile, 98);
    }

    /**
     * testInterfaceWithPrivateMagicMethod
     *
     * @return void
     */
    public function testInterfaceWithPrivateMagicMethod()
    {
        $this->assertError($this->_sniffFile, 109, "Magic methods must be public (since PHP 5.3)");
    }
}
