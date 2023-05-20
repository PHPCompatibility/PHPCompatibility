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
 * Helper method for creating a "disable this sniff" notice.
 *
 * Used by the Upgrade LowPHP/LowPHPCS sniffs.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is only intended for internal use by PHPCompatibility and is not part of the public API.
 * This also means that it has no promise of backward compatibility. Use at your own risk.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 10.0.0 Extracted duplicate code from the above mentioned sniffs into this class.
 */
final class DisableSniffMsg
{

    /**
     * Create a "disable this sniff" notice to be added to an error message.
     *
     * @since 10.0.0
     *
     * @param string $sniffName The name of the sniff to mention in the message.
     * @param string $errorCode The specific error code under which this message will
     *                          be displayed.
     *
     * @return string
     */
    public static function create($sniffName, $errorCode)
    {
        $disableNotice = 'To disable this notice, add --exclude=%1$s to your command or add <exclude name="%1$s.%2$s"/> to your custom ruleset.';

        $message  = \PHP_EOL . \PHP_EOL;
        $message .= \sprintf($disableNotice, $sniffName, $errorCode);
        $message .= \PHP_EOL . \PHP_EOL;
        $message .= 'Thank you for using PHPCompatibility!';

        return $message;
    }
}
