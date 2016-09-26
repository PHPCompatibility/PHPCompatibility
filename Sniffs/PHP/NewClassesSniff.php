<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewClassesSniff.
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
class PHPCompatibility_Sniffs_PHP_NewClassesSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of new classes, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the class appears.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newClasses = array(
                                        'DateTime' => array(
                                            '5.1' => false,
                                            '5.2' => true
                                        ),
                                        'DateTimeZone' => array(
                                            '5.1' => false,
                                            '5.2' => true
                                        ),
                                        'RegexIterator' => array(
                                            '5.1' => false,
                                            '5.2' => true
                                        ),
                                        'RecursiveRegexIterator' => array(
                                            '5.1' => false,
                                            '5.2' => true
                                        ),

                                        'DateInterval' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'DatePeriod' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'Phar' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'PharData' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'PharException' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'PharFileInfo' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'FilesystemIterator' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'GlobIterator' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'MultipleIterator' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'RecursiveTreeIterator' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'SplDoublyLinkedList' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'SplFixedArray' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'SplHeap' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'SplMaxHeap' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'SplMinHeap' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'SplPriorityQueue' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'SplQueue' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),
                                        'SplStack' => array(
                                            '5.2' => false,
                                            '5.3' => true
                                        ),

                                        'CallbackFilterIterator' => array(
                                            '5.3' => false,
                                            '5.4' => true
                                        ),
                                        'RecursiveCallbackFilterIterator' => array(
                                            '5.3' => false,
                                            '5.4' => true
                                        ),
                                        'ReflectionZendExtension' => array(
                                            '5.3' => false,
                                            '5.4' => true
                                        ),
                                        'SessionHandler' => array(
                                            '5.3' => false,
                                            '5.4' => true
                                        ),
                                        'SNMP' => array(
                                            '5.3' => false,
                                            '5.4' => true
                                        ),
                                        'Transliterator' => array(
                                            '5.3' => false,
                                            '5.4' => true
                                        ),
                                        'Spoofchecker' => array(
                                            '5.3' => false,
                                            '5.4' => true
                                        ),

                                        'CURLFile' => array(
                                            '5.4' => false,
                                            '5.5' => true
                                        ),
                                        'DateTimeImmutable' => array(
                                            '5.4' => false,
                                            '5.5' => true
                                        ),
                                        'IntlCalendar' => array(
                                            '5.4' => false,
                                            '5.5' => true
                                        ),
                                        'IntlGregorianCalendar' => array(
                                            '5.4' => false,
                                            '5.5' => true
                                        ),
                                        'IntlTimeZone' => array(
                                            '5.4' => false,
                                            '5.5' => true
                                        ),
                                        'IntlBreakIterator' => array(
                                            '5.4' => false,
                                            '5.5' => true
                                        ),
                                        'IntlRuleBasedBreakIterator' => array(
                                            '5.4' => false,
                                            '5.5' => true
                                        ),
                                        'IntlCodePointBreakIterator' => array(
                                            '5.4' => false,
                                            '5.5' => true
                                        ),

                                    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of class names.
        $keys = array_keys( $this->newClasses );
        $keys = array_map( 'strtolower', $keys );
        $this->newClasses = array_combine( $keys, $this->newClasses );

        return array(
                T_NEW,
                T_CLASS,
                T_DOUBLE_COLON,
               );

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
        $tokens      = $phpcsFile->getTokens();
        $FQClassName = '';

        if ($tokens[$stackPtr]['type'] === 'T_NEW') {
            $FQClassName = $this->getFQClassNameFromNewToken($phpcsFile, $stackPtr);
        }
        else if ($tokens[$stackPtr]['type'] === 'T_CLASS') {
            $FQClassName = $this->getFQExtendedClassName($phpcsFile, $stackPtr);
        }
        else if ($tokens[$stackPtr]['type'] === 'T_DOUBLE_COLON') {
            $FQClassName = $this->getFQClassNameFromDoubleColonToken($phpcsFile, $stackPtr);
        }

        if ($FQClassName === '') {
            return;
        }

        if ($this->isNamespaced($FQClassName) === true) {
            return;
        }

        $className   = substr($FQClassName, 1); // Remove global namespace indicator.
        $classNameLc = strtolower($className);

        if (isset($this->newClasses[$classNameLc]) === false) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $className);

    }//end process()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the function
     *                                        in the token array.
     * @param string               $className The name of the class.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $className)
    {
        $error       = '';
        $isError     = false;
        $classNameLc = strtolower($className);

        foreach ($this->newClasses[$classNameLc] as $version => $present) {
            if ($this->supportsBelow($version)) {
                if ($present === false) {
                    $isError = true;
                    $error .= 'not present in PHP version ' . $version . ' or earlier';
                }
            }
        }
        if (strlen($error) > 0) {
            $error = 'The built-in class ' . $className . ' is ' . $error;

            if ($isError === true) {
                $phpcsFile->addError($error, $stackPtr);
            } else {
                $phpcsFile->addWarning($error, $stackPtr);
            }
        }

    }//end addError()

}//end class
