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

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Detect OO structures using a single underscore as the class name.
 *
 * The single underscore symbol is now reserved for PHP for future use (in pattern matching).
 *
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/deprecations_php_8_4#deprecate_using_a_single_underscore_as_a_class_name
 *
 * @since 10.0.0
 */
final class ForbiddenClassNameUnderscoreSniff extends Sniff
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
        $targets = Tokens::$ooScopeTokens;
        unset($targets[\T_ANON_CLASS]);

        return $targets;
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
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('8.4') === false) {
            return;
        }

        $name = ObjectDeclarations::getName($phpcsFile, $stackPtr);
        if (empty($name)) {
            // Live coding/parse error.
            return;
        }

        if ($name !== '_') {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $phpcsFile->addWarning(
            'Using a single underscore, "_", as %s name is deprecated since PHP 8.4.',
            $stackPtr,
            'Deprecated',
            [\strtolower($tokens[$stackPtr]['content'])]
        );
    }
}
