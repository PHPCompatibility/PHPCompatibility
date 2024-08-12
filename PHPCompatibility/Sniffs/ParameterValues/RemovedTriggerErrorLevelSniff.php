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
 * Detect calling trigger_error() with E_USER_ERROR.
 *
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/deprecations_php_8_4#deprecate_passing_e_user_error_to_trigger_error
 * @link https://www.php.net/trigger_error
 *
 * @since 10.0.0
 */
final class RemovedTriggerErrorLevelSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'trigger_error' => true,
    ];

    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrAbove('8.4') === false);
    }

    /**
     * Process the parameters of a matched function.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File                  $phpcsFile    The file being scanned.
     * @param int                                          $stackPtr     The position of the current token in the stack.
     * @param string                                       $functionName The token content (function name) which was matched.
     * @param array<int|string, array<string, int|string>> $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $targetParam = PassedParameters::getParameterFromStack($parameters, 2, 'error_level');
        if ($targetParam === false) {
            return;
        }

        $paramContent = \ltrim($targetParam['clean'], ' \\'); // Trim off potential leading namespace separator for FQN.
        if ($paramContent !== 'E_USER_ERROR'
            && $paramContent !== (string) \E_USER_ERROR
        ) {
            return;
        }

        $firstNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $targetParam['start'], ($targetParam['end'] + 1), true);

        $phpcsFile->addWarning(
            'Passing E_USER_ERROR to trigger_error() is deprecated since 8.4. Throw an exception or call exit with a string message instead.',
            $firstNonEmpty,
            'Deprecated'
        );
    }
}
