<?php

/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2024 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;

/**
 * Detect implicit nullable parameter types in function declarations. These have been deprecated in PHP 8.4 and will be removed in PHP 9.0.
 *
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/deprecate-implicitly-nullable-types
 *
 * @since 10.0.0
 */
class RemovedImplicitNullableParameterTypeSniff extends Sniff
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

        $isError = ScannedCode::shouldRunOnOrAbove('9.0');
        $error   = 'Implicitly marking parameter %s as nullable is deprecated since PHP 8.4';
        if ($isError) {
            $error .= ', and removed in PHP 9.0';
        }

        foreach ($parameters as $param) {
            if (!isset($param['default'])) {
                // No default value set.
                continue;
            }

            if ($param['default'] !== 'null') {
                // Non-null default value.
                continue;
            }

            if ($param['type_hint'] === '') {
                // No type definition
                continue;
            }

            if ($param['nullable_type'] === true) {
                // Null is already explicitly included in the type definition.
                continue;
            }

            if ($phpcsFile->findNext(\T_NULL, $param['type_hint_token'], ($param['type_hint_end_token'] + 1)) !== false) {
                // Union type which includes null.
                continue;
            }

            $data = [
                $param['name'],
            ];

            if ($isError) {
                $fix = $phpcsFile->addFixableError($error, $param['token'], 'Removed', $data);
            } else {
                $fix = $phpcsFile->addFixableWarning($error, $param['token'], 'Deprecated', $data);
            }

            if ($fix) {
                $typeHint = $param['type_hint'];

                if ($param['type_hint_token'] === $param['type_hint_end_token']) {
                    // Simple type, like 'int' or 'string'.
                    $typeHint = '?' . $typeHint;
                } elseif (strpos($typeHint, '&') === false) {
                    // This is a union type, like 'A|B'.
                    $typeHint .= '|null';
                } elseif (strpos($typeHint, '|') === false) {
                    // This is an intersection type, like 'A&B' or '(A&B)'.
                    if ($typeHint[0] !== '(') {
                        $typeHint = '(' . $typeHint . ')';
                    }
                    $typeHint .= '|null';
                } else {
                    // Disjunctive Normal Form, like 'A|(B&C)', or '(A&B)|C'.
                    // TODO: better handling here to avoid wrapping the whole type in parenthesis unnecessarily.
                    if (substr($typeHint, -1) !== ')') {
                        $typeHint = '(' . $typeHint . ')';
                    }
                    $typeHint .= '|null';
                }

                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->replaceToken($param['type_hint_token'], $typeHint);
                for ($i = $param['type_hint_token'] + 1; $i <= $param['type_hint_end_token']; $i++) {
                    $phpcsFile->fixer->replaceToken($i, '');
                }
                $phpcsFile->fixer->endChangeset();
            }
        }
    }
}
