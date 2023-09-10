<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Constants;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;

/**
 * Detect usage of the magic `::class` constant introduced in PHP 5.5.
 *
 * The special `ClassName::class` constant is available as of PHP 5.5.0, and allows
 * for fully qualified class name resolution at compile time.
 *
 * As of PHP 8.0, `::class` can also be used on objects.
 *
 * PHP version 5.5
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/class_name_scalars
 * @link https://www.php.net/manual/en/language.oop5.constants.php#example-186
 * @link https://wiki.php.net/rfc/class_name_literal_on_object
 *
 * @since 7.1.4
 * @since 7.1.5  Removed the incorrect checks against invalid usage of the constant.
 * @since 10.0.0 Now differentiates between Name::class (PHP 5.5+) and $obj::class (PHP 8.0+).
 */
class NewMagicClassConstantSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.1.4
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_STRING];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.1.4
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('7.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if (\strtolower($tokens[$stackPtr]['content']) !== 'class') {
            return;
        }

        $nextToken = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextToken !== false && $tokens[$nextToken]['code'] === \T_OPEN_PARENTHESIS) {
            // Function call or declaration for a function called "class".
            return;
        }

        $prevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($tokens[$prevToken]['code'] !== \T_DOUBLE_COLON) {
            return;
        }

        $subjectPtr = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prevToken - 1), null, true);
        if ($subjectPtr === false) {
            // Shouldn't be possible.
            return; // @codeCoverageIgnore
        }

        $preSubjectPtr = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($subjectPtr - 1), null, true);
        if (isset(Collections::ooHierarchyKeywords()[$tokens[$subjectPtr]['code']]) === true
            || ($tokens[$subjectPtr]['code'] === \T_STRING
                && isset(Collections::objectOperators()[$tokens[$preSubjectPtr]['code']]) === false)
        ) {
            // This is a syntax which is supported on PHP 5.5 and higher.
            if (ScannedCode::shouldRunOnOrBelow('5.4') === true) {
                $phpcsFile->addError(
                    'The magic class constant ClassName::class was not available in PHP 5.4 or earlier',
                    $stackPtr,
                    'Found'
                );
            }

            return;
        }

        /*
         * This syntax was not supported until PHP 8.0.
         *
         * Includes throwing an error for syntaxes which are still not supported, as differentiating
         * between them would be hard, if not impossible, and cause more overhead than it's worth.
         */
        $phpcsFile->addError(
            'Using the magic class constant ::class with dynamic class names is not supported in PHP 7.4 or earlier',
            $stackPtr,
            'UsedOnObject'
        );
    }
}
