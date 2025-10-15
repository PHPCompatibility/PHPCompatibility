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

use PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Detect use of deprecated/removed type casts.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/language.types.type-juggling.php#language.types.typecasting
 * @link https://wiki.php.net/rfc/deprecations_php_7_2#unset_cast
 * @link https://wiki.php.net/rfc/deprecations_php_7_4#the_real_type
 *
 * @since 8.0.1
 * @since 9.0.0  Renamed from `DeprecatedTypeCastsSniff` to `RemovedTypeCastsSniff`.
 * @since 10.0.0 - Now extends the base `Sniff` class and uses the `ComplexVersionDeprecatedRemovedFeatureTrait`.
 *               - This class is now `final`.
 */
final class RemovedTypeCastsSniff extends Sniff
{
    use ComplexVersionDeprecatedRemovedFeatureTrait;

    /**
     * A list of deprecated and removed type casts with their alternatives.
     *
     * The array lists : version number with false (deprecated) or true (removed) and an alternative function.
     * If no alternative exists, it is NULL, i.e, the type cast should just not be used.
     *
     * @since 8.0.1
     * @since 10.0.0 The array format was changed to allow for tokens covering multiple different casts.
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $deprecatedTypeCasts = [
        'unset' => [
            'token_type'  => 'T_UNSET_CAST',
            '7.2'         => false,
            '8.0'         => true,
            'alternative' => 'unset()',
        ],
        'real' => [
            'token_type'  => 'T_DOUBLE_CAST',
            '7.4'         => false,
            '8.0'         => true,
            'alternative' => '(float)',
        ],
        'integer' => [
            'token_type'  => 'T_INT_CAST',
            '8.5'         => false,
            'alternative' => '(int)',
        ],
        'boolean' => [
            'token_type'  => 'T_BOOL_CAST',
            '8.5'         => false,
            'alternative' => '(bool)',
        ],
        'double' => [
            'token_type'  => 'T_DOUBLE_CAST',
            '8.5'         => false,
            'alternative' => '(float)',
        ],
        'binary' => [
            'token_type'  => 'T_BINARY_CAST',
            '8.5'         => false,
            'alternative' => '(string)',
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
        foreach ($this->deprecatedTypeCasts as $versions) {
            $tokenConstant          = \constant($versions['token_type']);
            $tokens[$tokenConstant] = $tokenConstant;
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
        $tokens = $phpcsFile->getTokens();

        // Type casts are case-insensitive and can contain whitespace, but no new lines.
        // Normalize them to make them comparable.
        $contents      = $tokens[$stackPtr]['content'];
        $castToCompare = \substr($contents, 1, (\strlen($contents) - 2));
        $castToCompare = \trim($castToCompare, " \t");
        $castToCompare = \strtolower($castToCompare);

        if (isset($this->deprecatedTypeCasts[$castToCompare]) === false) {
            // Type cast which is still supported, using the same token as a deprecated type cast.
            return;
        }

        $itemInfo = [
            'name' => $castToCompare,
        ];
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
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
        $itemArray   = $this->deprecatedTypeCasts[$itemInfo['name']];
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

        $this->addMessage($phpcsFile, $stackPtr, $isError, $itemInfo, $itemArray, $versionInfo);
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
     * @param array                       $itemArray   The sub-array with all the details about
     *                                                 this item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addMessage(File $phpcsFile, $stackPtr, $isError, array $itemInfo, array $itemArray, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'The %s cast is ';

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
