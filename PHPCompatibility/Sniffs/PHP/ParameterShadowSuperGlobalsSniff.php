<?php
/**
 * \PHPCompatibility\Sniffs\PHP\ParameterShadowSuperGlobalsSniff
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Declan Kelly <declankelly90@gmail.com>
 * @copyright 2015 Declan Kelly
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;

/**
 * \PHPCompatibility\Sniffs\PHP\ParameterShadowSuperGlobalsSniff
 *
 * Discourages use of superglobals as parameters for functions.
 *
 * {@internal List of superglobals is maintained in the parent class.}}
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Declan Kelly <declankelly90@gmail.com>
 * @copyright 2015 Declan Kelly
 */
class ParameterShadowSuperGlobalsSniff extends Sniff
{

    /**
     * Register the tokens to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_FUNCTION,
            T_CLOSURE,
        );
    }

    /**
     * Processes the test.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('5.4') === false) {
            return;
        }

        // Get all parameters from function signature.
        $parameters = $this->getMethodParameters($phpcsFile, $stackPtr);
        if (empty($parameters) || is_array($parameters) === false) {
            return;
        }

        foreach ($parameters as $param) {
            if (in_array($param['name'], $this->superglobals, true)) {
                $error     = 'Parameter shadowing super global (%s) causes fatal error since PHP 5.4';
                $errorCode = $this->stringToErrorCode(substr($param['name'], 1)).'Found';
                $data      = array($param['name']);

                $phpcsFile->addError($error, $param['token'], $errorCode, $data);
            }
        }
    }
}
