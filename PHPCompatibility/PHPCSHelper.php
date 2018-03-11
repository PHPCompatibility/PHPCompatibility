<?php
/**
 * PHPCS cross-version compatibility helper class.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility;

/**
 * \PHPCompatibility\PHPCSHelper
 *
 * PHPCS cross-version compatibility helper class.
 *
 * A number of PHPCS classes were split up into several classes in PHPCS 3.x
 * Those classes cannot be aliased as they don't represent the same object.
 * This class provides helper methods for functions which were contained in
 * one of these classes and which are used within the PHPCompatibility library.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCSHelper
{

    /**
     * Get the PHPCS version number.
     *
     * @return string
     */
    public static function getVersion()
    {
        if (defined('\PHP_CodeSniffer\Config::VERSION')) {
            // PHPCS 3.x.
            return \PHP_CodeSniffer\Config::VERSION;
        } else {
            // PHPCS 1.x & 2.x.
            return \PHP_CodeSniffer::VERSION;
        }
    }


    /**
     * Pass config data to PHPCS.
     *
     * PHPCS cross-version compatibility helper.
     *
     * @param string      $key   The name of the config value.
     * @param string|null $value The value to set. If null, the config entry
     *                           is deleted, reverting it to the default value.
     * @param boolean     $temp  Set this config data temporarily for this script run.
     *                           This will not write the config data to the config file.
     *
     * @return void
     */
    public static function setConfigData($key, $value, $temp = false)
    {
        if (method_exists('\PHP_CodeSniffer\Config', 'setConfigData')) {
            // PHPCS 3.x.
            \PHP_CodeSniffer\Config::setConfigData($key, $value, $temp);
        } else {
            // PHPCS 1.x & 2.x.
            \PHP_CodeSniffer::setConfigData($key, $value, $temp);
        }
    }


    /**
     * Get the value of a single PHPCS config key.
     *
     * @param string $key The name of the config value.
     *
     * @return string|null
     */
    public static function getConfigData($key)
    {
        if (method_exists('\PHP_CodeSniffer\Config', 'getConfigData')) {
            // PHPCS 3.x.
            return \PHP_CodeSniffer\Config::getConfigData($key);
        } else {
            // PHPCS 1.x & 2.x.
            return \PHP_CodeSniffer::getConfigData($key);
        }
    }


    /**
     * Returns the name(s) of the interface(s) that the specified class implements.
     *
     * Returns FALSE on error or if there are no implemented interface names.
     *
     * {@internal Duplicate of same method as introduced in PHPCS 2.7.
     * This method also includes an improvement we use which was only introduced
     * in PHPCS 2.8.0, so only defer to upstream for higher versions.
     * Once the minimum supported PHPCS version for this sniff library goes beyond
     * that, this method can be removed and calls to it replaced with
     * `$phpcsFile->findImplementedInterfaceNames($stackPtr)` calls.}}
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the class token.
     *
     * @return array|false
     */
    public static function findImplementedInterfaceNames(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if (version_compare(self::getVersion(), '2.7.1', '>') === true) {
            return $phpcsFile->findImplementedInterfaceNames($stackPtr);
        }

        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        if ($tokens[$stackPtr]['code'] !== T_CLASS
            && $tokens[$stackPtr]['type'] !== 'T_ANON_CLASS'
        ) {
            return false;
        }

        if (isset($tokens[$stackPtr]['scope_closer']) === false) {
            return false;
        }

        $classOpenerIndex = $tokens[$stackPtr]['scope_opener'];
        $implementsIndex  = $phpcsFile->findNext(T_IMPLEMENTS, $stackPtr, $classOpenerIndex);
        if ($implementsIndex === false) {
            return false;
        }

        $find = array(
            T_NS_SEPARATOR,
            T_STRING,
            T_WHITESPACE,
            T_COMMA,
        );

        $end  = $phpcsFile->findNext($find, ($implementsIndex + 1), ($classOpenerIndex + 1), true);
        $name = $phpcsFile->getTokensAsString(($implementsIndex + 1), ($end - $implementsIndex - 1));
        $name = trim($name);

        if ($name === '') {
            return false;
        } else {
            $names = explode(',', $name);
            $names = array_map('trim', $names);
            return $names;
        }

    }//end findImplementedInterfaceNames()

}
