<?php
/**
 * Base class to use when testing methods in the Sniff.php file.
 *
 * @package PHPCompatibility
 */

/**
 * Set up and Tear down methods for testing methods in the Sniff.php file.
 *
 * @uses    PHPUnit_Framework_TestCase
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
abstract class BaseClass_MethodTestFrame extends PHPUnit_Framework_TestCase
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
    protected $phpcsFile;

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
            $this->phpcsFile = new PHP_CodeSniffer_File(
                $filename,
                array(),
                array(),
                array(),
                array(),
                $phpcs
            );

        } else {
            $this->phpcsFile = new PHP_CodeSniffer_File(
                $filename,
                array(),
                array(),
                $phpcs
            );
        }

        $contents = file_get_contents($filename);
        $this->phpcsFile->start($contents);
    }

    /**
     * Clean up after finished test.
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->phpcsFile, $this->helperClass);

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
        $start   = ($this->phpcsFile->numTokens - 1);
        $comment = $this->phpcsFile->findPrevious(
            T_COMMENT,
            $start,
            null,
            false,
            $commentString
        );

        return $this->phpcsFile->findNext(
            $tokenType,
            ($comment + 1)
        );
    }

}
