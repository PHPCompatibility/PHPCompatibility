<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionNameRestrictions;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\Namespaces;
use PHPCSUtils\Utils\Scopes;

/**
 * Detect declaration of the magic `__autoload()` function.
 *
 * This functionality has been deprecated in PHP 7.2 and removed in PHP 8.0 in favour of `spl_autoload_register()`.
 *
 * PHP version 7.2
 * PHP version 8.0
 *
 * @link https://www.php.net/manual/en/migration72.deprecated.php#migration72.deprecated.__autoload-method
 * @link https://wiki.php.net/rfc/deprecations_php_7_2#autoload
 * @link https://www.php.net/manual/en/function.autoload.php
 *
 * @since 8.1.0
 * @since 9.0.0 Renamed from `DeprecatedMagicAutoloadSniff` to `RemovedMagicAutoloadSniff`.
 */
class RemovedMagicAutoloadSniff extends Sniff
{

    /**
     * Scopes to look for when testing using validDirectScope.
     *
     * {@internal More tokens are added on register().}
     *
     * @since 8.1.0
     *
     * @var array<int|string, int|string>
     */
    private $checkForScopes = [
        \T_NAMESPACE => \T_NAMESPACE,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.1.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        $this->checkForScopes += Tokens::$ooScopeTokens;

        return [\T_FUNCTION];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 8.1.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('7.2') === false) {
            return;
        }

        $funcName = FunctionDeclarations::getName($phpcsFile, $stackPtr);

        if (\strtolower($funcName) !== '__autoload') {
            return;
        }

        if (Scopes::validDirectScope($phpcsFile, $stackPtr, $this->checkForScopes) !== false) {
            return;
        }

        if (Namespaces::determineNamespace($phpcsFile, $stackPtr) !== '') {
            return;
        }

        $error   = 'Specifying an autoloader using an __autoload() function is deprecated since PHP 7.2';
        $code    = 'Deprecated';
        $isError = false;

        if (ScannedCode::shouldRunOnOrAbove('8.0') === true) {
            $error  .= ' and no longer supported since PHP 8.0';
            $code    = 'Removed';
            $isError = true;
        }

        MessageHelper::addMessage($phpcsFile, $error, $stackPtr, $isError, $code);
    }
}
