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
            self::$phpcs->cli->setCommandLineValues(array('-p', '--colors'));
        }

        self::$phpcs->process(array(), __DIR__ . '/../');
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
     * Sniff a file and return resulting file object
     *
     * @param string $filename Filename to sniff
     * @param string $targetPhpVersion Value of 'testVersion' to set on PHPCS object
     * @return PHP_CodeSniffer_File File object
     */
    public function sniffFile($filename, $targetPhpVersion=null)
    {
        if (null !== $targetPhpVersion) {
            PHP_CodeSniffer::setConfigData('testVersion', $targetPhpVersion, true);
        }

        $filename = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $filename;
        try {
            $phpcsFile = self::$phpcs->processFile($filename);
        } catch (Exception $e) {
            $this->fail('An unexpected exception has been caught: ' . $e->getMessage());
            return false;
        }

        return $phpcsFile;
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

        if (!isset($errors[$lineNumber])) {
            throw new Exception("Expected error '$expectedMessage' on line number $lineNumber, but none found.");
        }

        $foundExpectedMessage = false;
        $insteadFoundMessages = array();

        // Concat any error messages so we can do an assertContains
        foreach ($errors[$lineNumber] as $error) {
            $insteadFoundMessages[] = $error['message'];
        }

        $insteadMessagesString = implode(', ', $insteadFoundMessages);
        return $this->assertContains(
            $expectedMessage, $insteadMessagesString,
            "Expected error message '$expectedMessage' on line $lineNumber not found. Instead found: $insteadMessagesString."
        );
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

        if (!isset($warnings[$lineNumber])) {
            throw new Exception("Expected warning '$expectedMessage' on line number $lineNumber, but none found.");
        }

        $foundExpectedMessage = false;
        $insteadFoundMessages = array();

        // Concat any error messages so we can do an assertContains
        foreach ($warnings[$lineNumber] as $warning) {
            $insteadFoundMessages[] = $warning['message'];
        }

        $insteadMessagesString = implode(', ', $insteadFoundMessages);
        return $this->assertContains(
            $expectedMessage, $insteadMessagesString,
            "Expected warning message '$expectedMessage' on line $lineNumber not found. Instead found: $insteadMessagesString."
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

        $allErrors = array();
        foreach ($foundErrors as $line => $lineErrors) {
            foreach ($lineErrors as $column => $errors) {
                foreach ($errors as $error) {

                    if (!isset($allErrors[$line])) {
                        $allErrors[$line] = array();
                    }

                    $allErrors[$line][] = $error;
                }
            }
        }

        return $allErrors;
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

        $allWarnings = array();
        foreach ($foundWarnings as $line => $lineWarnings) {
            foreach ($lineWarnings as $column => $warnings) {
                foreach ($warnings as $warning) {

                    if (!isset($allWarnings[$line])) {
                        $allWarnings[$line] = array();
                    }

                    $allWarnings[$line][] = $warning;
                }
            }
        }

        return $allWarnings;
    }
}

