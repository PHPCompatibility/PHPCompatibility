<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;

/**
 * Detect usage of non-cryptographic hashes.
 *
 * "The `hash_hmac()`, `hash_hmac_file()`, `hash_pbkdf2()`, and `hash_init()`
 * (with `HASH_HMAC`) functions no longer accept non-cryptographic hashes."
 *
 * PHP version 7.2
 *
 * @link https://www.php.net/manual/en/migration72.incompatible.php#migration72.incompatible.hash-functions
 *
 * @since 9.0.0
 */
class RemovedNonCryptoHashSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 9.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'hash_hmac'      => true,
        'hash_hmac_file' => true,
        'hash_init'      => true,
        'hash_pbkdf2'    => true,
    ];

    /**
     * List of the non-cryptographic hashes.
     *
     * @since 9.0.0
     *
     * @var array<string, true>
     */
    protected $disabledCryptos = [
        'adler32' => true,
        'crc32'   => true,
        'crc32b'  => true,
        'fnv132'  => true,
        'fnv1a32' => true,
        'fnv164'  => true,
        'fnv1a64' => true,
        'joaat'   => true,
    ];


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 9.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrAbove('7.2') === false);
    }


    /**
     * Process the parameters of a matched function.
     *
     * @since 9.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     * @param array                       $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $targetParam = PassedParameters::getParameterFromStack($parameters, 1, 'algo');
        if ($targetParam === false) {
            return;
        }

        if (isset($this->disabledCryptos[TextStrings::stripQuotes($targetParam['clean'])]) === false) {
            return;
        }

        // For hash_init(), these hashes are only disabled with HASH_HMAC set.
        $functionLC = \strtolower($functionName);
        if ($functionLC === 'hash_init') {
            $secondParam = PassedParameters::getParameterFromStack($parameters, 2, 'flags');
            if ($secondParam === false) {
                // $flags parameter not found.
                return;
            }

            $secondParamContent = ltrim($secondParam['clean'], ' \\'); // Trim off potential leading namespace separator for FQN.
            if ($secondParamContent !== 'HASH_HMAC'
                && $secondParamContent !== (string) \HASH_HMAC
            ) {
                // $flags parameter not set to HASH_HMAC.
                return;
            }
        }

        $phpcsFile->addError(
            'Non-cryptographic hashes are no longer accepted by function %s() since PHP 7.2. Found: %s',
            $targetParam['start'],
            MessageHelper::stringToErrorCode($functionLC),
            [
                $functionName,
                $targetParam['clean'],
            ]
        );
    }
}
