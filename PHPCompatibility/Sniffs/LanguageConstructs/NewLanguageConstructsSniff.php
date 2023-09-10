<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\LanguageConstructs;

use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Detect use of new PHP language constructs.
 *
 * PHP version All
 *
 * @link https://wiki.php.net/rfc/namespaceseparator
 * @link https://wiki.php.net/rfc/variadics
 * @link https://wiki.php.net/rfc/argument_unpacking
 *
 * @since 5.6
 * @since 7.1.0  Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class..
 * @since 9.0.0  Detection for new operator tokens has been moved to the `NewOperators` sniff.
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionNewFeatureTrait`.
 */
class NewLanguageConstructsSniff extends Sniff
{
    use ComplexVersionNewFeatureTrait;

    /**
     * A list of new language constructs, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * @since 5.6
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $newConstructs = [
        'T_NS_SEPARATOR' => [
            '5.2'         => false,
            '5.3'         => true,
            'description' => 'the \ operator (for namespaces)',
        ],
        'T_ELLIPSIS' => [
            '5.5'         => false,
            '5.6'         => true,
            'description' => 'the ... spread operator',
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.6
     *
     * @return array<int|string>
     */
    public function register()
    {
        $tokens = [];
        foreach ($this->newConstructs as $token => $versions) {
            $tokens[] = \constant($token);
        }
        return $tokens;
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.6
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $tokenType = $tokens[$stackPtr]['type'];

        $itemInfo = [
            'name' => $tokenType,
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
        $itemArray   = $this->newConstructs[$itemInfo['name']];
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
        $msgInfo = $this->getMessageInfo($itemArray['description'], $itemInfo['name'], $versionInfo);

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
