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
class PHPCompatibility_Sniffs_PHP_NewClassesSniff extends PHPCompatibility_AbstractNewFeatureSniff
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
        $this->newClasses = $this->arrayKeysToLowercase($this->newClasses);

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

        $itemInfo = array(
            'name'   => $className,
            'nameLc' => $classNameLc,
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
        return $this->newClasses[$itemInfo['nameLc']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The built-in class '.parent::getErrorMsgTemplate();
    }


}//end class
