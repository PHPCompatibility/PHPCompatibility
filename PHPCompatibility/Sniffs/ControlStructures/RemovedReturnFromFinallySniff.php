<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Tokens\Collections;

/**
 * Including a return statement in a finally block is deprecated since PHP 8.6.
 *
 * PHP version 8.6
 *
 * @link https://wiki.php.net/rfc/deprecations_php_8_6#deprecate_returning_from_a_finally_block
 * @link https://www.php.net/language.exceptions#language.exceptions.finally
 *
 * @since 10.0.0
 */
final class RemovedReturnFromFinallySniff extends Sniff
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
            \T_FINALLY,
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
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('8.6') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_opener'], $tokens[$stackPtr]['scope_closer']) === false) {
            return;
        }

        $closedScopes = Collections::closedScopes();

        for ($i = ($tokens[$stackPtr]['scope_opener'] + 1); $i < $tokens[$stackPtr]['scope_closer']; $i++) {
            if (isset($closedScopes[$tokens[$i]['code']]) === true
                && isset($tokens[$i]['scope_closer']) === true
            ) {
                $i = $tokens[$i]['scope_closer'];
                continue;
            }

            if ($tokens[$i]['code'] !== \T_RETURN) {
                continue;
            }

            $fix = $phpcsFile->addWarning(
                'Passing a return value from a finally block is deprecated since PHP 8.6.',
                $i,
                'Deprecated'
            );
        }
    }
}
