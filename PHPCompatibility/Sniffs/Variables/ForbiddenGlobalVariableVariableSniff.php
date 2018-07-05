<?php
/**
 * \PHPCompatibility\Sniffs\Variables\ForbiddenGlobalVariableVariableSniff.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */

namespace PHPCompatibility\Sniffs\Variables;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * \PHPCompatibility\Sniffs\Variables\ForbiddenGlobalVariableVariableSniff.
 *
 * Variable variables are forbidden with global
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */
class ForbiddenGlobalVariableVariableSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(\T_GLOBAL);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.0') === false) {
            return;
        }

        $tokens         = $phpcsFile->getTokens();
        $endOfStatement = $phpcsFile->findNext(array(\T_SEMICOLON, \T_CLOSE_TAG), ($stackPtr + 1));
        if ($endOfStatement === false) {
            // No semi-colon - live coding.
            return;
        }

        for ($ptr = ($stackPtr + 1); $ptr <= $endOfStatement; $ptr++) {
            $errorThrown = false;
            $nextComma   = $phpcsFile->findNext(\T_COMMA, $ptr, $endOfStatement, false, null, true);
            $varEnd      = ($nextComma === false) ? $endOfStatement : $nextComma;
            $variable    = $phpcsFile->findNext(\T_VARIABLE, $ptr, $varEnd);
            $varString   = trim($phpcsFile->getTokensAsString($ptr, ($varEnd - $ptr)));
            $data        = array($varString);

            if ($variable !== false) {

                $prev = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($variable - 1), $ptr, true);

                if ($prev !== false && $tokens[$prev]['type'] === 'T_DOLLAR') {

                    $next = $phpcsFile->findNext(Tokens::$emptyTokens, ($variable + 1), $varEnd, true);

                    if ($next !== false
                        && \in_array($tokens[$next]['code'], array(\T_OPEN_SQUARE_BRACKET, \T_OBJECT_OPERATOR, \T_DOUBLE_COLON), true) === true
                    ) {
                        $phpcsFile->addError(
                            'Global with variable variables is not allowed since PHP 7.0. Found %s',
                            $variable,
                            'Found',
                            $data
                        );
                        $errorThrown = true;
                    } else {
                        $phpcsFile->addWarning(
                            'Global with anything other than bare variables is discouraged since PHP 7.0. Found %s',
                            $variable,
                            'NonBareVariableFound',
                            $data
                        );
                        $errorThrown = true;
                    }
                }
            }

            if ($errorThrown === false) {
                $dollar = $phpcsFile->findNext(\T_DOLLAR, $ptr, $varEnd);
                if ($dollar !== false) {
                    $next = $phpcsFile->findNext(Tokens::$emptyTokens, ($dollar + 1), $varEnd, true);
                    if ($tokens[$next]['code'] === \T_OPEN_CURLY_BRACKET) {
                        $phpcsFile->addWarning(
                            'Global with anything other than bare variables is discouraged since PHP 7.0. Found %s',
                            $dollar,
                            'NonBareVariableFound',
                            $data
                        );
                    }
                }
            }

            // Move the stack pointer forward to the next variable for multi-variable statements.
            if ($nextComma === false) {
                break;
            }
            $ptr = $nextComma;
        }
    }
}
