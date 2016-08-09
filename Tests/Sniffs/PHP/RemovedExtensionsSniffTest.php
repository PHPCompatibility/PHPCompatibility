<?php
/**
 * Removed extensions sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Removed extensions sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class RemovedExtensionsSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/removed_extensions.php';

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

        $this->_sniffFile = $this->sniffFile(self::TEST_FILE);
    }


    /**
     * testRemovedExtension
     *
     * @dataProvider dataRemovedExtension
     *
     * @param string $extensionName  Name of the PHP extension.
     * @param string $removedIn      The PHP version in which the extension was removed.
     * @param array  $lines          The line numbers in the test file which apply to this extension.
     * @param string $okVersion      A PHP version in which the extension was still present.
     * @param string $removedVersion Optional PHP version to test removal message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedExtension($extensionName, $removedIn, $lines, $okVersion, $removedVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (isset($removedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $removedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        }
        foreach($lines as $line) {
            $this->assertError($file, $line, "Extension '{$extensionName}' is removed since PHP {$removedIn}");
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedExtension()
     *
     * @return array
     */
    public function dataRemovedExtension()
    {
        return array(
            array('activescript', '5.1', array(3, 4), '5.0'),
            array('cpdf', '5.1', array(6, 7, 8), '5.0'),
            array('dbase', '5.3', array(10), '5.2'),
            array('dbx', '5.1', array(12), '5.0'),
            array('dio', '5.1', array(14), '5.0'),
            array('fam', '5.1', array(16), '5.0'),
            array('fbsql', '5.3', array(18), '5.2'),
            array('fdf', '5.3', array(20), '5.2'),
            array('filepro', '5.2', array(22), '5.1'),
            array('hw_api', '5.2', array(24), '5.1'),
            array('ingres', '5.1', array(26), '5.0'),
            array('ircg', '5.1', array(28), '5.0'),
            array('mcve', '5.1', array(30), '5.0'),
            array('ming', '5.3', array(32), '5.2'),
            array('mnogosearch', '5.1', array(34), '5.0'),
            array('msql', '5.3', array(36), '5.2'),
            array('ncurses', '5.3', array(40), '5.2'),
            array('oracle', '5.1', array(42), '5.0'),
            array('ovrimos', '5.1', array(44), '5.0'),
            array('pfpro', '5.3', array(46), '5.2'),
            array('sqlite', '5.4', array(48), '5.3'),
            array('sybase', '5.3', array(50), '5.2'),
            array('w32api', '5.1', array(52), '5.0'),
            array('yp', '5.1', array(54), '5.0'),
            array('mssql', '7.0', array(63), '5.6'),
        );
    }


    /**
     * testDeprecatedRemovedExtension
     *
     * @dataProvider dataDeprecatedRemovedExtension
     *
     * @param string $extensionName     Name of the PHP extension.
     * @param string $deprecatedIn      The PHP version in which the extension was deprecated.
     * @param string $removedIn         The PHP version in which the extension was removed.
     * @param array  $lines             The line numbers in the test file which apply to this extension.
     * @param string $okVersion         A PHP version in which the extension was still present.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $removedVersion    Optional PHP version to test removal message with -
     *                                  if different from the $removedIn version.
     *
     * @return void
     */
    public function testDeprecatedRemovedExtension($extensionName, $deprecatedIn, $removedIn, $lines, $okVersion, $deprecatedVersion = null, $removedVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (isset($deprecatedVersion)){
            $file = $this->sniffFile(self::TEST_FILEE, $deprecatedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedIn);
        }
        foreach($lines as $line) {
            $this->assertWarning($file, $line, "Extension '{$extensionName}' is deprecated since PHP {$deprecatedIn}");
        }

        if (isset($removedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $removedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        }
        foreach($lines as $line) {
            $this->assertError($file, $line, "Extension '{$extensionName}' is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}");
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedRemovedExtension()
     *
     * @return array
     */
    public function dataDeprecatedRemovedExtension()
    {
        return array(
            array('ereg', '5.3', '7.0', array(65), '5.2'),
            array('mysql_', '5.5', '7.0', array(38), '5.4'),
        );
    }


    /**
     * testNotAFunctionCall
     *
     * @return void
     */
    public function testNotAFunctionCall()
    {
        $this->assertNoViolation($this->_sniffFile, 57);
    }

    /**
     * testFunctionDeclaration
     *
     * @return void
     */
    public function testFunctionDeclaration()
    {
        $this->assertNoViolation($this->_sniffFile, 58);
    }

    /**
     * testNewClass
     *
     * @return void
     */
    public function testNewClass()
    {
        $this->assertNoViolation($this->_sniffFile, 59);
    }

    /**
     * testMethod
     *
     * @return void
     */
    public function testMethod()
    {
        $this->assertNoViolation($this->_sniffFile, 60);
    }

    /**
     * testWhiteListing
     *
     * @return void
     */
    public function testWhiteListing()
    {
        $this->assertNoViolation($this->_sniffFile, 68);
    }
}
