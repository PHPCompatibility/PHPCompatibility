<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ControlStructures;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Detect use of a semicolon to terminate a switch case/default statement.
 *
 * This has been deprecated in PHP 8.5.
 *
 * PHP version 8.5
 *
 * @link https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_semicolon_after_case_in_switch_statement
 * @link https://www.php.net/manual/en/control-structures.switch.php
 *
 * @since 10.0.0
 */
final class RemovedTerminatingCaseWithSemicolonSniff extends Sniff
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
        return [
            \T_CASE,
            \T_DEFAULT,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('8.5') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            return;
        }

        $scopeOpener = $tokens[$stackPtr]['scope_opener'];
        if ($tokens[$scopeOpener]['code'] === \T_COLON) {
            // All good, nothing to do.
            return;
        }

        /*
         * If a case body is wrapped within curly braces, PHPCS sets the open brace as the scope opener,
         * so we need to walk back to check for the _real_ opener.
         */
        if ($tokens[$scopeOpener]['code'] === \T_OPEN_CURLY_BRACKET) {
            $prevNonEmpty = $phpcsFile->findPrevious(Tokens::EMPTY_TOKENS, ($scopeOpener - 1), null, true);
            if ($tokens[$prevNonEmpty]['code'] === \T_COLON) {
                // All good, nothing to do.
                return;
            }

            // Override what we regard as the scope opener.
            $scopeOpener = $prevNonEmpty;
        }

        /*
         * The scope opener can now only be one of two tokens: either the semicolon or a PHP close tag with an implied semicolon.
         * In both cases, this is deprecated.
         */
        $terminator = 'a semicolon';
        if ($tokens[$scopeOpener]['code'] === \T_CLOSE_TAG) {
            $terminator = 'an implied semicolon';
        }

        $fix = $phpcsFile->addFixableWarning(
            'Terminating a switch case statement with %s is deprecated since PHP 8.5.',
            $scopeOpener,
            'Deprecated',
            [$terminator]
        );

        if ($fix === true) {
            if ($tokens[$scopeOpener]['code'] === \T_SEMICOLON) {
                $phpcsFile->fixer->replaceToken($scopeOpener, ':');
            } else {
                // This is a PHP close tag. Figure out where to place the colon.
                $prevNonEmpty = $phpcsFile->findPrevious(Tokens::EMPTY_TOKENS, ($scopeOpener - 1), null, true);
                $phpcsFile->fixer->addContent($prevNonEmpty, ':');
            }
        }
    }
}
