<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Variables;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedIndirectModificationOfGlobals sniff.
 *
 * @group removedIndirectModificationOfGlobals
 * @group variables
 *
 * @covers \PHPCompatibility\Sniffs\Variables\RemovedIndirectModificationOfGlobalsSniff
 *
 * @since 10.0.0
 */
final class RemovedIndirectModificationOfGlobalsUnitTest extends BaseSniffTestCase
{

    /**
     * Test detecting use of `$GLOBALS[] = ...`.
     *
     * @dataProvider dataAppendToGlobals
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testAppendToGlobals($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertError($file, $line, 'Appending a new non-named array entry to the $GLOBALS variable is not allowed since PHP 8.1.');
    }

    /**
     * Data provider.
     *
     * @see testAppendToGlobals()
     *
     * @return array
     */
    public static function dataAppendToGlobals()
    {
        return [
            [82],
            [83],
        ];
    }


    /**
     * Test detecting access to `$GLOBALS` with an integer/float key.
     *
     * @dataProvider dataIntFloatKeyInGlobals
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testIntFloatKeyInGlobalsCrossVersion($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0-8.1');
        $this->assertWarning($file, $line, 'The way variables with an integer or floating point variable name are stored in the $GLOBALS variable has changed in PHP 8.1. Please review your code to evaluate the impact.');
    }

    /**
     * Verify that detecting access to `$GLOBALS` with an integer/float key does not get flagged
     * when only PHP 8.1+ needs to be supported.
     *
     * @dataProvider dataIntFloatKeyInGlobals
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testIntFloatKeyInGlobalsPHP81($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1-');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testIntFloatKeyInGlobals()
     *
     * @return array
     */
    public static function dataIntFloatKeyInGlobals()
    {
        return [
            [91],
            [94],
            [95],
            [100],
        ];
    }


    /**
     * Test detecting use of `$GLOBALS['GLOBALS']`.
     *
     * @dataProvider dataRemovedGlobalsSubkey
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testRemovedGlobalsSubkey($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertError($file, $line, 'The recursive $GLOBALS[\'GLOBALS\'] key no longer exists since PHP 8.1.');
    }

    /**
     * Data provider.
     *
     * @see testRemovedGlobalsSubkey()
     *
     * @return array
     */
    public static function dataRemovedGlobalsSubkey()
    {
        return [
            [108],
            [109],
        ];
    }


    /**
     * Test detecting use of `unset($GLOBALS)`.
     *
     * @dataProvider dataUnsettingGlobals
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testUnsettingGlobals($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertError($file, $line, 'Unsetting the $GLOBALS variable is not allowed since PHP 8.1.');
    }

    /**
     * Data provider.
     *
     * @see testUnsettingGlobals()
     *
     * @return array
     */
    public static function dataUnsettingGlobals()
    {
        return [
            [115],
            [116],
        ];
    }


    /**
     * Test detecting assignments to the $GLOBALS variable without a subkey.
     *
     * @dataProvider dataAssignmentToGlobals
     *
     * @param int          $line The line number.
     * @param string       $type The expected type of assignment found.
     * @param string|false $skip A skip reason or false when the test shouldn't be skipped.
     *
     * @return void
     */
    public function testAssignmentToGlobals($line, $type, $skip = false)
    {
        if ($skip !== false) {
            $this->markTestSkipped($skip);
        }

        $file  = $this->sniffFile(__FILE__, '8.1');
        $error = \sprintf(
            'Only individual keys in the $GLOBALS variable can be modified. The top-level $GLOBALS variable is read-only since PHP 8.1. Detected: %s',
            $type
        );
        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testAssignmentToGlobals()
     *
     * @return array
     */
    public static function dataAssignmentToGlobals()
    {
        $data = [
            [122, 'assignment to $GLOBALS'],
            [123, 'assignment to $GLOBALS'],
            [124, 'assignment to $GLOBALS'],
            [125, 'assignment to $GLOBALS'],
            [126, 'assignment to $GLOBALS'],
            [127, 'list assignment'],
            //[128, 'list assignment'], // False negative due to short list use.
            [129, 'foreach assignment'],
            [130, 'foreach assignment'],

            [133, 'list assignment'],

            [136, 'variable increment/decrement'],
            [137, 'variable increment/decrement'],
            [138, 'variable increment/decrement'],
            [139, 'variable increment/decrement'],

            [164, 'pass by reference'],
            [165, 'pass by reference'],
            [166, 'pass by reference'],
            [167, 'pass by reference'],
        ];

        /*
         * The sniff will only be able to identify named parameters correctly in all
         * PHP versions if the parameter name hasn't changed across PHP versions or if
         * an array of old and new names is passed to PassedParameters.
         *
         * Unfortunately, the parameter name of the "pass by reference" parameters for the
         * functions used in these tests, HAVE changed, so the tests will only be able to pass
         * on PHP 8.0+.
         * As the sniff uses Reflection to retrieve the parameter names, we cannot pass
         * an array of old and new names.
         */
        $skip = false;
        if (\PHP_VERSION_ID < 80000) {
            $skip = 'Parameter name in native PHP function has changed in PHP 8.0, test cannot succeed';
        }

        $data[] = [171, 'pass by reference', $skip];
        $data[] = [172, 'pass by reference', $skip];

        return $data;
    }


    /**
     * Test detecting use of `& $GLOBALS`.
     *
     * @dataProvider dataReferenceToGlobals
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testReferenceToGlobals($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertError($file, $line, 'Creating a reference to the $GLOBALS variable is no longer supported since PHP 8.1.');
    }

    /**
     * Data provider.
     *
     * @see testReferenceToGlobals()
     *
     * @return array
     */
    public static function dataReferenceToGlobals()
    {
        return [
            [145],
            [146],
        ];
    }


    /**
     * Test detecting use of `$foo = $GLOBALS;`.
     *
     * @dataProvider dataAssignmentOfGlobals
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testAssignmentOfGlobalsCrossVersion($line)
    {
        $file = $this->sniffFile(__FILE__, '8.0-8.1');
        $this->assertWarning($file, $line, 'Prior to PHP 8.1, assignment of $GLOBALS to another variable would effectively function like a reference. Since PHP 8.1, it creates a by-value copy.');
    }

    /**
     * Verify that `$foo = $GLOBALS;` does not get flagged when only PHP 8.1+ needs to be supported.
     *
     * @dataProvider dataAssignmentOfGlobals
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testAssignmentOfGlobalsPHP81($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1-');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testAssignmentOfGlobals()
     *
     * @return array
     */
    public static function dataAssignmentOfGlobals()
    {
        return [
            [154],
            [158],
        ];
    }


    /**
     * Test the sniff does not throw false positives.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        $cases = [];

        // No errors expected on the first 66 lines.
        for ($line = 1; $line <= 77; $line++) {
            $cases[] = [$line];
        }

        // No errors for the parse errors at the end of the file.
        for ($line = 173; $line <= 179; $line++) {
            $cases[] = [$line];
        }

        return $cases;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.0');
        $this->assertNoViolation($file);
    }
}
