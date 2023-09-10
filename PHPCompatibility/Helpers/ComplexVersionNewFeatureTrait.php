<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Helpers;

use PHPCSUtils\Utils\MessageHelper;

/**
 * Helper for working with the complex version arrays related to new features.
 *
 * Minimal array spec:
 * - List the version number as a string key with false (not present) or true (present).
 *   If's sufficient to list the first version where the feature appears.
 * - Other than this, the array can contain arbitrary additional key-value entries for
 *   use by the sniff. These will be disregarded by this trait.
 *
 * ---------------------------------------------------------------------------------------------
 * This trait is only intended for internal use by PHPCompatibility and is not part of the public API.
 * This also means that it has no promise of backward compatibility. Use at your own risk.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 10.0.0 Refactored out from the previous `AbstractNewFeatureSniff` class.
 */
trait ComplexVersionNewFeatureTrait
{

    /**
     * Basic error message template for new features.
     *
     * @var string
     */
    protected $msgTemplate = '%s is not present in PHP version %s or earlier';

    /**
     * Retrieve the "last version before" from an array with arbitrary contents.
     *
     * The array is expected to have at least one entry with a PHP version number as a key
     * and `false` as the value.
     *
     * @param array $itemArray Sub-array for a specific matched item from a complex version array.
     *
     * @return string[] Array with a single key `'not_in_version'` with as the value, a PHP version number
     *                  as a string or an empty string if the version number could not be determined.
     */
    protected function getVersionInfo(array $itemArray)
    {
        $versionInfo = [
            'not_in_version' => '',
        ];

        foreach ($itemArray as $version => $present) {
            if (\preg_match('`^\d\.\d(\.\d{1,2})?$`', $version) !== 1) {
                // Not a version key.
                continue;
            }

            if ($versionInfo['not_in_version'] === '' && $present === false) {
                $versionInfo['not_in_version'] = $version;
            }
        }

        return $versionInfo;
    }

    /**
     * Convenience method to retrieve the information to be passed to a call to the PHPCS native
     * `addError()` or `addWarning()` methods in a simple organized array.
     *
     * @param string   $itemName     Item name, normally name of the function or class detected.
     * @param string   $itemBaseCode The basis for the error code.
     * @param string[] $versionInfo  Array of version info as received from the getVersionInfo() method.
     *
     * @return array<string, string|array>
     */
    protected function getMessageInfo($itemName, $itemBaseCode, array $versionInfo)
    {
        return [
            'message'   => $this->msgTemplate,
            'errorcode' => MessageHelper::stringToErrorCode($itemBaseCode, true) . 'Found',
            'data'      => [
                $itemName,
                $versionInfo['not_in_version'],
            ],
        ];
    }
}
