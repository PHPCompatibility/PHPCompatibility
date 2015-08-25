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
                                        'JsonSerializable' => array(
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
        return array(T_NEW);

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
        if (
            $tokens[$stackPtr + 2]['type'] == 'T_STRING'
            &&
            (
                $tokens[$stackPtr + 3]['type'] == 'T_OPEN_PARENTHESIS'
                ||
                (
                    $tokens[$stackPtr + 3]['type'] == 'T_WHITESPACE'
                    &&
                    $tokens[$stackPtr + 4]['type'] == 'T_OPEN_PARENTHESIS'
                )
            )
        ) {
            $className = $tokens[$stackPtr + 2]['content'];
            if (array_key_exists($className, $this->newClasses) === false) {
                return;
            }
            $this->addError($phpcsFile, $stackPtr, $className);
        }



    }//end process()


    /**
     * Generates the error or wanrning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the function
     *                                        in the token array.
     * @param string               $function  The name of the function.
     * @param string               $pattern   The pattern used for the match.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $className, $pattern=null)
    {
        if ($pattern === null) {
            $pattern = $className;
        }

        $error = '';

        $this->error = false;
        foreach ($this->newClasses[$pattern] as $version => $present) {
            if ($this->supportsBelow($version)) {
                if ($present === false) {
                    $this->error = true;
                    $error .= 'not present in PHP version ' . $version . ' or earlier';
                }
            }
        }
        if (strlen($error) > 0) {
            $error = 'The built-in class ' . $className . ' is ' . $error;

            if ($this->error === true) {
                $phpcsFile->addError($error, $stackPtr);
            } else {
                $phpcsFile->addWarning($error, $stackPtr);
            }
        }

    }//end addError()

}//end class
