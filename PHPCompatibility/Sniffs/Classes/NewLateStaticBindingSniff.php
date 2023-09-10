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
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\Conditions;

/**
 * Detect use of late static binding as introduced in PHP 5.3.
 *
 * Checks for:
 * - Late static binding as introduced in PHP 5.3.
 * - Late static binding being used outside of class scope (unsupported).
 *
 * PHP version 5.3
 *
 * @link https://www.php.net/manual/en/language.oop5.late-static-bindings.php
 * @link https://wiki.php.net/rfc/lsb_parentself_forwarding
 *
 * @since 7.0.3
 * @since 9.0.0 Renamed from `LateStaticBindingSniff` to `NewLateStaticBindingSniff`.
 */
class NewLateStaticBindingSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.3
     * @since 10.0.0 Now also sniffs for `T_STRING`.
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            \T_STATIC,
            \T_STRING,
        ];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.3
     * @since 10.0.0 Will now also detect LSB with `instanceof static` and `new static`.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        switch ($tokens[$stackPtr]['code']) {
            case \T_STRING:
                // PHPCS changes T_STATIC to T_STRING when used with instanceof.
                if ($tokens[$stackPtr]['content'] !== 'static') {
                    return;
                }

                $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true);
                if ($prevNonEmpty === false
                    || $tokens[$prevNonEmpty]['code'] !== \T_INSTANCEOF
                ) {
                    return;
                }

                break;

            case \T_STATIC:
                $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true, null, true);
                if ($nextNonEmpty === false) {
                    return;
                }

                if ($tokens[$nextNonEmpty]['code'] !== \T_DOUBLE_COLON) {
                    $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true);
                    if ($prevNonEmpty === false
                        || $tokens[$prevNonEmpty]['code'] !== \T_NEW
                    ) {
                        return;
                    }
                }

                break;
        }

        $inClass = Conditions::hasCondition($phpcsFile, $stackPtr, Tokens::$ooScopeTokens);

        if ($inClass === true && ScannedCode::shouldRunOnOrBelow('5.2') === true) {
            $phpcsFile->addError(
                'Late static binding is not supported in PHP 5.2 or earlier.',
                $stackPtr,
                'Found'
            );
        }

        if ($inClass === false) {
            $phpcsFile->addError(
                'Late static binding is not supported outside of class scope.',
                $stackPtr,
                'OutsideClassScope'
            );
        }
    }
}
