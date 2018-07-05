<?php
/**
 * \PHPCompatibility\Sniffs\LanguageConstructs\NewEmptyNonVariableSniff.
 *
 * PHP version 5.5
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\LanguageConstructs;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * \PHPCompatibility\Sniffs\LanguageConstructs\NewEmptyNonVariableSniff.
 *
 * Verify that nothing but variables are passed to empty().
 *
 * PHP version 5.5
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewEmptyNonVariableSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(\T_EMPTY);
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
        if ($this->supportsBelow('5.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $open = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true, null, true);
        if ($open === false
            || $tokens[$open]['code'] !== \T_OPEN_PARENTHESIS
            || isset($tokens[$open]['parenthesis_closer']) === false
        ) {
            return;
        }

        $close = $tokens[$open]['parenthesis_closer'];

        $nestingLevel = 0;
        if ($close !== ($open + 1) && isset($tokens[$open + 1]['nested_parenthesis'])) {
            $nestingLevel = \count($tokens[$open + 1]['nested_parenthesis']);
        }

        if ($this->isVariable($phpcsFile, ($open + 1), $close, $nestingLevel) === true) {
            return;
        }

        $phpcsFile->addError(
            'Only variables can be passed to empty() prior to PHP 5.5.',
            $stackPtr,
            'Found'
        );
    }
}
