<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;

/**
 * Returning by reference from a void function is deprecated since PHP 8.1.
 *
 * PHP version 8.1
 *
 * @link https://wiki.php.net/rfc/deprecations_php_8_1#return_by_reference_with_void_type
 * @link https://www.php.net/manual/en/migration81.deprecated.php#migration81.deprecated.core.void-by-ref
 *
 * @since 10.0.0
 */
class RemovedReturnByReferenceFromVoidSniff extends Sniff
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
        return Collections::functionDeclarationTokensBC();
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
        if ($this->supportsAbove('8.1') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if (isset(Collections::arrowFunctionTokensBC()[$tokens[$stackPtr]['code']])
            && \strtolower($tokens[$stackPtr]['content']) !== 'fn'
        ) {
            // Not a function declaration.
            return;
        }

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextNonEmpty === false
            || ($tokens[$nextNonEmpty]['code'] !== \T_BITWISE_AND
            // Deal with PHP 8.1 tokenization when using PHPCS < 3.6.1.
            && $tokens[$nextNonEmpty]['type'] !== 'T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG'
            && $tokens[$nextNonEmpty]['type'] !== 'T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG')
        ) {
            // Not a function declared to return by reference.
            return;
        }

        try {
            $functionProps = FunctionDeclarations::getProperties($phpcsFile, $stackPtr);
        } catch (RuntimeException $e) {
            // Most likely a T_STRING which wasn't an arrow function.
            return;
        }

        if ($functionProps['return_type'] !== 'void') {
            // Not a void function.
            return;
        }

        $phpcsFile->addWarning(
            'Returning by reference from a void function is deprecated since PHP 8.1.',
            $stackPtr,
            'Deprecated'
        );
    }
}
