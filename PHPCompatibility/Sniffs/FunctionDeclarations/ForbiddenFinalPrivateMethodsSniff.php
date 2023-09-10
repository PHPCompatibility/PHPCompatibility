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
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\Scopes;

/**
 * Applying the final modifier on a private method will produce a warning since PHP 8.0
 * unless that method is the constructor.
 *
 * Previously final private methods were allowed and overriding the method in a child class
 * would result in a fatal "Cannot override final method". This was inappropriate as
 * private methods are not inherited.
 *
 * > Due to how common the usage of `final private function __construct` is and given that
 * > the same results cannot be achieved with a protected visibility, an exception to this rule
 * > is made for constructors.
 *
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/inheritance_private_methods
 *
 * @since 10.0.0
 */
class ForbiddenFinalPrivateMethodsSniff extends Sniff
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
        return [\T_FUNCTION];
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
        if (ScannedCode::shouldRunOnOrAbove('8.0') === false) {
            return;
        }

        if (Scopes::isOOMethod($phpcsFile, $stackPtr) === false) {
            // Function, not method.
            return;
        }

        $name = FunctionDeclarations::getName($phpcsFile, $stackPtr);
        if (empty($name) === true) {
            // Parse error or live coding.
            return;
        }

        if (\strtolower($name) === '__construct') {
            // The rule does not apply to constructors. Bow out.
            return;
        }

        $properties = FunctionDeclarations::getProperties($phpcsFile, $stackPtr);
        if ($properties['scope'] !== 'private' || $properties['is_final'] === false) {
            // Not an private final method.
            return;
        }

        $phpcsFile->addWarning(
            'Private methods should not be declared as final since PHP 8.0',
            $stackPtr,
            'Found'
        );
    }
}
