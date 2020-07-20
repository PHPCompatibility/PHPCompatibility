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

use PHP_CodeSniffer\Files\File;
use PHPCSUtils\BackCompat\Helper;

/**
 * Helper method for creating a "disable this sniff" notice.
 *
 * Used by the Upgrade LowPHP/LowPHPCS sniffs.
 *
 * @since 10.0.0 Extracted duplicate code from the above mentioned sniffs into this trait.
 */
trait DisableSniffMsgTrait
{

    /**
     * Create a "disable this sniff" notice to be added to an error message.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param string                      $sniffName The name of the sniff to mention in the message.
     * @param string                      $errorCode The specific error code under which this message will
     *                                               be displayed.
     *
     * @return string
     */
    protected function createDisableSniffNotice(File $phpcsFile, $sniffName, $errorCode)
    {
        /*
         * Figure out the report width to determine how long the delimiter lines should be.
         *
         * This is not an exact calculation as there are a number of unknowns at the time the
         * notice is thrown (whether there are other notices for the file, whether those are
         * warnings or errors, whether there are auto-fixable issues etc).
         *
         * In other words, this is just an approximation to get a reasonably stable and
         * readable message layout format.
         *
         * {@internal
         * PHPCS has had some changes as to how the messages display over the years.
         * Most significantly in 2.4.0 it was attempted to solve an issue with messages
         * containing new lines. Unfortunately, that solution is buggy.
         * An improved version has been included in PHPCS 3.3.1.
         * {@see https://github.com/squizlabs/PHP_CodeSniffer/pull/2093}
         *
         * Anyway, this means that instead of new lines, delimiter lines will be used to improved
         * the readability of the (long) message.
         *
         * Also, as of PHPCS 2.2.0, the report width when using the `-s` option is 8 wider than
         * it should be. A patch for that was merged in PHPCS 3.3.1 via the same upstream PR.
         *
         * When the minimum supported/recommended version of PHPCompatibility will be
         * upped to PHPCS 3.3.1 or higher, the below code should be adjusted.}
         */
        $reportWidth = Helper::getCommandLineData($phpcsFile, 'reportWidth');
        if (empty($reportWidth)) {
            $reportWidth = 80;
        }
        $showSources = Helper::getCommandLineData($phpcsFile, 'showSources');
        if ($showSources === true) {
            $reportWidth += 6;
        }

        $messageWidth  = ($reportWidth - 15); // 15 is length of " # | WARNING | ".
        $delimiterLine = \str_repeat('-', ($messageWidth));
        $disableNotice = 'To disable this notice, add --exclude=%1$s to your command or add <exclude name="%1$s.%2$s"/> to your custom ruleset.';

        $message  = ' ' . $delimiterLine;
        $message .= ' ' . \sprintf($disableNotice, $sniffName, $errorCode);
        $message .= ' ' . $delimiterLine;
        $message .= ' ' . 'Thank you for using PHPCompatibility!';

        return $message;
    }
}
