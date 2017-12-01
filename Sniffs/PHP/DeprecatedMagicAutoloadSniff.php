<?php
/**
 * PHPCompatibility_Sniffs_PHP_DeprecatedMagicAutoloadSniff.
 *
 * PHP version 7.2
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be
 */

/**
 * PHPCompatibility_Sniffs_PHP_DeprecatedMagicAutoloadSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be
 */
class PHPCompatibility_Sniffs_PHP_DeprecatedMagicAutoloadSniff extends PHPCompatibility_Sniff
{

    public function register()
    {
        return array(T_FUNCTION);

    }//end register()

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.2') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $function = $tokens[$stackPtr];
        var_dump($function) . "\n";
        $funcName = $phpcsFile->getDeclarationName($stackPtr);
        die('wtf');
        echo $funcName . "\n";

        if (strtolower($funcName) !== '__autoload') {
            return;
        }

        $phpcsFile->addWarning(
            'Use of __autoload() function is deprecated since PHP 7.2',
            $stackPtr,
            'Found'
        );
    }
}
