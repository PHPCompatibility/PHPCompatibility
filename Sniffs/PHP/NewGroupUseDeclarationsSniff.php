<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewGroupUseDeclarationsSniff.
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewGroupUseDeclarationsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_NewGroupUseDeclarationsSniff extends PHPCompatibility_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        if (defined('T_OPEN_USE_GROUP')) {
            return array(T_OPEN_USE_GROUP);
        } else {
            return array(T_USE);
        }
    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.6') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $token  = $tokens[$stackPtr];

        // Deal with PHPCS pre-2.6.0.
        if ($token['code'] === T_USE) {
            $hasCurlyBrace = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, ($stackPtr + 1), null, false, null, true);
            if ($hasCurlyBrace === false) {
                return;
            }

            $prevToken = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($hasCurlyBrace - 1), null, true);
            if ($prevToken === false || $tokens[$prevToken]['code'] !== T_NS_SEPARATOR) {
				return;
			}
        }

        // Still here ? In that case, it is a group use statement.
        $phpcsFile->addError(
            'Group use declarations are not allowed in PHP 5.6 or earlier',
            $stackPtr,
            'Found'
        );

    }//end process()
}//end class
