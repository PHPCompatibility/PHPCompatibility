<?php
/**
 * PHPCompatibility_Sniffs_PHP_RemovedGlobalVariablesSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_RemovedGlobalVariablesSniff.
 *
 * Discourages the use of removed global variables. Suggests alternative extensions if available
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_RemovedGlobalVariablesSniff extends PHPCompatibility_AbstractRemovedFeatureSniff
{

    /**
     * A list of removed global variables with their alternative, if any.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the variable was deprecated/removed.
     *
     * @var array(string|null)
     */
    protected $removedGlobalVariables = array(
        'HTTP_POST_VARS' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => '$_POST',
        ),
        'HTTP_GET_VARS' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => '$_GET',
        ),
        'HTTP_ENV_VARS' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => '$_ENV',
        ),
        'HTTP_SERVER_VARS' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => '$_SERVER',
        ),
        'HTTP_COOKIE_VARS' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => '$_COOKIE',
        ),
        'HTTP_SESSION_VARS' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => '$_SESSION',
        ),
        'HTTP_POST_FILES' => array(
            '5.3' => false,
            '5.4' => true,
            'alternative' => '$_FILES',
        ),

        'HTTP_RAW_POST_DATA' => array(
            '5.6' => false,
            '7.0' => true,
            'alternative' => 'php://input',
        ),
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_VARIABLE);

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
        if ($this->supportsAbove('5.3') === false) {
            return;
        }

        $tokens  = $phpcsFile->getTokens();
        $varName = substr($tokens[$stackPtr]['content'], 1);

        if (isset($this->removedGlobalVariables[$varName]) === false) {
            return;
        }

        if ($this->isClassProperty($phpcsFile, $stackPtr) === true) {
            // Ok, so this was a class property declaration, not our concern.
            return;
        }

        // Check for static usage of class properties shadowing the removed global variables.
        if ($this->inClassScope($phpcsFile, $stackPtr, false) === true) {
            $prevToken = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true, null, true);
            if ($prevToken !== false && $tokens[$prevToken]['code'] === T_DOUBLE_COLON) {
                return;
            }
        }

        // Still here, so throw an error/warning.
        $itemInfo = array(
            'name' => $varName,
        );
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);

    }//end process()


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->removedGlobalVariables[$itemInfo['name']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return "Global variable '\$%s' is ";
    }


}//end class
