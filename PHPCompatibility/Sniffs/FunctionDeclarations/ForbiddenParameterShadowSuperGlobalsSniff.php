<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\Variables;

/**
 * Detect the use of superglobals as parameters for functions, support for which was removed in PHP 5.4.
 *
 * PHP version 5.4
 *
 * @link https://www.php.net/manual/en/migration54.incompatible.php
 *
 * @since 7.0.0
 */
class ForbiddenParameterShadowSuperGlobalsSniff extends Sniff
{

    /**
     * Register the tokens to listen for.
     *
     * @since 7.0.0
     * @since 7.1.3  Allows for closures.
     * @since 10.0.0 Allows for PHP 7.4+ arrow functions.
     *
     * @return array
     */
    public function register()
    {
        return Collections::functionDeclarationTokensBC();
    }

    /**
     * Processes the test.
     *
     * @since 7.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('5.4') === false) {
            return;
        }

        try {
            // Get all parameters from the function signature.
            $parameters = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
            if (empty($parameters)) {
                return;
            }
        } catch (RuntimeException $e) {
            // Most likely a T_STRING which wasn't an arrow function.
            return;
        }

        foreach ($parameters as $param) {
            if (Variables::isSuperglobalName($param['name']) === true) {
                $error     = 'Parameter shadowing super global (%s) causes a fatal error since PHP 5.4';
                $errorCode = $this->stringToErrorCode(substr($param['name'], 1)) . 'Found';
                $data      = [$param['name']];

                $phpcsFile->addError($error, $param['token'], $errorCode, $data);
            }
        }
    }
}
