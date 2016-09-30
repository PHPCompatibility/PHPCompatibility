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
        // Handle case-insensitivity of class names.
        $keys = array_keys( $this->newInterfaces );
        $keys = array_map( 'strtolower', $keys );
        $this->newInterfaces = array_combine( $keys, $this->newInterfaces );

        $keys = array_keys( $this->unsupportedMethods );
        $keys = array_map( 'strtolower', $keys );
        $this->unsupportedMethods = array_combine( $keys, $this->unsupportedMethods );

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
            $lcInterface = strtolower($interface);
            if (isset($this->newInterfaces[$lcInterface]) === true) {
                $this->addError($phpcsFile, $stackPtr, $interface);
            }

            if ($checkMethods === true && isset($this->unsupportedMethods[$lcInterface]) === true) {
                $nextFunc = $stackPtr;
                while (($nextFunc = $phpcsFile->findNext(T_FUNCTION, ($nextFunc + 1), $scopeCloser)) !== false) {
                    $funcName = strtolower($phpcsFile->getDeclarationName($nextFunc));
                    if ($funcName === '') {
                        continue;
                    }

                    if (isset($this->unsupportedMethods[$lcInterface][$funcName]) === true) {
                        $error = 'Classes that implement interface %s do not support the method %s(). See %s';
                        $data  = array(
                            $interface,
                            $funcName,
                            $this->unsupportedMethods[$lcInterface][$funcName],
                        );
                        $phpcsFile->addError($error, $nextFunc, 'UnsupportedMethod', $data);
                    }
                }
            }
        }

    }//end process()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the function
     *                                        in the token array.
     * @param string               $interface The name of the interface.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $interface)
    {
        $interfaceLc = strtolower($interface);
        $error       = '';

        $isError = false;
        foreach ($this->newInterfaces[$interfaceLc] as $version => $present) {
            if ($this->supportsBelow($version)) {
                if ($present === false) {
                    $isError = true;
                    $error .= 'not present in PHP version ' . $version . ' or earlier';
                }
            }
        }

        if (strlen($error) > 0) {
            $error = 'The built-in interface ' . $interface . ' is ' . $error;

            if ($isError === true) {
                $phpcsFile->addError($error, $stackPtr);
            } else {
                $phpcsFile->addWarning($error, $stackPtr);
            }
        }

    }//end addError()

}//end class
