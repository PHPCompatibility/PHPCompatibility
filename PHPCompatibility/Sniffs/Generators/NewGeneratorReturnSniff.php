<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Generators;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Conditions;

/**
 * As of PHP 7.0, a `return` statement can be used within a generator for a final expression to be returned.
 *
 * PHP version 7.0
 *
 * @link https://www.php.net/manual/en/migration70.new-features.php#migration70.new-features.generator-return-expressions
 * @link https://wiki.php.net/rfc/generator-return-expressions
 * @link https://www.php.net/manual/en/language.generators.syntax.php
 *
 * @since 8.2.0
 */
class NewGeneratorReturnSniff extends Sniff
{

    /**
     * Scope conditions within which a yield can exist.
     *
     * @since 9.0.0
     *
     * @var array<int|string, int|string>
     */
    private $validConditions = [
        \T_FUNCTION => \T_FUNCTION,
        \T_CLOSURE  => \T_CLOSURE,
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.2.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            \T_YIELD,
            \T_YIELD_FROM,
            \T_FN, // Only to skip over.
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void|int Void or a stack pointer to skip forward.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('5.6') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        /**
         * Arrow functions cannot contain a return statement, but as they are not in the "conditions"
         * array, a `yield` in an arrow function could confuse the sniff, so better to skip over
         * them completely.
         */
        if ($tokens[$stackPtr]['code'] === \T_FN && isset($tokens[$stackPtr]['scope_closer'])) {
            return $tokens[$stackPtr]['scope_closer'];
        }

        $function = Conditions::getLastCondition($phpcsFile, $stackPtr, $this->validConditions);
        if ($function === false) {
            // Yield outside function scope, fatal error, but not our concern.
            return;
        }

        if (isset($tokens[$function]['scope_opener'], $tokens[$function]['scope_closer']) === false) {
            // Can't reliably determine start/end of function scope.
            return;
        }

        $targets            = Collections::closedScopes();
        $targets[\T_RETURN] = \T_RETURN;
        $current            = $tokens[$function]['scope_opener'];

        while (($current = $phpcsFile->findNext($targets, ($current + 1), $tokens[$function]['scope_closer'])) !== false) {
            if ($tokens[$current]['code'] === \T_RETURN) {
                $phpcsFile->addError(
                    'Returning a final expression from a generator was not supported in PHP 5.6 or earlier',
                    $current,
                    'ReturnFound'
                );

                return $tokens[$function]['scope_closer'];
            }

            // Found a nested scope in which return can exist without problems.
            if (isset($tokens[$current]['scope_closer'])) {
                // Skip past the nested scope.
                $current = $tokens[$current]['scope_closer'];
            }
        }

        // Don't examine this function again.
        return $tokens[$function]['scope_closer'];
    }
}
