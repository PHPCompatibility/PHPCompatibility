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
            [47],
            [51],
            [52],
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
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertWarning($file, $line, 'Calling ldap_connect() with a $port which is not `null` is deprecated since PHP 8.3.');
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
            [55],
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

        // No errors expected on the first 43 lines.
        for ($line = 1; $line <= 43; $line++) {
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
