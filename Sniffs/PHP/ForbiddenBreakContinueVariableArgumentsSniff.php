<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenBreakContinueVariableArguments.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenBreakContinueVariableArguments.
 *
 * Discourages the use of assigning the return value of new by reference
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenBreakContinueVariableArgumentsSniff implements PHP_CodeSniffer_Sniff
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
        return array(T_BREAK, T_CONTINUE);

    }//end register()


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
        $tokens = $phpcsFile->getTokens();
        $nextSemicolonToken = $phpcsFile->findNext(T_SEMICOLON, ($stackPtr), null, false);
        for ($curToken = $stackPtr + 1; $curToken < $nextSemicolonToken; $curToken++) {
            $gotError = false;
            if ($tokens[$curToken]['type'] == 'T_STRING') {
                // If the next non-whitespace token after the string
                // is an opening parenthesis then it's a function call.
                $openBracket = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $curToken + 1, null, true);
                if ($tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS) {
                    continue;
                } else {
                    $gotError = true;
                }
            }
            switch ($tokens[$curToken]['type']) {
                case 'T_VARIABLE':
                case 'T_FUNCTION':
                    $gotError = true;
                    break;
            }
            if ($gotError === true) {
                $error = 'Using a variable argument on break or continue is forbidden since PHP 5.4';
                $phpcsFile->addError($error, $stackPtr);
            }
        }
    }//end process()


}//end class
