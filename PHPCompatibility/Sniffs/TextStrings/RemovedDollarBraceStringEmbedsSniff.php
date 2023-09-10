<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\TextStrings;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\TextStrings;

/**
 * Detect use of select forms of variable embedding in heredocs and double strings as deprecated per PHP 8.2.
 *
 * > PHP allows embedding variables in strings with double-quotes (") and heredoc in various ways.
 * > 1. Directly embedding variables (`$foo`)
 * > 2. Braces outside the variable (`{$foo}`)
 * > 3. Braces after the dollar sign (`${foo}`)
 * > 4. Variable variables (`${expr}`, equivalent to `(string) ${expr}`)
 * >
 * > [...] to deprecate options 3 and 4 in PHP 8.2 and remove them in PHP 9.0.
 *
 * PHP version 8.2
 * PHP version 9.0
 *
 * @link https://wiki.php.net/rfc/deprecate_dollar_brace_string_interpolation
 *
 * @since 10.0.0
 */
class RemovedDollarBraceStringEmbedsSniff extends Sniff
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
            \T_START_HEREDOC,
            \T_DOLLAR_OPEN_CURLY_BRACES,
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
     * @return void|int Void or a stack pointer to skip forward.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('8.2') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        /*
         * Defensive coding, this code is not expected to ever actually be hit since PHPCS#3604
         * (included in 3.7.0), but _will_ be hit if a file containing a PHP 7.3 indented heredoc/nowdocs
         * is scanned with PHPCS on PHP < 7.3. People shouldn't do that, but hey, we can't stop them.
         */
        if ($tokens[$stackPtr]['code'] === \T_DOLLAR_OPEN_CURLY_BRACES) {
            // @codeCoverageIgnoreStart
            if ($tokens[($stackPtr - 1)]['code'] === \T_DOUBLE_QUOTED_STRING) {
                --$stackPtr;
            } else {
                // Throw an error anyway, though it won't be very informative.
                $message = 'Using ${} in strings is deprecated since PHP 8.2, use {$var} or {${expr}} instead.';
                $code    = 'DeprecatedDollarBraceEmbed';
                $phpcsFile->addWarning($message, $stackPtr, $code);
                return;
            }
            // @codeCoverageIgnoreEnd
        }

        $endOfString   = TextStrings::getEndOfCompleteTextString($phpcsFile, $stackPtr);
        $startOfString = $stackPtr;
        if ($tokens[$stackPtr]['code'] === \T_START_HEREDOC) {
            $startOfString = ($stackPtr + 1);
        }

        $contents = GetTokensAsString::normal($phpcsFile, $startOfString, $endOfString);
        if (\strpos($contents, '${') === false) {
            // No interpolation found or only interpolations which are still supported.
            return ($endOfString + 1);
        }

        $embeds = TextStrings::getEmbeds($contents);
        foreach ($embeds as $offset => $embed) {
            if (\strpos($embed, '${') !== 0) {
                continue;
            }

            // Figure out the stack pointer to throw the warning on.
            $errorPtr = $startOfString;
            $length   = 0;
            while (($length + $tokens[$errorPtr]['length']) < $offset) {
                $length += $tokens[$errorPtr]['length'];
                ++$errorPtr;
            }

            // Type 4.
            $message = 'Using %s (variable variables) in strings is deprecated since PHP 8.2, use {${expr}} instead.';
            $code    = 'DeprecatedExpressionSyntax';
            if (\preg_match('`^\$\{(?P<varname>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+)(?:\[([\'"])?[^\$\{\}\]]+(?:\2)?\])?\}$`', $embed) === 1) {
                // Type 3.
                $message = 'Using ${var} in strings is deprecated since PHP 8.2, use {$var} instead. Found: %s';
                $code    = 'DeprecatedVariableSyntax';
            }

            $phpcsFile->addWarning($message, $errorPtr, $code, [$embed]);

        }

        return ($endOfString + 1);
    }
}
