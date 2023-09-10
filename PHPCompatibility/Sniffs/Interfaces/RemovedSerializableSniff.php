<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Interfaces;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * Detect use of "only Serializable" classes which is deprecated as of PHP 8.1.0.
 *
 * A class is "only Serializable" if it is non-abstract, implements Serializable,
 * and does not implement __serialize() and __unserialize().
 *
 * As of PHP 8.1, declaring an "only Serializable" class will throw a deprecation warning.
 * Other implementations of Serializable will be accepted without a deprecation warning,
 * because libraries supporting PHP < 7.4 will generally need to implement both the
 * old and new mechanisms.
 *
 * If PHP < 7.4 does not need to be supported, the Serializable implementation can be removed
 * in favour of only implementing the magic methods.
 *
 * As of PHP 9.0, the Serializable interface will be removed.
 *
 * PHP version 8.1
 * PHP version 9.0
 *
 * @link https://www.php.net/manual/en/migration81.deprecated.php#migration81.deprecated.core.serialize-interface
 * @link https://wiki.php.net/rfc/phase_out_serializable
 * @link https://www.php.net/manual/en/class.serializable.php
 * @link https://www.php.net/manual/en/language.oop5.magic.php#object.serialize
 *
 * @since 10.0.0
 */
class RemovedSerializableSniff extends Sniff
{

    /**
     * Array of additional interface implementations to examine.
     *
     * This property can be provided via a custom ruleset.
     *
     * @since 10.0.0
     *
     * @var array
     */
    public $serializableInterfaces = [];

    /**
     * Array of additional interface implementations to examine.
     *
     * This is a cleaned up version of the property provided via a custom ruleset.
     *
     * @since 10.0.0
     *
     * @var array
     */
    private $customSerializableInterfaces = [];

    /**
     * Array of PHP native interfaces to look for.
     *
     * @since 10.0.0
     *
     * @var string[]
     */
    private $phpSerializableInterfaces = [
        'serializable',
    ];

    /**
     * Array of interfaces extending a PHP native or "extra" interface seen along the way.
     *
     * @since 10.0.0
     *
     * @var array
     */
    private $collectedInterfaces = [];

    /**
     * Array of interface implementations to examine.
     *
     * @since 10.0.0
     *
     * @var array
     */
    private $interfaceList = [];

    /**
     * Cache of the last seen value of the $serializableInterfaces property
     * to know whether the $interfaceList property needs updating.
     *
     * @since 10.0.0
     *
     * @var array
     */
    private $lastSeenSerializableInterfaces;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return Collections::ooCanImplement() + [\T_INTERFACE => \T_INTERFACE];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('8.1') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $class  = $tokens[$stackPtr];

        $this->updateInterfaceList();

        if ($class['code'] === \T_INTERFACE) {
            $extendedInterfaces = ObjectDeclarations::findExtendedInterfaceNames($phpcsFile, $stackPtr);
            if (empty($extendedInterfaces)) {
                // Interface doesn't extend other interfaces.
                return;
            }

            $extendedInterfaces = $this->cleanInterfaceNames($extendedInterfaces);
            $intersection       = \array_intersect($extendedInterfaces, $this->interfaceList);
            if (empty($intersection) === true) {
                // Interface doesn't extend a PHP native, collected or user provided serializable interface.
                return;
            }

            /*
             * Okay, so we have an interface which extends Serializable in one way or another.
             */
            if (ScannedCode::shouldRunOnOrBelow('7.3') === false) {
                /*
                 * Check if the interface requires implementation of the magic methods.
                 */
                $hasMagic = $this->findMagicMethods($phpcsFile, $stackPtr);

                if ($hasMagic['__serialize'] === true && $hasMagic['__unserialize'] === true) {
                    $message = 'When an interface enforces implementation of the magic __serialize() and __unserialize() methods and the code base does not need to support PHP < 7.4, the interface no longer needs to extend the Serializable interface.';
                    $code    = 'RedundantSerializableExtension';

                    $phpcsFile->addWarning($message, $stackPtr, $code);
                }
            }

            /*
             * - Check if the interface is already in the list of interfaces to check for.
             * - If not, we should recommend to the user to add it to the $serializableInterfaces
             *   property and to rescan the codebase.
             * - And in the mean time, we add the interface to the "Collected" list.
             */
            $interfaceName   = ObjectDeclarations::getName($phpcsFile, $stackPtr);
            $interfaceNameLC = (empty($interfaceName) === false) ? \strtolower($interfaceName) : '';

            if (empty($this->customSerializableInterfaces) === false
                && \in_array($interfaceNameLC, $this->customSerializableInterfaces, true) === true
            ) {
                // Interface has already been added to the list of additional interfaces to find.
                return;
            }

            $this->collectedInterfaces[] = $interfaceNameLC;
            $this->interfaceList[]       = $interfaceNameLC;

            $message = 'The "%1$s" interface extends the serializable %2$s interface%3$s. For the PHPCompatibility.Interface.RemovedSerializable sniff to be reliable, the name of this interface needs to be added to the list of interface implementations to find. Please add the interface name "%1$s" to the "serializableInterfaces" property for this sniff in your custom ruleset and rescan the codebase.';
            $code    = 'MissingInterface';
            $data    = [
                $interfaceName,
                \implode(', ', $intersection),
                (\count($intersection) > 1) ? 's' : '',
            ];

            $phpcsFile->addWarning($message, $stackPtr, $code, $data);

            // Nothing more to do for interfaces.
            return;
        }

        if ($class['code'] === \T_CLASS) {
            // Anon classes/enums cannot be abstract.
            $classProps = ObjectDeclarations::getClassProperties($phpcsFile, $stackPtr);
            if ($classProps['is_abstract'] === true) {
                // We cannot determine compliance for abstract classes.
                return;
            }
        }

        $implementedInterfaces = ObjectDeclarations::findImplementedInterfaceNames($phpcsFile, $stackPtr);
        if (empty($implementedInterfaces)) {
            // Class/enum doesn't implement any interface.
            return;
        }

        $implementedInterfaces = $this->cleanInterfaceNames($implementedInterfaces);

        $matchedInterfaces = \array_intersect($implementedInterfaces, $this->interfaceList);
        if (empty($matchedInterfaces) === true) {
            // Class/enum doesn't implement any of the known Serializable interfaces.
            return;
        }

        if ($class['code'] === \T_ENUM) {
            // Enums cannot declare the magic __serialize() and __unserialize() methods.
            $message = 'The Serializable interface is deprecated since PHP 8.1.';
            $code    = 'DeprecatedOnEnum';

            $phpcsFile->addWarning($message, $stackPtr, $code);
            return;
        }

        /*
         * Okay, if we're still here, we know that this is a class which implements
         * the Serializable interface.
         */
        $hasMagic = $this->findMagicMethods($phpcsFile, $stackPtr);

        if ($hasMagic['__serialize'] === true && $hasMagic['__unserialize'] === true) {
            /*
             * If PHP < 7.4 does not need to be supported, recommend removing the Serializable implementation,
             * but only for direct implementations of Serializable as other interfaces may provide additional functionality.
             */
            if (ScannedCode::shouldRunOnOrBelow('7.3') === false
                && \array_intersect($matchedInterfaces, $this->phpSerializableInterfaces) !== []
            ) {
                $message = 'When the magic __serialize() and __unserialize() methods are available and the code base does not need to support PHP < 7.4, the implementation of the Serializable interface can be removed.';
                $code    = 'RedundantSerializableImplementation';

                $phpcsFile->addWarning($message, $stackPtr, $code);
            }

            // Both magic methods have been found, we're good.
            return;
        }

        $methods = '';
        if ($hasMagic['__serialize'] === false && $hasMagic['__unserialize'] === false) {
            $methods = '__serialize() and __unserialize()';
        } elseif ($hasMagic['__serialize'] === false) {
            $methods = '__serialize()';
        } elseif ($hasMagic['__unserialize'] === false) {
            $methods = '__unserialize()';
        }

        $message = '"Only Serializable" classes are deprecated since PHP 8.1. The magic __serialize() and __unserialize() methods need to be implemented for cross-version compatibility. Missing implementation of: ' . $methods;
        $code    = 'Deprecated';

        $phpcsFile->addWarning($message, $stackPtr, $code);
    }

    /**
     * Update the interface list to match against.
     *
     * @since 10.0.0
     *
     * @return void
     */
    private function updateInterfaceList()
    {
        // Allow for resetting the property in test files.
        if ($this->serializableInterfaces === null || $this->serializableInterfaces === '') {
            $this->serializableInterfaces = [];
        }

        if (isset($this->lastSeenSerializableInterfaces)
            && $this->lastSeenSerializableInterfaces === $this->serializableInterfaces
        ) {
            // Nothing to do.
            return;
        }

        // Make sure that all name comparisons against the extra list will be done in a case-insensitive manner.
        $this->customSerializableInterfaces = $this->serializableInterfaces;
        if (empty($this->customSerializableInterfaces) === false) {
            $this->customSerializableInterfaces = $this->cleanInterfaceNames($this->customSerializableInterfaces);
        }

        // Overwrite any previously set list to clear out any "extras" no longer set.
        $interfaceList       = \array_merge($this->phpSerializableInterfaces, $this->customSerializableInterfaces, $this->collectedInterfaces);
        $this->interfaceList = \array_unique($interfaceList);

        $this->lastSeenSerializableInterfaces = $this->serializableInterfaces;
    }

    /**
     * Lowercase the interface names and remove leading namespace separators.
     *
     * @since 10.0.0
     *
     * @param array $interfaceNames List of interface names.
     *
     * @return array
     */
    private function cleanInterfaceNames($interfaceNames)
    {
        if (\is_string($interfaceNames) === true) {
            // Probably received in old, pre-PHPCS 3.3.0 syntax.
            $interfaceNames = \explode(',', $interfaceNames);
        }

        // Make double sure that this is an array.
        $interfaceNames = (array) $interfaceNames;

        $interfaceNames = \array_map('strtolower', $interfaceNames);
        $interfaceNames = \array_map('ltrim', $interfaceNames, \array_fill(0, \count($interfaceNames), '\\'));

        return $interfaceNames;
    }

    /**
     * Check whether the magic methods have been declared within the class/interface.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return array<string, bool> Array with two keys: '__serialize' and '__unserialize'.
     *                             The values are boolean indicators of whether the method declarations were found.
     */
    private function findMagicMethods($phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $class  = $tokens[$stackPtr];

        $scopeCloser           = $class['scope_closer'];
        $nextFunc              = $class['scope_opener'];
        $foundMagicSerialize   = false;
        $foundMagicUnserialize = false;

        while (($nextFunc = $phpcsFile->findNext([\T_FUNCTION, \T_DOC_COMMENT_OPEN_TAG, \T_ATTRIBUTE], ($nextFunc + 1), $scopeCloser)) !== false) {
            // Skip over docblocks.
            if ($tokens[$nextFunc]['code'] === \T_DOC_COMMENT_OPEN_TAG
                && isset($tokens[$nextFunc]['comment_closer'])
            ) {
                $nextFunc = $tokens[$nextFunc]['comment_closer'];
                continue;
            }

            // Skip over attributes.
            if ($tokens[$nextFunc]['code'] === \T_ATTRIBUTE
                && isset($tokens[$nextFunc]['attribute_closer'])
            ) {
                $nextFunc = $tokens[$nextFunc]['attribute_closer'];
                continue;
            }

            $functionScopeCloser = $nextFunc;
            if (isset($tokens[$nextFunc]['scope_closer'])) {
                // Normal (non-abstract, non-interface) method.
                $functionScopeCloser = $tokens[$nextFunc]['scope_closer'];
            }

            $funcName = FunctionDeclarations::getName($phpcsFile, $nextFunc);
            if (empty($funcName) || \is_string($funcName) === false) {
                // Shouldn't be possible, but just in case.
                $nextFunc = $functionScopeCloser; // @codeCoverageIgnore
                continue;
            }

            if (\strtolower($funcName) === '__serialize') {
                $foundMagicSerialize = true;
            } elseif (\strtolower($funcName) === '__unserialize') {
                $foundMagicUnserialize = true;
            }

            // If both have been found, no need to continue looping through the functions.
            if ($foundMagicSerialize === true && $foundMagicUnserialize === true) {
                break;
            }

            $nextFunc = $functionScopeCloser;
        }

        return [
            '__serialize'   => $foundMagicSerialize,
            '__unserialize' => $foundMagicUnserialize,
        ];
    }
}
