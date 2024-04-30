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

use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\UseStatements;

/**
 * Detect classes which implement PHP native interfaces intended only for PHP internal use.
 *
 * PHP version 5.0+
 *
 * @link https://www.php.net/manual/en/class.traversable.php
 * @link https://www.php.net/manual/en/class.throwable.php
 * @link https://www.php.net/manual/en/class.datetimeinterface.php
 *
 * @since 7.0.3
 */
class InternalInterfacesSniff extends Sniff
{

    /**
     * A list of PHP internal interfaces, not intended to be implemented by userland classes.
     *
     * The array lists : the error message to use.
     *
     * @since 7.0.3
     *
     * @var array<string, string>
     */
    protected $internalInterfaces = [
        'Traversable'       => 'shouldn\'t be implemented directly, implement the Iterator or IteratorAggregate interface instead.',
        'DateTimeInterface' => 'is intended for type hints only and is not implementable or extendable.',
        'Throwable'         => 'cannot be implemented directly, extend the Exception class instead.',
        'UnitEnum'          => 'is intended for type hints only and is not implementable or extendable.',
        'BackedEnum'        => 'is intended for type hints only and is not implementable or extendable.',
    ];

    /**
     * A list of PHP internal interfaces, which cannot be extended by userland interfaces.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    private $cannotBeExtended = [
        'DateTimeInterface' => true,
        'UnitEnum'          => true,
        'BackedEnum'        => true,
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
    private $importedDeclarations = [];


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
        $this->internalInterfaces = \array_change_key_case($this->internalInterfaces, \CASE_LOWER);
        $this->cannotBeExtended   = \array_change_key_case($this->cannotBeExtended, \CASE_LOWER);

        return Collections::ooCanImplement() + [\T_USE => \T_USE, \T_INTERFACE => \T_INTERFACE];
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
            $this->currentFile          = $fileName;
            $this->importedDeclarations = [];
        }

        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['code'] === \T_USE) {
            $this->processUseToken($phpcsFile, $stackPtr);
            return;
        }

        if ($tokens[$stackPtr]['code'] === \T_INTERFACE) {
            $interfaces = ObjectDeclarations::findExtendedInterfaceNames($phpcsFile, $stackPtr);
            $targets    = $this->cannotBeExtended;
        } else {
            $interfaces = ObjectDeclarations::findImplementedInterfaceNames($phpcsFile, $stackPtr);
            $targets    = $this->internalInterfaces;
        }

        if (\is_array($interfaces) === false || $interfaces === []) {
            return;
        }

        foreach ($interfaces as $interface) {
            $interface   = \ltrim($interface, '\\');
            $interfaceLc = \strtolower($interface);
            if (isset($targets[$interfaceLc]) && ! isset($this->importedDeclarations[$interfaceLc])) {
                $error     = 'The interface %s %s';
                $errorCode = MessageHelper::stringToErrorCode($interfaceLc) . 'Found';
                $data      = [
                    $interface,
                    $this->internalInterfaces[$interfaceLc],
                ];

                $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
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
        if (!UseStatements::isImportUse($phpcsFile, $stackPtr)) {
            return;
        }

        $splitUseStatement = UseStatements::splitImportUseStatement($phpcsFile, $stackPtr);

        foreach ($splitUseStatement['name'] as $name => $fullyQualifiedName) {
            $lowerFullyQualifiedName = strtolower($fullyQualifiedName);

            // If the imported declaration is imported from the internal namespace it will not be excluded.
            if (isset($this->internalInterfaces[$lowerFullyQualifiedName])) {
                continue;
            }

            $this->importedDeclarations[strtolower($name)] = true;
        }
    }
}
