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
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;

/**
 * Catching exceptions without capturing them to a variable is allowed since PHP 8.0.
 *
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/non-capturing_catches
 * @link https://www.php.net/manual/en/language.exceptions.php#language.exceptions.catch
 *
 * @since 10.0.0
 */
class NewNonCapturingCatchSniff extends Sniff
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
        return [\T_CATCH];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('7.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $token  = $tokens[$stackPtr];

        // Bow out during live coding.
        if (isset($token['parenthesis_opener'], $token['parenthesis_closer']) === false) {
            return;
        }

        $lastNonEmptyToken = $phpcsFile->findPrevious(
            Tokens::$emptyTokens,
            ($token['parenthesis_closer'] - 1),
            $token['parenthesis_opener'],
            true
        );

        if ($tokens[$lastNonEmptyToken]['code'] === \T_VARIABLE) {
            return;
        }

        $phpcsFile->addError(
            'Catching exceptions without capturing them to a variable is not supported in PHP 7.4 or earlier.',
            $stackPtr,
            'Found'
        );
    }
}
