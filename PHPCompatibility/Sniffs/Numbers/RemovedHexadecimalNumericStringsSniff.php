<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Numbers;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\Numbers;
use PHPCSUtils\Utils\Parentheses;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;

/**
 * PHP 7.0 removed support for recognizing hexadecimal numeric strings as numeric.
 *
 * Type juggling and recognition was inconsistent prior to PHP 7.
 * As of PHP 7, hexadecimal numeric strings are no longer treated as numeric.
 *
 * PHP version 7.0
 *
 * @link https://wiki.php.net/rfc/remove_hex_support_in_numeric_strings
 *
 * @since 7.0.3
 * @since 10.0.0 The check in this sniff was previously contained in the ValidIntegers
 *               sniff and has now been split off to a separate sniff.
 */
class RemovedHexadecimalNumericStringsSniff extends Sniff
{

    /**
     * Names of global/PHP native functions which still accept hexadecimal numeric strings
     * as numeric input.
     *
     * @link https://github.com/PHPCompatibility/PHPCompatibility/issues/1345
     *
     * @since 10.0.0
     *
     * @var array<string, array<int, string>|true> True if all arguments in the function call accept hex numeric
     *                                             strings. An array with 1-based parameter position (key) and
     *                                             names (value) for those functions which only accept
     *                                             hex numeric strings for select parameters.
     */
    private $excludedFunctions = [
        'gmp_abs'            => true,
        'gmp_add'            => true,
        'gmp_and'            => true,
        'gmp_binomial'       => [1 => 'n'],
        'gmp_cmp'            => true,
        'gmp_com'            => true,
        'gmp_div_q'          => [1 => 'num1', 2 => 'num2'],
        'gmp_div_qr'         => [1 => 'num1', 2 => 'num2'],
        'gmp_div_r'          => [1 => 'num1', 2 => 'num2'],
        'gmp_div'            => [1 => 'num1', 2 => 'num2'],
        'gmp_divexact'       => true,
        'gmp_export'         => [1 => 'num'],
        'gmp_fact'           => true,
        'gmp_gcd'            => true,
        'gmp_gcdext'         => true,
        'gmp_hamdist'        => true,
        'gmp_init'           => [1 => 'num'],
        'gmp_intval'         => true,
        'gmp_invert'         => true,
        'gmp_jacobi'         => true,
        'gmp_kronecker'      => true,
        'gmp_lcm'            => true,
        'gmp_legendre'       => true,
        'gmp_mod'            => true,
        'gmp_mul'            => true,
        'gmp_neg'            => true,
        'gmp_nextprime'      => true,
        'gmp_or'             => true,
        'gmp_perfect_power'  => true,
        'gmp_perfect_square' => true,
        'gmp_popcount'       => true,
        'gmp_pow'            => [1 => 'num'],
        'gmp_powm'           => true,
        'gmp_prob_prime'     => [1 => 'num'],
        'gmp_random_range'   => true,
        'gmp_random_seed'    => true,
        'gmp_root'           => [1 => 'num'],
        'gmp_rootrem'        => [1 => 'num'],
        'gmp_scan0'          => [1 => 'num'],
        'gmp_scan1'          => [1 => 'num'],
        'gmp_sign'           => true,
        'gmp_sqrt'           => true,
        'gmp_sqrtrem'        => true,
        'gmp_strval'         => [1 => 'num'],
        'gmp_sub'            => true,
        'gmp_testbit'        => [1 => 'num'],
        'gmp_xor'            => true,
        'hexdec'             => true,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.3
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            \T_CONSTANT_ENCAPSED_STRING,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (Numbers::isHexidecimalInt(TextStrings::stripQuotes($tokens[$stackPtr]['content'])) === false) {
            return;
        }

        /*
         * Prevent false positives if the text string is used within a function call to a function
         * which still accepts hexadecimal numeric strings.
         */
        $nested = Parentheses::getLastOpener($phpcsFile, $stackPtr);
        if ($nested !== false) {
            $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($nested - 1), null, true);
            $contentLc    = \strtolower($tokens[$prevNonEmpty]['content']);
            if ($tokens[$prevNonEmpty]['code'] === \T_STRING
                && isset($this->excludedFunctions[$contentLc]) === true
                && $this->isCallToGlobalFunction($phpcsFile, $prevNonEmpty) === true
            ) {
                /*
                 * Okay, so the string is apparently used in a function call to a function which still supports it.
                 * Now verify it is used in a valid parameter position.
                 */
                if ($this->excludedFunctions[$contentLc] === true) {
                    // All parameters support hex numeric strings. Bow out.
                    return;
                }

                $parameters = PassedParameters::getParameters($phpcsFile, $prevNonEmpty);
                foreach ($this->excludedFunctions[$contentLc] as $paramOffset => $paramName) {
                    $param = PassedParameters::getParameterFromStack($parameters, $paramOffset, $paramName);
                    if ($stackPtr >= $param['start'] && $stackPtr <= $param['end']) {
                        // Parameter used in a position which supports hex numeric strings. Bow out.
                        return;
                    }
                }
            }
        }

        $isError = ScannedCode::shouldRunOnOrAbove('7.0');

        MessageHelper::addMessage(
            $phpcsFile,
            'The behaviour of hexadecimal numeric strings was inconsistent prior to PHP 7 and support has been removed in PHP 7. Found: %s',
            $stackPtr,
            $isError,
            'Found',
            [$tokens[$stackPtr]['content']]
        );
    }

    /**
     * Check if a `T_STRING` token represents a function call to a global function.
     *
     * Note: not 100% precise, but should be sufficient for now. At a later point in
     * time there will probably be a PHPCSUtils function for this.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the potential function call
     *                                               token in the stack.
     *
     * @return bool
     */
    private function isCallToGlobalFunction(File $phpcsFile, $stackPtr)
    {
        $tokens       = $phpcsFile->getTokens();
        $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);

        if (isset(Collections::objectOperators()[$tokens[$prevNonEmpty]['code']]) === true) {
            // Method call.
            return false;
        }

        if ($tokens[$prevNonEmpty]['code'] === \T_NEW
            || $tokens[$prevNonEmpty]['code'] === \T_FUNCTION
        ) {
            return false;
        }

        if ($tokens[$prevNonEmpty]['code'] === \T_NS_SEPARATOR) {
            $prevPrevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prevNonEmpty - 1), null, true);
            if ($tokens[$prevPrevToken]['code'] === \T_STRING
                || $tokens[$prevPrevToken]['code'] === \T_NAMESPACE
            ) {
                // Namespaced function.
                return false;
            }
        }

        return true;
    }
}
