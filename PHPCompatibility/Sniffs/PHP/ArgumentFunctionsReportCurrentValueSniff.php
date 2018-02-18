<?php
/**
 * \PHPCompatibility\Sniffs\PHP\ArgumentFunctionsReportCurrentValue.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;

/**
 * \PHPCompatibility\Sniffs\PHP\ArgumentFunctionsReportCurrentValue.
 *
 * Functions inspecting function arguments report the current value instead of the original since PHP 7.0.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */
class ArgumentFunctionsReportCurrentValueSniff extends Sniff
{
    /**
     * A list of functions that, when called, have a different behaviour in PHP 7 when dealing with parameters of the function they're called in.
     * @var array(string)
     */
    
    protected $changedFunctions = array(
        'func_get_arg',
        'func_get_args',
        'debug_backtrace'
    );
    
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
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.0') === false) {
            return;
        }
        
        $tokens = $phpcsFile->getTokens();
        
        $ignore = array(
            T_DOUBLE_COLON,
            T_OBJECT_OPERATOR,
            T_FUNCTION,
            T_CLASS,
            T_CONST,
            T_USE,
            T_NS_SEPARATOR,
        );
        
        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if (in_array($tokens[$prevToken]['code'], $ignore) === true) {
            // Not a call to a PHP function.
            return;
        }
        
        $function   = $tokens[$stackPtr]['content'];
        $functionLc = strtolower($function);

        if (in_array($functionLc, $this->changedFunctions) === false) {
            return;
        }

        if (isset($tokens[$stackPtr]['conditions'])) {
            foreach ($tokens[$stackPtr]['conditions'] as $condition => $notimportant) {
                if ($tokens[$condition]['type'] == 'T_FUNCTION') {
                    $this->addMessage($phpcsFile, 'Functions inspecting arguments report the current parameter value Function since PHP 7.0. Verify if the value is changed somewhere.', $stackPtr, false);
                }
            }
        }
    }//end process()


}//end class
