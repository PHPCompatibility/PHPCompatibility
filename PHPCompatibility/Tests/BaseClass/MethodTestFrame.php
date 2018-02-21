<?php
/**
 * Base class to use when testing methods in the Sniff.php file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\BaseClass;

use PHPCompatibility\PHPCSHelper;

/**
 * Set up and Tear down methods for testing methods in the Sniff.php file.
 *
 * @uses    \PHPUnit_Framework_TestCase
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
abstract class MethodTestFrame extends \PHPUnit_Framework_TestCase
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
    protected $testcasePath = '../sniff-examples/utility-functions/';

    /**
     * The \PHP_CodeSniffer_File object containing parsed contents of this file.
     *
     * @var \PHP_CodeSniffer_File
     */
    protected $phpcsFile;

    /**
     * A wrapper for the abstract PHPCompatibility sniff.
     *
     * @var \PHPCompatibility\Sniff
     */
    protected $helperClass;


    /**
     * Set up fixtures for this unit test.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        require_once __DIR__ . '/TestHelperPHPCompatibility.php';
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

        $this->helperClass = new TestHelperPHPCompatibility;

        $filename = realpath(__DIR__) . DIRECTORY_SEPARATOR . $this->testcasePath . $this->filename;
        $contents = file_get_contents($filename);

        if (version_compare(PHPCSHelper::getVersion(), '2.99.99', '>')) {
            // PHPCS 3.x.
            $config            = new \PHP_Codesniffer\Config();
            $config->standards = array('PHPCompatibility');

            $ruleset = new \PHP_CodeSniffer\Ruleset($config);

            $this->phpcsFile = new \PHP_CodeSniffer\Files\DummyFile($contents, $ruleset, $config);
            $this->phpcsFile->process();

        } else {
            $phpcs = new \PHP_CodeSniffer();

            if (version_compare(PHPCSHelper::getVersion(), '1.99.99', '>')) {
                // PHPCS 2.x.
                $this->phpcsFile = new \PHP_CodeSniffer_File(
                    $filename,
                    array(),
                    array(),
                    $phpcs
                );
            } else {
                // PHPCS 1.x.
                $this->phpcsFile = new \PHP_CodeSniffer_File(
                    $filename,
                    array(),
                    array(),
                    array(),
                    array(),
                    $phpcs
                );
            }

            $this->phpcsFile->start($contents);
        }
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
     * @param string    $tokenContent  Optional. The token content for the target token.
     *
     * @return int
     */
    public function getTargetToken($commentString, $tokenType, $tokenContent = null)
    {
        $start   = ($this->phpcsFile->numTokens - 1);
        $comment = $this->phpcsFile->findPrevious(
            T_COMMENT,
            $start,
            null,
            false,
            $commentString
        );

        $tokens = $this->phpcsFile->getTokens();
        $end    = $start;

        // Limit the token finding to between this and the next case comment.
        for ($i = ($comment + 1); $i < $end; $i++) {
            if ($tokens[$i]['code'] !== T_COMMENT) {
                continue;
            }

            if (stripos($tokens[$i]['content'], '/* Case') === 0) {
                $end = $i;
                break;
            }
        }

        $target = $this->phpcsFile->findNext(
            $tokenType,
            ($comment + 1),
            $end,
            false,
            $tokenContent
        );

        if ($target === false) {
            $this->assertFalse(true, 'Failed to find test target token.');
        }

        return $target;
    }

}
