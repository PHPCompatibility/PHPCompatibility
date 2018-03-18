<?php
/**
 * \PHPCompatibility\Sniffs\PHP\NewGeneratorReturnSniff.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;
use PHPCompatibility\PHPCSHelper;

/**
 * \PHPCompatibility\Sniffs\PHP\NewGeneratorReturnSniff.
 *
 * As of PHP 7.0, a return statement can be used within a generator for a final expression to be returned.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewGeneratorReturnSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $targets = array();

        /*
         * The `yield` keyword was introduced in PHP 5.5 with the token T_YIELD.
         * The `yield from` keyword was introduced in PHP 7.0 and tokenizes as
         * "T_YIELD T_WHITESPACE T_STRING".
         *
         * Pre-PHPCS 3.1.0, the T_YIELD token was not back-filled for PHP < 5.5.
         * Also, as of PHPCS 3.1.0, the PHPCS tokenizer adds a new T_YIELD_FROM
         * token.
         *
         * So for PHP 5.3-5.4 icw PHPCS < 3.1.0, we need to look for T_STRING with content "yield".
         * For PHP 5.5+ we need to look for T_YIELD.
         * For PHPCS 3.1.0+, we also need to look for T_YIELD_FROM.
         */
        if (version_compare(PHP_VERSION_ID, '50500', '<') === true
            && version_compare(PHPCSHelper::getVersion(), '3.1.0', '<') === true
        ) {
            $targets[] = T_STRING;
        }

        if (defined('T_YIELD')) {
            // phpcs:ignore PHPCompatibility.PHP.NewConstants.t_yieldFound
            $targets[] = T_YIELD;
        }

        if (defined('T_YIELD_FROM')) {
            $targets[] = T_YIELD_FROM;
        }

        return $targets;
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void|int Void or a stack pointer to skip forward.
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.6') !== true) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['code'] === T_STRING
            && $tokens[$stackPtr]['content'] !== 'yield'
        ) {
            return;
        }

        $function = $phpcsFile->getCondition($stackPtr, T_FUNCTION);
        if ($function === false) {
            // Try again, but now for a closure.
            $function = $phpcsFile->getCondition($stackPtr, T_CLOSURE);
        }

        if ($function === false) {
            // Yield outside function scope, fatal error, but not our concern.
            return;
        }

        if (isset($tokens[$function]['scope_opener'], $tokens[$function]['scope_closer']) === false) {
            // Can't reliably determine start/end of function scope.
            return;
        }

        $hasReturn = $phpcsFile->findNext(T_RETURN, ($tokens[$function]['scope_opener'] + 1), $tokens[$function]['scope_closer']);
        if ($hasReturn === false) {
            return;
        }

        $phpcsFile->addError(
            'Returning a final expression from a generator was not supported in PHP 5.6 or earlier',
            $hasReturn,
            'ReturnFound'
        );

        // Don't examine this function again.
        return $tokens[$function]['scope_closer'];
    }
}
