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

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;

/**
 * Detect class member access on object instantiation/cloning without parentheses wrappers.
 *
 * As of PHP 8.4, a new expression with constructor arguments' parentheses no longer needs
 * to be wrapped within parentheses when accessing members on the object.
 *
 * PHP version 8.4
 *
 * Also {@see \PHPCompatibility\Sniffs\Syntax\NewClassMemberAccessSniff}.
 *
 * @link https://wiki.php.net/rfc/new_without_parentheses
 *
 * {@internal The reason for splitting the logic of this sniff into different methods is
 *            to allow re-use of the logic by the PHP 7.4 `RemovedCurlyBraceArrayAccess` sniff.}
 *
 * @since 10.0.0
 */
final class NewClassMemberAccessWithoutParenthesesSniff extends Sniff
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
        return [\T_NEW];
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
        if (ScannedCode::shouldRunOnOrBelow('8.3') === false) {
            return;
        }

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextNonEmpty === false) {
            // Live coding/parse error.
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Skip over a parenthesized expression at the start of the new expression,
        // so as not to mess up the parentheses count.
        $start = $nextNonEmpty;
        if ($tokens[$nextNonEmpty]['code'] === \T_OPEN_PARENTHESIS) {
            if (isset($tokens[$nextNonEmpty]['parenthesis_closer']) === false) {
                // Live coding/parse error.
                return;
            }

            $start = ($tokens[$nextNonEmpty]['parenthesis_closer'] + 1);
        }

        $endOfStatement = $phpcsFile->findEndOfStatement($stackPtr);

        $ignore              = Tokens::$emptyTokens;
        $ignore             += Collections::namespacedNameTokens();
        $ignore[\T_READONLY] = \T_READONLY; // PHP 8.3 readonly anonymous classes.
        $ignore[\T_VARIABLE] = \T_VARIABLE;
        $ignore[\T_DOLLAR]   = \T_DOLLAR; // Variable variables.

        $requiresConstructorParentheses = true;
        $seenParentheses                = 0;
        for ($i = $start; $i < $endOfStatement; $i++) {
            if (isset($ignore[$tokens[$i]['code']]) === true) {
                continue;
            }

            // Skip over attributes for anonymous classes.
            if (isset($tokens[$i]['attribute_closer']) === true) {
                $i = $tokens[$i]['attribute_closer'];
                continue;
            }

            if ($tokens[$i]['code'] === \T_ANON_CLASS) {
                $requiresConstructorParentheses = false;

                if (isset($tokens[$i]['scope_closer']) === true) {
                    $i = $tokens[$i]['scope_closer'];
                    continue;
                }
            }

            // Skip nested statements.
            if (isset($tokens[$i]['parenthesis_closer']) === true
                && $i === $tokens[$i]['parenthesis_opener']
            ) {
                ++$seenParentheses;
                $i = $tokens[$i]['parenthesis_closer'];
                continue;
            }

            if (isset($tokens[$i]['bracket_closer']) === true
                && $i === $tokens[$i]['bracket_opener']
                && $requiresConstructorParentheses === true
                && $seenParentheses === 0
            ) {
                $i = $tokens[$i]['bracket_closer'];
                continue;
            }

            break;
        }

        if ($requiresConstructorParentheses === true && $seenParentheses < 1) {
            // Missing required constructor argument parentheses. Ignore.
            return;
        }

        // Normalize the parentheses count to exclude the constructor argument parentheses.
        $parenthesesAfter = $seenParentheses;
        if ($requiresConstructorParentheses === true) {
            --$parenthesesAfter;
        }

        if (isset(Collections::objectOperators()[$tokens[$i]['code']])
            || $tokens[$i]['code'] === \T_OPEN_SQUARE_BRACKET
            || $parenthesesAfter >= 1
        ) {
            $error = 'Class member access on object instantiation, without parentheses around the new expression, was not supported in PHP 8.3 or earlier';
            $phpcsFile->addError($error, $i, 'Found');
        }
    }
}
