<?php
/**
 * PHPCompatibility_Sniffs_PHP_TernaryOperatorsSniff.
 *
 * PHP version 5.3
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Ben Selby <bselby@plus.net>
 * @copyright 2012 Ben Selby
 */

/**
 * PHPCompatibility_Sniffs_PHP_TernaryOperatorsSniff.
 *
 * Performs checks on ternary operators, specifically that the middle expression
 * is not omitted for versions that don't support this.
 *
 * PHP version 5.3
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Ben Selby <bselby@plus.net>
 * @copyright 2012 Ben Selby
 */
class PHPCompatibility_Sniffs_PHP_TernaryOperatorsSniff extends PHPCompatibility_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_INLINE_THEN);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.2') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Get next non-whitespace token, and check it isn't the related inline else
        // symbol, which is not allowed prior to PHP 5.3.
        $next = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens,
                                     ($stackPtr + 1), null, true);

        if ($next !== false && $tokens[$next]['code'] === T_INLINE_ELSE) {
            $phpcsFile->addError(
                'Middle may not be omitted from ternary operators in PHP < 5.3',
                $stackPtr,
                'MiddleMissing'
            );
        }
    }
}
