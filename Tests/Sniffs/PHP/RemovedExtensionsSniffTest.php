<?php
/**
 * Removed extensions sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Removed extensions sniff tests
 *
 * @group removedExtensions
 * @group extensions
 *
 * @covers PHPCompatibility_Sniffs_PHP_RemovedExtensionsSniff
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
     * Set up the test file for some of these unit tests.
     *
     * @return void
     */
    protected function setUp()
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
            array('dbase', '5.3', array(10), '5.2'),
            array('fam', '5.1', array(16), '5.0'),
            array('fbsql', '5.3', array(18), '5.2'),
            array('filepro', '5.2', array(22), '5.1'),
            array('hw_api', '5.2', array(24), '5.1'),
            array('ircg', '5.1', array(28), '5.0'),
            array('mnogosearch', '5.1', array(34), '5.0'),
            array('msql', '5.3', array(36), '5.2'),
            array('mssql', '7.0', array(63), '5.6'),
            array('ovrimos', '5.1', array(44), '5.0'),
            array('pfpro', '5.3', array(46), '5.2'),
            array('sqlite', '5.4', array(48), '5.3'),
//            array('sybase', '7.0', array(xx), '5.6'), sybase_ct ???
            array('yp', '5.1', array(54), '5.0'),
        );
    }

    /**
     * testRemovedExtensionWithAlternative
     *
     * @dataProvider dataRemovedExtensionWithAlternative
     *
     * @param string $extensionName  Name of the PHP extension.
     * @param string $removedIn      The PHP version in which the extension was removed.
     * @param string $alternative       An alternative extension.
     * @param array  $lines          The line numbers in the test file which apply to this extension.
     * @param string $okVersion      A PHP version in which the extension was still present.
     * @param string $removedVersion Optional PHP version to test removal message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedExtensionWithAlternative($extensionName, $removedIn, $alternative, $lines, $okVersion, $removedVersion = null)
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
            $this->assertError($file, $line, "Extension '{$extensionName}' is removed since PHP {$removedIn}; Use {$alternative} instead");
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedExtensionWithAlternative()
     *
     * @return array
     */
    public function dataRemovedExtensionWithAlternative()
    {
        return array(
            array('activescript', '5.1', 'pecl/activescript', array(3, 4), '5.0'),
            array('cpdf', '5.1', 'pecl/pdflib', array(6, 7, 8), '5.0'),
            array('dbx', '5.1', 'pecl/dbx', array(12), '5.0'),
            array('dio', '5.1', 'pecl/dio', array(14), '5.0'),
            array('fdf', '5.3', 'pecl/fdf', array(20), '5.2'),
            array('ingres', '5.1', 'pecl/ingres', array(26), '5.0'),
            array('mcve', '5.1', 'pecl/mvce', array(30), '5.0'),
            array('ming', '5.3', 'pecl/ming', array(32), '5.2'),
            array('ncurses', '5.3', 'pecl/ncurses', array(40), '5.2'),
            array('oracle', '5.1', 'oci8 or pdo_oci', array(42), '5.0'),
            array('sybase', '5.3', 'sybase_ct', array(50), '5.2'),
            array('w32api', '5.1', 'pecl/ffi', array(52), '5.0'),
        );
    }


    /**
     * testDeprecatedRemovedExtensionWithAlternative
     *
     * @dataProvider dataDeprecatedRemovedExtensionWithAlternative
     *
     * @param string $extensionName     Name of the PHP extension.
     * @param string $deprecatedIn      The PHP version in which the extension was deprecated.
     * @param string $removedIn         The PHP version in which the extension was removed.
     * @param string $alternative       An alternative extension.
     * @param array  $lines             The line numbers in the test file which apply to this extension.
     * @param string $okVersion         A PHP version in which the extension was still present.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $removedVersion    Optional PHP version to test removal message with -
     *                                  if different from the $removedIn version.
     *
     * @return void
     */
    public function testDeprecatedRemovedExtensionWithAlternative($extensionName, $deprecatedIn, $removedIn, $alternative, $lines, $okVersion, $deprecatedVersion = null, $removedVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (isset($deprecatedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedIn);
        }
        foreach($lines as $line) {
            $this->assertWarning($file, $line, "Extension '{$extensionName}' is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead");
        }

        if (isset($removedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $removedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $removedIn);
        }
        foreach($lines as $line) {
            $this->assertError($file, $line, "Extension '{$extensionName}' is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}; Use {$alternative} instead");
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedRemovedExtensionWithAlternative()
     *
     * @return array
     */
    public function dataDeprecatedRemovedExtensionWithAlternative()
    {
        return array(
            array('ereg', '5.3', '7.0', 'pcre', array(65, 76), '5.2'),
            array('mysql_', '5.5', '7.0', 'mysqli', array(38), '5.4'),
        );
    }


    /**
     * testDeprecatedExtensionWithAlternative
     *
     * @dataProvider dataDeprecatedExtensionWithAlternative
     *
     * @param string $extensionName     Name of the PHP extension.
     * @param string $deprecatedIn      The PHP version in which the extension was deprecated.
     * @param string $alternative       An alternative extension.
     * @param array  $lines             The line numbers in the test file which apply to this extension.
     * @param string $okVersion         A PHP version in which the extension was still present.
     * @param string $deprecatedVersion Optional PHP version to test removal message with -
     *                                  if different from the $deprecatedIn version.
     *
     * @return void
     */
    public function testDeprecatedExtensionWithAlternative($extensionName, $deprecatedIn, $alternative, $lines, $okVersion, $deprecatedVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        if (isset($deprecatedVersion)){
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedVersion);
        }
        else {
            $file = $this->sniffFile(self::TEST_FILE, $deprecatedIn);
        }
        foreach($lines as $line) {
            $this->assertWarning($file, $line, "Extension '{$extensionName}' is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead");
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedExtensionWithAlternative()
     *
     * @return array
     */
    public function dataDeprecatedExtensionWithAlternative()
    {
        return array(
            array('mcrypt', '7.1', 'openssl (preferred) or pecl/mcrypt once available', array(71), '7.0'),
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
        $file = $this->sniffFile(self::TEST_FILE);
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
            array(57), // Not a function call.
            array(58), // Function declaration.
            array(59), // Class instantiation.
            array(60), // Method call.
            array(68), // Whitelisted function.
            array(74), // Whitelisted function array.
            array(75), // Whitelisted function array.
            array(78), // Live coding
        );
    }

}
