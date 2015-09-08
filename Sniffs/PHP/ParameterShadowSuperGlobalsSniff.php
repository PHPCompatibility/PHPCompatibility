<?php
/**
 * PHPCompatibility_Sniffs_PHP_ParameterShadowSuperGlobalsSniff
 *
 * Discourages use of superglobals as parameters for functions.
 * 
 * PHP version 5.4
 *
 * @category   PHP
 * @package    PHPCompatibility
 * @author     Declan Kelly <declankelly90@gmail.com>
 * @copyright  2015 Declan Kelly
 */
class PHPCompatibility_Sniffs_PHP_ParameterShadowSuperGlobalsSniff implements PHP_CodeSniffer_Sniff {
    /**
     * List of superglobals as an array of strings.
     */
    protected $superglobals = array(
        '$GLOBALS',
        '$_SERVER',
        '$_GET',
        '$_POST',
        '$_FILES',
        '$_COOKIE',
        '$_SESSION',
        '$_REQUEST',
        '$_ENV'
    );

    /**
     * Returns array of tokens, in this case array containing
     * T_FUNCTION
     *
     * @return array
     */
    public function register() {
        return array(T_FUNCTION);
    }

    /**
     * Processes the test.
     * 
     * @param PHP_CodeSniffer_file $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
        if (
            is_null(PHP_CodeSniffer::getConfigData('testVersion'))
            ||
            (
                !is_null(PHP_CodeSniffer::getConfigData('testVersion'))
                &&
                version_compare(PHP_CodeSniffer::getConfigData('testVersion'), '5.4') >= 0
            )
        ) {
            $tokens = $phpcsFile->getTokens();
            $openBracket  = $tokens[$stackPtr]['parenthesis_opener'];
            $closeBracket = $tokens[$stackPtr]['parenthesis_closer'];

            for ($i = ($openBracket + 1); $i < $closeBracket; $i++) {
                $variable = $tokens[$i]['content'];
                if (in_array($variable, $this->superglobals)) {
                    $phpcsFile->addError("Parameter shadowing super global ($variable) causes fatal error since PHP 5.4", $i);
                }
            }
            
        }
    }
}