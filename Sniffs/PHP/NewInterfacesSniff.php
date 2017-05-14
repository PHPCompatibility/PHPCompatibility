<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewInterfacesSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewInterfacesSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_NewInterfacesSniff extends PHPCompatibility_AbstractNewFeatureSniff
{

    /**
     * A list of new interfaces, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the interface appears.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newInterfaces = array(
        'Traversable' => array(
            '4.4' => false,
            '5.0' => true,
        ),

        'Countable' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'OuterIterator' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'RecursiveIterator' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'SeekableIterator' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'Serializable' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'SplObserver' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'SplSubject' => array(
            '5.0' => false,
            '5.1' => true,
        ),

        'JsonSerializable' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'SessionHandlerInterface' => array(
            '5.3' => false,
            '5.4' => true,
        ),

        'DateTimeInterface' => array(
            '5.4' => false,
            '5.5' => true,
        ),

        'Throwable' => array(
            '5.6' => false,
            '7.0' => true,
        ),

    );

    /**
     * A list of methods which cannot be used in combination with particular interfaces.
     *
     * @var array(string => array(string => string))
     */
    protected $unsupportedMethods = array(
        'Serializable' => array(
            '__sleep'  => 'http://php.net/serializable',
            '__wakeup' => 'http://php.net/serializable',
        ),
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of interface names.
        $this->newInterfaces      = $this->arrayKeysToLowercase($this->newInterfaces);
        $this->unsupportedMethods = $this->arrayKeysToLowercase($this->unsupportedMethods);

        $targets = array(
            T_CLASS,
            T_FUNCTION,
            T_CLOSURE,
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
            case 'T_CLASS':
            case 'T_ANON_CLASS':
                $this->processClassToken($phpcsFile, $stackPtr);
                break;

            case 'T_FUNCTION':
            case 'T_CLOSURE':
                $this->processFunctionToken($phpcsFile, $stackPtr);
                break;

            default:
                // Deliberately left empty.
                break;
        }

    }//end process()


    /**
     * Processes this test for when a class token is encountered.
     *
     * - Detect classes implementing the new interfaces.
     * - Detect classes implementing the new interfaces with unsupported functions.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    private function processClassToken(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $interfaces = $this->findImplementedInterfaceNames($phpcsFile, $stackPtr);

        if (is_array($interfaces) === false || $interfaces === array()) {
            return;
        }

        $tokens       = $phpcsFile->getTokens();
        $checkMethods = false;

        if (isset($tokens[$stackPtr]['scope_closer'])) {
            $checkMethods = true;
            $scopeCloser  = $tokens[$stackPtr]['scope_closer'];
        }

        foreach ($interfaces as $interface) {
            $interfaceLc = strtolower($interface);

            if (isset($this->newInterfaces[$interfaceLc]) === true) {
                $itemInfo = array(
                    'name'   => $interface,
                    'nameLc' => $interfaceLc,
                );
                $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
            }

            if ($checkMethods === true && isset($this->unsupportedMethods[$interfaceLc]) === true) {
                $nextFunc = $stackPtr;
                while (($nextFunc = $phpcsFile->findNext(T_FUNCTION, ($nextFunc + 1), $scopeCloser)) !== false) {
                    $funcName   = $phpcsFile->getDeclarationName($nextFunc);
                    $funcNameLc = strtolower($funcName);
                    if ($funcNameLc === '') {
                        continue;
                    }

                    if (isset($this->unsupportedMethods[$interfaceLc][$funcNameLc]) === true) {
                        $error     = 'Classes that implement interface %s do not support the method %s(). See %s';
                        $errorCode = $this->stringToErrorCode($interface).'UnsupportedMethod';
                        $data      = array(
                            $interface,
                            $funcName,
                            $this->unsupportedMethods[$interfaceLc][$funcNameLc],
                        );

                        $phpcsFile->addError($error, $nextFunc, $errorCode, $data);
                    }
                }
            }
        }
    }//end processClassToken()


    /**
     * Processes this test for when a function token is encountered.
     *
     * - Detect new interfaces when used as a type hint.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    private function processFunctionToken(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $typeHints = $this->getTypeHintsFromFunctionDeclaration($phpcsFile, $stackPtr);
        if (empty($typeHints) || is_array($typeHints) === false) {
            return;
        }

        foreach ($typeHints as $hint) {

            $typeHintLc = strtolower($hint);

            if (isset($this->newInterfaces[$typeHintLc]) === true) {
                $itemInfo = array(
                    'name'   => $hint,
                    'nameLc' => $typeHintLc,
                );
                $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
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
        return $this->newInterfaces[$itemInfo['nameLc']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The built-in interface '.parent::getErrorMsgTemplate();
    }


}//end class
