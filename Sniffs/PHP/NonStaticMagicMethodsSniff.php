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
class PHPCompatibility_Sniffs_PHP_NonStaticMagicMethodsSniff implements PHP_CodeSniffer_Sniff
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
        $tokens = $phpcsFile->getTokens();
        $functionToken = $phpcsFile->findNext(T_FUNCTION, $stackPtr);
        if ($functionToken === false) {
            return;
        }
        $nameToken = $phpcsFile->findNext(T_STRING, $functionToken);
        if (in_array($tokens[$nameToken]['content'], $this->magicMethods) === false) {
            return;
        }
        $scopeToken = $phpcsFile->findPrevious(array(T_PUBLIC, T_PROTECTED, T_PRIVATE), $nameToken, $stackPtr);
        if ($scopeToken === false) {
            return;
        }
        if ($tokens[$scopeToken]['type'] != 'T_PUBLIC') {
            $error = "Magic methods must be public (since PHP 5.3) !";
            $phpcsFile->addError($error, $stackPtr);
        }
        $staticToken = $phpcsFile->findPrevious(T_STATIC, $scopeToken, $scopeToken - 2);
        if ($staticToken === false) {
            return;
        } else {
            $error = "Magic methods can not be static (since PHP 5.3) !";
            $phpcsFile->addError($error, $stackPtr);
        }

    }//end process()


}//end class
