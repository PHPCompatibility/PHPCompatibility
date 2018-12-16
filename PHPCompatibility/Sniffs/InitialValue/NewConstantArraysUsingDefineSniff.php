<?php
/**
 * \PHPCompatibility\Sniffs\InitialValue\NewConstantArraysUsingDefineSniff.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */

namespace PHPCompatibility\Sniffs\InitialValue;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\InitialValue\NewConstantArraysUsingDefineSniff.
 *
 * Constant arrays using define in PHP 7.0
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */
class NewConstantArraysUsingDefineSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING);
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
        if ($this->supportsBelow('5.6') !== true) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $ignore = array(
            T_DOUBLE_COLON    => true,
            T_OBJECT_OPERATOR => true,
            T_FUNCTION        => true,
            T_CONST           => true,
        );

        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if (isset($ignore[$tokens[$prevToken]['code']]) === true) {
            // Not a call to a PHP function.
            return;
        }

        $functionLc = strtolower($tokens[$stackPtr]['content']);
        if ($functionLc !== 'define') {
            return;
        }

        $secondParam = $this->getFunctionCallParameter($phpcsFile, $stackPtr, 2);
        if (isset($secondParam['start'], $secondParam['end']) === false) {
            return;
        }

        $targetNestingLevel = 0;
        if (isset($tokens[$secondParam['start']]['nested_parenthesis'])) {
            $targetNestingLevel = count($tokens[$secondParam['start']]['nested_parenthesis']);
        }

        $array = $phpcsFile->findNext(array(T_ARRAY, T_OPEN_SHORT_ARRAY), $secondParam['start'], ($secondParam['end'] + 1));
        if ($array !== false) {
            if ((isset($tokens[$array]['nested_parenthesis']) === false && $targetNestingLevel === 0) || count($tokens[$array]['nested_parenthesis']) === $targetNestingLevel) {
                $phpcsFile->addError(
                    'Constant arrays using define are not allowed in PHP 5.6 or earlier',
                    $array,
                    'Found'
                );
            }
        }
    }
}
