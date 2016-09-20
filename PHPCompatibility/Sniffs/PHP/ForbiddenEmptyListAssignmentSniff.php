<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenEmptyListAssignmentSniff.
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenEmptyListAssignmentSniff.
 *
 * Empty list() assignments have been removed in PHP 7.0
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenEmptyListAssignmentSniff extends PHPCompatibility_Sniff
{

    /**
     * List of tokens to disregard when determining whether the list() is empty.
     *
     * @var array
     */
    protected $ignoreTokens = array();

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Set up a list of tokens to disregard when determining whether the list() is empty.
        // Only needs to be set up once.
        $this->ignoreTokens = PHP_CodeSniffer_Tokens::$emptyTokens;
        if (version_compare(PHP_CodeSniffer::VERSION, '2.0', '<')) {
            $this->ignoreTokens = array_combine($this->ignoreTokens, $this->ignoreTokens);
        }
        $this->ignoreTokens[T_COMMA]             = T_COMMA;
        $this->ignoreTokens[T_OPEN_PARENTHESIS]  = T_OPEN_PARENTHESIS;
        $this->ignoreTokens[T_CLOSE_PARENTHESIS] = T_CLOSE_PARENTHESIS;


        return array(T_LIST);
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
        if ($this->supportsAbove('7.0')) {
            $tokens = $phpcsFile->getTokens();

            $open = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr, null, false, null, true);
            if ($open === false || isset($tokens[$open]['parenthesis_closer']) === false) {
                return;
            }

            $close = $tokens[$open]['parenthesis_closer'];
            $error = true;
            if(($close - $open) > 1) {
                for ($cnt = $open + 1; $cnt < $close; $cnt++) {
                    if (isset($this->ignoreTokens[$tokens[$cnt]['code']]) === false) {
                        $error = false;
                    }
                }
            }

            if ($error === true) {
                $error = 'Empty list() assignments are not allowed since PHP 7.0';
                $phpcsFile->addError($error, $stackPtr);
            }
        }
    }
}
