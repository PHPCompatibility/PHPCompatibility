<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Detect code which would throw a fatal recursion error on PHP < 8.1.
 *
 * PHP version 8.1
 *
 * @link https://www.php.net/manual/en/migration81.incompatible.php#migration81.incompatible.core.globals-access
 * @link https://wiki.php.net/rfc/restrict_globals_usage
 *
 * @since 10.0.0
 */
final class NewArrayMergeRecursiveWithGlobalsVarSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions the sniff is looking for. Should be defined in the child class.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'array_merge_recursive' => true,
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
        return (ScannedCode::shouldRunOnOrBelow('8.0') === false);
    }

    /**
     * Check if `$GLOBALS` is used twice in array_merge_recursive().
     *
     * {@internal Note that `array_merge_recursive()` does not support named parameters due to
     * the variable nature of the parameters.}
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     * @param array                       $parameters   Array with information about the parameters.
     *
     * @return void
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $tokens = $phpcsFile->getTokens();

        $globalsParamCount = 0;
        foreach ($parameters as $param) {
            $hasGlobals     = false;
            $hasNonVarToken = false;

            for ($i = $param['start']; $i <= $param['end']; $i++) {
                if (isset(Tokens::$emptyTokens[$tokens[$i]['code']])) {
                    continue;
                }

                if ($tokens[$i]['code'] !== \T_VARIABLE) {
                    $hasNonVarToken = true;
                    continue;
                }

                if ($tokens[$i]['content'] === '$GLOBALS') {
                    $hasGlobals = true;
                }
            }

            if ($hasGlobals === true && $hasNonVarToken === false) {
                ++$globalsParamCount;
            }
        }

        if ($globalsParamCount > 1) {
            $phpcsFile->addError(
                'Recursively merging the $GLOBALS array is not supported prior to PHP 8.1.',
                $stackPtr,
                'Found'
            );
        }
    }
}
