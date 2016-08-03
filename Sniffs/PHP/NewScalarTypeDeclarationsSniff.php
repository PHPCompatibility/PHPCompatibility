<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewScalarTypeDeclarationsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewScalarTypeDeclarationsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_NewScalarTypeDeclarationsSniff extends PHPCompatibility_Sniff
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
                                            'description' => 'int type'
                                        ),
                                        'float' => array(
                                            '5.6' => false,
                                            '7.0' => true,
                                            'description' => 'float type'
                                        ),
                                        'bool' => array(
                                            '5.6' => false,
                                            '7.0' => true,
                                            'description' => 'bool type'
                                        ),
                                        'string' => array(
                                            '5.6' => false,
                                            '7.0' => true,
                                            'description' => 'string type'
                                        ),
                                    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_FUNCTION);
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
        // Get all parameters from method signature.
        $paramNames = $phpcsFile->getMethodParameters($stackPtr);
        foreach ($paramNames as $param) {
            if (in_array($param['type_hint'], array_keys($this->newTypes))) {
                $this->addError($phpcsFile, $stackPtr, $param['type_hint']);
            }
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
    protected function addError($phpcsFile, $stackPtr, $typeName, $pattern=null)
    {
        if ($pattern === null) {
            $pattern = $typeName;
        }

        $error = '';

        $isError = false;
        foreach ($this->newTypes[$pattern] as $version => $present) {
            if ($this->supportsBelow($version)) {
                if ($present === false) {
                    $isError = true;
                    $error .= 'not present in PHP version ' . $version . ' or earlier';
                }
            }
        }
        if (strlen($error) > 0) {
            $error = $this->newTypes[$typeName]['description'] . ' is ' . $error;

            if ($isError === true) {
                $phpcsFile->addError($error, $stackPtr);
            } else {
                $phpcsFile->addWarning($error, $stackPtr);
            }
        }

    }//end addError()

}//end class
