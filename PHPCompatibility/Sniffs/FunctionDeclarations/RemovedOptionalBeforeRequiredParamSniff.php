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
 * While deprecated since PHP 8.0, optional parameters with an explicitly nullable type
 * and a null default value, and found before a required parameter, are only flagged since PHP 8.1.
 *
 * While deprecated since PHP 8.0, optional parameters with an union type which includes null
 * and a null default value, and found before a required parameter, are only flagged since PHP 8.3.
 *
 * And as of PHP 8.4, parameters of the form "Type $param = null" before a required parameter are
 * now also deprecated.
 *
 * PHP version 8.0
 * PHP version 8.1
 * PHP version 8.3
 * PHP version 8.4
 *
 * @link https://github.com/php/php-src/blob/69888c3ff1f2301ead8e37b23ff8481d475e29d2/UPGRADING#L145-L151
 * @link https://github.com/php/php-src/commit/c939bd2f10b41bced49eb5bf12d48c3cf64f984a
 * @link https://github.com/php/php-src/commit/68ef3938f42aefa3881c268b12b3c0f1ecc5888d
 * @link https://wiki.php.net/rfc/deprecate-implicitly-nullable-types
 *
 * @since 10.0.0
 */
class RemovedOptionalBeforeRequiredParamSniff extends Sniff
{

    /**
     * Base message for the PHP 8.0 deprecation.
     *
     * @var string
     */
    const PHP80_MSG = 'Declaring an optional parameter before a required parameter is deprecated since PHP 8.0.';

    /**
     * Base message for the PHP 8.1 deprecation.
     *
     * @var string
     */
    const PHP81_MSG = 'Declaring an optional parameter with a nullable type before a required parameter is soft deprecated since PHP 8.0 and hard deprecated since PHP 8.1';

    /**
     * Base message for the PHP 8.3 deprecation.
     *
     * @var string
     */
    const PHP83_MSG = 'Declaring an optional parameter with a null stand-alone type or a union type including null before a required parameter is soft deprecated since PHP 8.0 and hard deprecated since PHP 8.3';

    /**
     * Base message for the PHP 8.4 deprecation.
     *
     * @var string
     */
    const PHP84_MSG = 'Declaring an optional parameter with a non-nullable type and a null default value before a required parameter is deprecated since PHP 8.4';

    /**
     * Message template for detailed information about the deprecation.
     *
     * @var string
     */
    const MSG_DETAILS = ' Parameter %1$s is optional, while parameter %2$s is required. The %1$s parameter is implicitly treated as a required parameter.';

    /**
     * Tokens allowed in the default value (until PHP 8.4).
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
            // Note: as this will never be the _last_ parameter, we can be sure the 'comma_token' will be set to a token and not `false`.
            $hasNull    = $phpcsFile->findNext(\T_NULL, $param['default_token'], $param['comma_token']);
            $hasNonNull = $phpcsFile->findNext($this->allowedInDefault, $param['default_token'], $param['comma_token'], true);

            // Check for union types which include null, mixed types and stand-alone null types.
            $hasNullType = false;
            if ($param['type_hint_token'] !== false) {
                if ($param['type_hint'] === 'mixed' || $param['type_hint'] === 'null') {
                    $hasNullType = $param['type_hint_token'];
                } else {
                    $hasNullType = $phpcsFile->findNext(\T_NULL, $param['type_hint_token'], ($param['type_hint_end_token'] + 1));
                }
            }

            // Found an optional parameter with a required param after it.
            $error = self::PHP80_MSG . self::MSG_DETAILS;
            $code  = 'Deprecated80';
            $data  = [
                $param['name'],
                $requiredParam,
            ];

            if ($hasNull !== false && $hasNonNull === false) {
                if ($param['nullable_type'] === true) {
                    // Skip flagging the issue if the codebase doesn't need to run on PHP 8.1+.
                    if (ScannedCode::shouldRunOnOrAbove('8.1') === false) {
                        continue;
                    }

                    $error = self::PHP81_MSG . self::MSG_DETAILS;
                    $code  = 'Deprecated81';

                } elseif ($hasNullType !== false) {
                    // Skip flagging the issue if the codebase doesn't need to run on PHP 8.3+.
                    if (ScannedCode::shouldRunOnOrAbove('8.3') === false) {
                        continue;
                    }

                    $error = self::PHP83_MSG . self::MSG_DETAILS;
                    $code  = 'Deprecated83';
                } elseif ($param['type_hint'] !== '') {
                    // Skip flagging the issue if the codebase doesn't need to run on PHP 8.4+.
                    if (ScannedCode::shouldRunOnOrAbove('8.4') === false) {
                        continue;
                    }

                    $error = self::PHP84_MSG . self::MSG_DETAILS;
                    $code  = 'Deprecated84';
                }
            }

            $phpcsFile->addWarning($error, $param['token'], $code, $data);
        }
    }
}
