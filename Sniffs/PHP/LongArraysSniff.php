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
class PHPCompatibility_Sniffs_PHP_LongArraysSniff implements PHP_CodeSniffer_Sniff
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
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['type'] == 'T_VARIABLE'
            && in_array(substr($tokens[$stackPtr]['content'], 1), $this->deprecated)
        ) {
            $error = 'The use of long predefined variables has been deprecated in 5.3 and removed in 5.4';
            $phpcsFile->addWarning($error, $stackPtr);
        }
    }
}
