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
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;

/**
 * Declaring a required function parameter after an optional parameter is deprecated since PHP 8.0.
 *
 * > Declaring a required parameter after an optional one is deprecated. As an
 * > exception, declaring a parameter of the form "Type $param = null" before
 * > a required one continues to be allowed, because this pattern was sometimes
 * > used to achieve nullable types in older PHP versions.
 *
 * PHP version 8.0
 *
 * @link https://github.com/php/php-src/blob/69888c3ff1f2301ead8e37b23ff8481d475e29d2/UPGRADING#L145-L151
 *
 * @since 10.0.0
 */
class RemovedOptionalBeforeRequiredParamSniff extends Sniff
{

    /**
     * Tokens allowed in the default value.
     *
     * This property will be enriched in the register() method.
     *
     * @since 10.0.0
     *
     * @var array<int|string, int|string>
     */
    private $allowedInDefault = [
        \T_NULL => \T_NULL,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        $this->allowedInDefault += Tokens::$emptyTokens;

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
        if (ScannedCode::shouldRunOnOrAbove('8.0') === false) {
            return;
        }

        // Get all parameters from the function signature.
        $parameters = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        if (empty($parameters)) {
            return;
        }

        $error = 'Declaring a required parameter after an optional one is deprecated since PHP 8.0. Parameter %s is optional, while parameter %s is required.';

        $paramCount    = \count($parameters);
        $lastKey       = ($paramCount - 1);
        $firstOptional = null;

        foreach ($parameters as $key => $param) {
            /*
             * Ignore variadic parameters, which are optional by nature.
             * These always have to be declared last and this has been this way since their introduction.
             */
            if ($param['variable_length'] === true) {
                continue;
            }

            // Handle optional parameters.
            if (isset($param['default']) === true) {
                if ($key === $lastKey) {
                    // This is the last parameter and it's optional, no further checking needed.
                    break;
                }

                if (isset($firstOptional) === false) {
                    // Check if it's typed and has a null default value, in which case we can ignore it.
                    if ($param['type_hint'] !== '') {
                        $hasNull    = $phpcsFile->findNext(\T_NULL, $param['default_token'], $param['comma_token']);
                        $hasNonNull = $phpcsFile->findNext(
                            $this->allowedInDefault,
                            $param['default_token'],
                            $param['comma_token'],
                            true
                        );

                        if ($hasNull !== false && $hasNonNull === false) {
                            continue;
                        }
                    }

                    // Non-null default value. This is an optional param we need to take into account.
                    $firstOptional = $param['name'];
                }

                continue;
            }

            // Found a required parameter.
            if (isset($firstOptional) === false) {
                // No optional params found yet.
                continue;
            }

            // Found a required parameter with an optional param before it.
            $data = [
                $firstOptional,
                $param['name'],
            ];

            $phpcsFile->addWarning($error, $param['token'], 'Deprecated', $data);
        }
    }
}
