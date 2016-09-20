<?php
/**
 * PHPCompatibility_Sniffs_PHP_DeprecatedPHP4StyleConstructorsSniff.
 *
 * PHP version 7.0
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Koen Eelen <koen.eelen@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_DeprecatedPHP4StyleConstructorsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Koen Eelen <koen.eelen@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_DeprecatedPHP4StyleConstructorsSniff extends PHPCompatibility_Sniff
{

    public function register()
    {
        return array(T_CLASS);

    }//end register()

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.0') === false) {
            return;
        }

        if ($this->determineNamespace($phpcsFile, $stackPtr) !== '') {
            /*
             * Namespaced methods with the same name as the class are treated as
             * regular methods, so we can bow out if we're in a namespace.
             *
             * Note: the exception to this is PHP 5.3.0-5.3.2. This is currently
             * not dealt with.
             */
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $class = $tokens[$stackPtr];

        if (isset($class['scope_closer']) === false) {
            return;
        }

        $scopeCloser = $class['scope_closer'];
        $className   = $phpcsFile->getDeclarationName($stackPtr);

        if (empty($className) || is_string($className) === false) {
            return;
        }

        $nextFunc            = $stackPtr;
        $newConstructorFound = false;
        $oldConstructorFound = false;
        $oldConstructorPos   = -1;
        while (($nextFunc = $phpcsFile->findNext(T_FUNCTION, ($nextFunc + 1), $scopeCloser)) !== false) {
            $funcName = $phpcsFile->getDeclarationName($nextFunc);
            if (empty($funcName) || is_string($funcName) === false) {
                continue;
            }

            if ($funcName === '__construct') {
                $newConstructorFound = true;
            }

            if ($funcName === $className) {
                $oldConstructorFound = true;
                $oldConstructorPos   = $phpcsFile->findNext(T_STRING, $nextFunc);
            }
        }

        if ($newConstructorFound === false && $oldConstructorFound === true) {
            $phpcsFile->addError('Deprecated PHP4 style constructor are not supported since PHP7', $oldConstructorPos);
        }
    }
}
