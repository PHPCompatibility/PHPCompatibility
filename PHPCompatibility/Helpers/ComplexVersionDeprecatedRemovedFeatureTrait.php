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
 * Helper for working with the complex version arrays related to deprecated/removed features.
 *
 * Minimal array spec:
 * - List the version number as a string key with false (deprecated) or true (removed).
 * - Optionally the array can contain an 'alternative' key containing a hint on what to
 *   replace the feature with.
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
trait ComplexVersionDeprecatedRemovedFeatureTrait
{

    /**
     * Basic error message template for deprecated/removed features.
     *
     * @var string
     */
    protected $msgTemplate = '%s is ';

    /**
     * Message template to use if an alternative is available for the deprecated/removed
     * feature.
     *
     * @var string
     */
    protected $alternativeOptionTemplate = '; Use %s instead';

    /**
     * Retrieve version numbers in which a feature was deprecated and/or removed from
     * an array with arbitrary contents.
     * Will also retrieve a potential "alternative" for the feature from the same array.
     *
     * The array is expected to have at least one entry with a PHP version number as a key
     * and a boolean value.
     *
     * @param array $itemArray Sub-array for a specific matched item from a complex version array.
     *
     * @return string[] Array with three keys `'deprecated'`, `'removed'` and `'alternative'`.
     *                  The array values will always be strings and will be either the values retrieved
     *                  from the $itemArray or an empty string if the value for a key was unavailable
     *                  or could not be determined.
     */
    protected function getVersionInfo(array $itemArray)
    {
        $versionInfo = [
            'deprecated'  => '',
            'removed'     => '',
            'alternative' => '',
        ];

        foreach ($itemArray as $version => $removed) {
            if (isset($itemArray['alternative']) === true) {
                $versionInfo['alternative'] = (string) $itemArray['alternative'];
            }

            if (\preg_match('`^\d\.\d(\.\d{1,2})?$`', $version) !== 1) {
                // Not a version key.
                continue;
            }

            if ($removed === true && $versionInfo['removed'] === '') {
                $versionInfo['removed'] = $version;
            } elseif ($removed === false && $versionInfo['deprecated'] === '') {
                $versionInfo['deprecated'] = $version;
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
        $message   = $this->msgTemplate;
        $errorCode = MessageHelper::stringToErrorCode($itemBaseCode, true);
        $data      = [$itemName];

        if ($versionInfo['deprecated'] !== '') {
            $message   .= 'deprecated since PHP %s and ';
            $errorCode .= 'Deprecated';
            $data[]     = $versionInfo['deprecated'];
        }

        if ($versionInfo['removed'] !== '') {
            $message   .= 'removed since PHP %s and ';
            $errorCode .= 'Removed';
            $data[]     = $versionInfo['removed'];
        }

        // Remove the last 'and' from the message.
        $message = \substr($message, 0, (\strlen($message) - 5));

        if ($versionInfo['alternative'] !== '') {
            $message .= $this->alternativeOptionTemplate;
            $data[]   = $versionInfo['alternative'];
        }

        return [
            'message'   => $message,
            'errorcode' => $errorCode,
            'data'      => $data,
        ];
    }
}
