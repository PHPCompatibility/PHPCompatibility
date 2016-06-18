<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenEmptyListAssignmentSniff.
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenEmptyListAssignmentSniff.
 *
 * Empty list() assignments have been removed in PHP 7.0
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenEmptyListAssignmentSniff extends PHPCompatibility_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_LIST);
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

            $open = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr, null, false);
            $close = $phpcsFile->findNext(T_CLOSE_PARENTHESIS, $stackPtr, null, false);
            
            $error = true;
            for ($cnt = $open + 1; $cnt < $close; $cnt++) {
                if ($tokens[$cnt]['type'] != 'T_WHITESPACE' && $tokens[$cnt]['type'] != 'T_COMMA') {
                    $error = false;
                }
            }
            
            if ($open !== false && $close !== false) {
                if (
                    $close - $open == 1
                    ||
                    (
                        $close - $open > 2
                        &&
                        $error === true
                    )
                ) {
                    $error = sprintf(
                        "Empty list() assignments are not allowed since PHP 7.0"
                    );
                    $phpcsFile->addError($error, $stackPtr);
                }
            }
        }
    }
}
