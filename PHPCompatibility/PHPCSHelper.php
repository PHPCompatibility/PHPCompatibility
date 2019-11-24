<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility;

use PHP_CodeSniffer_File as File;
use PHPCSUtils\BackCompat\Helper;
use PHPCSUtils\BackCompat\BCFile;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * PHPCS cross-version compatibility helper class.
 *
 * A number of PHPCS classes were split up into several classes in PHPCS 3.x
 * Those classes cannot be aliased as they don't represent the same object.
 * This class provides helper methods for functions which were contained in
 * one of these classes and which are used within the PHPCompatibility library.
 *
 * Additionally, this class contains some duplicates of PHPCS native methods.
 * These methods have received bug fixes or improved functionality between the
 * lowest supported PHPCS version and the latest PHPCS stable version and
 * to provide the same results cross-version, PHPCompatibility needs to use
 * the up-to-date versions of these methods.
 *
 * @since 8.0.0
 * @since 8.2.0 The duplicate PHPCS methods have been moved from the `Sniff`
 *              base class to this class.
 *
 * @deprecated 10.0.0
 */
class PHPCSHelper
{

    /**
     * Get the PHPCS version number.
     *
     * @since      8.0.0
     * @deprecated 10.0.0 Use {@see PHPCSUtils\BackCompat\Helper::getVersion()} instead.
     *
     * @return string
     */
    public static function getVersion()
    {
        return Helper::getVersion();
    }


    /**
     * Pass config data to PHPCS.
     *
     * PHPCS cross-version compatibility helper.
     *
     * @since      8.0.0
     * @deprecated 10.0.0 Use {@see PHPCSUtils\BackCompat\Helper::setConfigData()} instead.
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
        Helper::setConfigData($key, $value, $temp);
    }


    /**
     * Get the value of a single PHPCS config key.
     *
     * @since      8.0.0
     * @deprecated 10.0.0 Use {@see PHPCSUtils\BackCompat\Helper::getConfigData()} instead.
     *
     * @param string $key The name of the config value.
     *
     * @return string|null
     */
    public static function getConfigData($key)
    {
        return Helper::getConfigData($key);
    }


    /**
     * Get the value of a single PHPCS config key.
     *
     * This config key can be set in the `CodeSniffer.conf` file, on the
     * command-line or in a ruleset.
     *
     * @since      8.2.0
     * @deprecated 10.0.0 Use {@see PHPCSUtils\BackCompat\Helper::getCommandLineData()} instead.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param string                $key       The name of the config value.
     *
     * @return string|null
     */
    public static function getCommandLineData(File $phpcsFile, $key)
    {
        return Helper::getCommandLineData($phpcsFile, $key);
    }


    /**
     * Returns the position of the first non-whitespace token in a statement.
     *
     * @since      9.1.0
     * @deprecated 10.0.0 Use {@see PHPCSUtils\BackCompat\BCFile::findStartOfStatement()} instead.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                   $start     The position to start searching from in the token stack.
     * @param int|array             $ignore    Token types that should not be considered stop points.
     *
     * @return int
     */
    public static function findStartOfStatement(File $phpcsFile, $start, $ignore = null)
    {
        return BCFile::findStartOfStatement($phpcsFile, $start, $ignore);
    }


    /**
     * Returns the position of the last non-whitespace token in a statement.
     *
     * @since      8.2.0
     * @deprecated 10.0.0 Use {@see PHPCSUtils\BackCompat\BCFile::findEndOfStatement()} instead.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                   $start     The position to start searching from in the token stack.
     * @param int|array             $ignore    Token types that should not be considered stop points.
     *
     * @return int
     */
    public static function findEndOfStatement(File $phpcsFile, $start, $ignore = null)
    {
        return BCFile::findEndOfStatement($phpcsFile, $start, $ignore);
    }


    /**
     * Returns the name of the class that the specified class extends
     * (works for classes, anonymous classes and interfaces).
     *
     * Returns FALSE on error or if there is no extended class name.
     *
     * @since      7.1.4
     * @since      8.2.0  Moved from the `Sniff` class to this class.
     * @deprecated 10.0.0 Use {@see PHPCSUtils\Utils\ObjectDeclarations::findExtendedClassName()} instead.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                   $stackPtr  The position of the class token in the stack.
     *
     * @return string|false
     */
    public static function findExtendedClassName(File $phpcsFile, $stackPtr)
    {
        return ObjectDeclarations::findExtendedClassName($phpcsFile, $stackPtr);
    }


    /**
     * Returns the name(s) of the interface(s) that the specified class implements.
     *
     * Returns FALSE on error or if there are no implemented interface names.
     *
     * @since      7.0.3
     * @since      8.2.0  Moved from the `Sniff` class to this class.
     * @deprecated 10.0.0 Use {@see PHPCSUtils\Utils\ObjectDeclarations::findImplementedInterfaceNames()} instead.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the class token.
     *
     * @return array|false
     */
    public static function findImplementedInterfaceNames(File $phpcsFile, $stackPtr)
    {
        return ObjectDeclarations::findImplementedInterfaceNames($phpcsFile, $stackPtr);
    }


    /**
     * Returns the method parameters for the specified function token.
     *
     * @since      7.0.3
     * @since      8.2.0  Moved from the `Sniff` class to this class.
     * @deprecated 10.0.0 Use {@see PHPCSUtils\Utils\FunctionDeclarations::getParameters()} instead.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                   $stackPtr  The position in the stack of the
     *                                         function token to acquire the
     *                                         parameters for.
     *
     * @return array|false
     * @throws \PHP_CodeSniffer_Exception If the specified $stackPtr is not of
     *                                    type T_FUNCTION or T_CLOSURE.
     */
    public static function getMethodParameters(File $phpcsFile, $stackPtr)
    {
        return FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
    }
}
