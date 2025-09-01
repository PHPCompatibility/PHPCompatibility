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

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\Namespaces;
use PHPCSUtils\Utils\NamingConventions;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * Detect declarations of PHP 4 style constructors which are deprecated as of PHP 7.0.0
 * and for which support has been removed in PHP 8.0.0.
 *
 * PHP 4 style constructors - methods that have the same name as the class they are defined in -
 * are deprecated as of PHP 7.0.0, and have been removed in PHP 8.0.
 * PHP 7 will emit `E_DEPRECATED` if a PHP 4 constructor is the only constructor defined
 * within a class. Classes that implement a `__construct()` method are unaffected.
 *
 * Note: Methods with the same name as the class they are defined in _within a namespace_
 * are not recognized as constructors anyway and therefore outside the scope of this sniff.
 *
 * PHP version 7.0
 * PHP version 8.0
 *
 * @link https://www.php.net/manual/en/migration70.deprecated.php#migration70.deprecated.php4-constructors
 * @link https://wiki.php.net/rfc/remove_php4_constructors
 * @link https://www.php.net/manual/en/language.oop5.decon.php
 *
 * @since 7.0.0
 * @since 7.0.8  This sniff now throws a warning instead of an error as the functionality is
 *               only deprecated (for now).
 * @since 9.0.0  Renamed from `DeprecatedPHP4StyleConstructorsSniff` to `RemovedPHP4StyleConstructorsSniff`.
 * @since 10.0.0 This class is now `final`.
 */
final class RemovedPHP4StyleConstructorsSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            \T_CLASS,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.0
     * @since 7.0.8 The message is downgraded from error to warning as - for now - support
     *              for PHP4-style constructors is just deprecated, not yet removed.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('7.0') === false || ScannedCode::shouldRunOnOrBelow('7.4') === false) {
            return;
        }

        if (Namespaces::determineNamespace($phpcsFile, $stackPtr) !== '') {
            /*
             * Namespaced methods with the same name as the class are treated as
             * regular methods, so we can bow out if we're in a namespace.
             *
             * Note: the exception to this is PHP 5.3.0-5.3.2. This is currently
             * not dealt with.
             */
            return;
        }

        $className = ObjectDeclarations::getName($phpcsFile, $stackPtr);
        if (empty($className) || \is_string($className) === false) {
            return;
        }

        $ooMethods = ObjectDeclarations::getDeclaredMethods($phpcsFile, $stackPtr);
        if (empty($ooMethods)) {
            // No methods declared in the OO construct at all. Bow out.
            return;
        }

        $ooMethodsLC         = \array_change_key_case($ooMethods, \CASE_LOWER);
        $newConstructorFound = isset($ooMethodsLC['__construct']);

        $oldConstructorFound = false;
        $oldConstructorPos   = -1;
        foreach ($ooMethods as $methodName => $functionPtr) {
            if (NamingConventions::isEqual($methodName, $className) === true) {
                $oldConstructorFound = true;
                $oldConstructorPos   = $functionPtr;
                break;
            }
        }

        if ($newConstructorFound === false && $oldConstructorFound === true) {
            $error   = 'Declaration of a PHP4 style class constructor is deprecated since PHP 7.0';
            $code    = 'Deprecated';
            $isError = false;

            if (ScannedCode::shouldRunOnOrAbove('8.0') === true) {
                $error  .= ' and removed since PHP 8.0';
                $code    = 'Removed';
                $isError = true;
            }

            MessageHelper::addMessage($phpcsFile, $error, $oldConstructorPos, $isError, $code);
        }
    }
}
