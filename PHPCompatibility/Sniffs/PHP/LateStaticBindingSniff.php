<?php
/**
 * PHPCompatibility_Sniffs_PHP_LateStaticBindingSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_LateStaticBindingSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_LateStaticBindingSniff extends PHPCompatibility_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STATIC);

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
        $nextNonEmpty = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        if ($nextNonEmpty === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        if ($tokens[$nextNonEmpty]['code'] !== T_DOUBLE_COLON) {
            return;
        }

        $inClass = $this->inClassScope($phpcsFile, $stackPtr, false);

        if ($inClass === true && $this->supportsBelow('5.2') === true) {
            $error = 'Late static binding is not supported in PHP 5.2 or earlier.';
            $phpcsFile->addError($error, $stackPtr, 'Found');
        }

        if ($inClass === false) {
            $error = 'Late static binding is not supported outside of class scope.';
            $phpcsFile->addError($error, $stackPtr, 'OutsideClassScope');
        }

    }//end process()


}//end class
