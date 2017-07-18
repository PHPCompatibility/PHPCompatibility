<?php
/**
 * \PHPCompatibility\Sniffs\PHP\NewConstVisibility.
 *
 * PHP version 7.1
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;

/**
 * \PHPCompatibility\Sniffs\PHP\NewConstVisibility.
 *
 * Visibility for class constants is available since PHP 7.1.
 *
 * PHP version 7.1
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewConstVisibilitySniff extends Sniff
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
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('7.0') === false) {
            return;
        }

        $tokens    = $phpcsFile->getTokens();
        $prevToken = $phpcsFile->findPrevious(\PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true);

        // Is the previous token a visibility indicator ?
        if ($prevToken === false || in_array($tokens[$prevToken]['code'], \PHP_CodeSniffer_Tokens::$scopeModifiers, true) === false) {
            return;
        }

        // Is this a class constant ?
        if ($this->isClassConstant($phpcsFile, $stackPtr) === false) {
            // This may be a constant declaration in the global namespace with visibility,
            // but that would throw a parse error, i.e. not our concern.
            return;
        }

        $phpcsFile->addError(
            'Visibility indicators for class constants are not supported in PHP 7.0 or earlier. Found "%s const"',
            $stackPtr,
            'Found',
            array($tokens[$prevToken]['content'])
        );

    }//end process()

}//end class
