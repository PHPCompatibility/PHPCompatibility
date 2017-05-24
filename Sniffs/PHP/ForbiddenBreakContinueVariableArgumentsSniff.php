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
 * Forbids variable arguments on break or continue statements.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenBreakContinueVariableArgumentsSniff extends PHPCompatibility_Sniff
{
    /**
     * Error types this sniff handles for forbidden break/continue arguments.
     *
     * Array key is the error code. Array value will be used as part of the error message.
     *
     * @var array
     */
    private $errorTypes = array(
        'variableArgument' => 'a variable argument',
        'zeroArgument'     => '0 as an argument',
    );

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
        if ($this->supportsAbove('5.4') === false) {
            return;
        }

        $tokens             = $phpcsFile->getTokens();
        $nextSemicolonToken = $phpcsFile->findNext(T_SEMICOLON, ($stackPtr), null, false);
        $errorType          = '';
        for ($curToken = $stackPtr + 1; $curToken < $nextSemicolonToken; $curToken++) {
            if ($tokens[$curToken]['type'] === 'T_STRING') {
                // If the next non-whitespace token after the string
                // is an opening parenthesis then it's a function call.
                $openBracket = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $curToken + 1, null, true);
                if ($tokens[$openBracket]['code'] === T_OPEN_PARENTHESIS) {
                    $errorType = 'variableArgument';
                    break;
                }

            } elseif (in_array($tokens[$curToken]['type'], array('T_VARIABLE', 'T_FUNCTION', 'T_CLOSURE'), true)) {
                $errorType = 'variableArgument';
                break;

            } elseif ($tokens[$curToken]['type'] === 'T_LNUMBER' && $tokens[$curToken]['content'] === '0') {
                $errorType = 'zeroArgument';
                break;
            }
            } elseif ($tokens[$curToken]['type'] === 'T_LNUMBER') {
                $errorType = 'variableArgument';
                break;
            }

        }

        if ($errorType !== '') {
            $error     = 'Using %s on break or continue is forbidden since PHP 5.4';
            $errorCode = $errorType.'Found';
            $data      = array($this->errorTypes[$errorType]);

            $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
        }

    }//end process()

}//end class
