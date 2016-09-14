<?php
/**
 * Base class to use when testing methods in the Sniff.php file.
 *
 * @package PHPCompatibility
 */

if (class_exists('BaseSniffTest', true) === false) {
    require_once dirname(dirname(__FILE__)) . '/BaseSniffTest.php';
}

/**
 * Set up and Tear down methods for testing methods in the Sniff.php file.
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
abstract class BaseClass_MethodTestFrame extends BaseSniffTest
{

    public $filename;

    /**
     * The PHP_CodeSniffer_File object containing parsed contents of this file.
     *
     * @var PHP_CodeSniffer_File
     */
    protected $_phpcsFile;

    /**
     * A wrapper for the abstract PHPCompatibility sniff.
     *
     * @var PHPCompatibility_Sniff
     */
    protected $helperClass;


    public static function setUpBeforeClass()
    {
        require_once dirname(__FILE__) . '/TestHelperPHPCompatibility.php';
    }

    protected function setUp()
    {
        parent::setUp();

        $this->helperClass = new BaseClass_TestHelperPHPCompatibility;

        $filename = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $this->filename;
        $phpcs    = new PHP_CodeSniffer();

        if (version_compare(PHP_CodeSniffer::VERSION, '2.0', '<')) {
            $this->_phpcsFile = new PHP_CodeSniffer_File(
                $filename,
                array(),
                array(),
                array(),
                array(),
                $phpcs
            );
        }
        else {
            $this->_phpcsFile = new PHP_CodeSniffer_File(
                $filename,
                array(),
                array(),
                $phpcs
            );
        }

        $contents = file_get_contents($filename);
        $this->_phpcsFile->start($contents);
    }

    /**
     * Clean up after finished test.
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->_phpcsFile, $this->helperClass);

    }//end tearDown()

}
