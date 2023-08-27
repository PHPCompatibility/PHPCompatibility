<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\TypeCasts;

use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Detect use of newly introduced type casts.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/language.types.type-juggling.php#language.types.typecasting
 *
 * @since 8.0.1
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionNewFeatureTrait`.
 */
class NewTypeCastsSniff extends Sniff
{
    use ComplexVersionNewFeatureTrait;

    /**
     * A list of new type casts, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * @since 8.0.1
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $newTypeCasts = [
        'T_UNSET_CAST' => [
            '4.4'         => false,
            '5.0'         => true,
            'description' => 'The unset cast',
        ],
        'T_BINARY_CAST' => [
            '5.2.0'       => false,
            '5.2.1'       => true,
            'description' => 'The binary cast',
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.0.1
     *
     * @return array<int|string>
     */
    public function register()
    {
        $tokens = [];
        foreach ($this->newTypeCasts as $token => $versions) {
            if (\defined($token)) {
                $tokens[] = \constant($token);
            }
        }

        return $tokens;
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 8.0.1
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
            'name'    => $tokenType,
            'content' => $tokens[$stackPtr]['content'],
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
        $itemArray   = $this->newTypeCasts[$itemInfo['name']];
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
        $this->msgTemplate = '%s is not present in PHP version %s or earlier. Found: %s';

        $msgInfo = $this->getMessageInfo($itemArray['description'], $itemInfo['name'], $versionInfo);

        $msgInfo['data'][] = $itemInfo['content'];

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
