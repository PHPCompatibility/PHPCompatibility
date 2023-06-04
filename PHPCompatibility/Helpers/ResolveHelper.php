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
use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\Namespaces;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * Helper functions to figure out the fully qualified name/namespace of a token.
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
final class ResolveHelper
{

    /**
     * Returns the fully qualified class name for a new class instantiation.
     *
     * Returns an empty string if the class name could not be reliably inferred.
     *
     * @since 7.0.3
     * @since 10.0.0 This method is now static.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of a T_NEW token.
     *
     * @return string
     */
    public static function getFQClassNameFromNewToken(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token and that an accepted token.
        if (isset($tokens[$stackPtr]) === false || $tokens[$stackPtr]['code'] !== \T_NEW) {
            return '';
        }

        $start = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true);
        if ($start === false
            || $tokens[$start]['code'] === \T_VARIABLE
            || $tokens[$start]['code'] === \T_ANON_CLASS
        ) {
            // Parse error, name cannot be determined or not a named class.
            return '';
        }

        $find                = Collections::namespacedNameTokens();
        $find[\T_WHITESPACE] = \T_WHITESPACE;

        $end       = $phpcsFile->findNext($find, ($start + 1), null, true);
        $className = GetTokensAsString::noEmpties($phpcsFile, $start, ($end - 1));
        $className = \trim($className);

        return self::getFQName($phpcsFile, $stackPtr, $className);
    }

    /**
     * Returns the fully qualified name of the class that the specified class extends.
     *
     * Returns an empty string if the class does not extend another class or if
     * the class name could not be reliably inferred.
     *
     * @since 7.0.3
     * @since 10.0.0 - This method is now static.
     *               - The method no longer accepts interface tokens.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of a T_CLASS token.
     *
     * @return string
     */
    public static function getFQExtendedClassName(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token and that it's one of the accepted tokens.
        if (isset($tokens[$stackPtr]) === false
            || ($tokens[$stackPtr]['code'] !== \T_CLASS
            && $tokens[$stackPtr]['code'] !== \T_ANON_CLASS)
        ) {
            return '';
        }

        $extends = ObjectDeclarations::findExtendedClassName($phpcsFile, $stackPtr);
        if (empty($extends)) {
            return '';
        }

        return self::getFQName($phpcsFile, $stackPtr, $extends);
    }

    /**
     * Returns the class name for the static usage of a class.
     * This can be a call to a method, the use of a property or constant.
     *
     * Returns an empty string if the class name could not be reliably inferred.
     *
     * @since 7.0.3
     * @since 10.0.0 This method is now static.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of a T_NEW token.
     *
     * @return string
     */
    public static function getFQClassNameFromDoubleColonToken(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== \T_DOUBLE_COLON) {
            return '';
        }

        // Nothing to do if previous token is a variable as we don't know where it was defined.
        if ($tokens[$stackPtr - 1]['code'] === \T_VARIABLE) {
            return '';
        }

        // Nothing to do if 'parent' or 'static' as we don't know how far the class tree extends.
        if (\in_array($tokens[$stackPtr - 1]['code'], [\T_PARENT, \T_STATIC], true)) {
            return '';
        }

        // Get the classname from the class declaration if self is used.
        if ($tokens[$stackPtr - 1]['code'] === \T_SELF) {
            $classDeclarationPtr = $phpcsFile->findPrevious(\T_CLASS, $stackPtr - 1);
            if ($classDeclarationPtr === false) {
                return '';
            }
            $className = $phpcsFile->getDeclarationName($classDeclarationPtr);
            return self::getFQName($phpcsFile, $classDeclarationPtr, $className);
        }

        $find = [
            \T_NS_SEPARATOR,
            \T_STRING,
            \T_NAMESPACE,
            \T_WHITESPACE,
        ];

        $start = $phpcsFile->findPrevious($find, $stackPtr - 1, null, true, null, true);
        if ($start === false || isset($tokens[($start + 1)]) === false) {
            return '';
        }

        $start     = ($start + 1);
        $className = $phpcsFile->getTokensAsString($start, ($stackPtr - $start));
        $className = \trim($className);

        return self::getFQName($phpcsFile, $stackPtr, $className);
    }

    /**
     * Get the Fully Qualified name for a class/function/constant etc.
     *
     * Checks if a class/function/constant name is already fully qualified and
     * if not, enriches it with the relevant namespace information.
     *
     * @since 7.0.3
     * @since 10.0.0 This method is now static.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the token.
     * @param string                      $name      The class / function / constant name.
     *
     * @return string
     */
    public static function getFQName(File $phpcsFile, $stackPtr, $name)
    {
        if (\strpos($name, '\\') === 0) {
            // Already fully qualified.
            return $name;
        }

        // Remove the namespace keyword if used.
        if (\strpos($name, 'namespace\\') === 0) {
            $name = \substr($name, 10);
        }

        $namespace = Namespaces::determineNamespace($phpcsFile, $stackPtr);

        if ($namespace === '') {
            return '\\' . $name;
        } else {
            return '\\' . $namespace . '\\' . $name;
        }
    }
}
