<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\PassedParameters;

/**
 * The constant value of the password hash algorithm constants has changed in PHP 7.4.
 *
 * Applications using the constants `PASSWORD_DEFAULT`, `PASSWORD_BCRYPT`,
 * `PASSWORD_ARGON2I`, and `PASSWORD_ARGON2ID` will continue to function correctly.
 * Using an integer will still work, but will produce a deprecation warning.
 *
 * PHP version 7.4
 *
 * @link https://www.php.net/manual/en/migration74.incompatible.php#migration74.incompatible.core.password-algorithm-constants
 * @link https://wiki.php.net/rfc/password_registry
 *
 * @since 9.3.0
 */
class NewPasswordAlgoConstantValuesSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * Key is the function name, value an array containing the 1-based parameter position
     * and the official name of the parameter.
     *
     * @since 9.3.0
     *
     * @var array<string, array<string, int|string>>
     */
    protected $targetFunctions = [
        'password_hash' => [
            'position' => 2,
            'name'     => 'algo',
        ],
        'password_needs_rehash' => [
            'position' => 2,
            'name'     => 'algo',
        ],
    ];

    /**
     * Tokens types which indicate that the parameter passed is not the PHP native constant.
     *
     * @since 9.3.0
     *
     * @var array<int|string, true>
     */
    private $invalidTokenTypes = [
        \T_NULL                     => true,
        \T_TRUE                     => true,
        \T_FALSE                    => true,
        \T_LNUMBER                  => true,
        \T_DNUMBER                  => true,
        \T_CONSTANT_ENCAPSED_STRING => true,
        \T_DOUBLE_QUOTED_STRING     => true,
        \T_HEREDOC                  => true,
        \T_NOWDOC                   => true,
    ];


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 9.3.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrAbove('7.4') === false);
    }


    /**
     * Process the parameters of a matched function.
     *
     * @since 9.3.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     * @param array                       $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $functionLC  = \strtolower($functionName);
        $paramInfo   = $this->targetFunctions[$functionLC];
        $targetParam = PassedParameters::getParameterFromStack($parameters, $paramInfo['position'], $paramInfo['name']);
        if ($targetParam === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        for ($i = $targetParam['start']; $i <= $targetParam['end']; $i++) {
            if (isset(Tokens::$emptyTokens[$tokens[$i]['code']]) === true) {
                continue;
            }

            // Ignore anything within square brackets.
            if (isset($tokens[$i]['bracket_closer'])) {
                $i = $tokens[$i]['bracket_closer'];
                continue;
            }

            // Skip past nested arrays, function calls and arbitrary groupings.
            if (isset($tokens[$i]['parenthesis_closer'])) {
                $i = $tokens[$i]['parenthesis_closer'];
                continue;
            }

            if (isset($this->invalidTokenTypes[$tokens[$i]['code']]) === true) {
                $phpcsFile->addWarning(
                    'The value of the password hash algorithm constants has changed in PHP 7.4. Pass a PHP native constant to the %s() function instead of using the value of the constant. Found: %s',
                    $stackPtr,
                    'NotAlgoConstant',
                    [
                        $functionName,
                        $targetParam['clean'],
                    ]
                );

                break;
            }
        }
    }
}
