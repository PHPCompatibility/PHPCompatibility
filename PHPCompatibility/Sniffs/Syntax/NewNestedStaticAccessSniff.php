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
 * (Nested) Static property and constant fetches as well as method calls can be applied to
 * any dereferencable expression since PHP 7.0.
 *
 * Class constants can be dereferenced since PHP 8.0.
 *
 * PHP version 7.0
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/uniform_variable_syntax
 * @link https://wiki.php.net/rfc/variable_syntax_tweaks#class_constant_dereferencability
 *
 * @since 10.0.0
 */
class NewNestedStaticAccessSniff extends Sniff
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
        return [\T_DOUBLE_COLON];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // PHP 8.0 supports both nested static access and class constant dereferencing
        if (ScannedCode::shouldRunOnOrBelow('7.4') === false) {
            return;
        }

        $tokens       = $phpcsFile->getTokens();
        $prev         = $stackPtr;
        $seenBrackets = false;
        $prevOperator = false;

        do {
            $prev = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prev - 1), null, true);

            if ($prev === false) {
                return;
            }

            if ($tokens[$prev]['code'] === \T_CLOSE_SQUARE_BRACKET
                || $tokens[$prev]['code'] === \T_CLOSE_CURLY_BRACKET
            ) {
                if (isset($tokens[$prev]['bracket_opener']) === false) {
                    // Parse error.
                    return;
                }

                $prev         = $tokens[$prev]['bracket_opener'];
                $seenBrackets = true;
                continue;
            }

            if ($tokens[$prev]['code'] === \T_CLOSE_PARENTHESIS) {
                if (isset($tokens[$prev]['parenthesis_opener']) === false) {
                    // Parse error.
                    return;
                }

                $prev         = $tokens[$prev]['parenthesis_opener'];
                $seenBrackets = true;
                continue;
            }

            // Now this should either be a T_STRING or a T_VARIABLE.
            if ($tokens[$prev]['code'] !== \T_STRING && $tokens[$prev]['code'] !== \T_VARIABLE) {
                // Not sure what's happening, but this is outside the scope of this sniff.
                return;
            }

            // OK, we have the start of the access, let see if it's nested.
            $prevOperator = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prev - 1), null, true);
            break;

        } while (true);

        if ($prevOperator === false
            || isset(Collections::objectOperators()[$tokens[$prevOperator]['code']]) === false
        ) {
            return;
        }

        // Class constants cannot be dereferenced before PHP 8.0.
        if ($seenBrackets === false
            && $tokens[$prev]['code'] === \T_STRING
            && $tokens[$prevOperator]['code'] === \T_DOUBLE_COLON
            && ScannedCode::shouldRunOnOrBelow('7.4') === true
        ) {
            $phpcsFile->addError(
                'Dereferencing class constants was not supported in PHP 7.4 or earlier.',
                $stackPtr,
                'ClassConstantDereferenced'
            );
            return;
        }

        // This is nested static access.
        if (ScannedCode::shouldRunOnOrBelow('5.6') === true) {
            $phpcsFile->addError(
                'Nested access to static properties, constants and methods was not supported in PHP 5.6 or earlier.',
                $stackPtr,
                'NestedStaticAccess'
            );
        }
    }
}
