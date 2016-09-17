<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenGlobalVariableVariableSniff.
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenGlobalVariableVariableSniff.
 *
 * Variable variables are forbidden with global
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenGlobalVariableVariableSniff extends PHPCompatibility_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_GLOBAL);
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
        if ($this->supportsAbove('7.0')) {
            $tokens = $phpcsFile->getTokens();

            $variable = $phpcsFile->findNext(T_VARIABLE, $stackPtr, $stackPtr + 4, false);

            if (isset($tokens[$variable - 1]) && $tokens[$variable - 1]['type'] == 'T_DOLLAR') {
                $error = 'Global with variable variables is not allowed since PHP 7.0';
                $phpcsFile->addError($error, $stackPtr);
            }
        }
    }
}
