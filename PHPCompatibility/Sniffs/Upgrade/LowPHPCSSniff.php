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

use PHPCompatibility\Helpers\DisableSniffMsgTrait;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\BackCompat\Helper;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Add a notification for users of low PHPCS versions.
 *
 * Originally PHPCompatibility supported PHPCS 1.5.x, 2.x and since PHPCompatibility 8.0.0, 3.x.
 * Support for PHPCS < 2.3.0 has been dropped in PHPCompatibility 9.0.0.
 * Support for PHPCS < 3.7.1 has been dropped in PHPCompatibility 10.0.0.
 *
 * The standard will - up to a point - still work for users of lower
 * PHPCS versions, but will give less accurate results and may throw
 * notices and warnings (or even fatal out).
 *
 * This sniff adds an explicit error/warning for users of the standard
 * using a PHPCS version below the recommended version.
 *
 * @link https://github.com/PHPCompatibility/PHPCompatibility/issues/688
 * @link https://github.com/PHPCompatibility/PHPCompatibility/issues/835
 * @link https://github.com/PHPCompatibility/PHPCompatibility/issues/1347
 *
 * @since 8.2.0
 */
class LowPHPCSSniff extends Sniff
{
    use DisableSniffMsgTrait;

    /**
     * The minimum supported PHPCS version.
     *
     * Users on PHPCS versions below this will see an ERROR message.
     *
     * @since 8.2.0
     * @since 9.3.0 Changed from $minSupportedVersion property to a constant.
     *
     * @var string
     */
    const MIN_SUPPORTED_VERSION = '3.7.1';

    /**
     * The minimum recommended PHPCS version.
     *
     * Users on PHPCS versions below this will see a WARNING.
     *
     * @since 8.2.0
     * @since 9.3.0 Changed from $minRecommendedVersion property to a constant.
     *
     * @var string
     */
    const MIN_RECOMMENDED_VERSION = '3.7.1';

    /**
     * Keep track of whether this sniff needs to actually run.
     *
     * This will be set to `false` when either a high enough PHPCS
     * version is detected or once the error/warning has been thrown,
     * to make sure that the notice will only be thrown once per run.
     *
     * @since 8.2.0
     *
     * @var bool
     */
    private $examine = true;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.2.0
     *
     * @return array
     */
    public function register()
    {
        return [
            \T_OPEN_TAG,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 8.2.0
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

        $phpcsVersion = Helper::getVersion();

        // Don't do anything if the PHPCS version used is above the minimum recommended version.
        if (\version_compare($phpcsVersion, self::MIN_RECOMMENDED_VERSION, '>=')) {
            $this->examine = false;
            return ($phpcsFile->numTokens + 1);
        }

        if (\version_compare($phpcsVersion, self::MIN_SUPPORTED_VERSION, '<')) {
            $isError      = true;
            $message      = 'IMPORTANT: Please be advised that the minimum PHP_CodeSniffer version the PHPCompatibility standard supports is %s. You are currently using PHP_CodeSniffer %s. Please upgrade your PHP_CodeSniffer installation. The recommended version of PHP_CodeSniffer for PHPCompatibility is %s or higher.';
            $errorCode    = 'Unsupported_' . $this->stringToErrorCode(self::MIN_SUPPORTED_VERSION);
            $replacements = [
                self::MIN_SUPPORTED_VERSION,
                $phpcsVersion,
                self::MIN_RECOMMENDED_VERSION,
            ];
        } else {
            $isError      = false;
            $message      = 'IMPORTANT: Please be advised that for the most reliable PHPCompatibility results, PHP_CodeSniffer %s or higher should be used. Support for lower versions will be dropped in the foreseeable future. You are currently using PHP_CodeSniffer %s. Please upgrade your PHP_CodeSniffer installation to version %s or higher.';
            $errorCode    = 'BelowRecommended_' . $this->stringToErrorCode(self::MIN_RECOMMENDED_VERSION);
            $replacements = [
                self::MIN_RECOMMENDED_VERSION,
                $phpcsVersion,
                self::MIN_RECOMMENDED_VERSION,
            ];
        }

        $message .= $this->createDisableSniffNotice($phpcsFile, 'PHPCompatibility.Upgrade.LowPHPCS', $errorCode);

        MessageHelper::addMessage($phpcsFile, $message, 0, $isError, $errorCode, $replacements);

        $this->examine = false;
    }
}
