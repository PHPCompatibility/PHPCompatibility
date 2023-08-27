<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2009-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Syntax;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\TextStrings;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Detect dereferencing of interpolated strings.
 *
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/variable_syntax_tweaks#interpolated_and_non-interpolated_strings
 * @link https://www.php.net/manual/en/language.types.string.php#language.types.string.parsing
 *
 * @since 10.0.0
 */
class NewInterpolatedStringDereferencingSniff extends Sniff
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
            \T_DOUBLE_QUOTED_STRING,
        ];
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
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // PHP 8.0 supports dereferencing interpolated strings
        if (ScannedCode::shouldRunOnOrBelow('7.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Check whether the text string uses interpolation.
        $endOfQuote = TextStrings::getEndOfCompleteTextString($phpcsFile, $stackPtr);
        $string     = TextStrings::stripQuotes(GetTokensAsString::normal($phpcsFile, $stackPtr, $endOfQuote));
        $nextAfter  = ($endOfQuote + 1);
        if (TextStrings::stripEmbeds($string) === $string) {
            return $nextAfter;
        }

        // Check whether the string is being dereferenced.
        $nextNonEmpty     = $phpcsFile->findNext(Tokens::$emptyTokens, $nextAfter, null, true);
        $nextNonEmptyCode = $tokens[$nextNonEmpty]['code'];
        if ($nextNonEmptyCode !== \T_OPEN_SQUARE_BRACKET
            && $nextNonEmptyCode !== \T_OBJECT_OPERATOR
        ) {
            return $nextAfter;
        }

        $phpcsFile->addError(
            'Dereferencing of interpolated strings is not supported in PHP 7.4 or earlier',
            $stackPtr,
            'Found'
        );

        return $nextAfter;
    }
}
