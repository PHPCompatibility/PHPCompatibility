<?php
/**
 * Long Arrays Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Long Arrays Sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class LongArraysSniffTest extends BaseSniffTest
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

        $this->_sniffFile = $this->sniffFile('sniff-examples/long_arrays.php');
    }

    /**
     * Test http post vars
     *
     * @return void
     */
    public function testHttpPostVars()
    {
        $this->assertWarning($this->_sniffFile, 3, "The use of long predefined variables has been deprecated in 5.3 and removed in 5.4; Found '\$HTTP_POST_VARS'");
    }

    /**
     * testHttpGetVars
     *
     * @return void
     */
    public function testHttpGetVars()
    {
        $this->assertWarning($this->_sniffFile, 4, "The use of long predefined variables has been deprecated in 5.3 and removed in 5.4; Found '\$HTTP_GET_VARS'");
    }

    /**
     * testHttpEnvVars
     *
     * @return void
     */
    public function testHttpEnvVars()
    {
        $this->assertWarning($this->_sniffFile, 5, "The use of long predefined variables has been deprecated in 5.3 and removed in 5.4; Found '\$HTTP_ENV_VARS'");
    }

    /**
     * testHttpServerVars
     *
     * @return void
     */
    public function testHttpServerVars()
    {
        $this->assertWarning($this->_sniffFile, 6, "The use of long predefined variables has been deprecated in 5.3 and removed in 5.4; Found '\$HTTP_SERVER_VARS'");
    }

    /**
     * testHttpCookieVars
     *
     * @return void
     */
    public function testHttpCookieVars()
    {
        $this->assertWarning($this->_sniffFile, 7, "The use of long predefined variables has been deprecated in 5.3 and removed in 5.4; Found '\$HTTP_COOKIE_VARS'");
    }

    /**
     * testHttpCookieVars
     *
     * @return void
     */
    public function testHttpSessionVars()
    {
        $this->assertWarning($this->_sniffFile, 8, "The use of long predefined variables has been deprecated in 5.3 and removed in 5.4; Found '\$HTTP_SESSION_VARS'");
    }

    /**
     * testHttpPostFiles
     *
     * @return void
     */
    public function testHttpPostFiles()
    {
        $this->assertWarning($this->_sniffFile, 9, "The use of long predefined variables has been deprecated in 5.3 and removed in 5.4; Found '\$HTTP_POST_FILES'");
    }

    /**
     * Test when sniffing for a testVersion config param
     *
     * @return void
     */
    public function testSpecificVersionTest()
    {
        $file = $this->sniffFile('sniff-examples/long_arrays.php', '5.2');
        $this->assertNoViolation($file, 3);

        $file = $this->sniffFile('sniff-examples/long_arrays.php', '5.3');
        $this->assertWarning($file, 3, "The use of long predefined variables has been deprecated in 5.3 and removed in 5.4; Found '\$HTTP_POST_VARS'");
    }
}
