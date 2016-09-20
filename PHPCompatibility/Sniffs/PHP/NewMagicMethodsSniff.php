<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewMagicMethodsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewMagicMethodsSniff.
 *
 * Warns for non-magic behaviour of magic methods prior to becoming magic.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_NewMagicMethodsSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of new magic methods, not considered magic in older versions.
     *
     * Method names in the array should be all *lowercase*.
     * The array lists : version number with false (not magic) or true (magic).
     * If's sufficient to list the first version where the method became magic.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newMagicMethods = array(
                               '__get' => array( // verified
                                   '4.4' => false,
                                   '5.0' => true,
                               ),

                               '__isset' => array( // verified
                                   '5.0' => false,
                                   '5.1' => true,
                               ),
                               '__unset' => array( // verified
                                   '5.0' => false,
                                   '5.1' => true,
                               ),
                               '__set_state' => array( // verified
                                   '5.0' => false,
                                   '5.1' => true,
                               ),

                               '__callstatic' => array( // verified
                                   '5.2' => false,
                                   '5.3' => true,
                               ),
                               '__invoke' => array( // verified
                                   '5.2' => false,
                                   '5.3' => true,
                               ),

                               '__debuginfo' => array( // verified
                                   '5.5' => false,
                                   '5.6' => true,
                               ),

                               // Special case - only became properly magical in 5.2.0,
                               // before that it was only called for echo and print.
                               '__tostring' => array(
                                   '5.1' => false,
                                   '5.2' => true,
                                   'message' => 'The method %s() was not truly magical in PHP version %s and earlier. The associated magic functionality will only be called when directly combined with echo or print.',
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
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $functionName   = $phpcsFile->getDeclarationName($stackPtr);
        $functionNameLc = strtolower($functionName);

        if (isset($this->newMagicMethods[$functionNameLc]) === false) {
            return;
        }

        if ($this->inClassScope($phpcsFile, $stackPtr, false) === false) {
            return;
        }

        $lastVersionBelow = '';
        foreach ($this->newMagicMethods[$functionNameLc] as $version => $magic) {
            if ($version !== 'message' && $magic === false && $this->supportsBelow($version)) {
                $lastVersionBelow = $version;
            }
        }

        if ($lastVersionBelow !== '') {
            $error = 'The method %s() was not magical in PHP version %s and earlier. The associated magic functionality will not be invoked.';
            if (empty($this->newMagicMethods[$functionNameLc]['message']) === false) {
                $error = $this->newMagicMethods[$functionNameLc]['message'];
            }

            $data  = array(
                $functionName,
                $lastVersionBelow,
            );

            $phpcsFile->addWarning($error, $stackPtr, 'Found', $data);
        }

    }//end process()

}//end class
