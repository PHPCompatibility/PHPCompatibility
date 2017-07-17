<?php
/**
 * \PHPCompatibility\Sniffs\PHP\NewMagicClassConstantSniff.
 *
 * PHP version 5.5
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;

/**
 * \PHPCompatibility\Sniffs\PHP\NewMagicClassConstantSniff.
 *
 * The special ClassName::class constant is available as of PHP 5.5.0, and allows for
 * fully qualified class name resolution at compile.
 *
 * PHP version 5.5
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewMagicClassConstantSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if (strtolower($tokens[$stackPtr]['content']) !== 'class') {
            return;
        }

        $prevToken = $phpcsFile->findPrevious(\PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true);
        if ($prevToken === false || $tokens[$prevToken]['code'] !== T_DOUBLE_COLON) {
            return;
        }

        $phpcsFile->addError(
            'The magic class constant ClassName::class was not available in PHP 5.4 or earlier',
            $stackPtr,
            'Found'
        );
    }
}
