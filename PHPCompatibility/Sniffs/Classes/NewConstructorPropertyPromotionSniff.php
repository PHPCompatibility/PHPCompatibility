<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * Detect constructor property promotion as supported since PHP 8.0.
 *
 * PHP version 8.0
 *
 * @link https://www.php.net/manual/en/language.oop5.decon.php#language.oop5.decon.constructor.promotion
 * @link https://wiki.php.net/rfc/constructor_promotion
 *
 * @since 10.0.0
 */
final class NewConstructorPropertyPromotionSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return Tokens::$ooScopeTokens;
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('7.4') === false) {
            return;
        }

        $ooMethods   = ObjectDeclarations::getDeclaredMethods($phpcsFile, $stackPtr);
        $ooMethodsLC = \array_change_key_case($ooMethods, \CASE_LOWER);

        if (isset($ooMethodsLC['__construct']) === false) {
            // OO construct doesn't declare a `__construct()` method.
            return;
        }

        $parameters = FunctionDeclarations::getParameters($phpcsFile, $ooMethodsLC['__construct']);
        if (empty($parameters)) {
            // Nothing to do.
            return;
        }

        foreach ($parameters as $param) {
            if (empty($param['property_visibility']) === true) {
                // Not property promotion.
                continue;
            }

            $phpcsFile->addError(
                'Constructor property promotion is not available in PHP version 7.4 or earlier. Found: %s',
                $param['token'],
                'Found',
                [$param['content']]
            );
        }
    }
}
