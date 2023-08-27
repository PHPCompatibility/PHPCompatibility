<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Extensions;

use PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Detect the use of deprecated and/or removed PHP extensions.
 *
 * This sniff examines function calls made and flags function calls to functions
 * prefixed with the dedicated prefix from a deprecated/removed native PHP extension.
 *
 * Suggests alternative extensions if available.
 *
 * As userland functions may be prefixed with a prefix also used by a native
 * PHP extension, the sniff offers the ability to whitelist specific functions
 * from being flagged by this sniff via a property in a custom ruleset
 * (since PHPCompatibility 7.0.2).
 *
 * {@internal This sniff is a candidate for removal once all functions from all
 * deprecated/removed extensions have been added to the RemovedFunctions sniff.}
 *
 * PHP version All
 *
 * @since 5.5
 * @since 7.1.0  Now extends the `AbstractRemovedFeatureSniff` instead of the base `Sniff` class.
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionDeprecatedRemovedFeatureTrait`.
 */
class RemovedExtensionsSniff extends Sniff
{
    use ComplexVersionDeprecatedRemovedFeatureTrait;

    /**
     * A list of functions to whitelist, if any.
     *
     * This is intended for projects using functions which start with the same
     * prefix as one of the removed extensions.
     *
     * This property can be set from the ruleset, like so:
     * <rule ref="PHPCompatibility.Extensions.RemovedExtensions">
     *   <properties>
     *     <property name="functionWhitelist" type="array" value="mysql_to_rfc3339,mysql_another_function" />
     *   </properties>
     * </rule>
     *
     * @since 7.0.2
     *
     * @var string[]
     */
    public $functionWhitelist;

    /**
     * A list of removed extensions with their alternative, if any.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the extension was deprecated/removed.
     *
     * @since 5.5
     *
     * @var array<string, array<string, bool|string|null>>
     */
    protected $removedExtensions = [
        'activescript' => [
            '5.1' => true,
            'alternative' => 'pecl/activescript',
        ],
        'cpdf' => [
            '5.1' => true,
            'alternative' => 'pecl/pdflib',
        ],
        'dbase' => [
            '5.3' => true,
            'alternative' => null,
        ],
        'dbx' => [
            '5.1' => true,
            'alternative' => 'pecl/dbx',
        ],
        'dio' => [
            '5.1' => true,
            'alternative' => 'pecl/dio',
        ],
        'ereg' => [
            '5.3' => false,
            '7.0' => true,
            'alternative' => 'pcre',
        ],
        'fam' => [
            '5.1' => true,
            'alternative' => null,
        ],
        'fbsql' => [
            '5.3' => true,
            'alternative' => null,
        ],
        'fdf' => [
            '5.3' => true,
            'alternative' => 'pecl/fdf',
        ],
        'filepro' => [
            '5.2' => true,
            'alternative' => null,
        ],
        'hw_api' => [
            '5.2' => true,
            'alternative' => null,
        ],
        'ibase' => [
            '7.4' => true,
            'alternative' => 'pecl/ibase',
        ],
        'ingres' => [
            '5.1' => true,
            'alternative' => 'pecl/ingres',
        ],
        'ircg' => [
            '5.1' => true,
            'alternative' => null,
        ],
        'mcrypt' => [
            '7.1' => false,
            '7.2' => true,
            'alternative' => 'openssl (preferred) or pecl/mcrypt once available',
        ],
        'mcve' => [
            '5.1' => true,
            'alternative' => 'pecl/mcve',
        ],
        'ming' => [
            '5.3' => true,
            'alternative' => 'pecl/ming',
        ],
        'mnogosearch' => [
            '5.1' => true,
            'alternative' => null,
        ],
        'msql' => [
            '5.3' => true,
            'alternative' => null,
        ],
        'mssql' => [
            '7.0' => true,
            'alternative' => null,
        ],
        'mysql_' => [
            '5.5' => false,
            '7.0' => true,
            'alternative' => 'mysqli',
        ],
        'ncurses' => [
            '5.3' => true,
            'alternative' => 'pecl/ncurses',
        ],
        'oracle' => [
            '5.1' => true,
            'alternative' => 'oci8 or pdo_oci',
        ],
        'ovrimos' => [
            '5.1' => true,
            'alternative' => null,
        ],
        'pfpro_' => [
            '5.1' => true,
            'alternative' => null,
        ],
        'recode' => [
            '7.4' => true,
            'alternative' => 'iconv or mbstring',
        ],
        'sqlite' => [
            '5.4' => true,
            'alternative' => null,
        ],
        // Has to be before `sybase` as otherwise it will never match.
        'sybase_ct' => [
            '7.0' => true,
            'alternative' => null,
        ],
        'sybase' => [
            '5.3' => true,
            'alternative' => 'sybase_ct',
        ],
        'w32api' => [
            '5.1' => true,
            'alternative' => 'pecl/ffi',
        ],
        'wddx' => [
            '7.4' => true,
            'alternative' => 'pecl/wddx',
        ],
        'yp' => [
            '5.1' => true,
            'alternative' => null,
        ],
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.5
     *
     * @return array<int|string>
     */
    public function register()
    {
        // Handle case-insensitivity of function names.
        $this->removedExtensions = \array_change_key_case($this->removedExtensions, \CASE_LOWER);

        return [\T_STRING];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Find the next non-empty token.
        $openBracket = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);

        if ($tokens[$openBracket]['code'] !== \T_OPEN_PARENTHESIS) {
            // Not a function call.
            return;
        }

        if (isset($tokens[$openBracket]['parenthesis_closer']) === false) {
            // Not a function call.
            return;
        }

        // Find the previous non-empty token.
        $search   = Tokens::$emptyTokens;
        $search[] = \T_BITWISE_AND;
        $previous = $phpcsFile->findPrevious($search, ($stackPtr - 1), null, true);
        if ($tokens[$previous]['code'] === \T_FUNCTION) {
            // It's a function definition, not a function call.
            return;
        }

        if ($tokens[$previous]['code'] === \T_NEW) {
            // We are creating an object, not calling a function.
            return;
        }

        if ($tokens[$previous]['code'] === \T_OBJECT_OPERATOR) {
            // We are calling a method of an object.
            return;
        }

        $function   = $tokens[$stackPtr]['content'];
        $functionLc = \strtolower($function);

        if ($this->isWhiteListed($functionLc) === true) {
            // Function is whitelisted.
            return;
        }

        foreach ($this->removedExtensions as $extension => $versionList) {
            if (\strpos($functionLc, $extension) === 0) {
                $itemInfo = [
                    'name'   => $extension,
                ];
                $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
                break;
            }
        }
    }


    /**
     * Is the current function being checked whitelisted ?
     *
     * Parsing the list late as it may be provided as a property, but also inline.
     *
     * @since 7.0.2
     *
     * @param string $content Content of the current token.
     *
     * @return bool
     */
    protected function isWhiteListed($content)
    {
        if (isset($this->functionWhitelist) === false) {
            return false;
        }

        if (\is_string($this->functionWhitelist) === true) {
            if (\strpos($this->functionWhitelist, ',') !== false) {
                $this->functionWhitelist = \explode(',', $this->functionWhitelist);
            } else {
                $this->functionWhitelist = (array) $this->functionWhitelist;
            }
        }

        if (\is_array($this->functionWhitelist) === true) {
            $this->functionWhitelist = \array_map('strtolower', $this->functionWhitelist);
            return \in_array($content, $this->functionWhitelist, true);
        }

        return false;
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
        $itemArray   = $this->removedExtensions[$itemInfo['name']];
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
        $this->msgTemplate = "Extension '%s' is ";

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
