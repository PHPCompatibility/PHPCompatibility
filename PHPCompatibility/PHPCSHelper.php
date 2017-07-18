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

}
