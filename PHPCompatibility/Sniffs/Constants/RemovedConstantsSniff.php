<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Constants;

use PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait;
use PHPCompatibility\Helpers\MiscHelper;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Detect use of deprecated and/or removed PHP native global constants.
 *
 * PHP version All
 *
 * @since 8.1.0
 * @since 10.0.0 - Now extends the base `Sniff` class and uses the `ComplexVersionDeprecatedRemovedFeatureTrait`.
 *               - This class is now `final`.
 */
final class RemovedConstantsSniff extends Sniff
{
    use ComplexVersionDeprecatedRemovedFeatureTrait;

    /**
     * A list of removed PHP Constants.
     *
     * The array lists : version number with false (deprecated) or true (removed).
     * It's sufficient to list the first version where the constant was deprecated/removed.
     *
     * Optional, the array can contain an `alternative` key listing an alternative constant
     * to be used instead.
     *
     * Note: PHP Constants are case-sensitive!
     *
     * @since 8.1.0
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $removedConstants = [
        'SORT_LOCALE_STRING' => [
            '8.6'         => false,
            'alternative' => 'one of the Collator::*sort*() methods',
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.1.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            \T_STRING,
            \T_NAME_FULLY_QUALIFIED,
        ];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 8.1.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens       = $phpcsFile->getTokens();
        $constantName = \ltrim($tokens[$stackPtr]['content'], '\\');

        if (isset($this->removedConstants[$constantName]) === false) {
            return;
        }

        if (MiscHelper::isUseOfGlobalConstant($phpcsFile, $stackPtr) === false) {
            return;
        }

        $itemInfo = [
            'name' => $constantName,
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
        $itemArray   = $this->removedConstants[$itemInfo['name']];
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

        if ($itemInfo['name'] === 'T_BAD_CHARACTER') {
            // T_BAD_CHARACTER is a special case. It was removed in 7.0.0 and re-added in 7.4.0
            // See also PHPCompatibility.Constants.NewConstants
            if (ScannedCode::shouldRunOnOrBelow('7.3')) {
                $message = 'The constant "T_BAD_CHARACTER" is not present in PHP versions 7.0 through 7.3';
                $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

                $phpcsFile->addError($message, $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
            }

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
        $this->msgTemplate = 'The constant "%s" is ';

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
