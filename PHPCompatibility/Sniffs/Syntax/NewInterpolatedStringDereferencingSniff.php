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

use PHPCompatibility\Sniff;
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
     * @return array
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
        if ($this->supportsBelow('7.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Check whether the text string uses interpolation.
        $string     = TextStrings::getCompleteTextString($phpcsFile, $stackPtr, true);
        $endOfQuote = $phpcsFile->findNext([\T_DOUBLE_QUOTED_STRING], $stackPtr + 1, null, true);
        if ($this->stripVariables($string) === $string) {
            return $endOfQuote;
        }

        // Check whether the string is being dereferenced.
        $nextNonEmpty     = $phpcsFile->findNext(Tokens::$emptyTokens, $endOfQuote, null, true);
        $nextNonEmptyCode = $tokens[$nextNonEmpty]['code'];
        if ($nextNonEmptyCode !== \T_OPEN_SQUARE_BRACKET
            && $nextNonEmptyCode !== \T_OBJECT_OPERATOR
            // Work-around for a bug in PHPCS < 3.6.0.
            // @link https://github.com/squizlabs/PHP_CodeSniffer/pull/3172
            && $nextNonEmptyCode !== \T_OPEN_SHORT_ARRAY
        ) {
            return $endOfQuote;
        }

        $phpcsFile->addError(
            'Dereferencing of interpolated strings is not supported in PHP 7.4 or earlier',
            $stackPtr,
            'Found'
        );

        return $endOfQuote;
    }
}
