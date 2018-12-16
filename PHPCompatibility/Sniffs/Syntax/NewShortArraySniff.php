<?php
/**
 * \PHPCompatibility\Sniffs\Syntax\NewShortArray.
 *
 * PHP version 5.4
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Alex Miroshnikov <unknown@example.com>
 */

namespace PHPCompatibility\Sniffs\Syntax;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\Syntax\NewShortArray.
 *
 * Short array syntax is available since PHP 5.4
 *
 * PHP version 5.4
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Alex Miroshnikov <unknown@example.com>
 */
class NewShortArraySniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_OPEN_SHORT_ARRAY,
            T_CLOSE_SHORT_ARRAY,
        );
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.3') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $token  = $tokens[$stackPtr];

        $error = '%s is available since 5.4';
        $data  = array();

        if ($token['type'] === 'T_OPEN_SHORT_ARRAY') {
            $data[] = 'Short array syntax (open)';
        } elseif ($token['type'] === 'T_CLOSE_SHORT_ARRAY') {
            $data[] = 'Short array syntax (close)';
        }

        $phpcsFile->addError($error, $stackPtr, 'Found', $data);
    }
}
