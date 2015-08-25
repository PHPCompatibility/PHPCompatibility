<?php
/**
 * PHPCompatibility_Sniffs_PHP_NonStaticMagicMethodsSniff.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_NonStaticMagicMethodsSniff.
 *
 * Prohibits the use of static magic methods as well as protected or private magic methods
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_NonStaticMagicMethodsSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of magic methods that must be public and not be static
     *
     * @var array(string)
     */
    protected $magicMethods = array(
        '__get',
        '__set',
        '__isset',
        '__unset',
        '__call'
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CLASS, T_INTERFACE);

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
        if ($this->supportsAbove('5.3')) {
            $tokens = $phpcsFile->getTokens();

            // Find all the functions in this class or interface

            // Use the scope closer to limit the sniffing within the scope of
            // this class or interface
            $classScopeCloser = (isset($tokens[$stackPtr]['scope_closer']))
                ? $tokens[$stackPtr]['scope_closer']
                : $stackPtr + 1;

            $functionPtr = $stackPtr;

            while ($functionToken = $phpcsFile->findNext(T_FUNCTION, $functionPtr, $classScopeCloser)) {

                // Get the scope closer for this function in order to know how
                // to advance to the next function.
                // If no body of function (e.g. for interfaces), there is
                // no closing curly brace; advance the pointer differently
                $scopeCloser = isset($tokens[$functionToken]['scope_closer'])
                    ? $tokens[$functionToken]['scope_closer']
                    : $functionToken + 1;

                $scopeToken = false;
                $staticToken = false;

                $nameToken = $phpcsFile->findNext(T_WHITESPACE, $functionToken + 1, $classScopeCloser, true);
                if (in_array($tokens[$nameToken]['content'], $this->magicMethods) === false) {
                    $functionPtr = $scopeCloser;
                    continue;
                }

                // What is the token before the function token?
                $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, $functionToken - 1, null, true);
                if (in_array($tokens[$prevToken]['code'], array(T_PUBLIC, T_PROTECTED, T_PRIVATE))) {
                    $scopeToken = $prevToken;
                } elseif ($tokens[$prevToken]['code'] == T_STATIC) {
                    $staticToken = $prevToken;
                } else {
                    // no scope or static modifiers, this function is okay
                    $functionPtr = $scopeCloser;
                    continue;
                }

                // What is the token before that one?
                $prevPrevToken = $phpcsFile->findPrevious(T_WHITESPACE, $prevToken - 1, null, true);
                if (in_array($tokens[$prevPrevToken]['code'], array(T_PUBLIC, T_PROTECTED, T_PRIVATE))) {
                    $scopeToken = $prevPrevToken;
                } elseif ($tokens[$prevPrevToken]['code'] == T_STATIC) {
                    $staticToken = $prevPrevToken;
                }

                $isPublic = ($scopeToken !== false && $tokens[$scopeToken]['type'] != 'T_PUBLIC') ? false : true;
                $isStatic = ($staticToken === false) ? false : true;

                if ($isPublic && !$isStatic) {
                    $functionPtr = $scopeCloser;
                    continue;
                }

                // Summarize what the problems are
                if (!$isPublic && $isStatic) {
                    $error = sprintf(
                        "Magic methods must be public and cannot be static (since PHP 5.3)! Found static %s function %s",
                        $tokens[$scopeToken]['content'],
                        $tokens[$nameToken]['content']
                    );
                    $phpcsFile->addError($error, $functionToken);
                } else {
                    if (!$isPublic) {
                        $error = sprintf(
                            "Magic methods must be public (since PHP 5.3)! Found %s function %s",
                            $tokens[$scopeToken]['content'],
                            $tokens[$nameToken]['content']
                        );
                        $phpcsFile->addError($error, $functionToken);
                    }

                    if ($isStatic) {
                        $error = sprintf(
                            "Magic methods cannot be static (since PHP 5.3)! Found static function %s",
                            $tokens[$nameToken]['content']
                        );
                        $phpcsFile->addError($error, $functionToken);
                    }
                }

                // Advance to next function
                $functionPtr = $scopeCloser;
            }
        }

    }//end process()


}//end class
