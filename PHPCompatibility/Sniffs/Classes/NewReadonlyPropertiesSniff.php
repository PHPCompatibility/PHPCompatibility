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
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Exceptions\ValueError;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\Parentheses;
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
        return Collections::ooPropertyScopes();
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

        $ooProperties   = ObjectDeclarations::getDeclaredProperties($phpcsFile, $stackPtr);
        $endOfStatement = false;
        foreach ($ooProperties as $varToken) {
            if ($endOfStatement !== false && $varToken < $endOfStatement) {
                // Don't throw the same error multiple times for multi-property declarations.
                // Also skip over any other constructor promoted properties.
                continue;
            }

            try {
                $properties = Variables::getMemberProperties($phpcsFile, $varToken);
            } catch (ValueError $e) {
                // This must be constructor property promotion.
                // Ignore for now and skip over any other promoted properties, these will be handled via the constructor.
                $deepestOpen = Parentheses::getLastOpener($phpcsFile, $varToken);
                if ($deepestOpen !== false
                    && $stackPtr < $deepestOpen
                    && Parentheses::isOwnerIn($phpcsFile, $deepestOpen, \T_FUNCTION)
                    && isset($tokens[$deepestOpen]['parenthesis_closer'])
                ) {
                    $endOfStatement = $tokens[$deepestOpen]['parenthesis_closer'];
                }

                continue;
            }

            if ($properties['is_readonly'] === false) {
                // Not a readonly property.
                continue;
            }

            $phpcsFile->addError($error, $varToken, 'Found', [$tokens[$varToken]['content']]);

            $endOfStatement = $phpcsFile->findNext([\T_SEMICOLON, \T_CLOSE_TAG], ($varToken + 1));
        }

        /*
         * Now, let's check for readonly properties in constructor property promotion.
         */
        $ooMethods   = ObjectDeclarations::getDeclaredMethods($phpcsFile, $stackPtr);
        $ooMethodsLC = \array_change_key_case($ooMethods, \CASE_LOWER);

        if (isset($ooMethodsLC['__construct']) === false) {
            // OO construct doesn't declare a `__construct()` method.
            return;
        }

        $parameters = FunctionDeclarations::getParameters($phpcsFile, $ooMethodsLC['__construct']);
        foreach ($parameters as $param) {
            if (empty($param['readonly_token']) === true) {
                // Not property promotion with readonly.
                continue;
            }

            $phpcsFile->addError($error, $param['readonly_token'], 'FoundInConstructor', [$param['name']]);
        }
    }
}
