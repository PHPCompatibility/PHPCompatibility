<?php
/**
 * \PHPCompatibility\Sniffs\PHP\TrailingCommaSniff.
 *
 * PHP version 7.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;

/**
 * \PHPCompatibility\Sniffs\PHP\NewTrailingCommaSniff.
 *
 * PHP version 7.3
 *
 * Note: trailing comma's in group use statements as introduced in PHP 7.2 is covered
 * by the `NewGroupUseDeclaration` sniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewTrailingCommaSniff extends Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_STRING,
            T_VARIABLE,
            T_ISSET,
            T_UNSET,
        );
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
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('7.2') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $nextNonEmpty = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($tokens[$nextNonEmpty]['code'] !== T_OPEN_PARENTHESIS
            || isset($tokens[$nextNonEmpty]['parenthesis_closer']) === false
        ) {
            return;
        }

        if ($tokens[$stackPtr]['code'] === T_STRING) {
            $ignore = array(
                T_FUNCTION        => true,
                T_CONST           => true,
                T_USE             => true,
            );

            $prevNonEmpty = $phpcsFile->findPrevious(\PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
            if (isset($ignore[$tokens[$prevNonEmpty]['code']]) === true) {
                // Not a function call.
                return;
            }
        }

        $closer            = $tokens[$nextNonEmpty]['parenthesis_closer'];
        $lastInParenthesis = $phpcsFile->findPrevious(
            \PHP_CodeSniffer_Tokens::$emptyTokens,
            ($closer - 1),
            $nextNonEmpty,
            true
        );

        if ($tokens[$lastInParenthesis]['code'] !== T_COMMA) {
            return;
        }

        $data = array();
        switch ($tokens[$stackPtr]['code']) {
            case T_ISSET:
                $data[]    = 'calls to isset()';
                $errorCode = 'FoundInIsset';
                break;

            case T_UNSET:
                $data[]    = 'calls to unset()';
                $errorCode = 'FoundInUnset';
                break;

            default:
                $data[]    = 'function calls';
                $errorCode = 'FoundInFunctionCall';
                break;
        }

        $phpcsFile->addError(
            'Trailing comma\'s are not allowed in %s in PHP 7.2 or earlier',
            $lastInParenthesis,
            $errorCode,
            $data
        );

    }//end process()

}//end class
