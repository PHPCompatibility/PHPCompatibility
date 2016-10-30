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
class PHPCompatibility_Sniffs_PHP_NewScalarTypeDeclarationsSniff extends PHPCompatibility_AbstractNewFeatureSniff
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
                           'array' => array(
                               '5.0' => false,
                               '5.1' => true,
                           ),
                           'callable' => array(
                               '5.3' => false,
                               '5.4' => true,
                           ),
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
                           'iterable' => array(
                               '7.0' => false,
                               '7.1' => true,
                           ),
                          );


    /**
     * Invalid types
     *
     * The array lists : the invalid type hint => what was probably intended/alternative.
     *
     * @var array(string => string)
     */
    protected $invalidTypes = array (
                               'parent'  => 'self',
                               'static'  => 'self',
                               'boolean' => 'bool',
                               'integer' => 'int',
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
        $paramNames   = $this->getMethodParameters($phpcsFile, $stackPtr);
        if (empty($paramNames)) {
            return;
        }

        $supportsPHP4 = $this->supportsBelow('4.4');

        foreach ($paramNames as $param) {
            if ($param['type_hint'] === '') {
                continue;
            }

            if ($supportsPHP4 === true) {
                $phpcsFile->addError(
                    'Type hints were not present in PHP 4.4 or earlier.',
                    $stackPtr,
                    'TypeHintFound'
                );
            }
            else if (isset($this->newTypes[$param['type_hint']])) {
                $itemInfo = array(
                    'name'   => $param['type_hint'],
                );
                $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
            }
            else if (isset($this->invalidTypes[$param['type_hint']])) {
                $error = "'%s' is not a valid type declaration. Did you mean %s ?";
                $data  = array(
                    $param['type_hint'],
                    $this->invalidTypes[$param['type_hint']],
                );

                $phpcsFile->addError($error, $stackPtr, 'InvalidTypeHintFound', $data);
            }
            else if ($param['type_hint'] === 'self') {
                if ($this->inClassScope($phpcsFile, $stackPtr) === false) {
                    $phpcsFile->addError(
                        "'self' type cannot be used outside of class scope",
                        $stackPtr,
                        'SelfOutsideClassScopeFound'
                    );
                }
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
        return $this->newTypes[$itemInfo['name']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return "'%s' type declaration is not present in PHP version %s or earlier";
    }


}//end class
