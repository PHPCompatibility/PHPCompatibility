<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewInterfacesSniff.
 *
 * PHP version 5.5
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewInterfacesSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_NewInterfacesSniff extends PHPCompatibility_Sniff
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
                                'Countable' => array(
                                    '5.0' => false,
                                    '5.1' => true
                                ),
                                'OuterIterator' => array(
                                    '5.0' => false,
                                    '5.1' => true
                                ),
                                'RecursiveIterator' => array(
                                    '5.0' => false,
                                    '5.1' => true
                                ),
                                'SeekableIterator' => array(
                                    '5.0' => false,
                                    '5.1' => true
                                ),
                                'Serializable' => array(
                                    '5.0' => false,
                                    '5.1' => true,
                                ),
                                'SplObserver' => array(
                                    '5.0' => false,
                                    '5.1' => true
                                ),
                                'SplSubject' => array(
                                    '5.0' => false,
                                    '5.1' => true
                                ),

                                'JsonSerializable' => array(
                                    '5.3' => false,
                                    '5.4' => true
                                ),
                                'SessionHandlerInterface' => array(
                                    '5.3' => false,
                                    '5.4' => true
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

        return array(T_CLASS);

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
        $interfaces = $this->findImplementedInterfaceNames($phpcsFile, $stackPtr);

        if (is_array($interfaces) === false || $interfaces === array()) {
            return;
        }

        $tokens       = $phpcsFile->getTokens();
        $checkMethods = false;

        if(isset($tokens[$stackPtr]['scope_closer'])) {
            $checkMethods = true;
            $scopeCloser = $tokens[$stackPtr]['scope_closer'];
        }

        foreach ($interfaces as $interface) {
            $interfaceLc = strtolower($interface);
            if (isset($this->newInterfaces[$interfaceLc]) === true) {

                $errorInfo = $this->getErrorInfo($interfaceLc);

                if ($errorInfo['not_in_version'] !== '') {
                    $this->addError($phpcsFile, $stackPtr, $interface, $errorInfo);
                }
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
                        $errorCode = $this->stringToErrorCode($interface) . 'UnsupportedMethod';
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

    }//end process()


    /**
     * Retrieve the relevant (version) information for the error message.
     *
     * @param string $interfaceLc The lowercase name of the interface.
     *
     * @return array
     */
    protected function getErrorInfo($interfaceLc)
    {
        $errorInfo  = array(
            'not_in_version' => '',
        );

        foreach ($this->newInterfaces[$interfaceLc] as $version => $present) {
            if ($present === false && $this->supportsBelow($version)) {
                $errorInfo['not_in_version'] = $version;
            }
        }

        return $errorInfo;

    }//end getErrorInfo()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the class token
     *                                        in the token array.
     * @param string               $interface The name of the interface.
     * @param array                $errorInfo Array with details about when the
     *                                        interface was not (yet) available.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $interface, $errorInfo)
    {
        $error     = 'The built-in interface %s is not present in PHP version %s or earlier';
        $errorCode = $this->stringToErrorCode($interface) . 'Found';
        $data      = array(
            $interface,
            $errorInfo['not_in_version'],
        );

        $phpcsFile->addError($error, $stackPtr, $errorCode, $data);

    }//end addError()

}//end class
