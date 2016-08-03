<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewKeywordsSniff.
 *
 * PHP version 5.5
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2013 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewClassesSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @version   1.0.0
 * @copyright 2013 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_NewKeywordsSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of new keywords, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newKeywords = array(
                                        'T_HALT_COMPILER' => array(
                                            '5.0' => false,
                                            '5.1' => true,
                                            'description' => '"__halt_compiler" keyword'
                                        ),
                                        'T_CONST' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '"const" keyword'
                                        ),
                                        'T_CALLABLE' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            'description' => '"callable" keyword'
                                        ),
                                        'T_DIR' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '__DIR__ magic constant'
                                        ),
                                        'T_GOTO' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '"goto" keyword'
                                        ),
                                        'T_INSTEADOF' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            'description' => '"insteadof" keyword (for traits)'
                                        ),
                                        'T_NAMESPACE' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '"namespace" keyword'
                                        ),
                                        'T_NS_C' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '__NAMESPACE__ magic constant'
                                        ),
                                        'T_USE' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '"use" keyword (for traits/namespaces/anonymous functions)'
                                        ),
                                        'T_TRAIT' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            'description' => '"trait" keyword'
                                        ),
                                        'T_TRAIT_C' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            'description' => '__TRAIT__ magic constant'
                                        ),
                                        'T_YIELD' => array(
                                            '5.4' => false,
                                            '5.5' => true,
                                            'description' => '"yield" keyword (for generators)'
                                        ),
                                        'T_FINALLY' => array(
                                            '5.4' => false,
                                            '5.5' => true,
                                            'description' => '"finally" keyword (in exception handling)'
                                        ),
                                        'T_START_NOWDOC' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => 'nowdoc functionality'
                                        ),
                                        'T_END_NOWDOC' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => 'nowdoc functionality'
                                        ),
                                    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $tokens = array();
        foreach ($this->newKeywords as $token => $versions) {
            if (defined($token)) {
                $tokens[] = constant($token);
            }
        }
        return $tokens;

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $nextToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        $prevToken = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);

        // Skip attempts to use keywords as functions or class names - the former
        // will be reported by FrobiddenNamesAsInvokedFunctionsSniff, whilst the
        // latter doesn't yet have an appropriate sniff.
        // Either type will result in false-positives when targetting lower versions
        // of PHP where the name was not reserved, unless we explicitly check for
        // them.
        if (
            $tokens[$nextToken]['type'] != 'T_OPEN_PARENTHESIS'
            &&
            $tokens[$prevToken]['type'] != 'T_CLASS'
        ) {
            $this->addError($phpcsFile, $stackPtr, $tokens[$stackPtr]['type']);
        }
    }//end process()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                  $stackPtr    The position of the function
     *                                          in the token array.
     * @param string               $keywordName The name of the keyword.
     * @param string               $pattern     The pattern used for the match.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $keywordName, $pattern=null)
    {
        if ($pattern === null) {
            $pattern = $keywordName;
        }

        $error = '';

        $isError = false;
        foreach ($this->newKeywords[$pattern] as $version => $present) {
            if ($this->supportsBelow($version)) {
                if ($present === false) {
                    $isError = true;
                    $error .= 'not present in PHP version ' . $version . ' or earlier';
                }
            }
        }
        if (strlen($error) > 0) {
            $error = $this->newKeywords[$keywordName]['description'] . ' is ' . $error;

            if ($isError === true) {
                $phpcsFile->addError($error, $stackPtr);
            } else {
                $phpcsFile->addWarning($error, $stackPtr);
            }
        }
    }//end addError()

}//end class
