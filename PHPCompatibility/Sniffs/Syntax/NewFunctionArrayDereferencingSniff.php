<?php
/**
 * \PHPCompatibility\Sniffs\Syntax\NewFunctionArrayDereferencingSniff.
 *
 * PHP version 5.4
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */

namespace PHPCompatibility\Sniffs\Syntax;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * \PHPCompatibility\Sniffs\Syntax\NewFunctionArrayDereferencingSniff.
 *
 * PHP version 5.4
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */
class NewFunctionArrayDereferencingSniff extends Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(\T_STRING);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.3') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Next non-empty token should be the open parenthesis.
        $openParenthesis = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true, null, true);
        if ($openParenthesis === false || $tokens[$openParenthesis]['code'] !== \T_OPEN_PARENTHESIS) {
            return;
        }

        // Don't throw errors during live coding.
        if (isset($tokens[$openParenthesis]['parenthesis_closer']) === false) {
            return;
        }

        // Is this T_STRING really a function or method call ?
        $prevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($prevToken !== false && \in_array($tokens[$prevToken]['code'], array(\T_DOUBLE_COLON, \T_OBJECT_OPERATOR), true) === false) {
            $ignore = array(
                \T_FUNCTION  => true,
                \T_CONST     => true,
                \T_USE       => true,
                \T_NEW       => true,
                \T_CLASS     => true,
                \T_INTERFACE => true,
            );

            if (isset($ignore[$tokens[$prevToken]['code']]) === true) {
                // Not a call to a PHP function or method.
                return;
            }
        }

        $closeParenthesis = $tokens[$openParenthesis]['parenthesis_closer'];
        $nextNonEmpty     = $phpcsFile->findNext(Tokens::$emptyTokens, ($closeParenthesis + 1), null, true, null, true);
        if ($nextNonEmpty !== false && $tokens[$nextNonEmpty]['type'] === 'T_OPEN_SQUARE_BRACKET') {
            $phpcsFile->addError(
                'Function array dereferencing is not present in PHP version 5.3 or earlier',
                $nextNonEmpty,
                'Found'
            );
        }
    }
}
