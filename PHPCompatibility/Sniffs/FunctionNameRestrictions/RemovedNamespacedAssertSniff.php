<?php
/**
 * \PHPCompatibility\Sniffs\FunctionNameRestrictions\RemovedNamespacedAssertSniff.
 *
 * PHP version 7.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\FunctionNameRestrictions;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;

/**
 * Removed Namespaced Assert.
 *
 * As of PHP 7.3, a compile-time deprecation warning will be thrown when a function
 * called `assert()` is declared. In PHP 8 this will become a compile-error.
 *
 * Methods are unaffected.
 * Global, non-namespaced, assert() function declarations were always a fatal
 * "function already declared" error, so not the concern of this sniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class RemovedNamespacedAssertSniff extends Sniff
{
    /**
     * Scopes in which an `assert` function can be declared without issue.
     *
     * @var array
     */
    private $scopes = array(
        T_CLASS,
        T_INTERFACE,
        T_TRAIT,
        T_CLOSURE,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Enrich the scopes list.
        if (defined('T_ANON_CLASS')) {
            $this->scopes[] = T_ANON_CLASS;
        }

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
        if ($this->supportsAbove('7.3') === false) {
            return;
        }

        $funcName = $phpcsFile->getDeclarationName($stackPtr);

        if (strtolower($funcName) !== 'assert') {
            return;
        }

        if ($phpcsFile->hasCondition($stackPtr, $this->scopes) === true) {
            return;
        }

        if ($this->determineNamespace($phpcsFile, $stackPtr) === '') {
            // Not a namespaced function declaration. Parse error, but not our concern.
            return;
        }

        $phpcsFile->addWarning('Declaring a free-standing function called assert() is deprecated since PHP 7.3.', $stackPtr, 'Found');
    }
}
