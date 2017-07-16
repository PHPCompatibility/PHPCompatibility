<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewHeredocInitializeSniff.
 *
 * PHP version 5.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewHeredocInitializeSniff.
 *
 * As of PHP 5.3.0, it's possible to initialize static variables and class
 * properties/constants using the Heredoc syntax.
 *
 * PHP version 5.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_NewHeredocInitializeSniff extends PHPCompatibility_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_START_HEREDOC);
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
        if ($this->supportsBelow('5.2') !== true) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $equalSign = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true);
        if ($equalSign === false || $tokens[$equalSign]['code'] !== T_EQUAL) {
            // Not an assignment.
            return;
        }

        $prevNonEmpty = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($equalSign - 1), null, true, null, true);
        if ($prevNonEmpty === false
            || ($tokens[$prevNonEmpty]['code'] !== T_VARIABLE
                && $tokens[$prevNonEmpty]['code'] !== T_STRING)
        ) {
            // Not a variable or constant assignment.
            return;
        }

        switch ($tokens[$prevNonEmpty]['type']) {
            /*
             * Check class constant assignments.
             */
            case 'T_STRING':
                // Walk back to check for the const keyword.
                $constPtr = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($prevNonEmpty - 1), null, true, null, true);
                if ($constPtr === false || $tokens[$constPtr]['code'] !== T_CONST) {
                    // Not a constant assignment.
                    return;
                }

                if ($this->isClassConstant($phpcsFile, $constPtr) === true) {
                    $this->throwError($phpcsFile, $stackPtr, 'const');
                }
                return;

            case 'T_VARIABLE':
                /*
                 * Check class property assignments.
                 */
                if ($this->isClassProperty($phpcsFile, $prevNonEmpty) === true) {
                    $this->throwError($phpcsFile, $stackPtr, 'property');
                }

                /*
                 * Check static variable assignments.
                 */
                else {
                    // Walk back to check this is a static variable `static $var =`.
                    $staticPtr = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($prevNonEmpty - 1), null, true, null, true);
                    if ($staticPtr === false || $tokens[$staticPtr]['code'] !== T_STATIC) {
                        // Not a static variable assignment.
                        return;
                    }

                    // Still here ? Then we have a static variable assignment.
                    $this->throwError($phpcsFile, $stackPtr, 'staticvar');
                }
                return;
        }
    }


    /**
     * Throw an error if a non-static value is found.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the token to link the error to.
     * @param string               $type      Type of usage found.
     *
     * @return void
     */
    protected function throwError(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $type)
    {
        switch ($type) {
            case 'const':
                $phrase = 'class constants';
                break;

            case 'property':
                $phrase = 'class properties';
                break;

            case 'staticvar':
                $phrase = 'static variables';
                break;

            default:
                $phrase = '';
                break;
        }

        $errorCode = $this->stringToErrorCode($type) . 'Found';

        $phpcsFile->addError(
            'Initializing %s using the Heredoc syntax was not supported in PHP 5.2 or earlier',
            $stackPtr,
            $errorCode,
            array($phrase)
        );
    }

}
