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
        if ($this->supportsAbove('7.0') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        if (isset($tokens[$stackPtr]['scope_closer']) === false) {
            return;
        }

        $defaultToken = $stackPtr;
        $defaultCount = 0;
        $targetLevel  = $tokens[$stackPtr]['level'] + 1;
        while ($defaultCount < 2 && $defaultToken = $phpcsFile->findNext(array(T_DEFAULT), $defaultToken + 1, $tokens[$stackPtr]['scope_closer'])) {
            // Same level or one below (= two default cases after each other).
            if ($tokens[$defaultToken]['level'] === $targetLevel || $tokens[$defaultToken]['level'] === ($targetLevel + 1)) {
                $defaultCount++;
            }
        }

        if ($defaultCount > 1) {
            $phpcsFile->addError(
                'Switch statements can not have multiple default blocks since PHP 7.0',
                $stackPtr,
                'Found'
            );
        }
    }//end process()

}//end class
