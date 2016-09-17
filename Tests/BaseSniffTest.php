<?php
/**
 * Base sniff test class file
 *
 * @package PHPCompatibility
 */

/**
 * BaseSniffTest
 *
 * Adds PHPCS sniffing logic and custom assertions for PHPCS errors and
 * warnings
 *
 * @uses PHPUnit_Framework_TestCase
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class BaseSniffTest extends PHPUnit_Framework_TestCase
{
    /**
     * The PHP_CodeSniffer object used for testing.
     *
     * @var PHP_CodeSniffer
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
    }

    /**
     * Sets up this unit test.
     *
     * @return void
     */
    protected function setUp()
    {
        if (self::$phpcs === null) {
            self::$phpcs = new PHP_CodeSniffer();
        }

        PHP_CodeSniffer::setConfigData('testVersion', null, true);
        if (method_exists('PHP_CodeSniffer_CLI', 'setCommandLineValues')) { // For PHPCS 2.x
            self::$phpcs->cli->setCommandLineValues(array('-pq', '--colors'));
        }

        self::$phpcs->process(array(), dirname( __FILE__ ) . '/../');
        self::$phpcs->setIgnorePatterns(array());
    }

    /**
     * Tear down after each test
     *
     * @return void
     */
    public function tearDown()
    {
        // Reset any settingsStandard (targetPhpVersion)
        self::$phpcs->cli->settingsStandard = array();
    }

    /**
     * Tear down after each test
     *
     * @return void
     */
    public static function tearDownAfterClass()
    {
        self::$sniffFiles = array();
    }

    /**
     * Sniff a file and return resulting file object
     *
     * @param string $filename Filename to sniff
     * @param string $targetPhpVersion Value of 'testVersion' to set on PHPCS object
     * @return PHP_CodeSniffer_File|false File object
     */
    public function sniffFile($filename, $targetPhpVersion = null)
    {
        if ( isset(self::$sniffFiles[$filename][$targetPhpVersion])) {
            return self::$sniffFiles[$filename][$targetPhpVersion];
        }

        if (null !== $targetPhpVersion) {
            PHP_CodeSniffer::setConfigData('testVersion', $targetPhpVersion, true);
        }

        $pathToFile = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $filename;
        try {
            self::$sniffFiles[$filename][$targetPhpVersion] = self::$phpcs->processFile($pathToFile);
        } catch (Exception $e) {
            $this->fail('An unexpected exception has been caught: ' . $e->getMessage());
            return false;
        }

        return self::$sniffFiles[$filename][$targetPhpVersion];
    }

    /**
     * Assert a PHPCS error on a particular line number
     *
     * @param PHP_CodeSniffer_File $file Codesniffer file object
     * @param int $lineNumber Line number
     * @param string $expectedMessage Expected error message (assertContains)
     * @return bool
     */
    public function assertError(PHP_CodeSniffer_File $file, $lineNumber, $expectedMessage)
    {
        $errors = $this->gatherErrors($file);

        return $this->assertForType($errors, 'error', $lineNumber, $expectedMessage);
    }

    /**
     * Assert a PHPCS warning on a particular line number
     *
     * @param PHP_CodeSniffer_File $file Codesniffer file object
     * @param int $lineNumber Line number
     * @param string $expectedMessage Expected message (assertContains)
     * @return bool
     */
    public function assertWarning(PHP_CodeSniffer_File $file, $lineNumber, $expectedMessage)
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
     * @return bool
     */
    private function assertForType($issues, $type, $lineNumber, $expectedMessage)
    {
        if (!isset($issues[$lineNumber])) {
            throw new Exception("Expected $type '$expectedMessage' on line number $lineNumber, but none found.");
        }

        $insteadFoundMessages = array();

        // Concat any error messages so we can do an assertContains
        foreach ($issues[$lineNumber] as $issue) {
            $insteadFoundMessages[] = $issue['message'];
        }

        $insteadMessagesString = implode(', ', $insteadFoundMessages);
        return $this->assertContains(
            $expectedMessage, $insteadMessagesString,
            "Expected $type message '$expectedMessage' on line $lineNumber not found. Instead found: $insteadMessagesString."
        );
    }

    /**
     * Assert no violation (warning or error) on a given line number
     *
     * @param PHP_CodeSniffer_File $file Codesniffer File object
     * @param mixed $lineNumber Line number
     * @return bool
     */
    public function assertNoViolation(PHP_CodeSniffer_File $file, $lineNumber = 0)
    {
        $errors   = $this->gatherErrors($file);
        $warnings = $this->gatherWarnings($file);

        if (!count($errors) && !count($warnings)) {
            return $this->assertTrue(true);
        }

        if ($lineNumber == 0) {
            $allMessages = $errors + $warnings;
            // TODO: Update the fail message to give the tester some
            // indication of what the errors or warnings were
            return $this->assertEmpty($allMessages, 'Failed asserting no violations in file');
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

        if (!count($encounteredMessages)) {
            return $this->assertTrue(true);
        }

        $failMessage = "Failed asserting no standards violation on line $lineNumber: "
            . implode(', ', $encounteredMessages);

        $this->assertEmpty($encounteredMessages, $failMessage);
    }

    /**
     * Show violations in file by line number
     *
     * This is useful for debugging sniffs on a file
     *
     * @param PHP_CodeSniffer_File $file Codesniffer file object
     * @return void
     */
    public function showViolations(PHP_CodeSniffer_File $file)
    {
        $violations = array(
            'errors'   => $this->gatherErrors($file),
            'warnings' => $this->gatherWarnings($file),
        );

        return $violations;
    }

    /**
     * Gather all error messages by line number from phpcs file result
     *
     * @param PHP_CodeSniffer_File $file Codesniffer File object
     * @return array
     */
    public function gatherErrors(PHP_CodeSniffer_File $file)
    {
        $foundErrors = $file->getErrors();

        return $this->gatherIssues($foundErrors);
    }

    /**
     * Gather all warning messages by line number from phpcs file result
     *
     * @param PHP_CodeSniffer_File $file Codesniffer File object
     * @return array
     */
    public function gatherWarnings(PHP_CodeSniffer_File $file)
    {
        $foundWarnings = $file->getWarnings();

        return $this->gatherIssues($foundWarnings);
    }

    /**
     * Gather all messages or a particular type by line number.
     *
     * @param array $issuesArray Array of a particular type of issues,
     *                           i.e. errors or warnings.
     * @return array
     */
    private function gatherIssues($issuesArray)
    {
        $allIssues = array();
        foreach ($issuesArray as $line => $lineIssues) {
            foreach ($lineIssues as $column => $issues) {
                foreach ($issues as $issue) {

                    if (!isset($allIssues[$line])) {
                        $allIssues[$line] = array();
                    }

                    $allIssues[$line][] = $issue;
                }
            }
        }

        return $allIssues;
    }
}

