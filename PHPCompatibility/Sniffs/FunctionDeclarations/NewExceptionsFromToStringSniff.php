<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * As of PHP 7.4, throwing exceptions from a `__toString()` method is allowed.
 *
 * PHP version 7.4
 *
 * @link https://wiki.php.net/rfc/tostring_exceptions
 * @link https://www.php.net/manual/en/language.oop5.magic.php#object.tostring
 *
 * @since 9.2.0
 * @since 10.0.0 This class is now `final`.
 */
final class NewExceptionsFromToStringSniff extends Sniff
{

    /**
     * Tokens which should be ignored when they preface a function declaration
     * when trying to find the docblock (if any).
     *
     * Array will be added to in the register() method.
     *
     * @since 9.3.0
     *
     * @var array<int|string, int|string>
     */
    private $docblockIgnoreTokens = [
        \T_WHITESPACE => \T_WHITESPACE,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.2.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        // Enhance the array of tokens to ignore for finding the docblock.
        $this->docblockIgnoreTokens += Tokens::$methodPrefixes;
        $this->docblockIgnoreTokens += Tokens::$phpcsCommentTokens;

        return Tokens::$ooScopeTokens;
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('7.3') === false) {
            return;
        }

        $ooMethods   = ObjectDeclarations::getDeclaredMethods($phpcsFile, $stackPtr);
        $ooMethodsLC = \array_change_key_case($ooMethods, \CASE_LOWER);

        if (isset($ooMethodsLC['__tostring']) === false) {
            // OO construct doesn't declare a `__tostring()` method.
            return;
        }

        $tokens              = $phpcsFile->getTokens();
        $toStringFunctionPtr = $ooMethodsLC['__tostring'];

        if (isset($tokens[$toStringFunctionPtr]['scope_opener'], $tokens[$toStringFunctionPtr]['scope_closer']) === false) {
            // Abstract function, interface function, live coding or parse error.
            return;
        }

        /*
         * Examine the content of the function.
         */
        $error       = 'Throwing exceptions from __toString() was not allowed prior to PHP 7.4';
        $throwPtr    = $tokens[$toStringFunctionPtr]['scope_opener'];
        $errorThrown = false;

        do {
            $throwPtr = $phpcsFile->findNext([\T_THROW, \T_TRY], ($throwPtr + 1), $tokens[$toStringFunctionPtr]['scope_closer']);
            if ($throwPtr === false) {
                break;
            }

            if ($tokens[$throwPtr]['code'] === \T_TRY
                && isset($tokens[$throwPtr]['scope_closer']) === true
            ) {
                // Skip over the try part of try/catch statements.
                $throwPtr = $tokens[$throwPtr]['scope_closer'];
                continue;
            }

            $phpcsFile->addError($error, $throwPtr, 'Found');
            $errorThrown = true;

        } while (true);

        if ($errorThrown === true) {
            // We've already thrown an error for this method, no need to examine the docblock.
            return;
        }

        /*
         * Check whether the function has a docblock and if so, whether it contains a @throws tag.
         *
         * {@internal This can be partially replaced by the findCommentAboveFunction()
         *            utility function in due time.}
         */
        $commentEnd = $phpcsFile->findPrevious($this->docblockIgnoreTokens, ($toStringFunctionPtr - 1), null, true);
        if ($commentEnd === false || $tokens[$commentEnd]['code'] !== \T_DOC_COMMENT_CLOSE_TAG) {
            return;
        }

        $commentStart = $tokens[$commentEnd]['comment_opener'];
        foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
            if ($tokens[$tag]['content'] !== '@throws') {
                continue;
            }

            // Found a throws tag.
            $phpcsFile->addError($error, $toStringFunctionPtr, 'ThrowsTagFoundInDocblock');
            break;
        }
    }
}
