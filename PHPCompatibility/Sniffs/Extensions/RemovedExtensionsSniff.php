<?php
/**
 * \PHPCompatibility\Sniffs\PHP\RemovedExtensionsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\AbstractRemovedFeatureSniff;

/**
 * \PHPCompatibility\Sniffs\PHP\RemovedExtensionsSniff.
 *
 * Discourages the use of removed extensions. Suggests alternative extensions if available
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class RemovedExtensionsSniff extends AbstractRemovedFeatureSniff
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
     * A list of removed extensions with their alternative, if any
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the extension was deprecated/removed.
     *
     * @var array(string|null)
     */
    protected $removedExtensions = array(
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
        ),
        'dbx' => array(
            '5.1' => true,
            'alternative' => 'pecl/dbx',
        ),
        'dio' => array(
            '5.1' => true,
            'alternative' => 'pecl/dio',
        ),
        'ereg' => array(
            '5.3' => false,
            '7.0' => true,
            'alternative' => 'pcre',
        ),
        'fam' => array(
            '5.1' => true,
            'alternative' => null,
        ),
        'fbsql' => array(
            '5.3' => true,
            'alternative' => null,
        ),
        'fdf' => array(
            '5.3' => true,
            'alternative' => 'pecl/fdf',
        ),
        'filepro' => array(
            '5.2' => true,
            'alternative' => null,
        ),
        'hw_api' => array(
            '5.2' => true,
            'alternative' => null,
        ),
        'ingres' => array(
            '5.1' => true,
            'alternative' => 'pecl/ingres',
        ),
        'ircg' => array(
            '5.1' => true,
            'alternative' => null,
        ),
        'mcrypt' => array(
            '7.1' => false,
            '7.2' => true,
            'alternative' => 'openssl (preferred) or pecl/mcrypt once available',
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
        ),
        'msql' => array(
            '5.3' => true,
            'alternative' => null,
        ),
        'mssql' => array(
            '7.0' => true,
            'alternative' => null,
        ),
        'mysql_' => array(
            '5.5' => false,
            '7.0' => true,
            'alternative' => 'mysqli',
        ),
        'ncurses' => array(
            '5.3' => true,
            'alternative' => 'pecl/ncurses',
        ),
        'oracle' => array(
            '5.1' => true,
            'alternative' => 'oci8 or pdo_oci',
        ),
        'ovrimos' => array(
            '5.1' => true,
            'alternative' => null,
        ),
        'pfpro' => array(
            '5.3' => true,
            'alternative' => null,
        ),
        'sqlite' => array(
            '5.4' => true,
            'alternative' => null,
        ),
        // Has to be before `sybase` as otherwise it will never match.
        'sybase_ct' => array(
            '7.0' => true,
            'alternative' => null,
        ),
        'sybase' => array(
            '5.3' => true,
            'alternative' => 'sybase_ct',
        ),
        'w32api' => array(
            '5.1' => true,
            'alternative' => 'pecl/ffi',
        ),
        'yp' => array(
            '5.1' => true,
            'alternative' => null,
        ),
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of function names.
        $this->removedExtensions = $this->arrayKeysToLowercase($this->removedExtensions);

        return array(T_STRING);

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
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

    }//end process()


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

    }//end isWhiteListed()


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


}//end class
