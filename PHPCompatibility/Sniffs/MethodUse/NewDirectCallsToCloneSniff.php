<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\MethodUse;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;

/**
 * Detect direct calls to the `__clone()` magic method, which is allowed since PHP 7.0.
 *
 * "Doing calls like `$obj->__clone()` is now allowed. This was the only magic method
 *  that had a compile-time check preventing some calls to it, which doesn't make sense.
 *  If we allow all other magic methods to be called, there's no reason to forbid this one."
 *
 * PHP version 7.0
 *
 * @link https://wiki.php.net/rfc/abstract_syntax_tree#directly_calling_clone_is_allowed
 * @link https://www.php.net/manual/en/language.oop5.cloning.php
 *
 * @since 9.1.0
 */
class NewDirectCallsToCloneSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.1.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return Collections::objectOperators();
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.1.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('5.6') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextNonEmpty === false || $tokens[$nextNonEmpty]['code'] !== \T_STRING) {
            /*
             * Not a method call.
             *
             * Note: This disregards method calls with the method name in a variable, like:
             *   $method = '__clone';
             *   $obj->$method();
             * However, that would be very hard to examine reliably anyway.
             */
            return;
        }

        if (\strtolower($tokens[$nextNonEmpty]['content']) !== '__clone') {
            // Not a call to the __clone() method.
            return;
        }

        $nextNextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonEmpty + 1), null, true);
        if ($nextNextNonEmpty === false || $tokens[$nextNextNonEmpty]['code'] !== \T_OPEN_PARENTHESIS) {
            // Not a method call.
            return;
        }

        $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($prevNonEmpty === false
            || isset(Collections::ooHierarchyKeywords()[$tokens[$prevNonEmpty]['code']])
        ) {
            // Class internal call to __clone().
            return;
        }

        $phpcsFile->addError(
            'Direct calls to the __clone() magic method are not allowed in PHP 5.6 or earlier.',
            $nextNonEmpty,
            'Found'
        );
    }
}
