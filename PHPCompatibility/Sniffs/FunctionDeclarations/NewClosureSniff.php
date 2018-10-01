<?php
/**
 * \PHPCompatibility\Sniffs\FunctionDeclarations\NewClosure.
 *
 * PHP version 5.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * \PHPCompatibility\Sniffs\FunctionDeclarations\NewClosure.
 *
 * Closures are available since PHP 5.3
 *
 * PHP version 5.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */
class NewClosureSniff extends Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CLOSURE);
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
        if ($this->supportsBelow('5.2')) {
            $phpcsFile->addError(
                'Closures / anonymous functions are not available in PHP 5.2 or earlier',
                $stackPtr,
                'Found'
            );
        }

        /*
         * Closures can only be declared as static since PHP 5.4.
         */
        $isStatic = $this->isClosureStatic($phpcsFile, $stackPtr);
        if ($this->supportsBelow('5.3') && $isStatic === true) {
            $phpcsFile->addError(
                'Closures / anonymous functions could not be declared as static in PHP 5.3 or earlier',
                $stackPtr,
                'StaticFound'
            );
        }

        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_opener'], $tokens[$stackPtr]['scope_closer']) === false) {
            // Live coding or parse error.
            return;
        }

        $scopeStart = ($tokens[$stackPtr]['scope_opener'] + 1);
        $scopeEnd   = $tokens[$stackPtr]['scope_closer'];
        $usesThis   = $this->findThisUsageInClosure($phpcsFile, $scopeStart, $scopeEnd);

        if ($this->supportsBelow('5.3')) {
            /*
             * Closures declared within classes only have access to $this since PHP 5.4.
             */
            if ($usesThis !== false) {
                $thisFound = $usesThis;
                do {
                    $phpcsFile->addError(
                        'Closures / anonymous functions did not have access to $this in PHP 5.3 or earlier',
                        $thisFound,
                        'ThisFound'
                    );

                    $thisFound = $this->findThisUsageInClosure($phpcsFile, ($thisFound + 1), $scopeEnd);

                } while ($thisFound !== false);
            }

            /*
             * Closures declared within classes only have access to self/parent/static since PHP 5.4.
             */
            $usesClassRef = $this->findClassRefUsageInClosure($phpcsFile, $scopeStart, $scopeEnd);

            if ($usesClassRef !== false) {
                do {
                    $phpcsFile->addError(
                        'Closures / anonymous functions could not use "%s::" in PHP 5.3 or earlier',
                        $usesClassRef,
                        'ClassRefFound',
                        array(strtolower($tokens[$usesClassRef]['content']))
                    );

                    $usesClassRef = $this->findClassRefUsageInClosure($phpcsFile, ($usesClassRef + 1), $scopeEnd);

                } while ($usesClassRef !== false);
            }
        }

        /*
         * Check for correct usage.
         */
        if ($this->supportsAbove('5.4') && $usesThis !== false) {

            $thisFound = $usesThis;

            do {
                /*
                 * Closures only have access to $this if not declared as static.
                 */
                if ($isStatic === true) {
                    $phpcsFile->addError(
                        'Closures / anonymous functions declared as static do not have access to $this',
                        $thisFound,
                        'ThisFoundInStatic'
                    );
                }

                /*
                 * Closures only have access to $this if used within a class context.
                 */
                elseif ($this->inClassScope($phpcsFile, $stackPtr, false) === false) {
                    $phpcsFile->addWarning(
                        'Closures / anonymous functions only have access to $this if used within a class or when bound to an object using bindTo(). Please verify.',
                        $thisFound,
                        'ThisFoundOutsideClass'
                    );
                }

                $thisFound = $this->findThisUsageInClosure($phpcsFile, ($thisFound + 1), $scopeEnd);

            } while ($thisFound !== false);
        }

        // Prevent double reporting for nested closures.
        return $scopeEnd;
    }


    /**
     * Check whether the closure is declared as static.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function isClosureStatic(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $prevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true);

        return ($prevToken !== false && $tokens[$prevToken]['code'] === T_STATIC);
    }


    /**
     * Check if the code within a closure uses the $this variable.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile  The file being scanned.
     * @param int                   $startToken The position within the closure to continue searching from.
     * @param int                   $endToken   The closure scope closer to stop searching at.
     *
     * @return int|false The stackPtr to the first $this usage if found or false if
     *                   $this is not used.
     */
    protected function findThisUsageInClosure(File $phpcsFile, $startToken, $endToken)
    {
        // Make sure the $startToken is valid.
        if ($startToken >= $endToken) {
            return false;
        }

        return $phpcsFile->findNext(
            T_VARIABLE,
            $startToken,
            $endToken,
            false,
            '$this'
        );
    }

    /**
     * Check if the code within a closure uses "self/parent/static".
     *
     * @param \PHP_CodeSniffer_File $phpcsFile  The file being scanned.
     * @param int                   $startToken The position within the closure to continue searching from.
     * @param int                   $endToken   The closure scope closer to stop searching at.
     *
     * @return int|false The stackPtr to the first classRef usage if found or false if
     *                   they are not used.
     */
    protected function findClassRefUsageInClosure(File $phpcsFile, $startToken, $endToken)
    {
        // Make sure the $startToken is valid.
        if ($startToken >= $endToken) {
            return false;
        }

        $tokens   = $phpcsFile->getTokens();
        $classRef = $phpcsFile->findNext(array(T_SELF, T_PARENT, T_STATIC), $startToken, $endToken);

        if ($classRef === false || $tokens[$classRef]['code'] !== T_STATIC) {
            return $classRef;
        }

        // T_STATIC, make sure it is used as a class reference.
        $next = $phpcsFile->findNext(Tokens::$emptyTokens, ($classRef + 1), $endToken, true);
        if ($next === false || $tokens[$next]['code'] !== T_DOUBLE_COLON) {
            return false;
        }

        return $classRef;
    }
}
