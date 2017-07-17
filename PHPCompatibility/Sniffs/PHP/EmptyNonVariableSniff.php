<?php
/**
 * \PHPCompatibility\Sniffs\PHP\EmptyNonVariableSniff.
 *
 * PHP version 5.5
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;

/**
 * \PHPCompatibility\Sniffs\PHP\EmptyNonVariableSniff.
 *
 * Verify that nothing but variables are passed to empty().
 *
 * PHP version 5.5
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class EmptyNonVariableSniff extends Sniff
{
    /**
     * List of tokens to check against.
     *
     * @var array
     */
    protected $tokenBlackList = array();

    /**
     * List of brackets which can be part of a variable variable.
     *
     * Key is the open bracket token, value the close bracket token.
     *
     * @var array
     */
    protected $bracketTokens = array(
        T_OPEN_CURLY_BRACKET   => T_CLOSE_CURLY_BRACKET,
        T_OPEN_SQUARE_BRACKET  => T_CLOSE_SQUARE_BRACKET,
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Set the token blacklist only once.
        $tokenBlackList = array_unique(array_merge(
            \PHP_CodeSniffer_Tokens::$assignmentTokens,
            \PHP_CodeSniffer_Tokens::$equalityTokens,
            \PHP_CodeSniffer_Tokens::$comparisonTokens,
            \PHP_CodeSniffer_Tokens::$operators,
            \PHP_CodeSniffer_Tokens::$booleanOperators,
            \PHP_CodeSniffer_Tokens::$castTokens,
            array(T_OPEN_PARENTHESIS, T_STRING_CONCAT)
        ));
        $this->tokenBlackList = array_combine($tokenBlackList, $tokenBlackList);

        return array(T_EMPTY);
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
        if ($this->supportsBelow('5.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $open = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr, null, false, null, true);
        if ($open === false || isset($tokens[$open]['parenthesis_closer']) === false) {
            return;
        }

        $close = $tokens[$open]['parenthesis_closer'];

        // If no variable at all was found, then it's definitely a no-no.
        $hasVariable = $phpcsFile->findNext(T_VARIABLE, $open + 1, $close);
        if ($hasVariable === false) {
            $this->addError($phpcsFile, $stackPtr);
            return;
        }

        // Check if the variable found is at the right level. Deeper levels are always an error.
        if (isset($tokens[$open + 1]['nested_parenthesis'], $tokens[$hasVariable]['nested_parenthesis'])) {
            $nestingLevel = count($tokens[$open + 1]['nested_parenthesis']);
            if (count($tokens[$hasVariable]['nested_parenthesis']) !== $nestingLevel) {
                $this->addError($phpcsFile, $stackPtr);
                return;
            }
        }

        // Ok, so the first variable is at the right level, now are there any
        // blacklisted tokens within the empty() ?
        $hasBadToken = $phpcsFile->findNext($this->tokenBlackList, $open + 1, $close);
        if ($hasBadToken === false) {
            return;
        }

        // If there are also bracket tokens, the blacklisted token might be part of a variable
        // variable, but if there are no bracket tokens, we know we have an error.
        $hasBrackets = $phpcsFile->findNext($this->bracketTokens, $open + 1, $close);
        if ($hasBrackets === false) {
            $this->addError($phpcsFile, $stackPtr);
            return;
        }

        // Ok, we have both a blacklisted token as well as brackets, so we need to walk
        // the tokens of the variable variable.
        for ($i = ($open + 1); $i < $close; $i++) {
            // If this is a bracket token, skip to the end of the bracketed expression.
            if (isset($this->bracketTokens[$tokens[$i]['code']], $tokens[$i]['bracket_closer'])) {
                $i = $tokens[$i]['bracket_closer'];
                continue;
            }

            // If it's a blacklisted token, not within brackets, we have an error.
            if (isset($this->tokenBlackList[$tokens[$i]['code']])) {
                $this->addError($phpcsFile, $stackPtr);
                return;
            }
        }
    }


    /**
     * Add the error message.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    protected function addError(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addError(
            'Only variables can be passed to empty() prior to PHP 5.5.',
            $stackPtr,
            'Found'
        );
    }
}
