<?php
/**
 * \PHPCompatibility\Sniffs\UseDeclarations\NewGroupUseDeclarationsSniff.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */

namespace PHPCompatibility\Sniffs\UseDeclarations;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * \PHPCompatibility\Sniffs\UseDeclarations\NewGroupUseDeclarationsSniff.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */
class NewGroupUseDeclarationsSniff extends Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        if (\defined('T_OPEN_USE_GROUP')) {
            return array(\T_OPEN_USE_GROUP);
        } else {
            return array(\T_USE);
        }
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('7.1') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $token  = $tokens[$stackPtr];

        // Deal with PHPCS pre-2.6.0.
        if ($token['code'] === \T_USE) {
            $hasCurlyBrace = $phpcsFile->findNext(\T_OPEN_CURLY_BRACKET, ($stackPtr + 1), null, false, null, true);
            if ($hasCurlyBrace === false) {
                return;
            }

            $prevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($hasCurlyBrace - 1), null, true);
            if ($prevToken === false || $tokens[$prevToken]['code'] !== \T_NS_SEPARATOR) {
                return;
            }

            $stackPtr = $hasCurlyBrace;
        }

        // Still here ? In that case, it is a group use statement.
        if ($this->supportsBelow('5.6') === true) {
            $phpcsFile->addError(
                'Group use declarations are not allowed in PHP 5.6 or earlier',
                $stackPtr,
                'Found'
            );
        }

        $closers = array(\T_CLOSE_CURLY_BRACKET);
        if (\defined('T_CLOSE_USE_GROUP')) {
            $closers[] = \T_CLOSE_USE_GROUP;
        }

        $closeCurly = $phpcsFile->findNext($closers, ($stackPtr + 1), null, false, null, true);
        if ($closeCurly === false) {
            return;
        }

        $prevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($closeCurly - 1), null, true);
        if ($tokens[$prevToken]['code'] === \T_COMMA) {
            $phpcsFile->addError(
                'Trailing comma\'s are not allowed in group use statements in PHP 7.1 or earlier',
                $prevToken,
                'TrailingCommaFound'
            );
        }
    }
}
