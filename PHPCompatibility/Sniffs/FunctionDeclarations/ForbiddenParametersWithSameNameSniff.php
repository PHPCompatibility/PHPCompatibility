<?php
/**
 * \PHPCompatibility\Sniffs\FunctionDeclarations\ForbiddenParametersWithSameName.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Sniff;
use PHPCompatibility\PHPCSHelper;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\FunctionDeclarations\ForbiddenParametersWithSameName.
 *
 * Functions can not have multiple parameters with the same name since PHP 7.0
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */
class ForbiddenParametersWithSameNameSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
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
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.0') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $token  = $tokens[$stackPtr];
        // Skip function without body.
        if (isset($token['scope_opener']) === false) {
            return;
        }

        // Get all parameters from method signature.
        $parameters = PHPCSHelper::getMethodParameters($phpcsFile, $stackPtr);
        if (empty($parameters) || is_array($parameters) === false) {
            return;
        }

        $paramNames = array();
        foreach ($parameters as $param) {
            $paramNames[] = strtolower($param['name']);
        }

        if (count($paramNames) !== count(array_unique($paramNames))) {
            $phpcsFile->addError(
                'Functions can not have multiple parameters with the same name since PHP 7.0',
                $stackPtr,
                'Found'
            );
        }
    }
}
