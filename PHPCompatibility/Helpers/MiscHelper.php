<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Helpers;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Parentheses;

/**
 * Miscellaneous helper functions
 *
 * ---------------------------------------------------------------------------------------------
 * This class is only intended for internal use by PHPCompatibility and is not part of the public API.
 * This also means that it has no promise of backward compatibility. Use at your own risk.
 * ---------------------------------------------------------------------------------------------
 *
 * {@internal The functionality in this class will likely be replaced at some point in
 * the future by functions from PHPCSUtils.}
 *
 * @since 10.0.0 These functions were moved from the generic `Sniff` class to this class.
 */
final class MiscHelper
{

    /**
     * Determine whether an arbitrary T_STRING or T_NAME_FULLY_QUALIFIED token is the use of a global constant.
     *
     * @since 8.1.0
     * @since 10.0.0 This method is now static.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the T_STRING token.
     *
     * @return bool
     */
    public static function isUseOfGlobalConstant(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        // Is this one of the tokens this function handles ?
        if ($tokens[$stackPtr]['code'] !== \T_STRING
            && $tokens[$stackPtr]['code'] !== \T_NAME_FULLY_QUALIFIED
        ) {
            return false;
        }

        if ($tokens[$stackPtr]['code'] === \T_NAME_FULLY_QUALIFIED
            && \strpos($tokens[$stackPtr]['content'], '\\', 1) !== false
        ) {
            // This is a fully qualified name with a namespace, not for the global namespace.
            return false;
        }

        // Handle things within attributes.
        if (isset($tokens[$stackPtr]['attribute_opener'], $tokens[$stackPtr]['attribute_closer']) === true) {
            $attributeIsNestedInParentheses = Parentheses::getLastOpener($phpcsFile, $tokens[$stackPtr]['attribute_opener']);
            $constantIsNestedInParentheses  = Parentheses::getLastOpener($phpcsFile, $stackPtr);

            // Check if the same parenthesis level applies.
            if ($attributeIsNestedInParentheses === $constantIsNestedInParentheses) {
                // Attribute name, not part of a parameter.
                return false;
            }

            return true;
        }

        // Ignore exception names in catch structures.
        if (Parentheses::lastOwnerIn($phpcsFile, $stackPtr, [\T_CATCH]) !== false) {
            return false;
        }

        $next = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($next !== false
            && ($tokens[$next]['code'] === \T_OPEN_PARENTHESIS
                || $tokens[$next]['code'] === \T_DOUBLE_COLON
                || $tokens[$next]['code'] === \T_EQUAL
                || $tokens[$next]['code'] === \T_TYPE_UNION
                || $tokens[$next]['code'] === \T_TYPE_INTERSECTION
                || $tokens[$next]['code'] === \T_TYPE_CLOSE_PARENTHESIS
                || $tokens[$next]['code'] === \T_VARIABLE)
        ) {
            // Function call, function declaration, type declaration or constant/property assignment.
            return false;
        }

        $prev = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($tokens[$prev]['code'] === \T_NS_SEPARATOR) {
            $prevPrev = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prev - 1), null, true);
            if ($tokens[$prevPrev]['code'] === \T_STRING
                || $tokens[$prevPrev]['code'] === \T_NAMESPACE
            ) {
                // Namespaced constant on PHPCS 3.x.
                return false;
            }

            // If not a namespaced constant, skip over the NS separator when looking at the "previous" token.
            $prev = $prevPrev;
        }

        // Array of tokens which if found preceding the $stackPtr indicate that a T_STRING is not a global constant.
        $tokensToIgnore  = [
            \T_NAMESPACE             => true,
            \T_USE                   => true,
            \T_EXTENDS               => true,
            \T_IMPLEMENTS            => true,
            \T_NEW                   => true,
            \T_INSTANCEOF            => true,
            \T_INSTEADOF             => true,
            \T_GOTO                  => true,
            \T_AS                    => true,
            \T_CONST                 => true,
            \T_NULLABLE              => true,
            \T_TYPE_UNION            => true,
            \T_TYPE_INTERSECTION     => true,
            \T_TYPE_OPEN_PARENTHESIS => true,
        ];
        $tokensToIgnore += Tokens::$ooScopeTokens;
        $tokensToIgnore += Collections::objectOperators();
        $tokensToIgnore += Tokens::$scopeModifiers;

        if (isset($tokensToIgnore[$tokens[$prev]['code']]) === true) {
            // Not the use of a constant.
            return false;
        }

        // Handle plain return types.
        if ($tokens[$prev]['code'] === \T_COLON) {
            if ($tokens[$next]['code'] === \T_OPEN_CURLY_BRACKET
                && isset($tokens[$next]['scope_condition'])
            ) {
                // Return type declaration.
                return false;
            }

            if ($tokens[$next]['code'] === \T_SEMICOLON) {
                $prevPrev = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prev - 1), null, true);
                if ($tokens[$prevPrev]['code'] === \T_CLOSE_PARENTHESIS
                    && isset($tokens[$prevPrev]['parenthesis_owner'])
                ) {
                    // Return type declaration.
                    return false;
                }
            }
        }

        /*
         * Deal with a number of variations of use statements.
         */
        $find                   = [
            \T_SEMICOLON,
            \T_OPEN_TAG,
            \T_OPEN_TAG_WITH_ECHO,
            \T_OPEN_CURLY_BRACKET,
            \T_OPEN_SQUARE_BRACKET,
            \T_OPEN_PARENTHESIS,
        ];
        $endOfPreviousStatement = $phpcsFile->findPrevious($find, ($stackPtr - 1));
        $startOfThisStatement   = $phpcsFile->findNext(Tokens::$emptyTokens, ($endOfPreviousStatement + 1), null, true);

        if ($tokens[$startOfThisStatement]['code'] === \T_USE) {
            $nextOnLine = $phpcsFile->findNext(Tokens::$emptyTokens, ($startOfThisStatement + 1), null, true);
            if ($nextOnLine !== false) {
                if (($tokens[$nextOnLine]['code'] === \T_STRING && $tokens[$nextOnLine]['content'] === 'const')) {
                    $hasNsSep = $phpcsFile->findNext(\T_NS_SEPARATOR, ($nextOnLine + 1), $stackPtr);
                    if ($hasNsSep !== false) {
                        // Namespaced const (group) use statement.
                        return false;
                    }
                } else {
                    // Not a const use statement.
                    return false;
                }
            }
        }

        return true;
    }
}
