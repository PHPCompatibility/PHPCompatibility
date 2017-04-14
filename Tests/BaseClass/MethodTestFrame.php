<?php
/**
 * Base class to use when testing methods in the Sniff.php file.
 *
 * @package PHPCompatibility
 */

/**
 * Set up and Tear down methods for testing methods in the Sniff.php file.
 *
 * @uses    PHPCompatibility_Testcase_Wrapper
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
abstract class BaseClass_MethodTestFrame extends PHPCompatibility_Testcase_Wrapper
{

    /**
     * The file name for the file containing the test cases within the $case_directory.
     *
     * @var string
     */
    protected $filename;

    /**
     * The path to the directory containing the test file for stand-alone method tests
     * relative to this file.
     *
     * @var string
     */
    protected $case_directory = '../sniff-examples/utility-functions/';

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


    /**
     * Set up fixtures for this unit test.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        require_once dirname(__FILE__) . '/TestHelperPHPCompatibility.php';
        parent::setUpBeforeClass();
    }

    /**
     * Sets up this unit test.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->helperClass = new BaseClass_TestHelperPHPCompatibility;

        $filename = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $this->case_directory . $this->filename;
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


    /**
     * Get the token pointer for a target token based on a specific comment found on the line before.
     *
     * @param string    $commentString The comment to look for.
     * @param int|array $tokenType     The type of token(s) to look for.
     *
     * @return int
     */
    public function getTargetToken($commentString, $tokenType)
    {
        $start   = ($this->_phpcsFile->numTokens - 1);
        $comment = $this->_phpcsFile->findPrevious(
            T_COMMENT,
            $start,
            null,
            false,
            $commentString
        );

        return $this->_phpcsFile->findNext(
            $tokenType,
            ($comment + 1)
        );
    }

}
