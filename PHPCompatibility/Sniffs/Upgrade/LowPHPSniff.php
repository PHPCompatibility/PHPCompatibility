<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Upgrade;

use PHPCompatibility\Helpers\DisableSniffMsg;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Add a notification for users of low PHP versions.
 *
 * Originally PHPCompatibility supported PHP 5.1 and higher.
 * As of PHPCompatibility 8.0.0, support for PHP < 5.3 has been dropped.
 * As of PHPCompatibility 10.0.0, support for PHP < 5.4 has been dropped.
 *
 * This sniff adds an explicit error/warning for users of the standard
 * using a PHP version below the recommended version.
 *
 * @link https://github.com/PHPCompatibility/PHPCompatibility/issues/835
 *
 * @since 9.3.0
 */
class LowPHPSniff extends Sniff
{

    /**
     * The minimum supported PHP version.
     *
     * Users on PHP versions below this will see an ERROR message.
     *
     * @since 9.3.0
     *
     * @var string
     */
    const MIN_SUPPORTED_VERSION = '5.4';

    /**
     * The minimum recommended PHP version.
     *
     * Users on PHP versions below this will see a WARNING.
     *
     * @since 9.3.0
     *
     * @var string
     */
    const MIN_RECOMMENDED_VERSION = '7.2';

    /**
     * Keep track of whether this sniff needs to actually run.
     *
     * This will be set to `false` when either a high enough PHP
     * version is detected or once the error/warning has been thrown,
     * to make sure that the notice will only be thrown once per run.
     *
     * @since 9.3.0
     *
     * @var bool
     */
    private $examine = true;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.3.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return Collections::phpOpenTags();
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.3.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // Don't do anything if the warning has already been thrown or is not necessary.
        if ($this->examine === false) {
            return ($phpcsFile->numTokens + 1);
        }

        $phpVersion = \phpversion();

        // Don't do anything if the PHPCS version used is above the minimum recommended version.
        if (\version_compare($phpVersion, self::MIN_RECOMMENDED_VERSION, '>=')) {
            $this->examine = false;
            return ($phpcsFile->numTokens + 1);
        }

        if (\version_compare($phpVersion, self::MIN_SUPPORTED_VERSION, '<')) {
            $isError      = true;
            $message      = 'IMPORTANT: Please be advised that the minimum PHP version the PHPCompatibility standard supports is %s. You are currently using PHP %s. Please upgrade your PHP installation. The recommended version of PHP for PHPCompatibility is %s or higher.';
            $errorCode    = 'Unsupported_' . MessageHelper::stringToErrorCode(self::MIN_SUPPORTED_VERSION);
            $replacements = [
                self::MIN_SUPPORTED_VERSION,
                $phpVersion,
                self::MIN_RECOMMENDED_VERSION,
            ];
        } else {
            $isError      = false;
            $message      = 'IMPORTANT: Please be advised that for the most reliable PHPCompatibility results, PHP %s or higher should be used. Support for lower versions will be dropped in the foreseeable future. You are currently using PHP %s. Please upgrade your PHP installation to version %s or higher.';
            $errorCode    = 'BelowRecommended_' . MessageHelper::stringToErrorCode(self::MIN_RECOMMENDED_VERSION);
            $replacements = [
                self::MIN_RECOMMENDED_VERSION,
                $phpVersion,
                self::MIN_RECOMMENDED_VERSION,
            ];
        }

        $message .= DisableSniffMsg::create('PHPCompatibility.Upgrade.LowPHP', $errorCode);

        MessageHelper::addMessage($phpcsFile, $message, 0, $isError, $errorCode, $replacements);

        $this->examine = false;

        // No need to look at this file again.
        return ($phpcsFile->numTokens + 1);
    }
}
