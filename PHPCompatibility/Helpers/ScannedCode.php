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

use PHPCompatibility\Exceptions\InvalidTestVersion;
use PHPCompatibility\Exceptions\InvalidTestVersionRange;
use PHPCSUtils\BackCompat\Helper;

/**
 * Helper for working with the PHPCompatibility `testVersion` configuration variable.
 *
 * The `testVersion` is used to determine whether the code being scanned needs to support
 * a certain PHP version or not.
 *
 * Used by nearly all sniffs.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is only intended for internal use by PHPCompatibility and is not part of the public API.
 * This also means that it has no promise of backward compatibility. Use at your own risk.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 5.6    Base methods introduced in the generic `Sniff` class.
 * @since 10.0.0 Methods moved from the generic `Sniff` class to this dedicated class to
 *               allow for sniffs which don't extends the PHPCompatibility `Sniff` class.
 */
final class ScannedCode
{

    /**
     * Default testVersion if no valid testVersion was provided or
     * could be determined based on the provided information.
     *
     * @var array<string|null>
     */
    private static $testVersionsDefault = [null, null];

    /**
     * Test versions applicable for the current PHPCS run.
     *
     * @var array<string|null> Array with two values.
     *                         Index 0 contains the low end supported PHP version.
     *                         Index 1 contains the high end supported PHP version.
     */
    private static $testVersions;

    /**
     * Test version as received from the PHPCS configuration.
     *
     * @var string
     */
    private static $testVersion;

    /**
     * Get the testVersion configuration variable.
     *
     * The testVersion configuration variable may be in any of the following formats:
     * 1) Omitted/empty, in which case no version is specified. This effectively
     *    disables all the checks for new PHP features provided by this standard.
     * 2) A single PHP version number, e.g. "5.4" in which case the standard checks that
     *    the code will run on that version of PHP (no deprecated features or newer
     *    features being used).
     * 3) A range, e.g. "5.0-5.5", in which case the standard checks the code will run
     *    on all PHP versions in that range, and that it doesn't use any features that
     *    were deprecated by the final version in the list, or which were not available
     *    for the first version in the list.
     *    We accept ranges where one of the components is missing, e.g. "-5.6" means
     *    all versions up to PHP 5.6, and "7.0-" means all versions above PHP 7.0.
     * PHP version numbers should always be in Major.Minor format.  Both "5", "5.3.2"
     * would be treated as invalid, and ignored.
     *
     * @since 7.0.0
     * @since 7.1.3  Now allows for partial ranges such as `5.2-`.
     * @since 10.0.0 - Will allow for "testVersion" config in lowercase.
     *               - Will throw a PHP Exception instead of a warning for an invalid testVersion.
     *               - The method is now static.
     *
     * @return array<string> An array containing min/max version of PHP that we are checking
     *                       against (see above). If only a single version number is specified,
     *                       then this is used as both the min and max.
     *
     * @throws \PHPCompatibility\Exceptions\InvalidTestVersionRange When the range in the testVersion is invalid.
     * @throws \PHPCompatibility\Exceptions\InvalidTestVersion      When the testVersion itself is invalid.
     */
    private static function getTestVersion()
    {
        // Only retrieve the testVersion once from the PHPCS Config as it won't change during a run.
        if (isset(self::$testVersion) === false || \defined('PHP_CODESNIFFER_IN_TESTS')) {
            $testVersion = Helper::getConfigData('testVersion');

            // Case-sensitivity tolerance.
            if (empty($testVersion) === true) {
                $testVersion = Helper::getConfigData('testversion');
            }

            self::$testVersion = \trim((string) $testVersion);
        }

        if (empty(self::$testVersion)) {
            return self::$testVersionsDefault;
        }

        if (isset(self::$testVersions) === true && \defined('PHP_CODESNIFFER_IN_TESTS') === false) {
            return self::$testVersions; // @codeCoverageIgnore
        }

        /*
         * Determine the applicable test versions.
         */
        self::$testVersions = self::$testVersionsDefault;

        if (\preg_match('`^\d+\.\d+$`', self::$testVersion)) {
            self::$testVersions = [self::$testVersion, self::$testVersion];
            return self::$testVersions;
        }

        if (\preg_match('`^(\d+\.\d+)?\s*-\s*(\d+\.\d+)?$`', self::$testVersion, $matches)) {
            if (empty($matches[1]) === false || empty($matches[2]) === false) {
                /*
                 * If no lower-limit is set, we set the min version to 4.0.
                 * Whilst development focuses on PHP 5 and above, we also accept
                 * sniffs for PHP 4, so we include that as the minimum.
                 * (It makes no sense to support PHP 3 as that was effectively a
                 * different language).
                 */
                $min = empty($matches[1]) ? '4.0' : $matches[1];

                // If no upper-limit is set, we set the max version to 99.9.
                $max = empty($matches[2]) ? '99.9' : $matches[2];

                if (\version_compare($min, $max, '>')) {
                    throw InvalidTestVersionRange::create(self::$testVersion);
                }

                self::$testVersions = [$min, $max];
                return self::$testVersions;
            }
        }

        throw InvalidTestVersion::create(self::$testVersion);
    }


    /**
     * Check whether a specific PHP version is equal to or higher than the maximum
     * supported PHP version as provided by the user in `testVersion`.
     *
     * Should be used when sniffing for *old* PHP features (deprecated/removed).
     *
     * @since 5.6
     * @since 10.0.0 The method is now static.
     *
     * @param string $phpVersion A PHP version number in 'major.minor' format.
     *
     * @return bool True if testVersion has not been provided or if the PHP version
     *              is equal to or higher than the highest supported PHP version
     *              in testVersion. False otherwise.
     */
    public static function shouldRunOnOrAbove($phpVersion)
    {
        $testVersion = self::getTestVersion()[1];

        if (\is_null($testVersion) === true
            || \version_compare($testVersion, $phpVersion, '>=') === true
        ) {
            return true;
        }

        return false;
    }


    /**
     * Check whether a specific PHP version is equal to or lower than the minimum
     * supported PHP version as provided by the user in `testVersion`.
     *
     * Should be used when sniffing for *new* PHP features.
     *
     * @since 5.6
     * @since 10.0.0 The method is now static.
     *
     * @param string $phpVersion A PHP version number in 'major.minor' format.
     *
     * @return bool True if the PHP version is equal to or lower than the lowest
     *              supported PHP version in testVersion.
     *              False otherwise or if no testVersion is provided.
     */
    public static function shouldRunOnOrBelow($phpVersion)
    {
        $testVersion = self::getTestVersion()[0];

        if (\is_null($testVersion) === false
            && \version_compare($testVersion, $phpVersion, '<=') === true
        ) {
            return true;
        }

        return false;
    }
}
