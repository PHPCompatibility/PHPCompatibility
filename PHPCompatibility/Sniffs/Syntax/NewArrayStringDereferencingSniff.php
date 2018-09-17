<?php
/**
 * \PHPCompatibility\Sniffs\PHP\NewArrayStringDereferencingSniff.
 *
 * PHP version 5.5
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;

/**
 * \PHPCompatibility\Sniffs\PHP\NewArrayStringDereferencingSniff.
 *
 * Array and string literals can now be dereferenced directly to access individual elements and characters.
 *
 * PHP version 5.5
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewArrayStringDereferencingSniff extends Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_ARRAY,
            T_OPEN_SHORT_ARRAY,
            T_CONSTANT_ENCAPSED_STRING,
        );
    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        switch ($tokens[$stackPtr]['code']) {
            case T_CONSTANT_ENCAPSED_STRING:
                $type = 'string literals';
                $end  = $stackPtr;
                break;

            case T_ARRAY:
                if (isset($tokens[$stackPtr]['parenthesis_closer']) === false) {
                    // Live coding.
                    return;
                } else {
                    $type = 'arrays';
                    $end  = $tokens[$stackPtr]['parenthesis_closer'];
                }
                break;

            case T_OPEN_SHORT_ARRAY:
                if (isset($tokens[$stackPtr]['bracket_closer']) === false) {
                    // Live coding.
                    return;
                } else {
                    $type = 'arrays';
                    $end  = $tokens[$stackPtr]['bracket_closer'];
                }
                break;
        }

        if (isset($type, $end) === false) {
            // Shouldn't happen, but for some reason did.
            return;
        }

        $nextNonEmpty = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, ($end + 1), null, true, null, true);

        if ($nextNonEmpty !== false
            && ($tokens[$nextNonEmpty]['type'] === 'T_OPEN_SQUARE_BRACKET'
                || $tokens[$nextNonEmpty]['type'] === 'T_OPEN_SHORT_ARRAY') // Work around bug #1381 in PHPCS 2.8.1 and lower.
        ) {
            $phpcsFile->addError(
                'Direct array dereferencing of %s is not present in PHP version 5.4 or earlier',
                $nextNonEmpty,
                'Found',
                array($type)
            );
        }

    }//end process()

}//end class
