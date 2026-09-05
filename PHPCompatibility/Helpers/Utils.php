<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Helpers;

/**
 * Helper for cross-version compatibility of PHPCompatibility code.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is only intended for internal use by PHPCompatibility and is not part of the public API.
 * This also means that it has no promise of backward compatibility. Use at your own risk.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 10.0.0
 */
final class Utils
{

    /**
     * Default characters to trim.
     *
     * The PHP native default changed in PHP 8.6. This standardizes on the PHP 8.6 default value.
     *
     * @since 10.0.0
     *
     * @var string
     */
    private const TRIM_CHARS_DEFAULT = " \f\n\r\t\v\x00";

    /**
     * PHP wrapper function: Strip whitespace (or other characters) from the beginning and end of a string.
     *
     * @link https://www.php.net/trim
     *
     * @since 10.0.0
     *
     * @param string $text       The string that will be trimmed.
     * @param string $characters Optionally, the stripped characters can also be specified
     *                           using the characters parameter.
     *
     * @return string
     */
    public static function trim(string $text, string $characters = self::TRIM_CHARS_DEFAULT): string
    {
        return \trim($text, $characters);
    }

    /**
     * PHP wrapper function: Strip whitespace (or other characters) from the beginning of a string.
     *
     * @link https://www.php.net/ltrim
     *
     * @since 10.0.0
     *
     * @param string $text       The string that will be trimmed.
     * @param string $characters Optionally, the stripped characters can also be specified
     *                           using the characters parameter.
     *
     * @return string
     */
    public static function ltrim(string $text, string $characters = self::TRIM_CHARS_DEFAULT): string
    {
        return \ltrim($text, $characters);
    }

    /**
     * PHP wrapper function: Strip whitespace (or other characters) from the end of a string.
     *
     * @link https://www.php.net/rtrim
     *
     * @since 10.0.0
     *
     * @param string $text       The string that will be trimmed.
     * @param string $characters Optionally, the stripped characters can also be specified
     *                           using the characters parameter.
     *
     * @return string
     */
    public static function rtrim(string $text, string $characters = self::TRIM_CHARS_DEFAULT): string
    {
        return \rtrim($text, $characters);
    }
}
