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

use PHP_CodeSniffer\Files\File;
use PHPCompatibility\AbstractNewFeatureSniff;

/**
 * Detect use of PHP extensions which were not available in older PHP versions.
 *
 * PHP version All
 *
 * @since x.x.x
 */
class NewExtensionsSniff extends AbstractNewFeatureSniff
{

    /**
     * A list of functions to whitelist, if any.
     *
     * This is intended for projects using functions which start with the same
     * prefix as one of the removed extensions.
     *
     * This property can be set from the ruleset, like so:
     * <rule ref="PHPCompatibility.PHP.RemovedExtensions">
     *   <properties>
     *     <property name="functionWhitelist" type="array" value="mysql_to_rfc3339,mysql_another_function" />
     *   </properties>
     * </rule>
     *
     * @since x.x.x
     *
     * @var array
     */
    public $functionWhitelist;

    /**
     * A list of new PHP extensions.
     *
     * The array lists : version number with false (available in PECL) and true (shipped with PHP).
     * If's sufficient to list the first version where the extension was introduced.
     *
     * @since x.x.x
     *
     * @var array(string|null)
     */
    protected $newExtensions = [
        'csprng' => [
            '7.0'      => true,
            'prefixes' => [
                // Function prefix all functions: verified.
                'random_', // In practice: only random_bytes, random_int
                // NO ini settings
                // NO constants
            ],
        ],
        'fileinfo' => [
            '5.3'      => true,
            'prefixes' => [
                // Function prefix all functions: verified.
                'finfo_',
                // Also function: mime_content_type()
                // Class: finfo
                // NO ini settings
                // Constants prefix: FILEINFO_
            ],
        ],
        'hash' => [
            '5.1.2'    => true,
            'prefixes' => [
                // Function prefix: verified.
                'hash_',
                // Function `hash()` also exists!!!!
                // NO ini settings
                // 1 constant: HASH_HMAC
            ],
        ],
        'opcache' => [
            '5.2'      => false,
            '5.5'      => true,
            'prefixes' => [
                // Function prefix all functions: verified.
                'opcache_',
                // ini prefix: `opcache.`
                // NO constants
            ],
        ],
        'password' => [
            '5.5'      => true,
            'prefixes' => [
                // Function prefix all functions: verified.
                'password_',
                // NO ini settings
                // 2 constants: PASSWORD_BCRYPT, PASSWORD_DEFAULT
            ],
        ],
        'phar' => [
            '5.3'      => true,
            'prefixes' => [
                // NO functions, only classes.
                // ini prefix: `phar.`
                // constants - all class constants: `Phar::`
                // Classes: Phar, PharData, PharFileInfo, PharException
            ],
        ],
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since x.x.x
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of function names.
        $this->newExtensions = \array_change_key_case($this->newExtensions, \CASE_LOWER);

        return [\T_STRING];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since x.x.x
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
/*
        $tokens = $phpcsFile->getTokens();

        // Find the next non-empty token.
        $openBracket = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);

        if ($tokens[$openBracket]['code'] !== \T_OPEN_PARENTHESIS) {
            // Not a function call.
            return;
        }

        if (isset($tokens[$openBracket]['parenthesis_closer']) === false) {
            // Not a function call.
            return;
        }

        // Find the previous non-empty token.
        $search   = \PHP_CodeSniffer_Tokens::$emptyTokens;
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
                $itemInfo = array(
                    'name'   => $extension,
                );
                $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
                break;
            }
        }
*/
    }

    /**
     * Is the current function being checked whitelisted ?
     *
     * Parsing the list late as it may be provided as a property, but also inline.
     *
     * @since x.x.x
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
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @since x.x.x
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->removedExtensions[$itemInfo['name']];
    }

    /**
     * Get the error message template for this sniff.
     *
     * @since x.x.x
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return "Extension '%s' is ";
    }
}
