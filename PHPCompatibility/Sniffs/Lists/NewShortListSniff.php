<?php
/**
 * \PHPCompatibility\Sniffs\Lists\NewShortListSniff.
 *
 * PHP version 7.1
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\Lists;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\Lists\NewShortListSniff.
 *
 * "The shorthand array syntax ([]) may now be used to destructure arrays for
 * assignments (including within foreach), as an alternative to the existing
 * list() syntax, which is still supported."
 *
 * PHP version 7.1
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewShortListSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(\T_OPEN_SHORT_ARRAY);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('7.0') === false) {
            return;
        }

        if ($this->isShortList($phpcsFile, $stackPtr) === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $closer = $tokens[$stackPtr]['bracket_closer'];

        $hasVariable = $phpcsFile->findNext(\T_VARIABLE, ($stackPtr + 1), $closer);
        if ($hasVariable === false) {
            // List syntax is only valid if there are variables in it.
            return;
        }

        $phpcsFile->addError(
            'The shorthand list syntax "[]" to destructure arrays is not available in PHP 7.0 or earlier.',
            $stackPtr,
            'Found'
        );

        return ($closer + 1);
    }
}
