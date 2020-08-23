<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Extensions;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedExtensions sniff.
 *
 * @group removedExtensions
 * @group extensions
 *
 * @covers \PHPCompatibility\Sniffs\Extensions\RemovedExtensionsSniff
 *
 * @since 5.5
 */
class RemovedExtensionsUnitTest extends BaseSniffTest
{

    /**
     * testRemovedExtension
     *
     * @dataProvider dataRemovedExtension
     *
     * @param string $extensionName  Name of the PHP extension.
     * @param string $removedIn      The PHP version in which the extension was removed.
     * @param array  $lines          The line numbers in the test file which apply to this extension.
     * @param string $okVersion      A PHP version in which the extension was still present.
     * @param string $removedVersion Optional PHP version to test removal message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedExtension($extensionName, $removedIn, $lines, $okVersion, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Extension '{$extensionName}' is removed since PHP {$removedIn}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedExtension()
     *
     * @return array
     */
    public function dataRemovedExtension()
    {
        return [
            ['dbase', '5.3', [10], '5.2'],
            ['fam', '5.1', [16], '5.0'],
            ['fbsql', '5.3', [18], '5.2'],
            ['filepro', '5.2', [22], '5.1'],
            ['hw_api', '5.2', [24], '5.1'],
            ['ircg', '5.1', [28], '5.0'],
            ['mnogosearch', '5.1', [34], '5.0'],
            ['msql', '5.3', [36], '5.2'],
            ['mssql', '7.0', [63], '5.6'],
            ['ovrimos', '5.1', [44], '5.0'],
            ['pfpro_', '5.1', [46], '5.0'],
            ['sqlite', '5.4', [48], '5.3'],
            // array('sybase', '7.0', array(xx), '5.6'), sybase_ct ???
            ['yp', '5.1', [54], '5.0'],
        ];
    }

    /**
     * testRemovedExtensionWithAlternative
     *
     * @dataProvider dataRemovedExtensionWithAlternative
     *
     * @param string $extensionName  Name of the PHP extension.
     * @param string $removedIn      The PHP version in which the extension was removed.
     * @param string $alternative    An alternative extension.
     * @param array  $lines          The line numbers in the test file which apply to this extension.
     * @param string $okVersion      A PHP version in which the extension was still present.
     * @param string $removedVersion Optional PHP version to test removal message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedExtensionWithAlternative($extensionName, $removedIn, $alternative, $lines, $okVersion, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Extension '{$extensionName}' is removed since PHP {$removedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedExtensionWithAlternative()
     *
     * @return array
     */
    public function dataRemovedExtensionWithAlternative()
    {
        return [
            ['activescript', '5.1', 'pecl/activescript', [3, 4], '5.0'],
            ['cpdf', '5.1', 'pecl/pdflib', [6, 7, 8], '5.0'],
            ['dbx', '5.1', 'pecl/dbx', [12], '5.0'],
            ['dio', '5.1', 'pecl/dio', [14], '5.0'],
            ['fdf', '5.3', 'pecl/fdf', [20], '5.2'],
            ['ibase', '7.4', 'pecl/ibase', [78], '7.3'],
            ['ingres', '5.1', 'pecl/ingres', [26], '5.0'],
            ['mcve', '5.1', 'pecl/mcve', [30], '5.0'],
            ['ming', '5.3', 'pecl/ming', [32], '5.2'],
            ['ncurses', '5.3', 'pecl/ncurses', [40], '5.2'],
            ['oracle', '5.1', 'oci8 or pdo_oci', [42], '5.0'],
            ['recode', '7.4', 'iconv or mbstring', [80], '7.3'],
            ['sybase', '5.3', 'sybase_ct', [50], '5.2'],
            ['w32api', '5.1', 'pecl/ffi', [52], '5.0'],
            ['wddx', '7.4', 'pecl/wddx', [79], '7.3'],
        ];
    }


    /**
     * testDeprecatedRemovedExtensionWithAlternative
     *
     * @dataProvider dataDeprecatedRemovedExtensionWithAlternative
     *
     * @param string $extensionName     Name of the PHP extension.
     * @param string $deprecatedIn      The PHP version in which the extension was deprecated.
     * @param string $removedIn         The PHP version in which the extension was removed.
     * @param string $alternative       An alternative extension.
     * @param array  $lines             The line numbers in the test file which apply to this extension.
     * @param string $okVersion         A PHP version in which the extension was still present.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $removedVersion    Optional PHP version to test removal message with -
     *                                  if different from the $removedIn version.
     *
     * @return void
     */
    public function testDeprecatedRemovedExtensionWithAlternative($extensionName, $deprecatedIn, $removedIn, $alternative, $lines, $okVersion, $deprecatedVersion = null, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Extension '{$extensionName}' is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "Extension '{$extensionName}' is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedRemovedExtensionWithAlternative()
     *
     * @return array
     */
    public function dataDeprecatedRemovedExtensionWithAlternative()
    {
        return [
            ['ereg', '5.3', '7.0', 'pcre', [65, 76], '5.2'],
            ['mysql_', '5.5', '7.0', 'mysqli', [38], '5.4'],
            ['mcrypt', '7.1', '7.2', 'openssl (preferred) or pecl/mcrypt once available', [71], '7.0'],
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond latest deprecation.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return [
            [57], // Not a function call.
            [58], // Function declaration.
            [59], // Class instantiation.
            [60], // Method call.
            [68], // Whitelisted function.
            [74], // Whitelisted function array.
            [75], // Whitelisted function array.
            [82], // Live coding.
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.0'); // Low version below the first deprecation.
        $this->assertNoViolation($file);
    }
}
