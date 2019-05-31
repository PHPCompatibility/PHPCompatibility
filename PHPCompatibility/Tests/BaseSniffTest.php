<?php
/**
 * Base sniff test class file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests;

use PHPUnit_Framework_TestCase as PHPUnit_TestCase;
use PHPCompatibility\PHPCSHelper;
use PHP_CodeSniffer_File as File;

/**
 * BaseSniffTest
 *
 * Adds PHPCS sniffing logic and custom assertions for PHPCS errors and
 * warnings.
 *
 * @uses    \PHPUnit_Framework_TestCase
 * @package PHPCompatibility
 * @author  Jansen Price <jansen.price@gmail.com>
 */
class BaseSniffTest extends PHPUnit_TestCase
{
    const STANDARD_NAME = 'PHPCompatibility';

    /**
     * The PHP_CodeSniffer object used for testing.
     *
     * Used by PHPCS 2.x.
     *
     * @var \PHP_CodeSniffer
     */
    protected static $phpcs = null;

    /**
     * An array of PHPCS results by filename and PHP version.
     *
     * @var array
     */
    public static $sniffFiles = array();

    /**
     * Sets up this unit test.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$sniffFiles = array();
        parent::setUpBeforeClass();
    }

    /**
     * Sets up this unit test.
     *
     * @return void
     */
    protected function setUp()
    {
        if (class_exists('\PHP_CodeSniffer') === true) {
            /*
             * PHPCS 2.x.
             */
            if (self::$phpcs === null) {
                self::$phpcs = new \PHP_CodeSniffer();
            }

            self::$phpcs->cli->setCommandLineValues(array('-pq', '--colors'));

            // Restrict the sniffing of the test case files to the particular sniff being tested.
            self::$phpcs->initStandard(self::STANDARD_NAME, array($this->getSniffCode()));

            self::$phpcs->setIgnorePatterns(array());
        }

        parent::setUp();
    }

    /**
     * Tear down after each test.
     *
     * @return void
     */
    public function tearDown()
    {
        // Reset the targetPhpVersion.
        PHPCSHelper::setConfigData('testVersion', null, true);
    }

    /**
     * Tear down after each test.
     *
     * @return void
     */
    public static function tearDownAfterClass()
    {
        self::$sniffFiles = array();
    }

    /**
     * Get the sniff code for the current sniff being tested.
     *
     * @return string
     */
    protected function getSniffCode()
    {
        $class    = \get_class($this);
        $parts    = explode('\\', $class);
        $sniff    = array_pop($parts);
        $sniff    = str_replace('UnitTest', '', $sniff);
        $category = array_pop($parts);
        return self::STANDARD_NAME . '.' . $category . '.' . $sniff;
    }

    /**
     * Sniff a file and return resulting file object.
     *
     * @param string $pathToFile       Absolute path to the file to sniff.
     *                                 Allows for passing __FILE__ from the unit test
     *                                 file. In that case, the test case file is presumed
     *                                 to have the same name, but with an `inc` extension.
     * @param string $targetPhpVersion Value of 'testVersion' to set on PHPCS object.
     *
     * @return \PHP_CodeSniffer_File|false File object.
     */
    public function sniffFile($pathToFile, $targetPhpVersion = 'none')
    {
        if (strpos($pathToFile, 'UnitTest.php') !== false) {
            // Ok, so __FILE__ was passed, change the file extension.
            $pathToFile = str_replace('UnitTest.php', 'UnitTest.inc', $pathToFile);
        }
        $pathToFile = realpath($pathToFile);

        if (isset(self::$sniffFiles[$pathToFile][$targetPhpVersion])) {
            return self::$sniffFiles[$pathToFile][$targetPhpVersion];
        }

        if ($targetPhpVersion !== 'none') {
            PHPCSHelper::setConfigData('testVersion', $targetPhpVersion, true);
        }

        try {
            if (class_exists('\PHP_CodeSniffer\Files\LocalFile')) {
                // PHPCS 3.x.
                $config            = new \PHP_CodeSniffer\Config();
                $config->cache     = false;
                $config->standards = array(self::STANDARD_NAME);
                $config->sniffs    = array($this->getSniffCode());
                $config->ignored   = array();
                $ruleset           = new \PHP_CodeSniffer\Ruleset($config);

                self::$sniffFiles[$pathToFile][$targetPhpVersion] = new \PHP_CodeSniffer\Files\LocalFile($pathToFile, $ruleset, $config);
                self::$sniffFiles[$pathToFile][$targetPhpVersion]->process();
            } else {
                // PHPCS 2.x.
                self::$sniffFiles[$pathToFile][$targetPhpVersion] = self::$phpcs->processFile($pathToFile);
            }

        } catch (\Exception $e) {
            $this->fail('An unexpected exception has been caught when loading file "' . $pathToFile . '" : ' . $e->getMessage());
            return false;
        }

        return self::$sniffFiles[$pathToFile][$targetPhpVersion];
    }

    /**
     * Assert a PHPCS error on a particular line number.
     *
     * @param \PHP_CodeSniffer_File $file            Codesniffer file object.
     * @param int                   $lineNumber      Line number.
     * @param string                $expectedMessage Expected error message (assertContains).
     *
     * @return bool
     */
    public function assertError(File $file, $lineNumber, $expectedMessage)
    {
        $errors = $this->gatherErrors($file);

        return $this->assertForType($errors, 'error', $lineNumber, $expectedMessage);
    }

    /**
     * Assert a PHPCS warning on a particular line number.
     *
     * @param \PHP_CodeSniffer_File $file            Codesniffer file object.
     * @param int                   $lineNumber      Line number.
     * @param string                $expectedMessage Expected message (assertContains).
     *
     * @return bool
     */
    public function assertWarning(File $file, $lineNumber, $expectedMessage)
    {
        $warnings = $this->gatherWarnings($file);

        return $this->assertForType($warnings, 'warning', $lineNumber, $expectedMessage);
    }

    /**
     * Assert a PHPCS error or warning on a particular line number.
     *
     * @param array  $issues          Array of issues of a particular type.
     * @param string $type            The type of issues, either 'error' or 'warning'.
     * @param int    $lineNumber      Line number.
     * @param string $expectedMessage Expected message (assertContains).
     *
     * @return bool
     */
    private function assertForType($issues, $type, $lineNumber, $expectedMessage)
    {
        if (isset($issues[$lineNumber]) === false) {
            throw new \Exception("Expected $type '$expectedMessage' on line number $lineNumber, but none found.");
        }

        $insteadFoundMessages = array();

        // Concat any error messages so we can do an assertContains.
        foreach ($issues[$lineNumber] as $issue) {
            $insteadFoundMessages[] = $issue['message'];
        }

        $insteadMessagesString = implode(', ', $insteadFoundMessages);
        return $this->assertContains(
            $expectedMessage,
            $insteadMessagesString,
            "Expected $type message '$expectedMessage' on line $lineNumber not found. Instead found: $insteadMessagesString."
        );
    }

    /**
     * Assert no violation (warning or error) on a given line number.
     *
     * @param \PHP_CodeSniffer_File $file       Codesniffer File object.
     * @param mixed                 $lineNumber Line number.
     *
     * @return bool
     */
    public function assertNoViolation(File $file, $lineNumber = null)
    {
        $errors   = $this->gatherErrors($file);
        $warnings = $this->gatherWarnings($file);

        if (empty($errors) && empty($warnings)) {
            return $this->assertTrue(true);
        }

        if (is_null($lineNumber)) {
            $failMessage = 'Failed asserting no violations in file. Found ' . \count($errors) . ' errors and ' . \count($warnings) . ' warnings.';
            $allMessages = $errors + $warnings;
            // TODO: Update the fail message to give the tester some
            // indication of what the errors or warnings were.
            return $this->assertEmpty($allMessages, $failMessage);
        }

        $encounteredMessages = array();
        if (isset($errors[$lineNumber])) {
            foreach ($errors[$lineNumber] as $error) {
                $encounteredMessages[] = 'ERROR: ' . $error['message'];
            }
        }

        if (isset($warnings[$lineNumber])) {
            foreach ($warnings[$lineNumber] as $warning) {
                $encounteredMessages[] = 'WARNING: ' . $warning['message'];
            }
        }

        $failMessage = "Failed asserting no standards violation on line $lineNumber. Found: \n"
            . implode("\n", $encounteredMessages);
        $this->assertCount(0, $encounteredMessages, $failMessage);
    }

    /**
     * Show violations in file by line number.
     *
     * This is useful for debugging sniffs on a file.
     *
     * @param \PHP_CodeSniffer_File $file Codesniffer file object.
     *
     * @return array
     */
    public function showViolations(File $file)
    {
        $violations = array(
            'errors'   => $this->gatherErrors($file),
            'warnings' => $this->gatherWarnings($file),
        );

        return $violations;
    }

    /**
     * Gather all error messages by line number from phpcs file result.
     *
     * @param \PHP_CodeSniffer_File $file Codesniffer File object.
     *
     * @return array
     */
    public function gatherErrors(File $file)
    {
        $foundErrors = $file->getErrors();

        return $this->gatherIssues($foundErrors);
    }

    /**
     * Gather all warning messages by line number from phpcs file result.
     *
     * @param \PHP_CodeSniffer_File $file Codesniffer File object.
     *
     * @return array
     */
    public function gatherWarnings(File $file)
    {
        $foundWarnings = $file->getWarnings();

        return $this->gatherIssues($foundWarnings);
    }

    /**
     * Gather all messages or a particular type by line number.
     *
     * @param array $issuesArray Array of a particular type of issues,
     *                           i.e. errors or warnings.
     *
     * @return array
     */
    private function gatherIssues($issuesArray)
    {
        $allIssues = array();
        foreach ($issuesArray as $line => $lineIssues) {
            foreach ($lineIssues as $column => $issues) {
                foreach ($issues as $issue) {

                    if (isset($allIssues[$line]) === false) {
                        $allIssues[$line] = array();
                    }

                    $allIssues[$line][] = $issue;
                }
            }
        }

        return $allIssues;
    }
}
