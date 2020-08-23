<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Interfaces;

use PHPCompatibility\AbstractNewFeatureSniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * Detect use of new PHP native interfaces and unsupported interface methods.
 *
 * PHP version 5.0+
 *
 * @since 7.0.3
 * @since 7.1.0 Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class..
 * @since 7.1.4 Now also detects new interfaces when used as parameter type declarations.
 * @since 8.2.0 Now also detects new interfaces when used as return type declarations.
 */
class NewInterfacesSniff extends AbstractNewFeatureSniff
{

    /**
     * A list of new interfaces, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the interface appears.
     *
     * @since 7.0.3
     *
     * @var array(string => array(string => bool))
     */
    protected $newInterfaces = array(
        'Traversable' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'Reflector' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ),

        'Countable' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'OuterIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'RecursiveIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'SeekableIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'Serializable' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'SplObserver' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'SplSubject' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),

        'JsonSerializable' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'json',
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

        'Stringable' => array(
            '7.4' => false,
            '8.0' => true,
        ),
    );

    /**
     * A list of methods which cannot be used in combination with particular interfaces.
     *
     * @since 7.0.3
     *
     * @var array(string => array(string => string))
     */
    protected $unsupportedMethods = array(
        'Serializable' => array(
            '__sleep'  => 'https://www.php.net/serializable',
            '__wakeup' => 'https://www.php.net/serializable',
        ),
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.3
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of interface names.
        $this->newInterfaces      = \array_change_key_case($this->newInterfaces, \CASE_LOWER);
        $this->unsupportedMethods = \array_change_key_case($this->unsupportedMethods, \CASE_LOWER);

        return array(
            \T_CLASS,
            \T_ANON_CLASS,
            \T_FUNCTION,
            \T_CLOSURE,
            \T_RETURN_TYPE,
            \T_CATCH,
        );
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
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

            case 'T_CATCH':
                $this->processCatchToken($phpcsFile, $stackPtr);
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
     * @since 7.1.4 Split off from the `process()` method.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processClassToken(File $phpcsFile, $stackPtr)
    {
        $interfaces = ObjectDeclarations::findImplementedInterfaceNames($phpcsFile, $stackPtr);

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
     * @since 7.1.4
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
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
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processReturnTypeToken(File $phpcsFile, $stackPtr)
    {
        $returnTypeHint = $this->getReturnTypeHintName($phpcsFile, $stackPtr);
        if (empty($returnTypeHint)) {
            return;
        }

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
     * Processes this test for when a catch token is encountered.
     *
     * - Detect interfaces (Throwable) when used in a catch statement.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processCatchToken(File $phpcsFile, $stackPtr)
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
            \T_STRING              => true,
            \T_NS_SEPARATOR        => true,
            // End/split tokens.
            \T_VARIABLE            => false,
            \T_BITWISE_OR          => false,
            \T_CLOSE_CURLY_BRACKET => false, // Shouldn't be needed as we expect a var before this.
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

                if (isset($this->newInterfaces[$nameLC]) === true) {
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
     * @since 7.1.0
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
     * @since 7.1.0
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The built-in interface ' . parent::getErrorMsgTemplate();
    }
}
