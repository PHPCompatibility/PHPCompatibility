<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenBreakContinueOutsideLoop.
 *
 * PHP version 7
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenBreakContinueOutsideLoop.
 *
 * Forbids use of break or continue statements outside of looping structures.
 *
 * PHP version 7
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenBreakContinueOutsideLoopSniff extends PHPCompatibility_Sniff
{

    /**
     * Token codes of control structure in which usage of break/continue is valid.
     *
     * @var array
     */
    protected $validLoopStructures = array(
        T_FOR     => true,
        T_FOREACH => true,
        T_WHILE   => true,
        T_DO      => true,
        T_SWITCH  => true,
    );

    /**
     * Token codes which did not correctly get a condition assigned in older PHPCS versions.
     *
     * @var array
     */
    protected $backCompat = array(
        T_CASE    => true,
        T_DEFAULT => true,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_BREAK,
            T_CONTINUE,
        );

    }//end register()

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
        $token  = $tokens[$stackPtr];

        // Check if the break/continue is within a valid loop structure.
        if (empty($token['conditions']) === false) {
            foreach ($token['conditions'] as $tokenCode) {
                if (isset($this->validLoopStructures[$tokenCode]) === true) {
                    return;
                }
            }
        }
        else {
            // Deal with older PHPCS versions.
            if (isset($token['scope_condition']) === true && isset($this->backCompat[$tokens[$token['scope_condition']]['code']]) === true) {
                return;
            }
        }

        // If we're still here, no valid loop structure container has been found, so throw an error.
        $error     = "Using '%s' outside of a loop or switch structure is invalid";
        $isError   = false;
        $errorCode = 'Found';
        $data      = array($token['content']);

        if ($this->supportsAbove('7.0')) {
            $error    .= ' and will throw a fatal error since PHP 7.0';
            $isError   = true;
            $errorCode = 'FatalError';
        }

        $this->addMessage($phpcsFile, $error, $stackPtr, $isError, $errorCode, $data);

    }//end process()

}//end class
