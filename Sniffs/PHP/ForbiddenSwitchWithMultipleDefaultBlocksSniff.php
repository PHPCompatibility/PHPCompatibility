<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenSwitchWithMultipleDefaultBlocksSniff.
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenSwitchWithMultipleDefaultBlocksSniff.
 *
 * Switch statements can not have multiple default blocks since PHP 7.0
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenSwitchWithMultipleDefaultBlocksSniff extends PHPCompatibility_Sniff
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
        return array(T_SWITCH);

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
            
            $defaultToken = $stackPtr;
            $defaultCount = 0;
            if (isset($tokens[$stackPtr]['scope_closer'])) {
                while ($defaultToken = $phpcsFile->findNext(array(T_DEFAULT, T_SWITCH), $defaultToken + 1, $tokens[$stackPtr]['scope_closer'])) {
                    if ($tokens[$defaultToken]['type'] == 'T_SWITCH') {
                        $defaultToken = $tokens[$defaultToken]['scope_closer'];
                    } else {
                        $defaultCount++;
                    }
                }
                
                if ($defaultCount > 1) {
                    $phpcsFile->addError('Switch statements can not have multiple default blocks since PHP 7.0', $stackPtr);
                }
            }
        }
    }//end process()

}//end class
