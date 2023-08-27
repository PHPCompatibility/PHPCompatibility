<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Syntax;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\BackCompat\BCTokens;

/**
 * Detect dereferencing of magic constants as allowed per PHP 8.0.
 *
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/variable_syntax_tweaks#constants_and_magic_constants
 *
 * @since 10.0.0
 */
class NewMagicConstantDereferencingSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return Tokens::$magicConstants;
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('7.4') === false) {
            return;
        }

        $tokens       = $phpcsFile->getTokens();
        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextNonEmpty === false) {
            return;
        }

        if ($tokens[$nextNonEmpty]['code'] !== \T_OPEN_SQUARE_BRACKET) {
            return;
        }

        if (isset($tokens[$nextNonEmpty]['bracket_closer']) === false) {
            // Live coding or parse error.
            return;
        }

        $hasContent = $phpcsFile->findNext(
            Tokens::$emptyTokens,
            ($nextNonEmpty + 1),
            $tokens[$nextNonEmpty]['bracket_closer'],
            true
        );

        if ($hasContent === false) {
            /*
             * Attempt to assign to the magic constant as if it were an array.
             * Not allowed now, nor prior to PHP 8, so not our concern.
             */
            return;
        }

        // Make sure this isn't an array assignment. That would be illegal, i.e. a parse error.
        $nextNext = $tokens[$nextNonEmpty]['bracket_closer'];
        do {
            $nextNext = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNext + 1), null, true);
            if ($nextNext === false) {
                break;
            }

            // If the next token is an assignment, that's all we need to know.
            if (isset(BCTokens::assignmentTokens()[$tokens[$nextNext]['code']]) === true) {
                return;
            }

            // Check if this is an multi-access array assignment, e.g., `__FILE__[1][2] = 'val';` .
            if ($tokens[$nextNext]['code'] === \T_OPEN_SQUARE_BRACKET
                && isset($tokens[$nextNext]['bracket_closer']) === true
            ) {
                $nextNext = $tokens[$nextNext]['bracket_closer'];
                continue;
            }

            break;
        } while (true);

        $phpcsFile->addError(
            'Dereferencing of magic constants is not present in PHP version 7.4 or earlier. Found dereferencing of: %s',
            $nextNonEmpty,
            'Found',
            [$tokens[$stackPtr]['content']]
        );
    }
}
