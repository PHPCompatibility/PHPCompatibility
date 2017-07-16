<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewClassesSniff.
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
     * @var array(string => array(string => bool))
     */
    protected $newClasses = array(
        'libXMLError' => array(
            '5.0' => false,
            '5.1' => true,
        ),

        'DateTime' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'DateTimeZone' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'RegexIterator' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'RecursiveRegexIterator' => array(
            '5.1' => false,
            '5.2' => true,
        ),

        'DateInterval' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'DatePeriod' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'Phar' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'PharData' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'PharFileInfo' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'FilesystemIterator' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'GlobIterator' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'MultipleIterator' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'RecursiveTreeIterator' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'SplDoublyLinkedList' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'SplFixedArray' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'SplHeap' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'SplMaxHeap' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'SplMinHeap' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'SplPriorityQueue' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'SplQueue' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'SplStack' => array(
            '5.2' => false,
            '5.3' => true,
        ),

        'CallbackFilterIterator' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'RecursiveCallbackFilterIterator' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'ReflectionZendExtension' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'SessionHandler' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'SNMP' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'Transliterator' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'Spoofchecker' => array(
            '5.3' => false,
            '5.4' => true,
        ),

        'CURLFile' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'DateTimeImmutable' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'IntlCalendar' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'IntlGregorianCalendar' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'IntlTimeZone' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'IntlBreakIterator' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'IntlRuleBasedBreakIterator' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'IntlCodePointBreakIterator' => array(
            '5.4' => false,
            '5.5' => true,
        ),

    );

    /**
     * A list of new Exception classes, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the class appears.
     *
     * {@internal Classes listed here do not need to be added to the $newClasses
     *            property as well.
     *            This list is automatically added to the $newClasses property
     *            in the `register()` method.}}
     *
     * @var array(string => array(string => bool))
     */
    protected $newExceptions = array(
        'Exception' => array(
            // According to the docs introduced in PHP 5.1, but this appears to be.
            // an error.  Class was introduced with try/catch keywords in PHP 5.0.
            '4.4' => false,
            '5.0' => true,
        ),
        'ErrorException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'BadFunctionCallException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'BadMethodCallException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'DomainException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'InvalidArgumentException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'LengthException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'LogicException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'OutOfBoundsException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'OutOfRangeException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'OverflowException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'RangeException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'RuntimeException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'UnderflowException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'UnexpectedValueException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'DOMException' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'mysqli_sql_exception' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'PDOException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'ReflectionException' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'SoapFault' => array(
            '4.4' => false,
            '5.0' => true,
        ),

        'PharException' => array(
            '5.2' => false,
            '5.3' => true,
        ),

        'SNMPException' => array(
            '5.3' => false,
            '5.4' => true,
        ),

        'IntlException' => array(
            '5.5.0' => false,
            '5.5.1' => true,
        ),

        'Error' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'ArithmeticError' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'AssertionError' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'DivisionByZeroError' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'ParseError' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'TypeError' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'UI\Exception\InvalidArgumentException' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'UI\Exception\RuntimeException' => array(
            '5.6' => false,
            '7.0' => true,
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
        $this->newExceptions = $this->arrayKeysToLowercase($this->newExceptions);

        // Add the Exception classes to the Classes list.
        $this->newClasses = array_merge($this->newClasses, $this->newExceptions);

        $targets = array(
            T_NEW,
            T_CLASS,
            T_DOUBLE_COLON,
            T_FUNCTION,
            T_CLOSURE,
            T_CATCH,
        );

        if (defined('T_ANON_CLASS')) {
            $targets[] = constant('T_ANON_CLASS');
        }

        return $targets;

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

        switch ($tokens[$stackPtr]['type']) {
            case 'T_FUNCTION':
            case 'T_CLOSURE':
                $this->processFunctionToken($phpcsFile, $stackPtr);
                break;

            case 'T_CATCH':
                $this->processCatchToken($phpcsFile, $stackPtr);
                break;

            default:
                $this->processSingularToken($phpcsFile, $stackPtr);
                break;
        }

    }//end process()


    /**
     * Processes this test for when a token resulting in a singular class name is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    private function processSingularToken(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens      = $phpcsFile->getTokens();
        $FQClassName = '';

        if ($tokens[$stackPtr]['type'] === 'T_NEW') {
            $FQClassName = $this->getFQClassNameFromNewToken($phpcsFile, $stackPtr);

        } elseif ($tokens[$stackPtr]['type'] === 'T_CLASS' || $tokens[$stackPtr]['type'] === 'T_ANON_CLASS') {
            $FQClassName = $this->getFQExtendedClassName($phpcsFile, $stackPtr);

        } elseif ($tokens[$stackPtr]['type'] === 'T_DOUBLE_COLON') {
            $FQClassName = $this->getFQClassNameFromDoubleColonToken($phpcsFile, $stackPtr);
        }

        if ($FQClassName === '') {
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

    }//end processSingularToken()


    /**
     * Processes this test for when a function token is encountered.
     *
     * - Detect new classes when used as a type hint.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    private function processFunctionToken(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        // Retrieve typehints stripped of global NS indicator and/or nullable indicator.
        $typeHints = $this->getTypeHintsFromFunctionDeclaration($phpcsFile, $stackPtr);
        if (empty($typeHints) || is_array($typeHints) === false) {
            return;
        }

        foreach ($typeHints as $hint) {

            $typeHintLc = strtolower($hint);

            if (isset($this->newClasses[$typeHintLc]) === true) {
                $itemInfo = array(
                    'name'   => $hint,
                    'nameLc' => $typeHintLc,
                );
                $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
            }
        }
    }


    /**
     * Processes this test for when a catch token is encountered.
     *
     * - Detect exceptions when used in a catch statement.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    private function processCatchToken(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Bow out during live coding.
        if (isset($tokens[$stackPtr]['parenthesis_opener'], $tokens[$stackPtr]['parenthesis_closer']) === false) {
            return;
        }

        $opener = $tokens[$stackPtr]['parenthesis_opener'];
        $closer = ($tokens[$stackPtr]['parenthesis_closer'] + 1);
        $name   = '';
        $listen = array(
            // Parts of a (namespaced) class name.
            T_STRING              => true,
            T_NS_SEPARATOR        => true,
            // End/split tokens.
            T_VARIABLE            => false,
            T_BITWISE_OR          => false,
            T_CLOSE_CURLY_BRACKET => false, // Shouldn't be needed as we expect a var before this.
        );

        for ($i = ($opener + 1); $i < $closer; $i++) {
            if (isset($listen[$tokens[$i]['code']]) === false) {
                continue;
            }

            if ($listen[$tokens[$i]['code']] === true) {
                $name .= $tokens[$i]['content'];
                continue;
            } else {
                if (empty($name) === true) {
                    // Weird, we should have a name by the time we encounter a variable or |.
                    // So this may be the closer.
                    continue;
                }

                $name   = ltrim($name, '\\');
                $nameLC = strtolower($name);

                if (isset($this->newExceptions[$nameLC]) === true) {
                    $itemInfo = array(
                        'name'   => $name,
                        'nameLc' => $nameLC,
                    );
                    $this->handleFeature($phpcsFile, $i, $itemInfo);
                }

                // Reset for a potential multi-catch.
                $name = '';
            }
        }
    }


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
