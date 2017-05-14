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
 * Verifies the use of the correct visibility and static properties of magic methods.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_NonStaticMagicMethodsSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of PHP magic methods and their visibility and static requirements.
     *
     * Method names in the array should be all *lowercase*.
     * Visibility can be either 'public', 'protected' or 'private'.
     * Static can be either 'true' - *must* be static, or 'false' - *must* be non-static.
     * When a method does not have a specific requirement for either visibility or static,
     * do *not* add the key.
     *
     * @var array(string)
     */
    protected $magicMethods = array(
        '__get' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__set' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__isset' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__unset' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__call' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__callstatic' => array(
            'visibility' => 'public',
            'static'     => true,
        ),
        '__sleep' => array(
            'visibility' => 'public',
        ),
        '__tostring' => array(
            'visibility' => 'public',
        ),
        '__set_state' => array(
            'static'     => true,
        ),
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $targets = array(
            T_CLASS,
            T_INTERFACE,
            T_TRAIT,
        );

        if (defined('T_ANON_CLASS')) {
            $targets[] = constant('T_ANON_CLASS');
        }

        return $targets;

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
        // Should be removed, the requirement was previously also there, 5.3 just started throwing a warning about it.
        if ($this->supportsAbove('5.3') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_closer']) === false) {
            return;
        }

        $classScopeCloser = $tokens[$stackPtr]['scope_closer'];
        $functionPtr      = $stackPtr;

        // Find all the functions in this class or interface.
        while (($functionToken = $phpcsFile->findNext(T_FUNCTION, $functionPtr, $classScopeCloser)) !== false) {
            /*
             * Get the scope closer for this function in order to know how
             * to advance to the next function.
             * If no body of function (e.g. for interfaces), there is
             * no closing curly brace; advance the pointer differently.
             */
            $scopeCloser = isset($tokens[$functionToken]['scope_closer'])
                ? $tokens[$functionToken]['scope_closer']
                : $functionToken + 1;


            $methodName   = $phpcsFile->getDeclarationName($functionToken);
            $methodNameLc = strtolower($methodName);
            if (isset($this->magicMethods[$methodNameLc]) === false) {
                $functionPtr = $scopeCloser;
                continue;
            }

            $methodProperties = $phpcsFile->getMethodProperties($functionToken);
            $errorCodeBase    = $this->stringToErrorCode($methodNameLc);

            if (isset($this->magicMethods[$methodNameLc]['visibility']) && $this->magicMethods[$methodNameLc]['visibility'] !== $methodProperties['scope']) {
                $error     = 'Visibility for magic method %s must be %s. Found: %s';
                $errorCode = $errorCodeBase.'MethodVisibility';
                $data      = array(
                    $methodName,
                    $this->magicMethods[$methodNameLc]['visibility'],
                    $methodProperties['scope'],
                );

                $phpcsFile->addError($error, $functionToken, $errorCode, $data);
            }

            if (isset($this->magicMethods[$methodNameLc]['static']) && $this->magicMethods[$methodNameLc]['static'] !== $methodProperties['is_static']) {
                $error     = 'Magic method %s cannot be defined as static.';
                $errorCode = $errorCodeBase.'MethodStatic';
                $data      = array($methodName);

                if ($this->magicMethods[$methodNameLc]['static'] === true) {
                    $error     = 'Magic method %s must be defined as static.';
                    $errorCode = $errorCodeBase.'MethodNonStatic';
                }

                $phpcsFile->addError($error, $functionToken, $errorCode, $data);
            }

            // Advance to next function.
            $functionPtr = $scopeCloser;
        }

    }//end process()


}//end class
