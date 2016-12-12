<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewScalarReturnTypeDeclarationsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewScalarReturnTypeDeclarationsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_NewScalarReturnTypeDeclarationsSniff extends PHPCompatibility_AbstractNewFeatureSniff
{

    /**
     * A list of new types
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newTypes = array (
                                        'int' => array(
                                            '5.6' => false,
                                            '7.0' => true,
                                        ),
                                        'float' => array(
                                            '5.6' => false,
                                            '7.0' => true,
                                        ),
                                        'bool' => array(
                                            '5.6' => false,
                                            '7.0' => true,
                                        ),
                                        'string' => array(
                                            '5.6' => false,
                                            '7.0' => true,
                                        ),

                                        'void' => array(
                                            '7.0' => false,
                                            '7.1' => true,
                                        ),
                                    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        if (version_compare(PHP_CodeSniffer::VERSION, '2.3.4') >= 0) {
            return array(T_RETURN_TYPE);
        } else {
            return array();
        }
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

        if (isset($this->newTypes[$tokens[$stackPtr]['content']]) === true) {
            $itemInfo = array(
                'name'   => $tokens[$stackPtr]['content'],
            );
            $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
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
        return $this->newTypes[$itemInfo['name']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return '%s return type is not present in PHP version %s or earlier';
    }


}//end class
