<?php
/**
 * \PHPCompatibility\Sniffs\FunctionNameRestrictions\RemovedMagicAutoloadSniff.
 *
 * PHP version 7.2
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */

namespace PHPCompatibility\Sniffs\FunctionNameRestrictions;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\FunctionNameRestrictions\RemovedMagicAutoloadSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */
class RemovedMagicAutoloadSniff extends Sniff
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
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.2') === false) {
            return;
        }

        $funcName = $phpcsFile->getDeclarationName($stackPtr);

        if (strtolower($funcName) !== '__autoload') {
            return;
        }

        if ($this->validDirectScope($phpcsFile, $stackPtr, $this->checkForScopes) !== false) {
            return;
        }

        if ($this->determineNamespace($phpcsFile, $stackPtr) !== '') {
            return;
        }

        $phpcsFile->addWarning('Use of __autoload() function is deprecated since PHP 7.2', $stackPtr, 'Found');
    }
}
