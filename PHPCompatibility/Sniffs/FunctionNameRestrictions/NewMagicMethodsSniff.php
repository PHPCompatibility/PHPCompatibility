<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionNameRestrictions;

use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\Scopes;

/**
 * Warns for non-magic behaviour of magic methods prior to becoming magic.
 *
 * PHP version 5.0+
 *
 * @link https://www.php.net/manual/en/language.oop5.magic.php
 * @link https://wiki.php.net/rfc/closures#additional_goodyinvoke
 * @link https://wiki.php.net/rfc/debug-info
 * @link https://wiki.php.net/rfc/phase_out_serializable Special casing of the __[un]serialize methods.
 *
 * @since 7.0.4
 * @since 7.1.0  Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class.
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionNewFeatureTrait`.
 */
class NewMagicMethodsSniff extends Sniff
{
    use ComplexVersionNewFeatureTrait;

    /**
     * A list of new magic methods, not considered magic in older versions.
     *
     * Method names in the array should be all *lowercase*.
     * The array lists : version number with false (not magic) or true (magic).
     * If's sufficient to list the first version where the method became magic.
     *
     * @since 7.0.4
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $newMagicMethods = [
        '__construct' => [
            '4.4' => false,
            '5.0' => true,
        ],
        '__destruct' => [
            '4.4' => false,
            '5.0' => true,
        ],
        '__get' => [
            '4.4' => false,
            '5.0' => true,
        ],

        '__isset' => [
            '5.0' => false,
            '5.1' => true,
        ],
        '__unset' => [
            '5.0' => false,
            '5.1' => true,
        ],
        '__set_state' => [
            '5.0' => false,
            '5.1' => true,
        ],

        '__callstatic' => [
            '5.2' => false,
            '5.3' => true,
        ],
        '__invoke' => [
            '5.2' => false,
            '5.3' => true,
        ],

        '__debuginfo' => [
            '5.5' => false,
            '5.6' => true,
        ],

        '__serialize' => [
            '7.3' => false,
            '7.4' => true,
        ],
        '__unserialize' => [
            '7.3' => false,
            '7.4' => true,
        ],

        // Special case - only became properly magical in 5.2.0,
        // before that it was only called for echo and print.
        '__tostring' => [
            '5.1'     => false,
            '5.2'     => true,
            'message' => 'The method %s() was not truly magical in PHP version %s and earlier. The associated magic functionality will only be called when directly combined with echo or print.',
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.4
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_FUNCTION];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.4
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $functionName   = FunctionDeclarations::getName($phpcsFile, $stackPtr);
        $functionNameLc = \strtolower($functionName);

        if (isset($this->newMagicMethods[$functionNameLc]) === false) {
            return;
        }

        $scopePtr = Scopes::validDirectScope($phpcsFile, $stackPtr, Tokens::$ooScopeTokens);
        if ($scopePtr === false) {
            return;
        }

        /*
         * Special case: don't throw an error when a declaration is found for __[un]serialize()
         * and the class in which the methods are implemented/the interface also implements/extends
         * the Serializable interface.
         */
        if ($functionNameLc === '__serialize' || $functionNameLc === '__unserialize') {
            $tokens = $phpcsFile->getTokens();

            if ($tokens[$scopePtr]['code'] === \T_INTERFACE) {
                $extendedInterfaces = ObjectDeclarations::findExtendedInterfaceNames($phpcsFile, $scopePtr);

                if (\is_array($extendedInterfaces) === true) {
                    $extendedInterfaces = \array_map('strtolower', $extendedInterfaces);

                    if (\in_array('serializable', $extendedInterfaces, true) === true) {
                        return;
                    }
                }
            } elseif (isset(Collections::ooCanImplement()[$tokens[$scopePtr]['code']]) === true) {
                // Class or enum.
                $implementedInterfaces = ObjectDeclarations::findImplementedInterfaceNames($phpcsFile, $scopePtr);

                if (\is_array($implementedInterfaces) === true) {
                    $implementedInterfaces = \array_map('strtolower', $implementedInterfaces);

                    if (\in_array('serializable', $implementedInterfaces, true) === true) {
                        return;
                    }
                }
            }
        }

        $itemInfo = [
            'name'   => $functionName,
            'nameLc' => $functionNameLc,
        ];
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
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
        $itemArray   = $this->newMagicMethods[$itemInfo['nameLc']];
        $versionInfo = $this->getVersionInfo($itemArray);

        if (empty($versionInfo['not_in_version'])
            || ScannedCode::shouldRunOnOrBelow($versionInfo['not_in_version']) === false
        ) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $itemInfo, $itemArray, $versionInfo);
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
     * @param array                       $itemArray   The sub-array with all the details about
     *                                                 this item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, array $itemInfo, array $itemArray, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'The method %s() was not magical in PHP version %s and earlier. The associated magic functionality will not be invoked.';

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        $message = $msgInfo['message'];
        if (empty($itemArray['message']) === false) {
            $message = $itemArray['message'];
        }

        $phpcsFile->addWarning($message, $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
