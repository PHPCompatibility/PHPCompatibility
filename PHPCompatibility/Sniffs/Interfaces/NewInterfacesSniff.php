<?php
/**
 * \PHPCompatibility\Sniffs\Interfaces\NewInterfacesSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\Interfaces;

use PHPCompatibility\AbstractNewFeatureSniff;
use PHPCompatibility\PHPCSHelper;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\Interfaces\NewInterfacesSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewInterfacesSniff extends AbstractNewFeatureSniff
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
        'Reflector' => array(
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

        'SessionIdInterface' => array(
            '5.5.0' => false,
            '5.5.1' => true,
        ),

        'Throwable' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'SessionUpdateTimestampHandlerInterface' => array(
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
            \T_CLASS,
            \T_FUNCTION,
            \T_CLOSURE,
        );

        if (\defined('T_ANON_CLASS')) {
            $targets[] = \T_ANON_CLASS;
        }

        if (\defined('T_RETURN_TYPE')) {
            $targets[] = \T_RETURN_TYPE;
        }

        return $targets;
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
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

                // Deal with older PHPCS versions which don't recognize return type hints
                // as well as newer PHPCS versions (3.3.0+) where the tokenization has changed.
                $returnTypeHint = $this->getReturnTypeHintToken($phpcsFile, $stackPtr);
                if ($returnTypeHint !== false) {
                    $this->processReturnTypeToken($phpcsFile, $returnTypeHint);
                }
                break;

            case 'T_RETURN_TYPE':
                $this->processReturnTypeToken($phpcsFile, $stackPtr);
                break;

            default:
                // Deliberately left empty.
                break;
        }
    }


    /**
     * Processes this test for when a class token is encountered.
     *
     * - Detect classes implementing the new interfaces.
     * - Detect classes implementing the new interfaces with unsupported functions.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    private function processClassToken(File $phpcsFile, $stackPtr)
    {
        $interfaces = PHPCSHelper::findImplementedInterfaceNames($phpcsFile, $stackPtr);

        if (\is_array($interfaces) === false || $interfaces === array()) {
            return;
        }

        $tokens       = $phpcsFile->getTokens();
        $checkMethods = false;

        if (isset($tokens[$stackPtr]['scope_closer'])) {
            $checkMethods = true;
            $scopeCloser  = $tokens[$stackPtr]['scope_closer'];
        }

        foreach ($interfaces as $interface) {
            $interface   = ltrim($interface, '\\');
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
                while (($nextFunc = $phpcsFile->findNext(\T_FUNCTION, ($nextFunc + 1), $scopeCloser)) !== false) {
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
    }


    /**
     * Processes this test for when a function token is encountered.
     *
     * - Detect new interfaces when used as a type hint.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    private function processFunctionToken(File $phpcsFile, $stackPtr)
    {
        $typeHints = $this->getTypeHintsFromFunctionDeclaration($phpcsFile, $stackPtr);
        if (empty($typeHints) || \is_array($typeHints) === false) {
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
     * Processes this test for when a return type token is encountered.
     *
     * - Detect new interfaces when used as a return type declaration.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    private function processReturnTypeToken(File $phpcsFile, $stackPtr)
    {
        $returnTypeHint   = $this->getReturnTypeHintName($phpcsFile, $stackPtr);
        $returnTypeHint   = ltrim($returnTypeHint, '\\');
        $returnTypeHintLc = strtolower($returnTypeHint);

        if (isset($this->newInterfaces[$returnTypeHintLc]) === false) {
            return;
        }

        // Still here ? Then this is a return type declaration using a new interface.
        $itemInfo = array(
            'name'   => $returnTypeHint,
            'nameLc' => $returnTypeHintLc,
        );
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
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
        return 'The built-in interface ' . parent::getErrorMsgTemplate();
    }
}
