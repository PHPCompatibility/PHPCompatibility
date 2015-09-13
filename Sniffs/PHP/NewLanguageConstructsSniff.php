<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewLanguageConstructsSniff.
 *
 * PHP version 5.5
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2013 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewLanguageConstructsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @version   1.0.0
 * @copyright 2013 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_NewLanguageConstructsSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of new language constructs, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newConstructs = array(
                                        'T_NS_SEPARATOR' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => 'the \ operator (for namespaces)'
                                        ),
                                        'T_POW' => array(
                                            '5.5' => false,
                                            '5.6' => true,
                                            'description' => 'power operator (**)'
                                        ),
                                        'T_POW_EQUAL' => array(
                                            '5.5' => false,
                                            '5.6' => true,
                                            'description' => 'power assignment operator (**=)'
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
        foreach ($this->newConstructs as $token => $versions) {
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
        $this->addError($phpcsFile, $stackPtr, $tokens[$stackPtr]['type']);
    }//end process()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the function
     *                                        in the token array.
     * @param string               $function  The name of the function.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $keywordName, $pattern=null)
    {
        if ($pattern === null) {
            $pattern = $keywordName;
        }

        $error = '';

        $this->error = false;
        foreach ($this->newConstructs[$pattern] as $version => $present) {
            if ($this->supportsBelow($version)) {
                if ($present === false) {
                    $this->error = true;
                    $error .= 'not present in PHP version ' . $version . ' or earlier';
                }
            }
        }
        if (strlen($error) > 0) {
            $error = $this->newConstructs[$keywordName]['description'] . ' is ' . $error;

            if ($this->error === true) {
                $phpcsFile->addError($error, $stackPtr);
            } else {
                $phpcsFile->addWarning($error, $stackPtr);
            }
        }

    }//end addError()

}//end class
