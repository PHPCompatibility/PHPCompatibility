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

use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Exceptions\ValueError;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Constants;
use PHPCSUtils\Utils\ControlStructures;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\Parentheses;
use PHPCSUtils\Utils\TypeString;
use PHPCSUtils\Utils\UseStatements;
use PHPCSUtils\Utils\Variables;

/**
 * Detect use of new PHP native interfaces and unsupported interface methods.
 *
 * PHP version 5.0+
 *
 * @since 7.0.3
 * @since 7.1.0  Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class..
 * @since 7.1.4  Now also detects new interfaces when used as parameter type declarations.
 * @since 8.2.0  Now also detects new interfaces when used as return type declarations.
 * @since 10.0.0 - Now extends the base `Sniff` class and uses the `ComplexVersionNewFeatureTrait`.
 *               - This class is now `final`.
 */
final class NewInterfacesSniff extends Sniff
{
    use ComplexVersionNewFeatureTrait;

    /**
     * A list of new interfaces, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the interface appears.
     *
     * @since 7.0.3
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $newInterfaces = [
        'Traversable' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'Reflector' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ],

        'Countable' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'OuterIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'RecursiveIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'SeekableIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'Serializable' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'SplObserver' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'SplSubject' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],

        'JsonSerializable' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'json',
        ],
        'SessionHandlerInterface' => [
            '5.3' => false,
            '5.4' => true,
        ],

        'DateTimeInterface' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'datetime',
        ],

        'SessionIdInterface' => [
            '5.5.0' => false,
            '5.5.1' => true,
        ],

        'Throwable' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'SessionUpdateTimestampHandlerInterface' => [
            '5.6' => false,
            '7.0' => true,
        ],

        'Stringable' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'DOMChildNode' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'dom',
        ],
        'DOMParentNode' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'dom',
        ],

        'UnitEnum' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'BackedEnum' => [
            '8.0' => false,
            '8.1' => true,
        ],

        'Random\Engine' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'random',
        ],
        'Random\CryptoSafeEngine' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'random',
        ],
    ];

    /**
     * A list of methods which cannot be used in combination with particular interfaces.
     *
     * @since 7.0.3
     *
     * @var array<string, array<string, string>> Sub-key should be method name in lowercase.
     */
    protected $unsupportedMethods = [
        'Serializable' => [
            '__sleep'  => 'https://www.php.net/serializable',
            '__wakeup' => 'https://www.php.net/serializable',
        ],
    ];

    /**
     * Current file being scanned.
     *
     * @since 10.0.0
     *
     * @var string
     */
    private $currentFile = '';

    /**
     * Stores information about imported, namespaced declarations with names which are also in use by PHP.
     *
     * When those declarations are used, they do not point to the PHP internal declarations, but to the
     * namespaced, imported declarations and those usages should be ignored by the sniff.
     *
     * The array is indexed by unqualified declarations names in lower case. The value is always true.
     * It is structured this way to utilize the isset() function for faster lookups.
     *
     * @since 10.0.0
     *
     * @var array<string,true>
     */
    private $importedDeclaration = [];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.3
     *
     * @return array<int|string>
     */
    public function register()
    {
        // Handle case-insensitivity of interface names.
        $this->newInterfaces      = \array_change_key_case($this->newInterfaces, \CASE_LOWER);
        $this->unsupportedMethods = \array_change_key_case($this->unsupportedMethods, \CASE_LOWER);

        $targets = [
            \T_USE       => \T_USE,
            \T_INTERFACE => \T_INTERFACE,
            \T_CATCH     => \T_CATCH,
            \T_CONST     => \T_CONST,
        ];

        $targets += Collections::ooCanImplement();
        $targets += Collections::functionDeclarationTokens();
        $targets += Collections::ooPropertyScopes();

        return $targets;
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
        $fileName = $phpcsFile->getFilename();
        if ($this->currentFile !== $fileName) {
            // Reset the properties for each new file.
            $this->currentFile         = $fileName;
            $this->importedDeclaration = [];
        }

        $tokens = $phpcsFile->getTokens();

        switch ($tokens[$stackPtr]['code']) {
            case \T_USE:
                $this->processUseToken($phpcsFile, $stackPtr);
                break;

            case \T_INTERFACE:
                $this->processInterfaceToken($phpcsFile, $stackPtr);
                break;

            case \T_CONST:
                $this->processConstantToken($phpcsFile, $stackPtr);
                break;

            case \T_CATCH:
                $this->processCatchToken($phpcsFile, $stackPtr);
                break;
        }

        if (isset(Collections::ooCanImplement()[$tokens[$stackPtr]['code']]) === true) {
            $this->processOOToken($phpcsFile, $stackPtr);
        }

        if (isset(Collections::ooPropertyScopes()[$tokens[$stackPtr]['code']]) === true) {
            $this->processOOProperties($phpcsFile, $stackPtr);
        }

        if (isset(Collections::functionDeclarationTokens()[$tokens[$stackPtr]['code']]) === true) {
            $this->processFunctionToken($phpcsFile, $stackPtr);
        }
    }


    /**
     * Processes this test for when a class token is encountered.
     *
     * - Detect classes and enums implementing the new interfaces.
     * - Detect classes and enums implementing the new interfaces with unsupported functions.
     *
     * @since 7.1.4  Split off from the `process()` method.
     * @since 10.0.0 Renamed from `processClassToken()` to `processOOToken()`.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processOOToken(File $phpcsFile, $stackPtr)
    {
        $interfaces = ObjectDeclarations::findImplementedInterfaceNames($phpcsFile, $stackPtr);

        if (\is_array($interfaces) === false || $interfaces === []) {
            return;
        }

        $this->processInterfaceList($phpcsFile, $stackPtr, $interfaces, 'Classes that implement');
    }


    /**
     * Processes this test for when an interface token is encountered.
     *
     * - Detect interfaces extending the new interfaces.
     * - Detect interfaces extending the new interfaces with unsupported functions.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processInterfaceToken(File $phpcsFile, $stackPtr)
    {
        $interfaces = ObjectDeclarations::findExtendedInterfaceNames($phpcsFile, $stackPtr);

        if (\is_array($interfaces) === false || $interfaces === []) {
            return;
        }

        $this->processInterfaceList($phpcsFile, $stackPtr, $interfaces, 'Interfaces that extend');
    }


    /**
     * Processes a list of interfaces being extended/implemented.
     *
     * @since 10.0.0 Split off from the `processClassToken()` method.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile  The file being scanned.
     * @param int                         $stackPtr   The position of the current token in
     *                                                the stack passed in $tokens.
     * @param array                       $interfaces List of interface names.
     * @param string                      $phrase     Start of the error phrase for unsupported functions.
     *
     * @return void
     */
    private function processInterfaceList(File $phpcsFile, $stackPtr, array $interfaces, $phrase)
    {
        $tokens       = $phpcsFile->getTokens();
        $checkMethods = false;

        if (isset($tokens[$stackPtr]['scope_closer'])) {
            $checkMethods = true;
        }

        $ooMethods   = ObjectDeclarations::getDeclaredMethods($phpcsFile, $stackPtr);
        $ooMethodsLc = \array_change_key_case($ooMethods, \CASE_LOWER);

        foreach ($interfaces as $interface) {
            $interface   = \ltrim($interface, '\\');
            $interfaceLc = \strtolower($interface);

            if (isset($this->newInterfaces[$interfaceLc]) === true) {
                $itemInfo = [
                    'name'   => $interface,
                    'nameLc' => $interfaceLc,
                ];
                $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
            }

            if (isset($this->unsupportedMethods[$interfaceLc]) === true) {
                foreach ($this->unsupportedMethods[$interfaceLc] as $methodName => $see) {
                    if (isset($ooMethodsLc[$methodName])) {
                        $error     = $phrase . ' interface %s do not support the method %s(). See %s';
                        $errorCode = MessageHelper::stringToErrorCode($interfaceLc) . 'UnsupportedMethod';
                        $data      = [
                            $interface,
                            $methodName,
                            $see,
                        ];

                        $phpcsFile->addError($error, $ooMethodsLc[$methodName], $errorCode, $data);
                    }
                }
            }
        }
    }


    /**
     * Processes this test for when a constant token is encountered.
     *
     * - Detect new interfaces when used as a class constant type declaration.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processConstantToken(File $phpcsFile, $stackPtr)
    {
        try {
            $properties = Constants::getProperties($phpcsFile, $stackPtr);
        } catch (ValueError $e) {
            // Not an OO constant or parse error.
            return;
        }

        if ($properties['type'] === '') {
            return;
        }

        $this->checkTypeDeclaration($phpcsFile, $properties['type_token'], $properties['type']);
    }


    /**
     * Processes an OO token for properties declared in the OO scope.
     *
     * - Detect new interfaces when used as a property type declaration.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processOOProperties(File $phpcsFile, $stackPtr)
    {
        $ooProperties = ObjectDeclarations::getDeclaredProperties($phpcsFile, $stackPtr);
        if (empty($ooProperties)) {
            return;
        }

        $tokens         = $phpcsFile->getTokens();
        $endOfStatement = false;
        foreach ($ooProperties as $variableToken) {
            if ($endOfStatement !== false && $variableToken < $endOfStatement) {
                // Don't throw the same error multiple times for multi-property declarations.
                // Also skip over any other constructor promoted properties.
                continue;
            }

            try {
                $properties = Variables::getMemberProperties($phpcsFile, $variableToken);
            } catch (ValueError $e) {
                /*
                 * This must be constructor property promotion.
                 * Ignore for now and skip over any other promoted properties, these will be handled
                 * via the function token for the constructor.
                 */
                $deepestOpen = Parentheses::getLastOpener($phpcsFile, $variableToken);
                if ($deepestOpen !== false
                    && $stackPtr < $deepestOpen
                    && Parentheses::isOwnerIn($phpcsFile, $deepestOpen, \T_FUNCTION)
                    && isset($tokens[$deepestOpen]['parenthesis_closer'])
                ) {
                    $endOfStatement = $tokens[$deepestOpen]['parenthesis_closer'];
                }

                continue;
            }

            if ($properties['type'] === '') {
                continue;
            }

            $this->checkTypeDeclaration($phpcsFile, $properties['type_token'], $properties['type']);

            $endOfStatement = $phpcsFile->findNext([\T_SEMICOLON, \T_CLOSE_TAG], ($variableToken + 1));
        }
    }


    /**
     * Processes this test for when a function token is encountered.
     *
     * - Detect new interfaces when used as a parameter type hint.
     * - Detect new interfaces when used as a return type hint.
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
        /*
         * Check parameter type declarations.
         */
        $parameters = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        if (empty($parameters) === false && \is_array($parameters) === true) {
            foreach ($parameters as $param) {
                if ($param['type_hint'] === '') {
                    continue;
                }

                $this->checkTypeDeclaration($phpcsFile, $param['type_hint_token'], $param['type_hint']);
            }
        }

        /*
         * Check return type declarations.
         */
        $properties = FunctionDeclarations::getProperties($phpcsFile, $stackPtr);
        if ($properties['return_type'] === '') {
            return;
        }

        $this->checkTypeDeclaration($phpcsFile, $properties['return_type_token'], $properties['return_type']);
    }


    /**
     * Processes a type declaration.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     * @param string                      $typeHint  The type declaration.
     *
     * @return void
     */
    private function checkTypeDeclaration($phpcsFile, $stackPtr, $typeHint)
    {
        $types = TypeString::filterOOTypes(TypeString::toArray($typeHint));

        if (empty($types) === true) {
            return;
        }

        foreach ($types as $type) {
            // Strip off potential (global) namespace indication.
            $type = \ltrim($type, '\\');

            if ($type === '') {
                continue;
            }

            $typeLc = \strtolower($type);
            if (isset($this->newInterfaces[$typeLc]) === false) {
                continue;
            }

            $itemInfo = [
                'name'   => $type,
                'nameLc' => $typeLc,
            ];
            $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
        }
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
        $exceptions = ControlStructures::getCaughtExceptions($phpcsFile, $stackPtr);
        if (empty($exceptions) === true) {
            return;
        }

        foreach ($exceptions as $exception) {
            // Strip off potential (global) namespace indication.
            $name   = \ltrim($exception['type'], '\\');
            $nameLC = \strtolower($name);

            if (isset($this->newInterfaces[$nameLC]) === true) {
                $itemInfo = [
                    'name'   => $name,
                    'nameLc' => $nameLC,
                ];
                $this->handleFeature($phpcsFile, $exception['type_token'], $itemInfo);
            }
        }
    }

    /**
     * Processes this test for when a use token is encountered.
     *
     * - Save imported declarations for later use.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processUseToken(File $phpcsFile, $stackPtr)
    {
        if (UseStatements::isImportUse($phpcsFile, $stackPtr) === false) {
            return;
        }

        $splitUseStatement = UseStatements::splitImportUseStatement($phpcsFile, $stackPtr);

        foreach ($splitUseStatement['name'] as $name => $fullyQualifiedName) {
            $lowerFullyQualifiedName = \strtolower($fullyQualifiedName);

            // If the imported declaration is imported from the internal namespace it will not be excluded.
            if (isset($this->newInterfaces[$lowerFullyQualifiedName])) {
                continue;
            }

            $this->importedDeclaration[\strtolower($name)] = true;
        }
    }

    /**
     * Handle the retrieval of relevant information and - if necessary - throwing of an
     * error for a matched item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the relevant token in
     *                                               the stack.
     * @param array                       $itemInfo  Base information about the item.
     *
     * @return void
     */
    protected function handleFeature(File $phpcsFile, $stackPtr, array $itemInfo)
    {
        if (isset($this->importedDeclaration[$itemInfo['nameLc']])) {
            return;
        }

        $itemArray   = $this->newInterfaces[$itemInfo['nameLc']];
        $versionInfo = $this->getVersionInfo($itemArray);

        if (empty($versionInfo['not_in_version'])
            || ScannedCode::shouldRunOnOrBelow($versionInfo['not_in_version']) === false
        ) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $itemInfo, $versionInfo);
    }


    /**
     * Generates the error for this item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param array                       $itemInfo    Base information about the item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, array $itemInfo, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'The built-in interface %s is not present in PHP version %s or earlier';

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['nameLc'], $versionInfo);

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
