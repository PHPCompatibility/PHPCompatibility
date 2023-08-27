<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\Scopes;
use PHPCSUtils\Utils\Variables;

/**
 * Detects declarations of readonly properties, as introduced in PHP 8.1.
 *
 * PHP version 8.1
 *
 * @link https://www.php.net/manual/en/migration81.new-features.php#migration81.new-features.core.readonly
 * @link https://wiki.php.net/rfc/readonly_properties_v2
 *
 * @since 10.0.0
 */
final class NewReadonlyPropertiesSniff extends Sniff
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
            \T_VARIABLE,
            \T_FUNCTION,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('8.0') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $error  = 'Readonly properties are not supported in PHP 8.0 or earlier. Property %s was declared as readonly.';

        if ($tokens[$stackPtr]['code'] === \T_VARIABLE) {
            if (Scopes::isOOProperty($phpcsFile, $stackPtr) === false) {
                // Not a class property.
                return;
            }

            $properties = Variables::getMemberProperties($phpcsFile, $stackPtr);
            if ($properties['is_readonly'] === false) {
                // Not a readonly property.
                return;
            }

            $phpcsFile->addError($error, $stackPtr, 'Found', [$tokens[$stackPtr]['content']]);

            $endOfStatement = $phpcsFile->findNext(\T_SEMICOLON, ($stackPtr + 1));
            if ($endOfStatement !== false) {
                // Don't throw the same error multiple times for multi-property declarations.
                return ($endOfStatement + 1);
            }

            return;
        }

        /*
         * This must be a function declaration. Let's check for constructor property promotion.
         */
        if (Scopes::isOOMethod($phpcsFile, $stackPtr) === false) {
            // Global function.
            return;
        }

        $functionName = FunctionDeclarations::getName($phpcsFile, $stackPtr);
        if (\strtolower($functionName) !== '__construct') {
            // Not a class constructor.
            return;
        }

        $parameters = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        foreach ($parameters as $param) {
            if (empty($param['readonly_token']) === true) {
                // Not property promotion with readonly.
                continue;
            }

            $phpcsFile->addError($error, $param['readonly_token'], 'FoundInConstructor', [$param['name']]);
        }
    }
}
