<?php
/**
 * PHPCompatibility_Sniffs_PHP_EmptyNonVariableSniff.
 *
 * PHP version 5.5
 *
 * @category  PHP
 * @package   PHPCompatibility
 */

/**
 * PHPCompatibility_Sniffs_PHP_EmptyNonVariableSniff.
 *
 * Verify that nothing but variables are passed to empty().
 *
 * PHP version 5.5
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_EmptyNonVariableSniff extends PHPCompatibility_Sniff
{
    /**
     * List of tokens to check against.
     *
     * @var array
     */
    protected $tokenBlackList = array();

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Set the token blacklist only once.
        $this->tokenBlackList = array_unique(array_merge(
            PHP_CodeSniffer_Tokens::$assignmentTokens,
            PHP_CodeSniffer_Tokens::$equalityTokens,
            PHP_CodeSniffer_Tokens::$comparisonTokens,
            PHP_CodeSniffer_Tokens::$operators,
            PHP_CodeSniffer_Tokens::$booleanOperators,
            PHP_CodeSniffer_Tokens::$castTokens,
            array(T_OPEN_PARENTHESIS, T_STRING_CONCAT)
        ));

        return array(T_EMPTY);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
        if ($hasBadToken !== false) {
            $this->addError($phpcsFile, $stackPtr);
            return;
        }
    }


    /**
     * Add the error message.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr)
    {
        $error = 'Only variables can be passed to empty() prior to PHP 5.5.';
        $phpcsFile->addError($error, $stackPtr, 'Found');
    }
}
