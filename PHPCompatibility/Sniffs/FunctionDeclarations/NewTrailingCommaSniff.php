<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;

/**
 * Detect trailing commas in function declarations and closure use lists as allowed since PHP 8.
 *
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/trailing_comma_in_parameter_list
 * @link https://wiki.php.net/rfc/trailing_comma_in_closure_use_list
 *
 * @since 10.0.0
 */
class NewTrailingCommaSniff extends Sniff
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
        return Collections::functionDeclarationTokens();
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

        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['parenthesis_closer']) === false) {
            // Live coding or parse error.
            return;
        }

        $closer = $tokens[$stackPtr]['parenthesis_closer'];

        /*
         * Check for trailing commas in a function declaration parameter list.
         */
        $lastInParenthesis = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($closer - 1), null, true);

        if ($tokens[$lastInParenthesis]['code'] === \T_COMMA) {
            $phpcsFile->addError(
                'Trailing commas are not allowed in function declaration parameter lists in PHP 7.4 or earlier',
                $lastInParenthesis,
                'InParameterList'
            );
        }

        /*
         * From this point forward, we're only interested in closures to check for
         * trailing commas in closure use lists.
         * Bow out for any of the other tokens.
         */
        if ($tokens[$stackPtr]['code'] !== \T_CLOSURE) {
            return;
        }

        $usePtr = $phpcsFile->findNext(Tokens::$emptyTokens, ($closer + 1), null, true);
        if ($usePtr === false || $tokens[$usePtr]['code'] !== \T_USE) {
            // Closure without use list or live coding/parse error.
            return;
        }

        $openParens = $phpcsFile->findNext(Tokens::$emptyTokens, ($usePtr + 1), null, true);
        if ($openParens === false
            || $tokens[$openParens]['code'] !== \T_OPEN_PARENTHESIS
            || isset($tokens[$openParens]['parenthesis_closer']) === false
        ) {
            // Live coding/parse error.
            return;
        }

        $closer            = $tokens[$openParens]['parenthesis_closer'];
        $lastInParenthesis = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($closer - 1), null, true);

        if ($tokens[$lastInParenthesis]['code'] === \T_COMMA) {
            $phpcsFile->addError(
                'Trailing commas are not allowed in closure use lists in PHP 7.4 or earlier',
                $lastInParenthesis,
                'InClosureUseList'
            );
        }
    }
}
