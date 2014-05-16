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
 * Prohibits the use of reserved keywords invoked as functions
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Jansen Price <jansen.price@gmail.com>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenNamesAsInvokedFunctionsSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    protected $error = true;

    /**
     * List of tokens to register
     *
     * @var array
     */
    protected $targetedTokens = array(
        T_ABSTRACT => '5.0',
        T_CALLABLE => '5.4',
        T_CATCH => '5.0',
        T_CLONE => '5.0',
        T_FINAL => '5.0',
        T_FINALLY => '5.5',
        T_GOTO => '5.3',
        T_IMPLEMENTS => '5.0',
        T_INTERFACE => '5.0',
        T_INSTANCEOF => '5.4',
        T_INSTEADOF => '5.4',
        T_NAMESPACE => '5.3',
        T_PRIVATE => '5.0',
        T_PROTECTED => '5.0',
        T_PUBLIC => '5.0',
        T_THROW => '5.0',
        T_TRAIT => '5.4',
        T_TRY => '5.0',
    );

    /**
     * targetedStringTokens
     *
     * @var array
     */
    protected $targetedStringTokens = array(
        'callable' => '5.4',
        'insteadof' => '5.4',
        'trait' => '5.4',
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array_merge(
            array(T_STRING),
            array_keys($this->targetedTokens)
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
        $isString = false;

        // For string tokens we only care if the string is a reserved word used
        // as a function. This only happens in older versions of PHP where the
        // token doesn't exist yet for that keyword.
        if ($tokens[$stackPtr]['code'] == T_STRING
            && (!in_array($tokens[$stackPtr]['content'], array_keys($this->targetedStringTokens)))
        ) {
            return;
        }

        if ($tokens[$stackPtr]['code'] == T_STRING) {
            $isString = true;
        }

        // Make sure this is a function call.
        $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        if ($next === false) {
            // Not a function call.
            return;
        }

        if ($tokens[$next]['code'] == T_OPEN_PARENTHESIS) {
            $prev = $phpcsFile->findPrevious(array(T_WHITESPACE, T_COMMENT), ($stackPtr - 1), null, true);

            // This sniff isn't concerned about function declaration
            if ($tokens[$prev]['code'] == T_FUNCTION) {
                return;
            }

            // For the word catch, it is valid to have an open parenthesis
            // after it, but only if it is preceded by a right curly brace
            if ($tokens[$stackPtr]['code'] == T_CATCH) {
                if ($prev !== false) {
                    if ($tokens[$prev]['code'] == T_CLOSE_CURLY_BRACKET) {
                        // Ok, it's fine
                        return;
                    }
                }
            }

            $content = $tokens[$stackPtr]['content'];
            $tokenCode = $tokens[$stackPtr]['code'];
            if ($isString) {
                $version = $this->targetedStringTokens[$content];
            } else {
                $version = $this->targetedTokens[$tokenCode];
            }

            if (
                is_null(PHP_CodeSniffer::getConfigData('testVersion'))
                ||
                (
                    !is_null(PHP_CodeSniffer::getConfigData('testVersion'))
                    &&
                    version_compare(PHP_CodeSniffer::getConfigData('testVersion'), $version) >= 0
                )
            ) {
                $error = sprintf(
                    "'%s' is a reserved keyword introduced in PHP version %s and cannot be invoked as a function (%s)",
                    $content,
                    $version,
                    $tokens[$stackPtr]['type']
                );
                $phpcsFile->addError($error, $stackPtr);
            }
        }
    }//end process()

}//end class
