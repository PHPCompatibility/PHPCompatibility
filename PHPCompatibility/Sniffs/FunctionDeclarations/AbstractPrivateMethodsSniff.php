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
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\Scopes;

/**
 * Abstract private methods are not allowed since PHP 5.1, though they are allowed in traits since PHP 8.0.
 *
 * Abstract private methods were supported between PHP 5.0.0 and PHP 5.0.4, but
 * were then disallowed on the grounds that the behaviours of `private` and `abstract`
 * are mutually exclusive.
 *
 * As of PHP 8.0, traits are allowed to declare abstract private methods.
 *
 * PHP version 5.1
 * PHP version 8.0
 *
 * @link https://www.php.net/manual/en/migration51.oop.php#migration51.oop-methods
 * @link https://wiki.php.net/rfc/abstract_trait_method_validation
 *
 * @since 9.2.0
 * @since 10.0.0 The sniff has been renamed from `PHPCompatibility.Classes.ForbiddenAbstractPrivateMethods`
 *               to `PHPCompatibility.FunctionDeclarations.AbstractPrivateMethods` and now
 *               includes detection of the PHP 8.0 change.
 */
class AbstractPrivateMethodsSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.2.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_FUNCTION];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.2.0
     * @since 10.0.0 New error message for abstract private methods in traits.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('5.1') === false) {
            return;
        }

        $tokens   = $phpcsFile->getTokens();
        $scopePtr = Scopes::validDirectScope($phpcsFile, $stackPtr, Tokens::$ooScopeTokens);
        if ($scopePtr === false) {
            // Function, not method.
            return;
        }

        $properties = FunctionDeclarations::getProperties($phpcsFile, $stackPtr);
        if ($properties['scope'] !== 'private' || $properties['is_abstract'] !== true) {
            // Not an abstract private method.
            return;
        }

        if ($tokens[$scopePtr]['code'] === \T_TRAIT) {
            if (ScannedCode::shouldRunOnOrBelow('7.4') === true) {
                $phpcsFile->addError(
                    'Traits cannot declare "abstract private" methods in PHP 7.4 or below',
                    $stackPtr,
                    'InTrait'
                );
            }

            return;
        }

        // Not a trait.
        $phpcsFile->addError(
            'Abstract methods cannot be declared as private since PHP 5.1',
            $stackPtr,
            'Found'
        );
    }
}
