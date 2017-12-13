<?php
/**
 * \PHPCompatibility\Sniffs\PHP\DeprecatedMagicAutoloadSniff.
 *
 * PHP version 7.2
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;

/**
 * \PHPCompatibility\Sniffs\PHP\DeprecatedMagicAutoloadSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */
class DeprecatedMagicAutoloadSniff extends Sniff
{
    /**
     * Scopes to look for when testing using validDirectScope
     *
     * @var array
     */
    private $checkForScopes = array(
        'T_CLASS'      => true,
        'T_ANON_CLASS' => true,
        'T_INTERFACE'  => true,
        'T_TRAIT'      => true,
        'T_NAMESPACE'  => true,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_FUNCTION);
    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.2') === false) {
            return;
        }

        $funcName = $phpcsFile->getDeclarationName($stackPtr);

        if (strtolower($funcName) !== '__autoload') {
            return;
        }

        if ($this->validDirectScope($phpcsFile, $stackPtr, $this->checkForScopes) === true) {
            return;
        }

        if ($this->determineNamespace($phpcsFile, $stackPtr) != '') {
            return;
        }

        $phpcsFile->addWarning('Use of __autoload() function is deprecated since PHP 7.2', $stackPtr, 'Found');
    }//end process()

}//end class
