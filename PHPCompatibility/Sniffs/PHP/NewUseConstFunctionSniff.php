<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewUseConstFunctionSniff.
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewUseConstFunctionSniff.
 *
 * The use operator has been extended to support importing functions and
 * constants in addition to classes. This is achieved via the use function
 * and use const constructs, respectively.
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_NewUseConstFunctionSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of keywords that can follow use statements.
     *
     * @var array(string => string)
     */
    protected $validUseNames = array(
        'const'    => true,
        'function' => true,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_USE);
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
        if ($this->supportsBelow('5.5') !== true) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $nextNonEmpty = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextNonEmpty === false) {
            // Live coding.
            return;
        }

        if (isset($this->validUseNames[strtolower($tokens[$nextNonEmpty]['content'])]) === false) {
            // Not a `use const` or `use function` statement.
            return;
        }

        // `use const` and `use function` have to be followed by the function/constant name.
        $functionOrConstName = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($nextNonEmpty + 1), null, true);
        if ($functionOrConstName === false
            // Identifies as T_AS or T_STRING, this covers both.
            || ($tokens[$functionOrConstName]['content'] === 'as'
            || $tokens[$functionOrConstName]['code'] === T_COMMA)
        ) {
            // Live coding or incorrect use of reserved keyword, but that is
            // covered by the ForbiddenNames sniff.
            return;
        }

        // Still here ? In that case we have encountered a `use const` or `use function` statement.
        $phpcsFile->addError(
            'Importing functions and constants through a "use" statement is not supported in PHP 5.5 or lower.',
            $nextNonEmpty,
            'Found'
        );
    }

}
