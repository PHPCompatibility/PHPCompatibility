<?php
/**
 * \PHPCompatibility\Sniffs\Operators\ForbiddenNegativeBitshift.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */

namespace PHPCompatibility\Sniffs\Operators;

use PHPCompatibility\Sniff;
use PHPCompatibility\PHPCSHelper;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * \PHPCompatibility\Sniffs\Operators\ForbiddenNegativeBitshift.
 *
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */
class ForbiddenNegativeBitshiftSniff extends Sniff
{
    /**
     * Potential end tokens for which the end pointer has to be set back by one.
     *
     * {@internal The PHPCS `findEndOfStatement()` method is not completely consistent
     * in how it returns the statement end. This is just a simple way to bypass
     * the inconsistency for our purposes.}}
     *
     * @var array
     */
    private $inclusiveStopPoints = array(
        \T_COLON        => true,
        \T_COMMA        => true,
        \T_DOUBLE_ARROW => true,
        \T_SEMICOLON    => true,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            \T_SL,
            \T_SL_EQUAL,
            \T_SR,
            \T_SR_EQUAL,
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.0') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Determine the start and end of the part of the statement we need to examine.
        $start = ($stackPtr + 1);
        $next  = $phpcsFile->findNext(Tokens::$emptyTokens, $start, null, true);
        if ($next !== false && $tokens[$next]['code'] === \T_OPEN_PARENTHESIS) {
            $start = ($next + 1);
        }

        $end = PHPCSHelper::findEndOfStatement($phpcsFile, $start);
        if (isset($this->inclusiveStopPoints[$tokens[$end]['code']]) === true) {
            --$end;
        }

        if ($this->isNegativeNumber($phpcsFile, $start, $end, true) !== true) {
            // Not a negative number or undetermined.
            return;
        }

        $phpcsFile->addError(
            'Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0. Found: %s',
            $stackPtr,
            'Found',
            array($phpcsFile->getTokensAsString($start, ($end - $start + 1)))
        );
    }
}
