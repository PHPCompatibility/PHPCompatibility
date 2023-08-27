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

/**
 * Detects the use of the first class callable syntax, as introduced in PHP 8.1.
 *
 * PHP version 8.1
 *
 * @link https://www.php.net/manual/en/migration81.new-features.php#migration81.new-features.core.callable-syntax
 * @link https://wiki.php.net/rfc/first_class_callable_syntax
 *
 * @since 10.0.0
 */
final class NewFirstClassCallablesSniff extends Sniff
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
        return [\T_ELLIPSIS];
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
        if (ScannedCode::shouldRunOnOrBelow('8.0') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $prev = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($tokens[$prev]['code'] !== \T_OPEN_PARENTHESIS) {
            return;
        }

        $next = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($next === false || $tokens[$next]['code'] !== \T_CLOSE_PARENTHESIS) {
            return;
        }

        $phpcsFile->addError(
            'First class callables using the CallableExpr(...) syntax are not supported in PHP 8.0 or earlier.',
            $stackPtr,
            'Found'
        );
    }
}
