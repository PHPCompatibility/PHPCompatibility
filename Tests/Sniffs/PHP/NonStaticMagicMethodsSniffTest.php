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
     * helperAssertNoViolation
     *
     * @param int $line The line number.
     *
     * @return void
     */
    private function helperAssertNoViolation($line)
    {
        $this->assertNoViolation($this->_sniffFile, $line);
    }


    /**
     * testCorrectImplementation
     *
     * @dataProvider dataCorrectImplementation
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testCorrectImplementation($line)
    {
        $this->helperAssertNoViolation($line);
    }

    /**
     * Data provider.
     *
     * @see testCorrectImplementation()
     *
     * @return array
     */
    public function dataCorrectImplementation()
    {
        return array(
            array(5),
            array(6),
            array(7),
            array(8),
            array(9),
            array(14),
            array(15),
            array(16),
            array(17),
            array(18),
            array(19),
        );
    }


    /**
     * testClassWithPrivateMagicMethods
     *
     * @dataProvider dataClassWithPrivateMagicMethods
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testClassWithPrivateMagicMethods($line)
    {
        $this->assertError($this->_sniffFile, $line, 'Magic methods must be public (since PHP 5.3)');
    }

    /**
     * Data provider.
     *
     * @see testClassWithPrivateMagicMethods()
     *
     * @return array
     */
    public function dataClassWithPrivateMagicMethods()
    {
        return array(
            array(24),
            array(25),
            array(26),
            array(27),
            array(28),
        );
    }


    /**
     * testClassWithProtectedMagicMethods
     *
     * @dataProvider dataClassWithProtectedMagicMethods
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testClassWithProtectedMagicMethods($line)
    {
        $this->assertError($this->_sniffFile, $line, 'Magic methods must be public (since PHP 5.3)');
    }

    /**
     * Data provider.
     *
     * @see testClassWithProtectedMagicMethods()
     *
     * @return array
     */
    public function dataClassWithProtectedMagicMethods()
    {
        return array(
            array(33),
            array(34),
            array(35),
            array(36),
            array(37),
        );
    }


    /**
     * testClassWithStaticMagicMethods
     *
     * @dataProvider dataClassWithStaticMagicMethods
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testClassWithStaticMagicMethods($line)
    {
        $this->assertError($this->_sniffFile, $line, 'Magic methods cannot be static (since PHP 5.3)');
    }

    /**
     * Data provider.
     *
     * @see testClassWithStaticMagicMethods()
     *
     * @return array
     */
    public function dataClassWithStaticMagicMethods()
    {
        return array(
            array(42),
            array(43),
            array(44),
            array(45),
            array(46),
        );
    }


    /**
     * testClassWithStaticPublicMagicMethod
     *
     * @dataProvider dataClassWithStaticPublicMagicMethod
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testClassWithStaticPublicMagicMethod($line)
    {
        $this->assertError($this->_sniffFile, $line, 'Magic methods cannot be static (since PHP 5.3)');
    }

    /**
     * Data provider.
     *
     * @see testClassWithStaticPublicMagicMethod()
     *
     * @return array
     */
    public function dataClassWithStaticPublicMagicMethod()
    {
        return array(
            array(51),
            array(56),
        );
    }


    /**
     * testClassWithPrivateStaticMagicMethod
     *
     * @dataProvider dataClassWithPrivateStaticMagicMethod
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testClassWithPrivateStaticMagicMethod($line)
    {
        $this->assertError($this->_sniffFile, $line, 'Magic methods must be public and cannot be static (since PHP 5.3)');
    }

    /**
     * Data provider.
     *
     * @see testClassWithPrivateStaticMagicMethod()
     *
     * @return array
     */
    public function dataClassWithPrivateStaticMagicMethod()
    {
        return array(
            array(61),
            array(66),
        );
    }


    /**
     * testClassWithProtectedStaticMagicMethod
     *
     * @dataProvider dataClassWithProtectedStaticMagicMethod
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testClassWithProtectedStaticMagicMethod($line)
    {
        $this->assertError($this->_sniffFile, $line, 'Magic methods must be public and cannot be static (since PHP 5.3)');
    }

    /**
     * Data provider.
     *
     * @see testClassWithProtectedStaticMagicMethod()
     *
     * @return array
     */
    public function dataClassWithProtectedStaticMagicMethod()
    {
        return array(
            array(71),
            array(76),
        );
    }


    /**
     * testClassWithPrivateStaticMagicMethodAndWhitespace
     *
     * @dataProvider dataClassWithPrivateStaticMagicMethodAndWhitespace
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testClassWithPrivateStaticMagicMethodAndWhitespace($line)
    {
        $this->assertError($this->_sniffFile, $line, 'Magic methods must be public and cannot be static (since PHP 5.3)');
    }

    /**
     * Data provider.
     *
     * @see testClassWithPrivateStaticMagicMethodAndWhitespace()
     *
     * @return array
     */
    public function dataClassWithPrivateStaticMagicMethodAndWhitespace()
    {
        return array(
            array(83),
        );
    }


    /**
     * testCorrectInterface
     *
     * @dataProvider dataCorrectInterface
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testCorrectInterface($line)
    {
        $this->helperAssertNoViolation($line);
    }

    /**
     * Data provider.
     *
     * @see testCorrectInterface()
     *
     * @return array
     */
    public function dataCorrectInterface()
    {
        return array(
            array(89),
            array(90),
            array(91),
            array(92),
            array(93),
            array(98),
            array(99),
            array(100),
            array(101),
            array(102),
            array(103),
        );
    }

    /**
     * testInterfaceWithPrivateMagicMethod
     *
     * @dataProvider dataInterfaceWithPrivateMagicMethod
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testInterfaceWithPrivateMagicMethod($line)
    {
        $this->assertError($this->_sniffFile, $line, 'Magic methods must be public (since PHP 5.3)');
    }

    /**
     * Data provider.
     *
     * @see testInterfaceWithPrivateMagicMethod()
     *
     * @return array
     */
    public function dataInterfaceWithPrivateMagicMethod()
    {
        return array(
            array(109),
        );
    }

}
