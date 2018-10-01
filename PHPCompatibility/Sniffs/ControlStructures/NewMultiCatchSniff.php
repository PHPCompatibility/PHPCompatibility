<?php
/**
 * \PHPCompatibility\Sniffs\ControlStructures\NewMultiCatch.
 *
 * PHP version 7.1
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\ControlStructures;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\ControlStructures\NewMultiCatch.
 *
 * Catching multiple exception types in one statement is available since PHP 7.1.
 *
 * PHP version 7.1
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewMultiCatchSniff extends Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CATCH);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('7.0') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $token  = $tokens[$stackPtr];

        // Bow out during live coding.
        if (isset($token['parenthesis_opener'], $token['parenthesis_closer']) === false) {
            return;
        }

        $hasBitwiseOr = $phpcsFile->findNext(T_BITWISE_OR, $token['parenthesis_opener'], $token['parenthesis_closer']);

        if ($hasBitwiseOr === false) {
            return;
        }

        $phpcsFile->addError(
            'Catching multiple exceptions within one statement is not supported in PHP 7.0 or earlier.',
            $hasBitwiseOr,
            'Found'
        );
    }
}
