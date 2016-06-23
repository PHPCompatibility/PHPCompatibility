<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewOperatorsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewOperatorsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_NewOperatorsSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of new operators
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newOperators = array (
                                        'T_SPACESHIP' => array(
                                            '5.6' => false,
                                            '7.0' => true,
                                            'description' => 'Spaceship operator'
                                        ),
                                    );


    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    protected $error = false;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $tokens = array();
        foreach ($this->newOperators as $token => $versions) {
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
     * Generates the error or wanrning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the function
     *                                        in the token array.
     * @param string               $typeName  The type.
     * @param string               $pattern   The pattern used for the match.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $operatorName, $pattern=null)
    {
        if ($pattern === null) {
            $pattern = $operatorName;
        }
        
        $error = '';

        $this->error = false;
        foreach ($this->newOperators[$pattern] as $version => $present) {
            if ($this->supportsBelow($version)) {
                if ($present === false) {
                    $this->error = true;
                    $error .= 'not present in PHP version ' . $version . ' or earlier';
                }
            }
        }
        if (strlen($error) > 0) {
            $error = $this->newOperators[$operatorName]['description'] . ' is ' . $error;

            if ($this->error === true) {
                $phpcsFile->addError($error, $stackPtr);
            } else {
                $phpcsFile->addWarning($error, $stackPtr);
            }
        }
    }//end addError()

}//end class
