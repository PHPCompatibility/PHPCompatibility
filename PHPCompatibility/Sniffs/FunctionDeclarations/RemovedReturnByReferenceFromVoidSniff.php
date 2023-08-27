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

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
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
     * @return array<int|string>
     */
    public function register()
    {
        return Collections::functionDeclarationTokens();
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
        if (ScannedCode::shouldRunOnOrAbove('8.1') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextNonEmpty === false || $tokens[$nextNonEmpty]['code'] !== \T_BITWISE_AND) {
            // Not a function declared to return by reference.
            return;
        }

        $functionProps = FunctionDeclarations::getProperties($phpcsFile, $stackPtr);

        if (\strtolower($functionProps['return_type']) !== 'void') {
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
