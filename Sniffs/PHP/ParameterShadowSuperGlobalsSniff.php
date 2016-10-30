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
class PHPCompatibility_Sniffs_PHP_ParameterShadowSuperGlobalsSniff extends PHPCompatibility_Sniff
{
    /**
     * List of superglobals as an array of strings.
     *
     * @var array
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
     * Register the tokens to listen for.
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
        if ($this->supportsAbove('5.4') === false) {
            return;
        }

        // Get all parameters from function signature.
        $parameters = $phpcsFile->getMethodParameters($stackPtr);
        if (empty($parameters) || is_array($parameters) === false) {
            return;
        }

        foreach ($parameters as $param) {
            if (in_array($param['name'], $this->superglobals, true)) {
                $error     = 'Parameter shadowing super global (%s) causes fatal error since PHP 5.4';
                $errorCode = $this->stringToErrorCode(substr($param['name'], 1)).'Found';
                $data      = array($param['name']);

                $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
            }
        }
    }
}
