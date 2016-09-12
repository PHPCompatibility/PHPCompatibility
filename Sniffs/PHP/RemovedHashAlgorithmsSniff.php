<?php
/**
 * PHPCompatibility_Sniffs_PHP_RemovedHashAlgorithmsSniff.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_RemovedHashAlgorithmsSniff.
 *
 * Discourages the use of deprecated and removed hash algorithms
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_RemovedHashAlgorithmsSniff extends PHPCompatibility_Sniff
{

    /**
     * List of functions using the algorithm as parameter (always the first parameter)
     *
     * @var array
     */
    protected $algoFunctions = array(
        'hash_file',
        'hash_hmac_file',
        'hash_hmac',
        'hash_init',
        'hash'
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('5.4')) {
            $tokens = $phpcsFile->getTokens();

            if (in_array($tokens[$stackPtr]['content'], $this->algoFunctions) === true) {
                $openBracket = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);

                if ($tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS) {
                    return;
                }

                $firstParam = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($openBracket + 1), null, true);

                /**
                 * Algorithm is a T_CONSTANT_ENCAPSED_STRING, so we need to remove the quotes
                 */
                $algo = strtolower($tokens[$firstParam]['content']);
                $algo = $this->stripQuotes($algo);
                switch ($algo) {
                    case 'salsa10':
                    case 'salsa20':
                        $error = 'The Salsa10 and Salsa20 hash algorithms have been removed since PHP 5.4';
                        $phpcsFile->addError($error, $stackPtr);
                        break;
                }

            }
        }


    }//end process()


}//end class
