<?php
/**
 * \PHPCompatibility\Sniffs\Classes\NewLateStaticBindingSniff.
 *
 * PHP version 5.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\Classes;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * \PHPCompatibility\Sniffs\Classes\NewLateStaticBindingSniff.
 *
 * PHP version 5.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewLateStaticBindingSniff extends Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STATIC);
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        if ($nextNonEmpty === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        if ($tokens[$nextNonEmpty]['code'] !== T_DOUBLE_COLON) {
            return;
        }

        $inClass = $this->inClassScope($phpcsFile, $stackPtr, false);

        if ($inClass === true && $this->supportsBelow('5.2') === true) {
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
