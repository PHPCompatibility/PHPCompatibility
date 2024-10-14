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
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Util\Common;
use PHPCSUtils\BackCompat\Helper;
use Yoast\PHPUnitPolyfills\Polyfills\AssertStringContains;

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
 * @since 10.0.0 Updated for preliminary support of PHP_CodeSniffer 4 and dropped support for PHPCS 2.x.
 */
abstract class BaseSniffTestCase extends TestCase
{
    use AssertStringContains;

    /**
     * The name of the standard as registered with PHPCS.
     *
     * @since 7.1.3
     *
     * @var string
     */
    const STANDARD_NAME = 'PHPCompatibility';

    /**
     * An array of PHPCS results by filename and PHP version.
     *
     * @since 7.0.4
     *
     * @var array<string, array<string, \PHP_CodeSniffer\Files\File>>
     */
    public static $sniffFiles = [];

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
        self::$sniffFiles = [];
    }

    /**
     * Reset the testVersion after each test.
     *
     * @after
     *
     * @since 5.5
     * @since 10.0.0 Renamed the method from `tearDown()` to `resetTestVersion()` and now using
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
        return Common::getSniffCode(\get_class($this));
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
     * @return \PHP_CodeSniffer\Files\File File object.
     */
    public function sniffFile($pathToFile, $targetPhpVersion = 'none')
    {
        if (\strpos($pathToFile, 'UnitTest.php') !== false) {
            // Ok, so __FILE__ was passed, change the file extension.
            $pathToFile = \str_replace('UnitTest.php', 'UnitTest.inc', $pathToFile);
        }
        $pathToFile = \realpath($pathToFile);

        if (isset(self::$sniffFiles[$pathToFile][$targetPhpVersion])) {
            return self::$sniffFiles[$pathToFile][$targetPhpVersion];
        }

        // Set up the Config and tokenize the test case file only once.
        if (isset(self::$sniffFiles[$pathToFile]['only_parsed']) === false) {
            try {
                // PHPCS 3.x, 4.x.
                $config            = new Config();
                $config->cache     = false;
                $config->standards = [self::STANDARD_NAME];
                $config->sniffs    = [$this->getSniffCode()];
                $config->ignored   = [];

                self::$lastConfig = $config;

                $ruleset = new Ruleset($config);

                self::$sniffFiles[$pathToFile]['only_parsed'] = new LocalFile($pathToFile, $ruleset, $config);
                self::$sniffFiles[$pathToFile]['only_parsed']->parse();
            } catch (\Exception $e) {
                $this->fail('An unexpected exception has been caught when parsing file "' . $pathToFile . '" : ' . $e->getMessage());
            }
        }

        // Now process the file against the target testVersion setting and cache the results.
        self::$sniffFiles[$pathToFile][$targetPhpVersion] = clone self::$sniffFiles[$pathToFile]['only_parsed'];

        if ($targetPhpVersion !== 'none') {
            Helper::setConfigData('testVersion', $targetPhpVersion, true, self::$sniffFiles[$pathToFile][$targetPhpVersion]->config);
        }

        try {
            self::$sniffFiles[$pathToFile][$targetPhpVersion]->process();
        } catch (\Exception $e) {
            $this->fail('An unexpected exception has been caught when processing file "' . $pathToFile . '" : ' . $e->getMessage());
        }

        return self::$sniffFiles[$pathToFile][$targetPhpVersion];
    }

    /**
     * Assert a PHPCS error on a particular line number.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $file            Codesniffer file object.
     * @param int                         $lineNumber      Line number.
     * @param string                      $expectedMessage Expected error message (assertContains).
     *
     * @return void
     */
    public function assertError(File $file, $lineNumber, $expectedMessage)
    {
        $errors = $this->gatherErrors($file);

        $this->assertForType($errors, 'error', $lineNumber, $expectedMessage);
    }

    /**
     * Assert a PHPCS warning on a particular line number.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $file            Codesniffer file object.
     * @param int                         $lineNumber      Line number.
     * @param string                      $expectedMessage Expected message (assertContains).
     *
     * @return void
     */
    public function assertWarning(File $file, $lineNumber, $expectedMessage)
    {
        $warnings = $this->gatherWarnings($file);

        $this->assertForType($warnings, 'warning', $lineNumber, $expectedMessage);
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
     * @return void
     *
     * @throws \Exception When no issues of a certain type where found on a line
     *                    for which issues of that type where expected.
     */
    private function assertForType($issues, $type, $lineNumber, $expectedMessage)
    {
        if (isset($issues[$lineNumber]) === false) {
            $this->fail("Expected $type '$expectedMessage' on line number $lineNumber, but none found.");
        }

        $insteadFoundMessages = [];

        // Concat any error messages so we can do an assertContains.
        foreach ($issues[$lineNumber] as $issue) {
            $insteadFoundMessages[] = $issue['message'];
        }

        $insteadMessagesString = \implode(', ', $insteadFoundMessages);

        $msg = "Expected $type message '$expectedMessage' on line $lineNumber not found. Instead found: $insteadMessagesString.";

        $this->assertStringContainsString($expectedMessage, $insteadMessagesString, $msg);
    }

    /**
     * Assert no violation (warning or error) on a given line number.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $file       Codesniffer File object.
     * @param mixed                       $lineNumber Line number.
     *
     * @return void
     */
    public function assertNoViolation(File $file, $lineNumber = 0)
    {
        $errors   = $this->gatherErrors($file);
        $warnings = $this->gatherWarnings($file);

        if (empty($errors) && empty($warnings)) {
            $this->assertTrue(true);
            return;
        }

        if ($lineNumber === 0) {
            $failMessage = 'Failed asserting no violations in file. Found ' . \count($errors) . ' errors and ' . \count($warnings) . ' warnings.';
            $allMessages = $errors + $warnings;
            // TODO: Update the fail message to give the tester some
            // indication of what the errors or warnings were.
            $this->assertEmpty($allMessages, $failMessage);
            return;
        }

        $encounteredMessages = [];
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
            . \implode("\n", $encounteredMessages);
        $this->assertCount(0, $encounteredMessages, $failMessage);
    }

    /**
     * Show violations in file by line number.
     *
     * This is useful for debugging sniffs on a file.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $file Codesniffer file object.
     *
     * @return array<string, array>
     */
    public function showViolations(File $file)
    {
        $violations = [
            'errors'   => $this->gatherErrors($file),
            'warnings' => $this->gatherWarnings($file),
        ];

        return $violations;
    }

    /**
     * Gather all error messages by line number from phpcs file result.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $file Codesniffer File object.
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
     * @param \PHP_CodeSniffer\Files\File $file Codesniffer File object.
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
        $allIssues = [];
        foreach ($issuesArray as $line => $lineIssues) {
            foreach ($lineIssues as $column => $issues) {
                foreach ($issues as $issue) {

                    if (isset($allIssues[$line]) === false) {
                        $allIssues[$line] = [];
                    }

                    $allIssues[$line][] = $issue;
                }
            }
        }

        return $allIssues;
    }

    /**
     * Run assertions that the fixer output matches the ".fixed" file for a particular sniff.
     *
     * @since 10.0.0
     *
     * @param string $pathToTestFile   Absolute path to the file to sniff.
     *                                 Allows for passing __FILE__ from the unit test
     *                                 file. In that case, the test case file is presumed
     *                                 to have the same name, but with an `inc` extension.
     * @param string $targetPhpVersion Value of 'testVersion' to set on PHPCS object.
     *
     * @return void
     */
    protected function assertFixerOutputMatches($pathToTestFile, $targetPhpVersion = 'none')
    {
        $pathToFixedFile = $pathToTestFile . '.fixed';

        $this->assertTrue(file_exists($pathToTestFile), 'Test file exists');
        $this->assertTrue(file_exists($pathToFixedFile), 'Fixed file exists');

        $phpcsFile = $this->sniffFile($pathToTestFile, $targetPhpVersion);

        $this->assertGreaterThan(0, $phpcsFile->getFixableCount(), 'There are fixable errors or warnings');

        // Attempt to fix the errors.
        $phpcsFile->fixer->fixFile();
        $fixable = $phpcsFile->getFixableCount();

        $this->assertSame(0, $fixable, 'Sniff can actually fix all "fixable" errors');

        if ($phpcsFile->fixer->getContents() !== file_get_contents($pathToFixedFile)) {
            // Only generate the (expensive) diff if a difference is expected.
            $diff = $phpcsFile->fixer->generateDiff($pathToFixedFile);
            if (trim($diff) !== '') {
                $fixedFilename = basename($pathToFixedFile);
                $testFilename  = basename($pathToTestFile);
                $this->fail("Fixed version of $testFilename does not match expected version in $fixedFilename; the diff is\n$diff");
            }
        }
    }
}
