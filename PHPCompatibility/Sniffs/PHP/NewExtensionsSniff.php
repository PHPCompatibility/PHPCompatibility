<?php
/**
 * \PHPCompatibility\Sniffs\PHP\NewExtensionsSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHPCompatibility\AbstractNewFeatureSniff;

/**
 * \PHPCompatibility\Sniffs\PHP\NewExtensionsSniff.
 *
 * Detect use of PHP extensions which were not available in older PHP versions.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
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
     * @var array
     */
    public $functionWhitelist;

    /**
     * A list of new PHP extensions.
     *
     * The array lists : version number with false (available in PECL) and true (shipped with PHP).
     * If's sufficient to list the first version where the extension was introduced.
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

/*
        'activescript' => array(
            '5.1' => true,
            'alternative' => 'pecl/activescript',
        ),
        'cpdf' => array(
            '5.1' => true,
            'alternative' => 'pecl/pdflib',
        ),
        'dbase' => array(
            '5.3' => true,
            'alternative' => null,
            'separator' => '_', // Verified: all functions use separator.
        ),
        'dbx' => array(
            '5.1' => true,
            'alternative' => 'pecl/dbx',
            'separator' => '_', // Verified: all functions use separator.
        ),
        'dio' => array(
            '5.1' => true,
            'alternative' => 'pecl/dio',
            'separator' => '_', // Verified: all functions use separator.
        ),
        'ereg' => array(
            '5.3' => false,
            '7.0' => true,
            'alternative' => 'pcre',
        ),
        'fam' => array(
            '5.1' => true,
            'alternative' => null,
            'separator' => '_', // Verified: all functions use separator.
        ),
        'fbsql' => array(
            '5.3' => true,
            'alternative' => null,
            'separator' => '_', // Verified: all functions use separator.
        ),
        'fdf' => array(
            '5.3' => true,
            'alternative' => 'pecl/fdf',
            'separator' => '_', // Verified: all functions use separator.
        ),
        'filepro' => array(
            '5.2' => true,
            'alternative' => null,
            'separator' => '_', // Verified: function 'filepro' exists - all other functions use separator.
        ),
        'hw_api' => array(
            '5.2' => true,
            'alternative' => null,
        ),
        'ingres' => array(
            '5.1' => true,
            'alternative' => 'pecl/ingres',
            'separator' => '_', // Verified: all functions use separator.
        ),
        'ircg' => array(
            '5.1' => true,
            'alternative' => null,
        ),
        'mcrypt' => array(
            '7.1' => false,
            'alternative' => 'openssl (preferred) or pecl/mcrypt once available',
            'separator' => '_', // Verified: all functions use separator, though there is also the mdecrypt_generic function.
        ),
        'mcve' => array(
            '5.1' => true,
            'alternative' => 'pecl/mvce',
        ),
        'ming' => array(
            '5.3' => true,
            'alternative' => 'pecl/ming',
        ),
        'mnogosearch' => array(
            '5.1' => true,
            'alternative' => null,
            'prefix' => 'udm',
            'separator' => '_', // Verified: all functions use separator.
        ),
        'msql' => array(
            '5.3' => true,
            'alternative' => null,
            'separator' => '_', // Verified: function 'msql' exists - all other functions use separator.
        ),
        'mssql' => array(
            '7.0' => true,
            'alternative' => null,
            'separator' => '_', // Verified: all functions use separator.
        ),
        'mysql_' => array(
            '5.5' => false,
            '7.0' => true,
            'alternative' => 'mysqli',
            'separator' => '_', // Verified: all functions use separator.
        ),
        'ncurses' => array(
            '5.3' => true,
            'alternative' => 'pecl/ncurses',
            'separator' => '_', // Verified: all functions use separator.
        ),
        'oracle' => array(
            '5.1' => true,
            'alternative' => 'oci8 or pdo_oci',
        ),
        'ovrimos' => array(
            '5.1' => true,
            'alternative' => null,
            'separator' => '_', // Verified: all functions use separator.
        ),
        'pfpro' => array(
            '5.3' => true,
            'alternative' => null,
        ),
        'sqlite' => array(
            '5.4' => true,
            'alternative' => null,
            'separator' => '_', // Verified: all functions use separator.
        ),
        // Has to be before `sybase` as otherwise it will never match.
        'sybase_ct' => array(
            '7.0' => true,
            'alternative' => null,
        ),
        'sybase' => array(
            '5.3' => true,
            'alternative' => 'sybase_ct',
            'separator' => '_', // Verified: all functions use separator.
        ),
        'w32api' => array(
            '5.1' => true,
            'alternative' => 'pecl/ffi',
            'separator' => '_', // Verified: all functions use separator.
        ),
        'yp' => array(
            '5.1' => true,
            'alternative' => null,
            'separator' => '_', // Verified: all functions use separator.
        ),
*/
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
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

        if ($tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS) {
            // Not a function call.
            return;
        }

        if (isset($tokens[$openBracket]['parenthesis_closer']) === false) {
            // Not a function call.
            return;
        }

        // Find the previous non-empty token.
        $search   = \PHP_CodeSniffer_Tokens::$emptyTokens;
        $search[] = T_BITWISE_AND;
        $previous = $phpcsFile->findPrevious($search, ($stackPtr - 1), null, true);
        if ($tokens[$previous]['code'] === T_FUNCTION) {
            // It's a function definition, not a function call.
            return;
        }

        if ($tokens[$previous]['code'] === T_NEW) {
            // We are creating an object, not calling a function.
            return;
        }

        if ($tokens[$previous]['code'] === T_OBJECT_OPERATOR) {
            // We are calling a method of an object.
            return;
        }

        $function   = $tokens[$stackPtr]['content'];
        $functionLc = strtolower($function);

        if ($this->isWhiteListed($functionLc) === true) {
            // Function is whitelisted.
            return;
        }

        foreach ($this->removedExtensions as $extension => $versionList) {
            if (strpos($functionLc, $extension) === 0) {
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
     * @param string $content Content of the current token.
     *
     * @return bool
     */
    protected function isWhiteListed($content)
    {
        if (isset($this->functionWhitelist) === false) {
            return false;
        }

        if (is_string($this->functionWhitelist) === true) {
            if (strpos($this->functionWhitelist, ',') !== false) {
                $this->functionWhitelist = explode(',', $this->functionWhitelist);
            } else {
                $this->functionWhitelist = (array) $this->functionWhitelist;
            }
        }

        if (is_array($this->functionWhitelist) === true) {
            $this->functionWhitelist = array_map('strtolower', $this->functionWhitelist);
            return in_array($content, $this->functionWhitelist, true);
        }

        return false;
    }


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
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
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return "Extension '%s' is ";
    }
}
