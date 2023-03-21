<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Helpers;

use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;

/**
 * Helper function and property for sniffs examining the use of hash algorithms.
 *
 * Used by the new/removed hash algorithm sniffs.
 *
 * ---------------------------------------------------------------------------------------------
 * This trait is only intended for internal use by PHPCompatibility and is not part of the public API.
 * This also means that it has no promise of backward compatibility. Use at your own risk.
 * ---------------------------------------------------------------------------------------------
 *
 * @link https://www.php.net/manual/en/function.hash-algos.php#refsect1-function.hash-algos-changelog
 *
 * @since 5.5
 * @since 7.0.7  Logic moved from the `RemovedHashAlgorithms` sniff to the generic `Sniff` class.
 * @since 10.0.0 Logic moved from the generic `Sniff` class to a dedicated trait.
 */
trait HashAlgorithmsTrait
{

    /**
     * List of functions using hash algorithm as parameter (always the first parameter).
     *
     * Key is the function name, value is the 1-based parameter position in the function call.
     *
     * @since 5.5
     * @since 7.0.7  Moved from the `RemovedHashAlgorithms` sniff to the base `Sniff` class.
     * @since 10.0.0 Moved from the base `Sniff` class to the `HashAlgorithmsTrait`.
     *
     * @var array
     */
    protected $hashAlgoFunctions = [
        'hash_file' => [
            'position' => 1,
            'name'     => 'algo',
        ],
        'hash_hmac_file' => [
            'position' => 1,
            'name'     => 'algo',
        ],
        'hash_hmac' => [
            'position' => 1,
            'name'     => 'algo',
        ],
        'hash_init' => [
            'position' => 1,
            'name'     => 'algo',
        ],
        'hash_pbkdf2' => [
            'position' => 1,
            'name'     => 'algo',
        ],
        'hash' => [
            'position' => 1,
            'name'     => 'algo',
        ],
    ];

    /**
     * Get the hash algorithm name from the parameter in a hash function call.
     *
     * @since 7.0.7  Logic moved from the `RemovedHashAlgorithms` sniff to the generic `Sniff` class.
     * @since 10.0.0 Moved from the base `Sniff` class to the `HashAlgorithmsTrait`.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of T_STRING function token.
     *
     * @return string|false The algorithm name without quotes if this was a relevant hash
     *                      function call or false if it was not.
     */
    public function getHashAlgorithmParameter(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token and that it is the right token.
        if (isset($tokens[$stackPtr]) === false || $tokens[$stackPtr]['code'] !== \T_STRING) {
            return false;
        }

        $functionName   = $tokens[$stackPtr]['content'];
        $functionNameLc = \strtolower($functionName);

        // Bow out if not one of the functions we're targetting.
        if (isset($this->hashAlgoFunctions[$functionNameLc]) === false) {
            return false;
        }

        // Get the parameter from the function call which should contain the algorithm name.
        $paramInfo = $this->hashAlgoFunctions[$functionNameLc];
        $algoParam = PassedParameters::getParameter($phpcsFile, $stackPtr, $paramInfo['position'], $paramInfo['name']);
        if ($algoParam === false) {
            return false;
        }

        // Algorithm is a text string, so we need to remove the quotes.
        return TextStrings::stripQuotes(\strtolower($algoParam['raw']));
    }
}
