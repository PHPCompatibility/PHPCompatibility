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

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedNonCryptoHash sniff.
 *
 * @group removedNonCryptoHash
 * @group parameterValues
 * @group hashAlgorithms
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedNonCryptoHashSniff
 *
 * @since 9.0.0
 */
class RemovedNonCryptoHashUnitTest extends BaseSniffTest
{

    /**
     * testNonCryptoHash
     *
     * @dataProvider dataNonCryptoHash
     *
     * @param int    $line         Line number where the error should occur.
     * @param string $functionName The name of the function which was called.
     *
     * @return void
     */
    public function testNonCryptoHash($line, $functionName)
    {
        $file = $this->sniffFile(__FILE__, '7.2');
        $this->assertError($file, $line, "Non-cryptographic hashes are no longer accepted by function {$functionName}() since PHP 7.2.");
    }

    /**
     * dataNonCryptoHash
     *
     * @see testNonCryptoHash()
     *
     * @return array
     */
    public function dataNonCryptoHash()
    {
        return [
            [12, 'hash_hmac'],
            [13, 'hash_hmac_file'],
            [14, 'hash_pbkdf2'],
            [15, 'hash_init'],
            [16, 'hash_hmac'],
            [17, 'hash_hmac_file'],
            [18, 'hash_pbkdf2'],
            [19, 'hash_init'],
            [20, 'hash_pbkdf2'],
            [23, 'hash_init'],
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '7.2');

        // No errors expected on the first 10 lines.
        for ($line = 1; $line <= 10; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.1');
        $this->assertNoViolation($file);
    }
}
