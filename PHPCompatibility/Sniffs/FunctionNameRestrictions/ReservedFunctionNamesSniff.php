<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionNameRestrictions;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\Scopes;

/**
 * All function and method names starting with double underscore are reserved by PHP.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/language.oop5.magic.php
 *
 * @since 8.2.0  This was previously, since 7.0.3, checked by the upstream sniff.
 * @since 9.3.2  The sniff will now ignore functions marked as `@deprecated` by design.
 * @since 10.0.0 The sniff no longer extends the upstream `Generic.NamingConventions.CamelCapsFunctionName`
 *               sniff and has been completely rewritten using PHPCSUtils.
 */
class ReservedFunctionNamesSniff implements Sniff
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
            \T_FUNCTION,
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
        $functionName = FunctionDeclarations::getName($phpcsFile, $stackPtr);
        if (empty($functionName) === true) {
            return;
        }

        if (\preg_match('|^__[^_]|', $functionName) !== 1) {
            // Name doesn't start with double underscore.
            return;
        }

        if ($this->isFunctionDeprecated($phpcsFile, $stackPtr) === true) {
            /*
             * Deprecated functions don't have to comply with the naming conventions,
             * otherwise functions deprecated in favour of a function with a compliant
             * name would still trigger an error.
             */
            return;
        }

        $ooPtr = Scopes::validDirectScope($phpcsFile, $stackPtr, Tokens::$ooScopeTokens);

        /*
         * Check functions declared in the global namespace or in a namespace.
         */
        if ($ooPtr === false) {
            if (FunctionDeclarations::isMagicFunctionName($functionName) === true) {
                return;
            }

            $phpcsFile->addWarning(
                'Function name "%s" is discouraged; PHP has reserved all function names with a double underscore prefix for future use.',
                $stackPtr,
                'FunctionDoubleUnderscore',
                [$functionName]
            );

            return;
        }

        /*
         * Check method declarations.
         */
        if (FunctionDeclarations::isMagicMethodName($functionName) === true) {
            return;
        }

        if (FunctionDeclarations::isPHPDoubleUnderscoreMethod($phpcsFile, $stackPtr) === true) {
            return;
        }

        $className = ObjectDeclarations::getName($phpcsFile, $ooPtr);
        if (empty($className)) {
            $className = '[anonymous class]';
        }

        $phpcsFile->addWarning(
            'Method name "%s" is discouraged; PHP has reserved all method names with a double underscore prefix for future use.',
            $stackPtr,
            'MethodDoubleUnderscore',
            [$className . '::' . $functionName]
        );
    }

    /**
     * Check whether a function has been marked as deprecated via a @deprecated tag
     * in the function docblock.
     *
     * @since 9.3.2
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of a T_FUNCTION
     *                                               token in the stack.
     *
     * @return bool
     */
    private function isFunctionDeprecated(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $ignore                = Tokens::$methodPrefixes;
        $ignore               += Tokens::$phpcsCommentTokens;
        $ignore[\T_WHITESPACE] = \T_WHITESPACE;
        $ignore[\T_COMMENT]    = \T_COMMENT;

        for ($commentEnd = ($stackPtr - 1); $commentEnd >= 0; $commentEnd--) {
            if (isset($ignore[$tokens[$commentEnd]['code']]) === true) {
                continue;
            }

            if ($tokens[$commentEnd]['code'] === \T_ATTRIBUTE_END
                && isset($tokens[$commentEnd]['attribute_opener']) === true
            ) {
                $commentEnd = $tokens[$commentEnd]['attribute_opener'];
                continue;
            }

            break;
        }

        if ($tokens[$commentEnd]['code'] !== \T_DOC_COMMENT_CLOSE_TAG) {
            // Function doesn't have a doc comment or is using the wrong type of comment.
            return false;
        }

        $commentStart = $tokens[$commentEnd]['comment_opener'];
        foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
            if ($tokens[$tag]['content'] === '@deprecated') {
                return true;
            }
        }

        return false;
    }
}
