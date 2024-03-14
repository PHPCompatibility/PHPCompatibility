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
 * Declaring an optional function parameter before a required parameter is deprecated since PHP 8.0.
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

        $error = 'Declaring an optional parameter before a required parameter is deprecated since PHP 8.0. Parameter %1$s is optional, while parameter %2$s is required. The %1$s parameter is implicitly treated as a required parameter.';

        $requiredParam = null;
        $parameters    = \array_reverse($parameters);

        // Walk the parameters in reverse order (from last to first).
        foreach ($parameters as $key => $param) {
            /*
             * Ignore variadic parameters, which are optional by nature.
             * These always have to be declared last and this has been this way since their introduction.
             */
            if ($param['variable_length'] === true) {
                continue;
            }

            if (isset($param['default']) === false) {
                $requiredParam = $param['name'];
                continue;
            }

            // Found an optional parameter.
            if (isset($requiredParam) === false) {
                // No required params found yet.
                continue;
            }

            // Okay, so we have an optional parameter before a required one.
            // Check if it's typed and has a null default value, in which case we can ignore it.
            // Note: as this will never be the _last_ parameter, we can be sure the 'comma_token' will be set to a token and not `false`.
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

            // Found an optional parameter with a required param after it.
            $data = [
                $param['name'],
                $requiredParam,
            ];

            $phpcsFile->addWarning($error, $param['token'], 'Deprecated', $data);
        }
    }
}
