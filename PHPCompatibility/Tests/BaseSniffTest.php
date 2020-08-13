<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests;

use PHPUnit\Framework\TestCase;
use PHP_CodeSniffer_File as File;
use PHPCSUtils\BackCompat\Helper;

/**
 * Base sniff test class file.
 *
 * Adds PHPCS sniffing logic and custom assertions for PHPCS errors and
 * warnings.
 *
 * @since 5.5
 * @since 7.0.4  Caches sniff results per file and testVersion.
 * @since 7.1.3  Compatible with PHPUnit 6.
 * @since 7.1.3  Limits the sniff run to the actual sniff being tested.
 * @since 8.0.0  Compatible with PHP_CodeSniffer 3+.
 * @since 8.2.0  Allows for sniffs in multiple categories.
 * @since 9.0.0  Dropped support for PHP_CodeSniffer 1.x.
 * @since 10.0.0 Updated for preliminary support of PHP_CodeSniffer 4.
 */
class BaseSniffTest extends TestCase
{

    /**
     * The name of the standard as registered with PHPCS.
     *
     * @since 7.1.3
     *
     * @var string
     */
    const STANDARD_NAME = 'PHPCompatibility';

    /**
     * The PHP_CodeSniffer object used for testing.
     *
     * Used by PHPCS 2.x.
     *
     * @since 5.5
     *
     * @var \PHP_CodeSniffer
     */
    protected static $phpcs = null;

    /**
     * An array of PHPCS results by filename and PHP version.
     *
     * @since 7.0.4
     *
     * @var array
     */
    public static $sniffFiles = array();

    /**
     * Last PHPCS config instance used, if any. (PHPCS > 3)
     *
     * @since 10.0.0
     *
     * @var \PHP_CodeSniffer\Config
     */
    public static $lastConfig = null;

    /**
     * Reset the sniff file cache before/after each test class.
     *
     * @beforeClass
     * @afterClass
     *
     * @since 7.0.4
     * @since 10.0.0 Renamed the method from `setUpBeforeClass()` to `resetSniffFiles()` and
     *               now using the `@beforeClass`/`@afterClass` annotations to allow for
     *               PHPUnit cross-version compatibility.
     *
     * @return void
     */
    public static function resetSniffFiles()
    {
        self::$sniffFiles = array();
    }

    /**
     * Sets up this unit test.
     *
     * @before
     *
     * @since 5.5
     * @since 10.0.0 Renamed the method from `setUp()` to `setUpPHPCS()` and now using
     *               the `@before` annotation to allow for PHPUnit cross-version compatibility.
     *
     * @return void
     */
    protected function setUpPHPCS()
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
    }

    /**
     * Reset the testVersion after each test.
     *
     * @after
     *
     * @since 5.5
     * @since 10.0.0 Renamed the method from `tearDwon()` to `resetTestVersion()` and now using
     *               the `@after` annotation to allow for PHPUnit cross-version compatibility.
     *
     * @return void
     */
    public function resetTestVersion()
    {
        // Reset the targetPhpVersion.
        Helper::setConfigData('testVersion', null, true, self::$lastConfig);
    }

    /**
     * Get the sniff code for the current sniff being tested.
     *
     * @since 7.1.3
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
     * @since 5.5
     * @since 9.0.0 Signature change. The `$filename` parameter was renamed to
     *              `$pathToFile` and now expects an absolute path instead of
     *              a relative one.
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

        try {
            if (class_exists('\PHP_CodeSniffer\Files\LocalFile')) {
                // PHPCS 3.x, 4.x.
                $config            = new \PHP_CodeSniffer\Config();
                $config->cache     = false;
                $config->standards = array(self::STANDARD_NAME);
                $config->sniffs    = array($this->getSniffCode());
                $config->ignored   = array();

                if ($targetPhpVersion !== 'none') {
                    Helper::setConfigData('testVersion', $targetPhpVersion, true, $config);
                }

                self::$lastConfig = $config;

                $ruleset = new \PHP_CodeSniffer\Ruleset($config);

                self::$sniffFiles[$pathToFile][$targetPhpVersion] = new \PHP_CodeSniffer\Files\LocalFile($pathToFile, $ruleset, $config);
                self::$sniffFiles[$pathToFile][$targetPhpVersion]->process();
            } else {
                // PHPCS 2.x.
                self::$lastConfig = null;

                if ($targetPhpVersion !== 'none') {
                    Helper::setConfigData('testVersion', $targetPhpVersion, true);
                }

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
     * @since 5.5
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
     * @since 5.5
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
     * @since 7.0.3
     *
     * @param array  $issues          Array of issues of a particular type.
     * @param string $type            The type of issues, either 'error' or 'warning'.
     * @param int    $lineNumber      Line number.
     * @param string $expectedMessage Expected message (assertContains).
     *
     * @return bool
     *
     * @throws \Exception When no issues of a certain type where found on a line
     *                    for which issues of that type where expected.
     */
    private function assertForType($issues, $type, $lineNumber, $expectedMessage)
    {
        if (isset($issues[$lineNumber]) === false) {
            $this->fail("Expected $type '$expectedMessage' on line number $lineNumber, but none found.");
        }

        $insteadFoundMessages = array();

        // Concat any error messages so we can do an assertContains.
        foreach ($issues[$lineNumber] as $issue) {
            $insteadFoundMessages[] = $issue['message'];
        }

        $insteadMessagesString = implode(', ', $insteadFoundMessages);

        $msg = "Expected $type message '$expectedMessage' on line $lineNumber not found. Instead found: $insteadMessagesString.";

        if (method_exists($this, 'assertStringContainsString') === false) {
            // PHPUnit < 7.
            return $this->assertContains($expectedMessage, $insteadMessagesString, $msg);
        }

        return $this->assertStringContainsString($expectedMessage, $insteadMessagesString, $msg);
    }

    /**
     * Assert no violation (warning or error) on a given line number.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer_File $file       Codesniffer File object.
     * @param mixed                 $lineNumber Line number.
     *
     * @return bool
     */
    public function assertNoViolation(File $file, $lineNumber = 0)
    {
        $errors   = $this->gatherErrors($file);
        $warnings = $this->gatherWarnings($file);

        if (empty($errors) && empty($warnings)) {
            return $this->assertTrue(true);
        }

        if ($lineNumber === 0) {
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
     * @since 5.5
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
     * @since 5.5
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
     * @since 5.5
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
     * @since 7.0.3
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
