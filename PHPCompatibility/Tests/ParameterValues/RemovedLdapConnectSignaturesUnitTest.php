<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedLdapConnectSignatures sniff.
 *
 * @group removedLdapConnectSignatures
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedLdapConnectSignaturesSniff
 *
 * @since 10.0.0
 */
final class RemovedLdapConnectSignaturesUnitTest extends BaseSniffTestCase
{

    /**
     * Verify that the two parameter signature of ldap_connect() is correctly detected.
     *
     * @dataProvider dataRemovedLdapConnectSignatureTwoParams
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedLdapConnectSignatureTwoParams($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertWarning($file, $line, 'Calling ldap_connect() with two parameters is deprecated since PHP 8.3.');
    }

    /**
     * Data provider.
     *
     * @see testRemovedLdapConnectSignatureTwoParams()
     *
     * @return array
     */
    public static function dataRemovedLdapConnectSignatureTwoParams()
    {
        return [
            [36],
            [40],
            [41],
        ];
    }


    /**
     * Verify that the three+ parameter signature of ldap_connect() is correctly detected when the second param is not set to null.
     *
     * @dataProvider dataRemovedLdapConnectSignatureMoreParamsSecondNotNull
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedLdapConnectSignatureMoreParamsSecondNotNull($line)
    {
        $message = 'Calling ldap_connect() with a $port which is not `null` is deprecated since PHP 8.3.';
        $file    = $this->sniffFile(__FILE__, '8.3');
        $this->assertWarning($file, $line, $message);

        /*
         * This notice should not show for PHP 8.4 as the PHP 8.4 deprecation takes precedence.
         * This needs special handling as the base test class doesn't handle this.
         */
        $file     = $this->sniffFile(__FILE__, '8.4');
        $warnings = $this->gatherWarnings($file);

        if (isset($warnings[$line]) === false) {
            $this->assertTrue(true);
        } else {
            $found = 0;
            foreach ($warnings[$line] as $issue) {
                if (\strpos($issue['message'], $message) !== false) {
                    ++$found;
                }
            }

            $this->assertSame(
                0,
                $found,
                \sprintf(
                    'Found unexpected warning "%s" (%d times) on line %d when testing against PHP 8.4',
                    $message,
                    $found,
                    $line
                )
            );
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedLdapConnectSignatureMoreParamsSecondNotNull()
     *
     * @return array
     */
    public static function dataRemovedLdapConnectSignatureMoreParamsSecondNotNull()
    {
        return [
            [44],
        ];
    }


    /**
     * Test that there are no false positives for valid code for the two param signature check.
     *
     * @dataProvider dataNoFalsePositivesTwoParamSignature
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesTwoParamSignature($line)
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesTwoParamSignature()
     *
     * @return array
     */
    public static function dataNoFalsePositivesTwoParamSignature()
    {
        $data = [];

        // No errors expected on the first 31 lines.
        for ($line = 1; $line <= 31; $line++) {
            $data[] = [$line];
        }

        // Also no errors expected on the lines dealing with the PHP 8.4 deprecation.
        for ($line = 49; $line <= 66; $line++) {
            $data[] = [$line];
        }

        return $data;
    }


    /**
     * Verify that the two parameter signature of ldap_connect() is correctly detected.
     *
     * @dataProvider dataRemovedLdapConnectSignatureThreePlusParams
     *
     * @param int $line Line number where the error should occur.
     *
     * @return void
     */
    public function testRemovedLdapConnectSignatureThreePlusParams($line)
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertWarning($file, $line, 'Calling ldap_connect() with three or more parameters is deprecated since PHP 8.4.');
    }

    /**
     * Data provider.
     *
     * @see testRemovedLdapConnectSignatureThreePlusParams()
     *
     * @return array
     */
    public static function dataRemovedLdapConnectSignatureThreePlusParams()
    {
        return [
            [44],
            [51],
            [52],
            [59],
            [60],
            [61],
            [64],
            [65],
            [66],
            [67],
        ];
    }


    /**
     * Test that there are no false positives for valid code for the two param signature check.
     *
     * @dataProvider dataNoFalsePositivesThreePlusParams
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesThreePlusParams($line)
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesThreePlusParams()
     *
     * @return array
     */
    public static function dataNoFalsePositivesThreePlusParams()
    {
        $data = [];

        // No errors expected on the first 31 lines.
        for ($line = 1; $line <= 31; $line++) {
            $data[] = [$line];
        }

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.2');
        $this->assertNoViolation($file);
    }
}
