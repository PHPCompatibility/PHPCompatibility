<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenParenthesisAroundFunctionParametersSniff.
 *
 * PHP version 7.0 
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenParenthesisAroundFunctionParametersSniff.
 *
 * Parentheses around function parameters throws warning in PHP 7.0
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenParenthesisAroundFunctionParametersSniff extends PHPCompatibility_Sniff
{

    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    protected $error = true;

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
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.0')) {
            $tokens = $phpcsFile->getTokens();

            // Skip tokens that are the names of functions or classes
            // within their definitions. For example: function myFunction...
            // "myFunction" is T_STRING but we should skip because it is not a
            // function or method *call*.
            $functionName = $stackPtr;
            $findTokens   = array_merge(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                array(T_BITWISE_AND)
                );
            
            $functionKeyword = $phpcsFile->findPrevious(
                $findTokens,
                ($stackPtr - 1),
                null,
                true
                );
            
            if ($tokens[$functionKeyword]['code'] === T_FUNCTION
                || $tokens[$functionKeyword]['code'] === T_CLASS
                ) {
                    return;
            }
            
            // If the next non-whitespace token after the function or method call
            // is not an opening parenthesis then it cant really be a *call*.
            $openBracket = $phpcsFile->findNext(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($functionName + 1),
                null,
                true
                );

            if ($tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS) {
                return;
            }
        
            $closeBracket = $tokens[$openBracket]['parenthesis_closer'];

            $nextSeparator = $openBracket;
            while (($nextComma = $phpcsFile->findNext(T_COMMA, ($nextSeparator + 1), $closeBracket)) !== false) {
                // Find every argument along the way, check if there are parenthesis around them

                if ($tokens[$nextSeparator + 1]['type'] == 'T_OPEN_PARENTHESIS' && $tokens[$nextComma - 1]['type'] == 'T_CLOSE_PARENTHESIS' && $tokens[$nextSeparator + 3]['type'] != 'T_BITWISE_AND' && $tokens[$nextSeparator + 4]['type'] != 'T_BITWISE_AND' && $tokens[$nextSeparator + 5]['type'] != 'T_BITWISE_AND') {
                    $error = 'Parentheses around function parameters throws warning in PHP 7.0';
                    $phpcsFile->addWarning($error, $nextSeparator + 1);
                }
                
                $nextSeparator = $nextComma;
            }//end while
            
            if ($tokens[$nextSeparator + 1]['type'] == 'T_WHITESPACE') {
                $nextSeparator++;
            }
            
            $nextOpen = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $nextSeparator + 1, $nextSeparator + 3);
            if ($nextOpen !== false && $nextOpen <= $nextSeparator + 1 && $tokens[$nextSeparator + 3]['type'] != 'T_BITWISE_AND' && $tokens[$nextSeparator + 4]['type'] != 'T_BITWISE_AND' && $tokens[$nextSeparator + 5]['type'] != 'T_BITWISE_AND') {
                $error = 'Parentheses around function parameters throws warning in PHP 7.0';
                $phpcsFile->addWarning($error, $nextSeparator + 1);
            }
        }
    }//end process()

}//end class
