<?php
/**
 * PHPCompatibility_Sniffs_PHP_RemovedFunctionParametersSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_RemovedFunctionParametersSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_RemovedFunctionParametersSniff extends PHPCompatibility_AbstractRemovedFeatureSniff
{
    /**
     * A list of removed function parameters, which were present in older versions.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * The index is the location of the parameter in the parameter list, starting at 0 !
     * If's sufficient to list the first version where the function parameter was deprecated/removed.
     *
     * @var array
     */
    protected $removedFunctionParameters = array(
        'gmmktime' => array(
            6 => array(
                'name' => 'is_dst',
                '5.1' => false,
                '7.0' => true,
            ),
        ),
        'ldap_first_attribute' => array(
            2 => array(
                'name' => 'ber_identifier',
                '5.2.4' => true,
            ),
        ),
        'ldap_next_attribute' => array(
            2 => array(
                'name' => 'ber_identifier',
                '5.2.4' => true,
            ),
        ),
        'mktime' => array(
            6 => array(
                'name' => 'is_dst',
                '5.1' => false,
                '7.0' => true,
            ),
        ),
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of function names.
        $this->removedFunctionParameters = $this->arrayKeysToLowercase($this->removedFunctionParameters);

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

        $function   = $tokens[$stackPtr]['content'];
        $functionLc = strtolower($function);

        if (isset($this->removedFunctionParameters[$functionLc]) === false) {
            return;
        }

        $parameterCount = $this->getFunctionCallParameterCount($phpcsFile, $stackPtr);
        if ($parameterCount === 0) {
            return;
        }

        // If the parameter count returned > 0, we know there will be valid open parenthesis.
        $openParenthesis      = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        $parameterOffsetFound = $parameterCount - 1;

        foreach ($this->removedFunctionParameters[$functionLc] as $offset => $parameterDetails) {
            if ($offset <= $parameterOffsetFound) {
                $itemInfo = array(
                    'name'   => $function,
                    'nameLc' => $functionLc,
                    'offset' => $offset,
                );
                $this->handleFeature($phpcsFile, $openParenthesis, $itemInfo);
            }
        }

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
        return $this->removedFunctionParameters[$itemInfo['nameLc']][$itemInfo['offset']];
    }


    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * @return array
     */
    protected function getNonVersionArrayKeys()
    {
        return array('name');
    }


    /**
     * Retrieve the relevant detail (version) information for use in an error message.
     *
     * @param array $itemArray Version and other information about the item.
     * @param array $itemInfo  Base information about the item.
     *
     * @return array
     */
    public function getErrorInfo(array $itemArray, array $itemInfo)
    {
        $errorInfo = parent::getErrorInfo($itemArray, $itemInfo);
        $errorInfo['paramName'] = $itemArray['name'];

        return $errorInfo;
    }


    /**
     * Get the item name to be used for the creation of the error code.
     *
     * @param array $itemInfo  Base information about the item.
     * @param array $errorInfo Detail information about an item.
     *
     * @return string
     */
    protected function getItemName(array $itemInfo, array $errorInfo)
    {
        return $itemInfo['name'].'_'.$errorInfo['paramName'];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The "%s" parameter for function %s() is ';
    }


    /**
     * Allow for concrete child classes to filter the error data before it's passed to PHPCS.
     *
     * @param array $data      The error data array which was created.
     * @param array $itemInfo  Base information about the item this error message applied to.
     * @param array $errorInfo Detail information about an item this error message applied to.
     *
     * @return array
     */
    protected function filterErrorData(array $data, array $itemInfo, array $errorInfo)
    {
        array_shift($data);
        array_unshift($data, $errorInfo['paramName'], $itemInfo['name']);
        return $data;
    }


}//end class
