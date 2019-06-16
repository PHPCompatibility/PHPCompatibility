<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2019 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;

/**
 * As of PHP 7.4, throwing exceptions from a __toString() method is allowed.
 *
 * @link https://wiki.php.net/rfc/tostring_exceptions
 *
 * PHP version 7.4
 *
 * @since 9.2.0
 */
class NewExceptionsFromToStringSniff extends Sniff
{

    /**
     * Valid scopes for the __toString() method to live in.
     *
     * @since 9.2.0
     *
     * @var array
     */
    public $ooScopeTokens = array(
        'T_CLASS'      => true,
        'T_TRAIT'      => true,
        'T_ANON_CLASS' => true,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.2.0
     *
     * @return array
     */
    public function register()
    {
        return array(\T_FUNCTION);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.2.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('7.3') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        if (isset($tokens[$stackPtr]['scope_opener'], $tokens[$stackPtr]['scope_closer']) === false) {
            // Abstract function, interface function, live coding or parse error.
            return;
        }

        $functionName = $phpcsFile->getDeclarationName($stackPtr);
        if (strtolower($functionName) !== '__tostring') {
            // Not the right function.
            return;
        }

        if ($this->validDirectScope($phpcsFile, $stackPtr, $this->ooScopeTokens) === false) {
            // Function, not method.
            return;
        }

        $hasThrow = $phpcsFile->findNext(\T_THROW, ($tokens[$stackPtr]['scope_opener'] + 1), $tokens[$stackPtr]['scope_closer']);

        if ($hasThrow === false) {
            // No exception is being thrown.
            return;
        }

        $phpcsFile->addError(
            'Throwing exceptions from __toString() was not allowed prior to PHP 7.4',
            $hasThrow,
            'Found'
        );
    }
}
