<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Generators;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * As of PHP 8.3, there can be a comment between the "yield" and "from" keywords.
 *
 * PHP version 8.3
 *
 * @link https://github.com/php/php-src/issues/14926
 *
 * @since 10.0.0
 */
final class NewYieldFromCommentSniff extends Sniff
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
            \T_YIELD_FROM,
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
        if (ScannedCode::shouldRunOnOrBelow('8.2') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $content      = $tokens[$stackPtr]['content'];
        $yieldFromEnd = $stackPtr;

        /*
         * Handle potentially multi-token "yield from" expressions.
         */
        if (\strtolower(\trim($content)) === 'yield') {
            for ($i = ($stackPtr + 1); $i < $phpcsFile->numTokens; $i++) {
                if ($tokens[$i]['code'] === \T_YIELD_FROM && \strtolower(\trim($tokens[$i]['content'])) === 'from') {
                    $content     .= $tokens[$i]['content'];
                    $yieldFromEnd = $i;
                    break;
                }

                if (isset(Tokens::$emptyTokens[$tokens[$i]['code']]) === false && $tokens[$i]['code'] !== \T_YIELD_FROM) {
                    // Shouldn't be possible. Just to be on the safe side.
                    return; // @codeCoverageIgnore
                }

                $content .= $tokens[$i]['content'];
            }
        }

        $contentSansKeywords = \substr($content, 5, -4);

        if (\trim($contentSansKeywords) === '') {
            return ($yieldFromEnd + 1);
        }

        $phpcsFile->addError(
            'Comment(s) between the "yield" and "from" keywords were not supported in PHP 8.2 or earlier',
            $stackPtr,
            'Found'
        );

        // No need to look at this expression again.
        return ($yieldFromEnd + 1);
    }
}
