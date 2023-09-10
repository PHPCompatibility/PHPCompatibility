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

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;

/**
 * Functions can not have multiple parameters with the same name since PHP 7.0.
 *
 * PHP version 7.0
 *
 * @link https://www.php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.func-parameters
 *
 * @since 7.0.0
 */
class ForbiddenParametersWithSameNameSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.0
     * @since 7.1.3  Allows for closures.
     * @since 10.0.0 Allows for PHP 7.4+ arrow functions.
     *
     * @return array<int|string>
     */
    public function register()
    {
        return Collections::functionDeclarationTokens();
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('7.0') === false) {
            return;
        }

        // Get all parameters from method signature.
        $parameters = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        if (empty($parameters)) {
            return;
        }

        $paramNames = [];
        foreach ($parameters as $param) {
            $paramNames[$param['name']] = true;
        }

        if (\count($parameters) !== \count($paramNames)) {
            $phpcsFile->addError(
                'Functions can not have multiple parameters with the same name since PHP 7.0',
                $stackPtr,
                'Found'
            );
        }
    }
}
