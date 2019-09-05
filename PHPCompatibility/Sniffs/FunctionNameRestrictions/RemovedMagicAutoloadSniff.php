<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2019 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionNameRestrictions;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;

/**
 * Detect declaration of the magic __autoload() method.
 *
 * PHP version 7.2
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
        return array(\T_FUNCTION);
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
