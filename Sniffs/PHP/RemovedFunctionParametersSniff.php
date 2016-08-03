<?php
/**
 * PHPCompatibility_Sniffs_PHP_RemovedFunctionParametersSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_RemovedFunctionParametersSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_RemovedFunctionParametersSniff extends PHPCompatibility_Sniff
{

    /**
     * If true, forbidden functions will be considered regular expressions.
     *
     * @var bool
     */
    protected $patternMatch = false;
    
    /**
     * A list of Removed function parameters, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * The index is the location of the parameter in the parameter list, starting at 0 !
     * If's sufficient to list the first version where the function appears.
     *
     * @var array
     */
    protected $removedFunctionParameters = array(
                                        'mktime' => array(
                                            6 => array(
                                                'name' => 'is_dst',
                                                '5.1' => true,
                                                '7.0' => false
                                            ),
                                        ),
                                        'gmmktime' => array(
                                            6 => array(
                                                'name' => 'is_dst',
                                                '7.0' => false
                                            ),
                                        ),
                                    );


    /**
     * 
     * @var array
     */
    private $removedFunctionParametersNames;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Everyone has had a chance to figure out what forbidden functions
        // they want to check for, so now we can cache out the list.
        $this->removedFunctionParametersNames = array_keys($this->removedFunctionParameters);
    
        if ($this->patternMatch === true) {
            foreach ($this->removedFunctionParametersNames as $i => $name) {
                $this->removedFunctionParametersNames[$i] = '/'.$name.'/i';
            }
        }
    
        return array(T_STRING);
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

        $ignore = array(
                T_DOUBLE_COLON,
                T_OBJECT_OPERATOR,
                T_FUNCTION,
                T_CONST,
        );

        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if (in_array($tokens[$prevToken]['code'], $ignore) === true) {
            // Not a call to a PHP function.
            return;
        }

        $function = strtolower($tokens[$stackPtr]['content']);

        if (in_array($function, $this->removedFunctionParametersNames) === false) {
            return;
        }
        
        if (isset($tokens[$stackPtr + 1]) && $tokens[$stackPtr + 1]['type'] == 'T_OPEN_PARENTHESIS') {
            $closeParenthesis = $tokens[$stackPtr + 1]['parenthesis_closer'];
            $openParenthesis = $tokens[$closeParenthesis]['parenthesis_opener'];

            $nextItem = $phpcsFile->findNext(array(), $openParenthesis + 1, $closeParenthesis, true);
            if (isset($tokens[$nextItem]['nested_parenthesis'])) {
                $parenthesisCount = count($tokens[$nextItem]['nested_parenthesis']);
            } else {
                $parenthesisCount = 0;
            }

            $nextComma = $stackPtr + 1;
            $cnt = 0;
            while ($nextComma = $phpcsFile->findNext(array(T_COMMA, T_CLOSE_PARENTHESIS), $nextComma + 1, $closeParenthesis + 1)) {
                if (
                    $tokens[$nextComma]['type'] == 'T_COMMA'
                    &&
                    isset($tokens[$nextComma]['nested_parenthesis'])
                    &&
                    count($tokens[$nextComma]['nested_parenthesis']) != $parenthesisCount
                ) {
                    continue;
                }
                
                if ($tokens[$nextComma]['type'] == 'T_CLOSE_PARENTHESIS' && $nextComma != $closeParenthesis) {
                    continue;
                }
                
                if (isset($this->removedFunctionParameters[$function][$cnt])) {
                    $this->addError($phpcsFile, $nextComma, $function, $cnt);
                }
                $cnt++;
            }
            
        }
    }//end process()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile         The file being scanned.
     * @param int                  $stackPtr          The position of the function
     *                                                in the token array.
     * @param string               $function          The name of the function.
     * @param int                  $parameterLocation The parameter position within the function call.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $function, $parameterLocation)
    {
        $error = '';

        $isError = false;
        foreach ($this->removedFunctionParameters[$function][$parameterLocation] as $version => $present) {
            if ($version != 'name' && $this->supportsAbove($version)) {
                if ($present === false) {
                    $isError = true;
                    $error .= 'in PHP version ' . $version . ' or later';
                }
            }
        }
        
        if (strlen($error) > 0) {
            $error = 'The function ' . $function . ' does not have a parameter ' . $this->removedFunctionParameters[$function][$parameterLocation]['name'] . ' ' . $error;

            if ($isError === true) {
                $phpcsFile->addError($error, $stackPtr);
            } else {
                $phpcsFile->addWarning($error, $stackPtr);
            }
        }

    }//end addError()

}//end class
