<?php
/**
 * PHPCompatibility_Sniffs_PHP_LongArraysSniff.
 *
 * PHP version 5.3
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Ben Selby <bselby@plus.net>
 * @copyright 2012 Ben Selby
 */

/**
 * PHPCompatibility_Sniffs_PHP_LongArraysSniff.
 *
 * Marks the use of HTTP_*_VARS as deprecated
 *
 * PHP version 5.3
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Ben Selby <bselby@plus.net>
 * @copyright 2012 Ben Selby
 */
class PHPCompatibility_Sniffs_PHP_LongArraysSniff extends PHPCompatibility_Sniff
{
    /**
     * Array of HTTP_*_VARS that are now deprecated
     *
     * @var array
     */
    protected $deprecated = array(
        'HTTP_POST_VARS',
        'HTTP_GET_VARS',
        'HTTP_ENV_VARS',
        'HTTP_SERVER_VARS',
        'HTTP_COOKIE_VARS',
        'HTTP_SESSION_VARS',
        'HTTP_POST_FILES'
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_VARIABLE);
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
        if ($this->supportsAbove('5.3') === false) {
            return;
        }

        $tokens  = $phpcsFile->getTokens();
        $varName = substr($tokens[$stackPtr]['content'], 1);

        // Check if the variable name is in our blacklist.
        if (in_array($varName, $this->deprecated, true) === false) {
            return;
        }

        if ($this->inClassScope($phpcsFile, $stackPtr, false) === true) {
            /*
             * Check for class property definitions.
             */
            $properties = array();
            try {
                $properties = $phpcsFile->getMemberProperties($stackPtr);
            } catch ( PHP_CodeSniffer_Exception $e) {
                // If it's not an expected exception, throw it.
                if ($e->getMessage() !== '$stackPtr is not a class member var') {
                    throw $e;
                }
            }

            if (isset($properties['scope'])) {
                // Ok, so this was a class property declaration, not our concern.
                return;
            }

            /*
             * Check for static usage of class properties shadowing the long arrays.
             */
            $prevToken = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true);
            if ($tokens[$prevToken]['code'] === T_DOUBLE_COLON) {
                return;
            }
        }

        // Still here, so throw an error/warning.
        $error     = "The use of long predefined variables has been deprecated in PHP 5.3%s; Found '%s'";
        $isError   = $this->supportsAbove('5.4');
        $errorCode = $this->stringToErrorCode($varName).'Found';
        $data      = array(
            (($isError === true) ? ' and removed in PHP 5.4' : ''),
            $tokens[$stackPtr]['content'],
        );

        $this->addMessage($phpcsFile, $error, $stackPtr, $isError, $errorCode, $data);
    }
}
