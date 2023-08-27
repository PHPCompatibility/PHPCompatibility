<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\Scopes;
use PHPCSUtils\Utils\UseStatements;

/**
 * As of PHP 8.0, when an object constructor exit()s, the destructor will no longer be called.
 *
 * Note: shutdown functions registered with `register_shutdown_function()` will still be called.
 *
 * PHP version 8.0
 *
 * @link https://github.com/php/php-src/blob/71bfa5344ab207072f4cd25745d7023096338385/UPGRADING#L200-L201
 *
 * @since 10.0.0
 */
class RemovedCallingDestructAfterConstructorExitSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_FUNCTION];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('8.0') === false) {
            return;
        }

        // Note: interface constructors cannot contain code, enums cannot contain constructors or destructors.
        $classPtr = Scopes::validDirectScope($phpcsFile, $stackPtr, [\T_CLASS, \T_ANON_CLASS, \T_TRAIT]);
        if ($classPtr === false) {
            // Function, not method.
            return;
        }

        $tokens = $phpcsFile->getTokens();
        if (isset($tokens[$stackPtr]['scope_opener'], $tokens[$stackPtr]['scope_closer']) === false
            || isset($tokens[$classPtr]['scope_opener'], $tokens[$classPtr]['scope_closer']) === false
        ) {
            // Parse error, tokenizer error or live coding.
            return;
        }

        $name = FunctionDeclarations::getName($phpcsFile, $stackPtr);
        if (empty($name) === true) {
            // Parse error or live coding.
            return;
        }

        if (\strtolower($name) !== '__construct') {
            // The rule only applies to constructors. Bow out.
            return;
        }

        $functionOpen  = $tokens[$stackPtr]['scope_opener'];
        $functionClose = $tokens[$stackPtr]['scope_closer'];
        $exits         = [];
        for ($current = ($functionOpen + 1); $current < $functionClose; $current++) {
            if (isset(Tokens::$emptyTokens[$tokens[$current]['code']]) === true) {
                continue;
            }

            if ($tokens[$current]['code'] === \T_EXIT) {
                $exits[] = $current;
                continue;
            }

            // Skip over nested closed scopes as much as possible for efficiency.
            // Ignore arrow functions as they aren't closed scopes.
            if (isset(Collections::closedScopes()[$tokens[$current]['code']]) === true
                && isset($tokens[$current]['scope_closer']) === true
            ) {
                $current = $tokens[$current]['scope_closer'];
                continue;
            }

            // Skip over array access and short arrays/lists, but not control structures.
            if (isset($tokens[$current]['bracket_closer']) === true
                && isset($tokens[$current]['scope_closer']) === false
            ) {
                $current = $tokens[$current]['bracket_closer'];
                continue;
            }

            // Skip over long array/lists as they can't contain an exit statement, except within a closed scope.
            if (($tokens[$current]['code'] === \T_ARRAY || $tokens[$current]['code'] === \T_LIST)
                && isset($tokens[$current]['parenthesis_closer']) === true
            ) {
                $current = $tokens[$current]['parenthesis_closer'];
                continue;
            }
        }

        if (empty($exits) === true) {
            // No calls to exit or die found.
            return;
        }

        $hasDestruct = false;
        $usesTraits  = false;
        $isError     = false;
        $classOpen   = $tokens[$classPtr]['scope_opener'];
        $classClose  = $tokens[$classPtr]['scope_closer'];
        $nextFunc    = $classOpen;

        while (($nextFunc = $phpcsFile->findNext([\T_FUNCTION, \T_DOC_COMMENT_OPEN_TAG, \T_ATTRIBUTE, \T_USE], ($nextFunc + 1), $classClose)) !== false) {
            // Skip over docblocks.
            if ($tokens[$nextFunc]['code'] === \T_DOC_COMMENT_OPEN_TAG) {
                $nextFunc = $tokens[$nextFunc]['comment_closer'];
                continue;
            }

            // Skip over attributes.
            if ($tokens[$nextFunc]['code'] === \T_ATTRIBUTE
                && isset($tokens[$nextFunc]['attribute_closer'])
            ) {
                $nextFunc = $tokens[$nextFunc]['attribute_closer'];
                continue;
            }

            if ($tokens[$nextFunc]['code'] === \T_USE
                && UseStatements::isTraitUse($phpcsFile, $nextFunc) === true
            ) {
                $usesTraits = true;
                continue;
            }

            $functionScopeCloser = $nextFunc;
            if (isset($tokens[$nextFunc]['scope_closer'])) {
                // Normal (non-abstract) method.
                $functionScopeCloser = $tokens[$nextFunc]['scope_closer'];
            }

            $funcName = FunctionDeclarations::getName($phpcsFile, $nextFunc);
            $nextFunc = $functionScopeCloser; // Set up to skip over the method content.

            if (empty($funcName) === true) {
                continue;
            }

            if (\strtolower($funcName) !== '__destruct') {
                continue;
            }

            $hasDestruct = true;
            $isError     = true;
            break;
        }

        if ($hasDestruct === false && $usesTraits === false) {
            /*
             * No destruct method or trait use found, check if this class extends another one
             * which may contain a destruct method.
             */
            $extends = ObjectDeclarations::findExtendedClassName($phpcsFile, $classPtr);
            if (empty($extends) === true) {
                // No destruct method and class doesn't extend nor uses traits, so the calls to exit can be ignored.
                return;
            }
        }

        /*
         * Ok, either a destruct method has been found and we can throw an error, or either a class extends
         * or trait use has been found and no destruct method, in which case, we throw a warning.
         */
        $error     = 'When %s() is called within an object constructor, the object destructor will no longer be called since PHP 8.0';
        $errorCode = 'Found';
        if ($isError === false) {
            $error    .= ' While no __destruct() method was found in this class, one may be declared in the parent class or in a trait being used.';
            $errorCode = 'NeedsInspection';
        }

        foreach ($exits as $ptr) {
            MessageHelper::addMessage($phpcsFile, $error, $ptr, $isError, $errorCode, [$tokens[$ptr]['content']]);
        }
    }
}
