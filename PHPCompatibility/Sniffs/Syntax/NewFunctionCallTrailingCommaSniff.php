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

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;

/**
 * Detect trailing commas in function calls, `isset()` and `unset()` as allowed since PHP 7.3.
 *
 * As of PHP 8.4, exit/die are now treated as function calls, which means they now also support
 * trailing commas.
 *
 * PHP version 7.3
 * PHP version 8.4
 *
 * @link https://www.php.net/manual/en/migration73.new-features.php#migration73.new-features.core.trailing-commas
 * @link https://wiki.php.net/rfc/trailing-comma-function-calls
 * @link https://wiki.php.net/rfc/exit-as-function
 *
 * @since 8.2.0
 * @since 9.0.0  Renamed from `NewTrailingCommaSniff` to `NewFunctionCallTrailingCommaSniff`.
 * @since 10.0.0 This class is now `final`.
 */
final class NewFunctionCallTrailingCommaSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.2.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        $targets           = Collections::functionCallTokens();
        $targets[\T_ISSET] = \T_ISSET;
        $targets[\T_UNSET] = \T_UNSET;

        return $targets;
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('8.3') === false) {
            // Trailing commas are supported in both function calls + exit on PHP 8.4 and higher.
            return;
        }

        $tokens = $phpcsFile->getTokens();
        if ($tokens[$stackPtr]['code'] !== \T_EXIT
            && ScannedCode::shouldRunOnOrBelow('7.2') === false
        ) {
            // Not an exit expression, so we only need run for PHP 7.2 and lower.
            return;
        }

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($tokens[$nextNonEmpty]['code'] !== \T_OPEN_PARENTHESIS
            || isset($tokens[$nextNonEmpty]['parenthesis_closer']) === false
        ) {
            return;
        }

        if (($tokens[$stackPtr]['code'] === \T_STRING
            || isset(Collections::ooHierarchyKeywords()[$tokens[$stackPtr]['code']]))
                && isset($tokens[$nextNonEmpty]['parenthesis_owner']) === true
        ) {
            // Function declaration, not a function call.
            return;
        }

        $closer            = $tokens[$nextNonEmpty]['parenthesis_closer'];
        $lastInParenthesis = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($closer - 1), $nextNonEmpty, true);

        if ($tokens[$lastInParenthesis]['code'] !== \T_COMMA) {
            return;
        }

        $message = 'Trailing commas are not allowed in %s in PHP 7.2 or earlier';
        $data    = [];
        switch ($tokens[$stackPtr]['code']) {
            case \T_ISSET:
                $data[]    = 'calls to isset()';
                $errorCode = 'FoundInIsset';
                break;

            case \T_UNSET:
                $data[]    = 'calls to unset()';
                $errorCode = 'FoundInUnset';
                break;

            case \T_EXIT:
                $message   = 'Trailing commas are not allowed in %s in PHP 8.3 or earlier';
                $data[]    = 'calls to exit() or die()';
                $errorCode = 'FoundInExitDie';
                break;

            default:
                $data[]    = 'function calls';
                $errorCode = 'FoundInFunctionCall';
                break;
        }

        $phpcsFile->addError($message, $lastInParenthesis, $errorCode, $data);
    }
}
