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
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;

/**
 * Nullable parameter type declarations and return types are available since PHP 7.1.
 *
 * PHP version 7.1
 *
 * @link https://www.php.net/manual/en/migration71.new-features.php#migration71.new-features.nullable-types
 * @link https://wiki.php.net/rfc/nullable_types
 * @link https://www.php.net/manual/en/functions.arguments.php#example-146
 *
 * @since 7.0.7
 */
class NewNullableTypesSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * {@internal Not sniffing for T_NULLABLE which was introduced in PHPCS 2.8.0
     * as in that case we can't distinguish between parameter type hints and
     * return type hints for the error message.}
     *
     * @since 7.0.7
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
     * @since 7.0.7
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('7.0') === false) {
            return;
        }

        /*
         * Check parameter type declarations.
         */
        $params = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        if (empty($params) === false) {
            foreach ($params as $param) {
                if ($param['nullable_type'] === true) {
                    $phpcsFile->addError(
                        'Nullable type declarations are not supported in PHP 7.0 or earlier. Found: %s',
                        $param['token'],
                        'typeDeclarationFound',
                        [$param['type_hint']]
                    );
                }
            }
        }

        /*
         * Check return type declarations.
         */
        $properties = FunctionDeclarations::getProperties($phpcsFile, $stackPtr);
        if ($properties['nullable_return_type'] === true) {
            $nullPtr = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($properties['return_type_token'] - 1), null, true);
            $phpcsFile->addError(
                'Nullable return types are not supported in PHP 7.0 or earlier. Found: %s',
                $nullPtr,
                'returnTypeFound',
                [$properties['return_type']]
            );
        }
    }
}
