<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenNamesAsInvokedFunctionsSniff.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Jansen Price <jansen.price@gmail.com>
 * @copyright 2012 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenNamesAsInvokedFunctionsSniff.
 *
 * Prohibits the use of reserved keywords invoked as functions.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Jansen Price <jansen.price@gmail.com>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenNamesAsInvokedFunctionsSniff extends PHPCompatibility_Sniff
{

    /**
     * List of tokens to register.
     *
     * @var array
     */
    protected $targetedTokens = array(
        T_ABSTRACT => '5.0',
        T_CALLABLE => '5.4',
        T_CATCH => '5.0',
        T_FINAL => '5.0',
        T_FINALLY => '5.5',
        T_GOTO => '5.3',
        T_IMPLEMENTS => '5.0',
        T_INTERFACE => '5.0',
        T_INSTANCEOF => '5.0',
        T_INSTEADOF => '5.4',
        T_NAMESPACE => '5.3',
        T_PRIVATE => '5.0',
        T_PROTECTED => '5.0',
        T_PUBLIC => '5.0',
        T_TRAIT => '5.4',
        T_TRY => '5.0',
    );

    /**
     * T_STRING keywords to recognize as targetted tokens.
     *
     * Compatibility for PHP versions where the keyword is not yet recognized
     * as its own token.
     *
     * @var array
     */
    protected $targetedStringTokens = array(
        'goto' => '5.3',
        'namespace' => '5.3',
        'callable' => '5.4',
        'insteadof' => '5.4',
        'trait' => '5.4',
        'finally' => '5.5'
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $tokens   = array_keys($this->targetedTokens);
        $tokens[] = T_STRING;

        return $tokens;
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
        $tokens         = $phpcsFile->getTokens();
        $tokenCode      = $tokens[$stackPtr]['code'];
        $tokenContentLc = strtolower($tokens[$stackPtr]['content']);
        $isString       = false;

        // For string tokens we only care if the string is a reserved word used
        // as a function. This only happens in older versions of PHP where the
        // token doesn't exist yet for that keyword.
        if ($tokenCode === T_STRING
            && (isset($this->targetedStringTokens[$tokenContentLc]) === false)
        ) {
            return;
        }

        if ($tokenCode === T_STRING) {
            $isString = true;
        }

        // Make sure this is a function call.
        $next = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($next === false || $tokens[$next]['code'] !== T_OPEN_PARENTHESIS) {
            // Not a function call.
            return;
        }

        // This sniff isn't concerned about function declaration.
        $prev = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($prev !== false && $tokens[$prev]['code'] === T_FUNCTION) {
            return;
        }

        // For the word catch, it is valid to have an open parenthesis
        // after it, but only if it is preceded by a right curly brace.
        if ($tokenCode === T_CATCH) {
            if ($prev !== false && $tokens[$prev]['code'] === T_CLOSE_CURLY_BRACKET) {
                // Ok, it's fine
                return;
            }
        }

        if ($isString) {
            $version = $this->targetedStringTokens[$tokenContentLc];
        } else {
            $version = $this->targetedTokens[$tokenCode];
        }

        if ($this->supportsAbove($version)) {
            $error     = "'%s' is a reserved keyword introduced in PHP version %s and cannot be invoked as a function (%s)";
            $errorCode = $this->stringToErrorCode($tokenContentLc).'Found';
            $data      = array(
                $tokenContentLc,
                $version,
                $tokens[$stackPtr]['type'],
            );

            $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
        }
    }//end process()

}//end class
