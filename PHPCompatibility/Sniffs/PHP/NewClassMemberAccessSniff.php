<?php
/**
 * \PHPCompatibility\Sniffs\PHP\NewClassMemberAccessSniff.
 *
 * PHP version 5.4
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;

/**
 * \PHPCompatibility\Sniffs\PHP\NewClassMemberAccessSniff.
 *
 * PHP 5.4: Class member access on instantiation has been added, e.g. (new Foo)->bar().
 * PHP 7.0: Class member access on cloning has been added, e.g. (clone $foo)->bar().
 *
 * PHP version 5.4
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewClassMemberAccessSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_NEW,
            T_CLONE,
        );
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
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['code'] === T_NEW && $this->supportsBelow('5.3') !== true) {
            return;
        } elseif ($tokens[$stackPtr]['code'] === T_CLONE && $this->supportsBelow('5.6') !== true) {
            return;
        }

        if (isset($tokens[$stackPtr]['nested_parenthesis']) === false) {
            // The `new className/clone $a` has to be in parentheses, without is not supported.
            return;
        }

        $parenthesisCloser = end($tokens[$stackPtr]['nested_parenthesis']);
        $parenthesisOpener = key($tokens[$stackPtr]['nested_parenthesis']);

        if (isset($tokens[$parenthesisOpener]['parenthesis_owner']) === true) {
            // If there is an owner, these parentheses are for a different purpose.
            return;
        }

        $prevBeforeParenthesis = $phpcsFile->findPrevious(
            \PHP_CodeSniffer_Tokens::$emptyTokens,
            ($parenthesisOpener - 1),
            null,
            true
        );
        if ($prevBeforeParenthesis !== false && $tokens[$prevBeforeParenthesis]['code'] === T_STRING) {
            // This is most likely a function call with the new/cloned object as a parameter.
            return;
        }

        $nextAfterParenthesis = $phpcsFile->findNext(
            \PHP_CodeSniffer_Tokens::$emptyTokens,
            ($parenthesisCloser + 1),
            null,
            true
        );
        if ($nextAfterParenthesis === false) {
            // Live coding.
            return;
        }

        if ($tokens[$nextAfterParenthesis]['code'] !== T_OBJECT_OPERATOR
            && $tokens[$nextAfterParenthesis]['code'] !== T_OPEN_SQUARE_BRACKET
        ) {
            return;
        }

        $data      = array('instantiation', '5.3');
        $errorCode = 'OnNewFound';

        if ($tokens[$stackPtr]['code'] === T_CLONE) {
            $data      = array('cloning', '5.6');
            $errorCode = 'OnCloneFound';
        }

        $phpcsFile->addError(
            'Class member access on object %s was not supported in PHP %s or earlier',
            $parenthesisCloser,
            $errorCode,
            $data
        );
    }
}
