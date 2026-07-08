<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionUse;

use PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Detect calls to deprecated/removed native PHP functions.
 *
 * Suggests alternative if available.
 *
 * PHP version All
 *
 * @since 5.5
 * @since 5.6    Now extends the base `Sniff` class instead of the upstream
 *               `Generic.PHP.ForbiddenFunctions` sniff.
 * @since 7.1.0  Now extends the `AbstractRemovedFeatureSniff` instead of the base `Sniff` class.
 * @since 9.0.0  Renamed from `DeprecatedFunctionsSniff` to `RemovedFunctionsSniff`.
 * @since 10.0.0 - Now extends the base `Sniff` class and uses the `ComplexVersionDeprecatedRemovedFeatureTrait`.
 *               - This class is now `final`.
 */
final class RemovedFunctionsSniff extends Sniff
{
    use ComplexVersionDeprecatedRemovedFeatureTrait;

    /**
     * A list of deprecated and removed functions with their alternatives.
     *
     * The array lists : version number with false (deprecated) or true (removed) and an alternative function.
     * If no alternative exists, it is NULL, i.e, the function should just not be used.
     *
     * @since 5.5
     * @since 5.6   Visibility changed from `protected` to `public`.
     * @since 7.0.2 Visibility changed back from `public` to `protected`.
     *              The earlier change was made to be in line with the upstream sniff,
     *              but that sniff is no longer being extended.
     * @since 7.0.8 Property renamed from `$forbiddenFunctions` to `$removedFunctions`.
     *
     * @var array<string, array<string, bool|string|null>>
     */
    protected $removedFunctions = [
        '_' => [
            '8.6'         => false,
            'alternative' => 'gettext()',
            'extension'   => 'gettext',
        ],

        'is_double' => [
            '8.6'         => false,
            'alternative' => 'is_float()',
        ],
        'is_integer' => [
            '8.6'         => false,
            'alternative' => 'is_int()',
        ],
        'is_long' => [
            '8.6'         => false,
            'alternative' => 'is_int()',
        ],
        'doubleval' => [
            '8.6'         => false,
            'alternative' => 'floatval()',
        ],
        'strcoll' => [
            '8.6'         => false,
            'alternative' => 'Collator::compare()',
        ],
        'metaphone' => [
            '8.6'         => false,
        ],

        'spl_classes' => [
            '8.6'         => false,
            'alternative' => 'ReflectionExtension::getClassNames()',
            'extension'   => 'spl',
        ],
        'spl_object_hash' => [
            '8.6'         => false,
            'alternative' => 'spl_object_id()',
            'extension'   => 'spl',
        ],

        'mysqli_stmt_init' => [
            '8.6'         => false,
            'alternative' => 'mysqli::prepare()',
            'extension'   => 'mysqli',
        ],
        'mysqli_get_charset' => [
            '8.6'         => false,
            'extension'   => 'mysqli',
        ],

// Not properly listed in RFC yet, only in TOC, no text or voting widget
        'imagegd' => [
            '8.6'         => false,
            'extension'   => 'gd',
        ],
        'imagegd2' => [
            '8.6'         => false,
            'extension'   => 'gd',
        ],
        'imagecreatefromgd2' => [
            '8.6'         => false,
            'extension'   => 'gd',
        ],
        'imagecreatefromgd2part' => [
            '8.6'         => false,
            'extension'   => 'gd',
        ],
        'imagecreatefromgd' => [
            '8.6'         => false,
            'extension'   => 'gd',
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
        // Handle case-insensitivity of function names.
        $this->removedFunctions = \array_change_key_case($this->removedFunctions, \CASE_LOWER);

        return [
            \T_STRING,
            \T_NAME_FULLY_QUALIFIED,
        ];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
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

        $function   = \ltrim($tokens[$stackPtr]['content'], '\\');
        $functionLc = \strtolower($function);

        if (isset($this->removedFunctions[$functionLc]) === false) {
            return;
        }

        $nextToken = $phpcsFile->findNext(Tokens::EMPTY_TOKENS, ($stackPtr + 1), null, true);
        if ($nextToken === false
            || $tokens[$nextToken]['code'] !== \T_OPEN_PARENTHESIS
            || isset($tokens[$nextToken]['parenthesis_owner']) === true
        ) {
            return;
        }

        $ignore  = [\T_NEW => true];
        $ignore += Collections::objectOperators();

        $prevToken = $phpcsFile->findPrevious(Tokens::EMPTY_TOKENS, ($stackPtr - 1), null, true);
        if (isset($ignore[$tokens[$prevToken]['code']]) === true) {
            // Not a call to a PHP function.
            return;

        }

        $itemInfo = [
            'name'   => $function,
            'nameLc' => $functionLc,
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
        $itemArray   = $this->removedFunctions[$itemInfo['nameLc']];
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
        $this->msgTemplate = 'Function %s() is ';

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
