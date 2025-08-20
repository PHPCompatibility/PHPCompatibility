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

use PHP_CodeSniffer\Files\File;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\TypeString;

/**
 * Declaring an implicitly nullable parameter is deprecated since PHP 8.4.
 *
 * Implicitly nullable parameters are parameters with a `null` default value, but
 * without a nullable type.
 * Support is expected to be removed in PHP 9.0.
 *
 * These parameters may now also hit the PHP 8.0 "optional before required" deprecation.
 * That deprecation is handled separately via the RemovedOptionalBeforeRequiredParam sniff.
 *
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/deprecate-implicitly-nullable-types
 *
 * @since 10.0.0
 */
final class RemovedImplicitlyNullableParamSniff extends Sniff
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
        return Collections::functionDeclarationTokens();
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('8.4') === false) {
            return;
        }

        // Get all parameters from the function signature.
        $parameters = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        if (empty($parameters)) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Parenthesis closer will be defined, otherwise we'd have received an empty parameters array.
        $closeParens = $tokens[$stackPtr]['parenthesis_closer'];

        foreach ($parameters as $key => $param) {
            if (isset($param['property_visibility'])) {
                /*
                 * Implicitly nullable parameters were never allowed for promoted properties
                 * and always resulted in a fatal error. Ignore as this is outside the scope of this sniff.
                 */
                continue;
            }

            if (isset($param['default']) === false
                || $param['type_hint'] === ''
                || $param['type_hint_token'] === false
            ) {
                // If there is no default value or no type hint, there is no issue.
                continue;
            }

            $typeHint = \strtolower($param['type_hint']);
            if ($typeHint === 'null'
                || $typeHint === 'mixed'
                || TypeString::isNullable($typeHint)
            ) {
                // Type is nullable, no issue.
                continue;
            }

            /*
             * Non-nullable type, now check if the default value is `null`.
             *
             * We can ignore `null` when it is part of a constant expression or in new in initializers,
             * as PHP does not regard parameters with that kind of default value as nullable and
             * would throw a fatal when the function is called. This behaviour is not changed by the
             * PHP 8.4 deprecation of implicitly nullable parameters, so those parameters should be
             * ignored by this sniff.
             * Also see: https://github.com/php/php-src/issues/13752
             */

            // Determine the end of the parameter.
            $paramEnd          = ($param['comma_token'] === false) ? $closeParens : $param['comma_token'];
            $cleanDefaultValue = GetTokensAsString::noEmpties($phpcsFile, $param['default_token'], ($paramEnd - 1));
            $cleanDefaultValue = \strtolower($cleanDefaultValue);

            if ($cleanDefaultValue !== 'null' && $cleanDefaultValue !== '\null') {
                // No null default value, we're okay.
                continue;
            }

            $error = 'Implicitly marking a parameter as nullable is deprecated since PHP 8.4. Update the type to be explicitly nullable instead. Found implicitly nullable parameter: %s.';
            $code  = 'Deprecated';
            $data  = [$param['name']];

            $phpcsFile->addWarning($error, $param['token'], $code, $data);
        }
    }
}
