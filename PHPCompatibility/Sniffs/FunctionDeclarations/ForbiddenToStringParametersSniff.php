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
 * As of PHP 5.3, the __toString() magic method can no longer accept arguments.
 *
 * Sister-sniff to `PHPCompatibility.MethodUse.ForbiddenToStringParameters`.
 *
 * PHP version 5.3
 *
 * @link https://www.php.net/manual/en/migration53.incompatible.php
 * @link https://www.php.net/manual/en/language.oop5.magic.php#object.tostring
 *
 * @since 9.2.0
 */
class ForbiddenToStringParametersSniff extends Sniff
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
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('5.3') === false) {
            return;
        }

        $functionName = FunctionDeclarations::getName($phpcsFile, $stackPtr);
        if (\strtolower($functionName) !== '__tostring') {
            // Not the right function.
            return;
        }

        if (Scopes::isOOMethod($phpcsFile, $stackPtr) === false) {
            // Function, not method.
            return;
        }

        $params = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        if (empty($params)) {
            // Function declared without parameters.
            return;
        }

        $phpcsFile->addError(
            'The __toString() magic method can no longer accept arguments since PHP 5.3',
            $stackPtr,
            'Declared'
        );
    }
}
