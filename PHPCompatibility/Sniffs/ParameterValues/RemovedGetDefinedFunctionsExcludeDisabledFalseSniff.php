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
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect: Passing `false` to `get_defined_functions()` is deprecated as of PHP 8.0.
 *
 * > Calling `get_defined_functions()` with `$exclude_disabled` explicitly set to `false`
 * > is deprecated. `get_defined_functions()` will never include disabled functions.
 *
 * PHP version 8.0
 *
 * @link https://github.com/php/php-src/blob/69888c3ff1f2301ead8e37b23ff8481d475e29d2/UPGRADING#L514-L516
 *
 * @since 10.0.0
 */
class RemovedGetDefinedFunctionsExcludeDisabledFalseSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'get_defined_functions' => true,
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
        return (ScannedCode::shouldRunOnOrAbove('8.0') === false);
    }

    /**
     * Process the parameters of a matched function.
     *
     * @since 10.0.0
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
        $targetParam = PassedParameters::getParameterFromStack($parameters, 1, 'exclude_disabled');
        if ($targetParam === false) {
            return;
        }

        if ($targetParam['clean'] !== 'false') {
            return;
        }

        $phpcsFile->addWarning(
            'Explicitly passing "false" as the value for $exclude_disabled to get_defined_functions() is deprecated since PHP 8.0.',
            $targetParam['start'],
            'Deprecated'
        );
    }
}
