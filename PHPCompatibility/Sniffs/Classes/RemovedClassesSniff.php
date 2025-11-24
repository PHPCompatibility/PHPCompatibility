<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Classes;

use PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait;
use PHPCompatibility\Helpers\ResolveHelper;
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
 * Detect use of removed PHP native classes.
 *
 * The sniff analyses the following constructs to find usage of removed classes:
 * - Class instantiation using the `new` keyword.
 * - (Anonymous) Class declarations to detect removed classes being extended by userland classes.
 * - Static use of class properties, constants or functions using the double colon.
 * - Function/closure declarations to detect removed classes used as parameter type declarations.
 * - Function/closure declarations to detect removed classes used as return type declarations.
 * - Property declarations to detect removed classes used as property type declarations.
 * - Try/catch statements to detect removed exception classes being caught.
 *
 * PHP version All
 *
 * @since 10.0.0
 */
final class RemovedClassesSniff extends Sniff
{
    use ComplexVersionDeprecatedRemovedFeatureTrait;

    /**
     * A list of deprecated/removed classes, not present in older versions.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the class was deprecated/removed.
     *
     * The optional `extension` key should be used to list the name of the extension
     * the class comes from if this class is part of a removed extension and should
     * match the array in the RemovedExtensionsTrait.
     *
     * @since 10.0.0
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $removedClasses = [
        'HW_API' => [
            '5.2'       => true,
            'extension' => 'hwapi',
        ],
        'HW_API_Object' => [
            '5.2'       => true,
            'extension' => 'hwapi',
        ],
        'HW_API_Attribute' => [
            '5.2'       => true,
            'extension' => 'hwapi',
        ],
        'HW_API_Error' => [
            '5.2'       => true,
            'extension' => 'hwapi',
        ],
        'HW_API_Content' => [
            '5.2'       => true,
            'extension' => 'hwapi',
        ],
        'HW_API_Reason' => [
            '5.2'       => true,
            'extension' => 'hwapi',
        ],

        'SWFAction' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBitmap' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFButton' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFDisplayItem' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFFill' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFFont' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFFontChar' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFGradient' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFMorph' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFMovie' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFPrebuiltClip' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFShape' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFSound' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFSoundInstance' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFSprite' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFText' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTextField' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFVideoStream' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SQLiteDatabase' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLiteResult' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLiteUnbuffered' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],

        'XmlRpcServer' => [
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ],
        /*
        See: https://bugs.php.net/bug.php?id=79625
        'OCI-Collection' => [
            '8.0'         => true,
            'alternative' => 'OCICollection',
            'extension'   => 'oci8',
        ],
        'OCI-Lob' => [
            '8.0'         => true,
            'alternative' => 'OCILob',
            'extension'   => 'oci8',
        ],
        */

        'IMAP\Connection' => [
            '8.4'       => true,
            'extension' => 'imap',
        ],
        'OCICollection' => [
            '8.4'       => true,
            'extension' => 'oci8',
        ],
        'OCILob' => [
            '8.4'       => true,
            'extension' => 'oci8',
        ],
        'PSpell\Config' => [
            '8.4'       => true,
            'extension' => 'pspell',
        ],
        'PSpell\Dictionary' => [
            '8.4'       => true,
            'extension' => 'pspell',
        ],
    ];

    /**
     * A list of deprecated/removed Exception classes.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the class was deprecated/removed.
     *
     * The optional `extension` key should be used to list the name of the extension
     * the class comes from if this class is part of a removed extension and should
     * match the array in the ReovedExtensionsTrait.
     *
     * {@internal Classes listed here do not need to be added to the $removedClasses
     *            property as well.
     *            This list is automatically added to the $removedClasses property
     *            in the `register()` method.}
     *
     * @since 10.0.0
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $removedExceptions = [
        'SQLiteException' => [
            '5.4'       => true,
            'extension' => 'sqlite',
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
     * Stores information about imported, namespaced classes with names which are also in use by PHP.
     *
     * When those classes are used, they do not point to the PHP classes, but to the
     * namespaced, imported class and those usages should be ignored by the sniff.
     *
     * The array is indexed by unqualified class names in lower case. The value is always true.
     * It is structured this way to utilize the isset() function for faster lookups.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    private $importedClasses = [];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        // Handle case-insensitivity of class names.
        $this->removedClasses    = \array_change_key_case($this->removedClasses, \CASE_LOWER);
        $this->removedExceptions = \array_change_key_case($this->removedExceptions, \CASE_LOWER);

        // Add the Exception classes to the Classes list.
        $this->removedClasses = \array_merge($this->removedClasses, $this->removedExceptions);

        $targets = [
            \T_USE          => \T_USE,
            \T_NEW          => \T_NEW,
            \T_CLASS        => \T_CLASS,
            \T_ANON_CLASS   => \T_ANON_CLASS,
            \T_DOUBLE_COLON => \T_DOUBLE_COLON,
            \T_CATCH        => \T_CATCH,
            \T_CONST        => \T_CONST,
        ];

        $targets += Collections::functionDeclarationTokens();
        $targets += Collections::ooPropertyScopes();

        return $targets;
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
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
            $this->currentFile     = $fileName;
            $this->importedClasses = [];
        }

        $tokens = $phpcsFile->getTokens();

        switch ($tokens[$stackPtr]['code']) {
            case \T_USE:
                $this->processUseToken($phpcsFile, $stackPtr);
                break;

            case \T_CONST:
                $this->processConstantToken($phpcsFile, $stackPtr);
                break;

            case \T_CATCH:
                $this->processCatchToken($phpcsFile, $stackPtr);
                break;

            case \T_NEW:
            case \T_CLASS:
            case \T_ANON_CLASS:
            case \T_DOUBLE_COLON:
                $this->processSingularToken($phpcsFile, $stackPtr);
                break;
        }

        if (isset(Collections::ooPropertyScopes()[$tokens[$stackPtr]['code']]) === true) {
            $this->processOOProperties($phpcsFile, $stackPtr);
        }

        if (isset(Collections::functionDeclarationTokens()[$tokens[$stackPtr]['code']]) === true) {
            $this->processFunctionToken($phpcsFile, $stackPtr);
        }
    }


    /**
     * Processes this test for when a token resulting in a singular class name is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processSingularToken(File $phpcsFile, $stackPtr)
    {
        $tokens      = $phpcsFile->getTokens();
        $FQClassName = '';

        if ($tokens[$stackPtr]['code'] === \T_NEW) {
            $FQClassName = ResolveHelper::getFQClassNameFromNewToken($phpcsFile, $stackPtr);

        } elseif ($tokens[$stackPtr]['code'] === \T_CLASS || $tokens[$stackPtr]['code'] === \T_ANON_CLASS) {
            $FQClassName = ResolveHelper::getFQExtendedClassName($phpcsFile, $stackPtr);

        } elseif ($tokens[$stackPtr]['code'] === \T_DOUBLE_COLON) {
            $FQClassName = ResolveHelper::getFQClassNameFromDoubleColonToken($phpcsFile, $stackPtr);
        }

        if ($FQClassName === '') {
            return;
        }

        $className   = \substr($FQClassName, 1); // Remove global namespace indicator.
        $classNameLc = \strtolower($className);

        if (isset($this->removedClasses[$classNameLc]) === false) {
            return;
        }

        $itemInfo = [
            'name'   => $className,
            'nameLc' => $classNameLc,
        ];
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
    }


    /**
     * Processes this test for when a function token is encountered.
     *
     * - Detect removed classes when used as a parameter type declaration.
     * - Detect removed classes when used as a return type declaration.
     *
     * @since 10.0.0
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
     * Processes this test for when a constant token is encountered.
     *
     * - Detect removed classes when used as a class constant type declaration.
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
     * - Detect removed classes when used as a property type declaration.
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
     * Processes a type declaration.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile  The file being scanned.
     * @param int                         $stackPtr   The position of the current token in
     *                                                the stack passed in $tokens.
     * @param string                      $typeString The type declaration.
     *
     * @return void
     */
    private function checkTypeDeclaration($phpcsFile, $stackPtr, $typeString)
    {
        $types = TypeString::filterOOTypes(TypeString::toArray($typeString));

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
            if (isset($this->removedClasses[$typeLc]) === false) {
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
     * - Detect exceptions when used in a catch statement.
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

            if (isset($this->removedExceptions[$nameLC]) === true) {
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
     * - Save imported classes for later use.
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

            if (isset($this->removedClasses[$lowerFullyQualifiedName])) {
                continue;
            }

            $this->importedClasses[\strtolower($name)] = true;
        }
    }


    /**
     * Handle the retrieval of relevant information and - if necessary - throwing of an
     * error/warning for a matched item.
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
        if (isset($this->importedClasses[$itemInfo['nameLc']])) {
            return;
        }

        $itemArray   = $this->removedClasses[$itemInfo['nameLc']];
        $versionInfo = $this->getVersionInfo($itemArray);
        $isError     = null;

        if (empty($versionInfo['removed']) === false
            && ScannedCode::shouldRunOnOrAbove($versionInfo['removed']) === true
        ) {
            $isError = true;
        } elseif (empty($versionInfo['deprecated']) === false
            && ScannedCode::shouldRunOnOrAbove($versionInfo['deprecated']) === true
        ) {
            $isError = false;

            // Reset the 'removed' info as it is not relevant for the current notice.
            $versionInfo['removed'] = '';
        }

        if (isset($isError) === false) {
            return;
        }

        $this->addMessage($phpcsFile, $stackPtr, $isError, $itemInfo, $versionInfo);
    }


    /**
     * Generates the error or warning for this item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param bool                        $isError     Whether this should be an error or a warning.
     * @param array                       $itemInfo    Base information about the item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addMessage(File $phpcsFile, $stackPtr, $isError, array $itemInfo, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'The built-in class %s is ';

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        MessageHelper::addMessage(
            $phpcsFile,
            $msgInfo['message'],
            $stackPtr,
            $isError,
            $msgInfo['errorcode'],
            $msgInfo['data']
        );
    }
}
