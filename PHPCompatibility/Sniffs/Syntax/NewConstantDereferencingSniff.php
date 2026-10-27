<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Syntax;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Detect constant dereferencing.
 *
 * As of PHP 5.6, constants can now be dereferenced to access individual
 * elements and characters.
 *
 * As of PHP 8.0, constants can also be dereferenced to invoke method calls,
 * though this support only applies to syntax and must be used in conjunction
 * with the scalar objects extension to work at runtime.
 *
 * PHP version 5.6
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/const_scalar_exprs
 * @link https://github.com/php/php-src/commit/d5ddd2dbb263cd626a5e0f203c00d6f39168507b
 * @link https://wiki.php.net/rfc/variable_syntax_tweaks#constant_dereferencability
 *
 * @since 10.0.0
 */
class NewConstantDereferencingSniff extends Sniff
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
            \T_STRING,
        ];
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
        // Impacted features are fully supported in PHP 8.0 and above, bail early.
        if ($this->supportsBelow('7.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Check if this is a reference.
        $referenceTokens  = [
            \T_OPEN_SQUARE_BRACKET => true,
            \T_OPEN_CURLY_BRACKET  => true,
            \T_OBJECT_OPERATOR     => true,
        ];
        $nextNonEmpty     = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        $nextNonEmptyCode = $tokens[$nextNonEmpty]['code'];
        if (!isset($referenceTokens[$nextNonEmptyCode])
            || ($nextNonEmptyCode === T_OPEN_CURLY_BRACKET
            && isset($tokens[$nextNonEmpty]['scope_opener']) === true)
        ) {
            return;
        }

        // Check if this T_STRING is a constant.
        $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($tokens[$prevNonEmpty]['code'] === \T_OBJECT_OPERATOR) {
            return;
        }

        // PHP 5.5 and below does not support array dereferencing of constants.
        $openBracketTokens = [
            \T_OPEN_SQUARE_BRACKET => true,
            \T_OPEN_CURLY_BRACKET  => true,
        ];
        if ($this->supportsBelow('5.5') === true
            && isset($openBracketTokens[$nextNonEmptyCode])
        ) {
            $phpcsFile->addError(
                'Array dereferencing of constants is not present in PHP version 5.5 or earlier',
                $stackPtr,
                'ArrayDereferencing'
            );
            return;
        }

        // PHP 7.4 and below does not support object dereferencing of constants.
        if ($this->supportsBelow('7.4') === true
            && $nextNonEmptyCode === \T_OBJECT_OPERATOR
        ) {
            $phpcsFile->addError(
                'Object dereferencing of constants is not present in PHP version 7.4 or earlier',
                $stackPtr,
                'ObjectDereferencing'
            );
        }
    }
}
