<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewFunctionArrayDereferencingSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewFunctionArrayDereferencingSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_NewFunctionArrayDereferencingSniff extends PHPCompatibility_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING);
    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.3') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $ignore = array(
                T_DOUBLE_COLON,
                T_OBJECT_OPERATOR,
                T_FUNCTION,
                T_CONST,
        );

        $prevToken = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if (in_array($tokens[$prevToken]['code'], $ignore) === true) {
            // Not a call to a PHP function.
            return;
        }

        $open = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr, null, false, null, true);
        if ($open === false || isset($tokens[$open]['parenthesis_closer']) === false) {
            return;
        }

        $closeParenthesis  = $tokens[$open]['parenthesis_closer'];
        $squareOpenBracket = $phpcsFile->findNext(T_OPEN_SQUARE_BRACKET, ($closeParenthesis + 1), null, false, null, true);
        if ($squareOpenBracket !== false) {
            $phpcsFile->addError('Function array dereferencing is not present in PHP version 5.3 or earlier', $squareOpenBracket);
        }

    }//end process()
}//end class
