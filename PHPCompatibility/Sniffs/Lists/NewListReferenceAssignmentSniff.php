<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Lists;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Lists;

/**
 * Detect reference assignments in array destructuring using (short) list.
 *
 * PHP version 7.3
 *
 * @link https://www.php.net/manual/en/migration73.new-features.php#migration73.new-features.core.destruct-reference
 * @link https://wiki.php.net/rfc/list_reference_assignment
 * @link https://www.php.net/manual/en/function.list.php
 *
 * @since 9.0.0
 * @since 10.0.0 Complete rewrite. No longer extends the `NewKeyedListSniff`.
 *               Now extends the base `Sniff` class.
 */
class NewListReferenceAssignmentSniff extends Sniff
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
        return Collections::listOpenTokensBC();
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
        if (ScannedCode::shouldRunOnOrBelow('7.2') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        if (isset(Collections::shortArrayListOpenTokensBC()[$tokens[$stackPtr]['code']]) === true
            && Lists::isShortList($phpcsFile, $stackPtr) === false
        ) {
            // Real square brackets or short array, not short list.
            return;
        }

        try {
            $assignments = Lists::getAssignments($phpcsFile, $stackPtr);
        } catch (RuntimeException $e) {
            // Parse error/live coding.
            return;
        }

        foreach ($assignments as $assign) {
            if ($assign['assign_by_reference'] === false) {
                continue;
            }

            $phpcsFile->addError(
                'Reference assignments within list constructs are not supported in PHP 7.2 or earlier.',
                $assign['assignment_token'],
                'Found'
            );
        }
    }
}
