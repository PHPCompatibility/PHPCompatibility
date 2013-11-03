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

        $this->_sniffFile = $this->sniffFile('sniff-examples/removed_extensions.php');
    }

    /**
     * testActiveScript
     *
     * @return void
     */
    public function testActiveScript()
    {
        $this->assertError($this->_sniffFile, 3, "Extension 'activescript' is removed since PHP 5.3");
        $this->assertError($this->_sniffFile, 4, "Extension 'activescript' is removed since PHP 5.3");
    }

    /**
     * testCpdf
     *
     * @return void
     */
    public function testCpdf()
    {
        $this->assertError($this->_sniffFile, 6, "Extension 'cpdf' is removed since PHP 5.3");
        $this->assertError($this->_sniffFile, 7, "Extension 'cpdf' is removed since PHP 5.3");
        $this->assertError($this->_sniffFile, 8, "Extension 'cpdf' is removed since PHP 5.3");
    }

    /**
     * testDbase
     *
     * @return void
     */
    public function testDbase()
    {
        $this->assertError($this->_sniffFile, 10, "Extension 'dbase' is removed since PHP 5.3");
    }

    /**
     * testDbx
     *
     * @return void
     */
    public function testDbx()
    {
        $this->assertError($this->_sniffFile, 12, "Extension 'dbx' is removed since PHP 5.1");
    }

    /**
     * testDio
     *
     * @return void
     */
    public function testDio()
    {
        $this->assertError($this->_sniffFile, 14, "Extension 'dio' is removed since PHP 5.1");
    }

    /**
     * testFam
     *
     * @return void
     */
    public function testFam()
    {
        $this->assertError($this->_sniffFile, 16, "Extension 'fam' is removed since PHP 5.1");
    }

    /**
     * testFbsql
     *
     * @return void
     */
    public function testFbsql()
    {
        $this->assertError($this->_sniffFile, 18, "Extension 'fbsql' is removed since PHP 5.3");
    }

    /**
     * testFdf
     *
     * @return void
     */
    public function testFdf()
    {
        $this->assertError($this->_sniffFile, 20, "Extension 'fdf' is removed since PHP 5.3");
    }

    /**
     * testFilepro
     *
     * @return void
     */
    public function testFilepro()
    {
        $this->assertError($this->_sniffFile, 22, "Extension 'filepro' is removed since PHP 5.2");
    }

    /**
     * testHwApi
     *
     * @return void
     */
    public function testHwApi()
    {
        $this->assertError($this->_sniffFile, 24, "Extension 'hw_api' is removed since PHP 5.2");
    }

    /**
     * testIngres
     *
     * @return void
     */
    public function testIngres()
    {
        $this->assertError($this->_sniffFile, 26, "Extension 'ingres' is removed since PHP 5.1");
    }

    /**
     * testIrcg
     *
     * @return void
     */
    public function testIrcg()
    {
        $this->assertError($this->_sniffFile, 28, "Extension 'ircg' is removed since PHP 5.3");
    }

    /**
     * testMcve
     *
     * @return void
     */
    public function testMcve()
    {
        $this->assertError($this->_sniffFile, 30, "Extension 'mcve' is removed since PHP 5.1");
    }

    /**
     * testMing
     *
     * @return void
     */
    public function testMing()
    {
        $this->assertError($this->_sniffFile, 32, "Extension 'ming' is removed since PHP 5.3");
    }

    /**
     * testMnogosearch
     *
     * @return void
     */
    public function testMnogosearch()
    {
        $this->assertError($this->_sniffFile, 34, "Extension 'mnogosearch' is removed since PHP 5.1");
    }

    /**
     * testMsql
     *
     * @return void
     */
    public function testMsql()
    {
        $this->assertError($this->_sniffFile, 36, "Extension 'msql' is removed since PHP 5.3");
    }

    /**
     * testMysql
     *
     * @return void
     */
    public function testMysql()
    {
        $this->assertError($this->_sniffFile, 38, "Extension 'mysql_' is deprecated since PHP 5.5");
    }

    /**
     * testNcurses
     *
     * @return void
     */
    public function testNcurses()
    {
        $this->assertError($this->_sniffFile, 40, "Extension 'ncurses' is removed since PHP 5.3");
    }

    /**
     * testOracle
     *
     * @return void
     */
    public function testOracle()
    {
        $this->assertError($this->_sniffFile, 42, "Extension 'oracle' is removed since PHP 5.3");
    }

    /**
     * testOvrimos
     *
     * @return void
     */
    public function testOvrimos()
    {
        $this->assertError($this->_sniffFile, 44, "Extension 'ovrimos' is removed since PHP 5.1");
    }

    /**
     * testPfpro
     *
     * @return void
     */
    public function testPfpro()
    {
        $this->assertError($this->_sniffFile, 46, "Extension 'pfpro' is removed since PHP 5.3");
    }

    /**
     * testSqlite
     *
     * @return void
     */
    public function testSqlite()
    {
        $this->assertError($this->_sniffFile, 48, "Extension 'sqlite' is removed since PHP 5.4");
    }

    /**
     * testSybase
     *
     * @return void
     */
    public function testSybase()
    {
        $this->assertError($this->_sniffFile, 50, "Extension 'sybase' is removed since PHP 5.3");
    }

    /**
     * testW32api
     *
     * @return void
     */
    public function testW32api()
    {
        $this->assertError($this->_sniffFile, 52, "Extension 'w32api' is removed since PHP 5.1");
    }

    /**
     * testYp
     *
     * @return void
     */
    public function testYp()
    {
        $this->assertError($this->_sniffFile, 54, "Extension 'yp' is removed since PHP 5.3");
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
}
