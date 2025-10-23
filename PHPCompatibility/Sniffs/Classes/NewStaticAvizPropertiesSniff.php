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
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\Parentheses;
use PHPCSUtils\Utils\Variables;

/**
 * Detects declarations of asymmetric visibility for static properties, as introduced in PHP 8.5.
 *
 * PHP version 8.5
 *
 * {@internal Asymmetric visibility in general, as introduced in PHP 8.4, is detected via the NewKeywords sniff.}
 *
 * @link https://wiki.php.net/rfc/static-aviz
 *
 * @since 10.0.0
 */
final class NewStaticAvizPropertiesSniff extends Sniff
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
        if (ScannedCode::shouldRunOnOrBelow('8.4') === false) {
            return;
        }

        $ooProperties = ObjectDeclarations::getDeclaredProperties($phpcsFile, $stackPtr);
        if (empty($ooProperties)) {
            // No properties, no problem.
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $error  = 'Asymmetric visibility on static properties is not supported in PHP 8.4 or earlier.';

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
                // Ignore as static properties cannot be defined as promoted properties.
                // Skip over the rest of the constructor arguments.
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

            if ($properties['is_static'] === false || $properties['set_scope'] === false) {
                // Not a static property with asymmetric visbility.
                continue;
            }

            $phpcsFile->addError($error, $varToken, 'Found', [$tokens[$varToken]['content']]);

            $endOfStatement = $phpcsFile->findNext([\T_SEMICOLON, \T_CLOSE_TAG], ($varToken + 1));
        }
    }
}
