<?php
/**
 * PHPCompatibility_Sniffs_PHP_InternalInterfacesSniff.
 *
 * PHP version 5.5
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_InternalInterfacesSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_InternalInterfacesSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of PHP internal interfaces, not intended to be implemented by userland classes.
     *
     * The array lists : the error message to use.
     *
     * @var array(string => string)
     */
    protected $internalInterfaces = array(
        'Traversable'       => 'shouldn\'t be implemented directly, implement the Iterator or IteratorAggregate interface instead.',
        'DateTimeInterface' => 'is intended for type hints only and is not implementable.',
        'Throwable'         => 'cannot be implemented directly, extend the Exception class instead.',
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of interface names.
        $this->internalInterfaces = $this->arrayKeysToLowercase($this->internalInterfaces);

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

        foreach ($interfaces as $interface) {
            $interfaceLc = strtolower($interface);
            if (isset($this->internalInterfaces[$interfaceLc]) === true) {
                $error     = 'The interface %s %s';
                $errorCode = $this->stringToErrorCode($interfaceLc).'Found';
                $data      = array(
                    $interface,
                    $this->internalInterfaces[$interfaceLc],
                );

                $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
            }
        }

    }//end process()


}//end class
