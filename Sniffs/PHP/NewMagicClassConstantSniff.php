<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewMagicClassConstantSniff.
 *
 * PHP version 5.5
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewMagicClassConstantSniff.
 *
 * The special ClassName::class constant is available as of PHP 5.5.0, and allows for
 * fully qualified class name resolution at compile.
 *
 * PHP version 5.5
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_NewMagicClassConstantSniff extends PHPCompatibility_Sniff
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
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (strtolower($tokens[$stackPtr]['content']) !== 'class') {
            return;
        }

        $prevToken = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true);
        if ($prevToken === false || $tokens[$prevToken]['code'] !== T_DOUBLE_COLON) {
            return;
        }

        if ($this->supportsBelow('5.4')) {
            $phpcsFile->addError(
                'The magic class constant ClassName::class was not available in PHP 5.4 or earlier',
                $stackPtr,
                'Found'
            );
        }

        /*
         * Check against invalid use of the magic `::class` constant.
         */
        if ($this->supportsAbove('5.5') === false) {
            return;
        }


        // Useless if not in a namespace.
        $classNameToken = $phpcsFile->findPrevious(T_STRING, ($prevToken - 1), null, false, null, true);
        if ($classNameToken === false) {
            return;
        }

        $namespace = $this->determineNamespace($phpcsFile, $classNameToken);
        if (empty($namespace) === false) {
            return;
        }

        $phpcsFile->addWarning(
            'Using the magic class constant ClassName::class is only useful in combination with a namespaced class',
            $stackPtr,
            'NotInNamespace'
        );
    }

}
