<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Syntax;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;

/**
 * Class constants can be accessed dynamically using the C::{$name} syntax.
 *
 * PHP version 8.3
 *
 * @link https://www.php.net/releases/8.3/en.php#dynamic_class_constant_fetch
 * @link https://wiki.php.net/rfc/dynamic_class_constant_fetch
 *
 * @since 10.0.0
 */
final class NewDynamicClassConstantFetchSniff extends Sniff
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
            \T_DOUBLE_COLON,
        ];
    }

    /**
     * Processes this test when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (!ScannedCode::shouldRunOnOrBelow('8.2')) {
            return;
        }

        $tokens       = $phpcsFile->getTokens();
        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true);
        if ($nextNonEmpty === false) {
            return;
        }

        if ($tokens[$nextNonEmpty]['code'] !== \T_OPEN_CURLY_BRACKET) {
            return;
        }

        $phpcsFile->addError(
            'Dynamic class constant fetch is not available in PHP 8.2 or earlier.',
            $nextNonEmpty,
            'Found'
        );
    }
}
