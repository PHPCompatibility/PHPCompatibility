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
class PHPCompatibility_Sniffs_PHP_DeprecatedPHP4StyleConstructorsSniff extends PHPCompatibility_Sniff {
    public function register()
    {
        return array(T_CLASS);

    }//end register()

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
        $tokens = $phpcsFile->getTokens();

        $class = $tokens[$stackPtr];

        if(!IsSet($class['scope_closer'])) {
            return;
        }

        $scopeCloser = $class['scope_closer'];

        //get the name of the class
        $classNamePos = $phpcsFile->findNext(T_STRING, $stackPtr);
        $className = $tokens[$classNamePos]['content'];

        $nextFunc = $stackPtr;
        while (($nextFunc = $phpcsFile->findNext(T_FUNCTION, ($nextFunc + 1), $scopeCloser)) !== false) {
            $funcNamePos = $phpcsFile->findNext(T_STRING, $nextFunc);

            if ($this->supportsAbove('7.0')) {
                if ($funcNamePos !== false && $tokens[$funcNamePos]['content'] === $className) {
                    $phpcsFile->addError('Deprecated PHP4 style constructor are not supported since PHP7', $funcNamePos);
                }
            }
        }
    }
}
