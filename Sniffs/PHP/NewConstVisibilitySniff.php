<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewConstVisibility.
 *
 * PHP version 7.1
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewConstVisibility.
 *
 * Visibility for class constants is available since PHP 7.1.
 *
 * PHP version 7.1
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_NewConstVisibilitySniff extends PHPCompatibility_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CONST);

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
        $tokens    = $phpcsFile->getTokens();
        $prevToken = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true);

        if ($prevToken === false) {
            return;
        }

        // Is the previous token a visibility indicator ?
        if (in_array($tokens[$prevToken]['code'], PHP_CodeSniffer_Tokens::$scopeModifiers, true) === false) {
            return;
        }

        if ($this->tokenHasScope($phpcsFile, $stackPtr, array(T_CLASS, T_INTERFACE)) === true && $this->supportsBelow('7.0') === true) {
            $error = 'Visibility indicators for class constants are not supported in PHP 7.0 or earlier. Found "%s const"';
            $data  = array($tokens[$prevToken]['content']);

            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
        }

    }//end process()

}//end class
