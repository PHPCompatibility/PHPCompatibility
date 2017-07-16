<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewClosure.
 *
 * PHP version 5.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewClosure.
 *
 * Closures are available since PHP 5.3
 *
 * PHP version 5.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_NewClosureSniff extends PHPCompatibility_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CLOSURE);

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.2')) {
            $phpcsFile->addError(
                'Closures / anonymous functions are not available in PHP 5.2 or earlier',
                $stackPtr,
                'Found'
            );
        }

        $isStatic = $this->isClosureStatic($phpcsFile, $stackPtr);
        $usesThis = $this->findThisUsageInClosure($phpcsFile, $stackPtr);

        if ($this->supportsBelow('5.3')) {

            /*
             * Closures can only be declared as static since PHP 5.4.
             */
            if ($isStatic === true) {
                $phpcsFile->addError(
                    'Closures / anonymous functions could not be declared as static in PHP 5.3 or earlier',
                    $stackPtr,
                    'StaticFound'
                );
            }

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

                    $thisFound = $this->findThisUsageInClosure($phpcsFile, $stackPtr, ($thisFound + 1));

                } while ($thisFound !== false);
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
                    $phpcsFile->addError(
                        'Closures / anonymous functions only have access to $this if used within a class',
                        $thisFound,
                        'ThisFoundOutsideClass'
                    );
                }

                $thisFound = $this->findThisUsageInClosure($phpcsFile, $stackPtr, ($thisFound + 1));

            } while ($thisFound !== false);
        }

    }//end process()


    /**
     * Check whether the closure is declared as static.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function isClosureStatic(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $prevToken = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true);

        return ($prevToken !== false && $tokens[$prevToken]['code'] === T_STATIC);
    }


    /**
     * Check if the code within a closure uses the $this variable.
     *
     * @param PHP_CodeSniffer_File $phpcsFile  The file being scanned.
     * @param int                  $stackPtr   The position of the closure token.
     * @param int                  $startToken Optional. The position within the closure to continue searching from.
     *
     * @return int|false The stackPtr to the first $this usage if found or false if
     *                   $this is not used or usage of $this could not reliably be determined.
     */
    protected function findThisUsageInClosure(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $startToken = null)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_opener'], $tokens[$stackPtr]['scope_closer']) === false) {
            // Live coding or parse error.
            return false;
        }

        // Make sure the optional $startToken is valid.
        if (isset($startToken) === true && (isset($tokens[$startToken]) === false || $startToken >= $tokens[$stackPtr]['scope_closer'])) {
            return false;
        }

        $start = ($tokens[$stackPtr]['scope_opener'] + 1);
        if (isset($startToken) === true) {
            $start = $startToken;
        }

        return $phpcsFile->findNext(
            T_VARIABLE,
            $start,
            $tokens[$stackPtr]['scope_closer'],
            false,
            '$this'
        );
    }

}//end class
