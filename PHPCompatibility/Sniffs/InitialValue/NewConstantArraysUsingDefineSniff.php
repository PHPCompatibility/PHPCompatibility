<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\InitialValue;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Arrays;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect declaration of constants using `define()` with a (constant) array value
 * as supported since PHP 7.0.
 *
 * PHP version 7.0
 *
 * @link https://www.php.net/manual/en/migration70.new-features.php#migration70.new-features.define-array
 * @link https://www.php.net/manual/en/language.constants.syntax.php
 *
 * @since 7.0.0
 * @since 9.0.0 Renamed from `ConstantArraysUsingDefineSniff` to `NewConstantArraysUsingDefineSniff`.
 * @since 10.0.0 Now extends the base `AbstractFunctionCallParameterSniff` class instead of `Sniff`.
 */
class NewConstantArraysUsingDefineSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions the sniff is looking for.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'define' => true,
    ];

    /**
     * Should the sniff bow out early for specific PHP versions ?
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrBelow('5.6') === false);
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
     * @return void
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $valueParam = PassedParameters::getParameterFromStack($parameters, 2, 'value');
        if (isset($valueParam['start'], $valueParam['end']) === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $targetNestingLevel = 0;
        if (isset($tokens[$valueParam['start']]['nested_parenthesis'])) {
            $targetNestingLevel = \count($tokens[$valueParam['start']]['nested_parenthesis']);
        }

        $find             = Collections::arrayOpenTokensBC();
        $find[\T_CLOSURE] = \T_CLOSURE;
        $find[\T_FN]      = \T_FN;

        $current = ($valueParam['start'] - 1);
        do {
            $current = $phpcsFile->findNext($find, ($current + 1), ($valueParam['end'] + 1));
            if ($current === false) {
                break;
            }

            if (isset(Collections::functionDeclarationTokens()[$tokens[$current]['code']], $tokens[$current]['scope_closer'])) {
                // Skip over closure and arrow function definitions. Not the concern of this sniff.
                $current = $tokens[$current]['scope_closer'];
                continue;
            }

            if (isset(Collections::shortArrayListOpenTokensBC()[$tokens[$current]['code']])
                && Arrays::isShortArray($phpcsFile, $current) === false
            ) {
                // Not an array.
                continue;
            }

            if ((isset($tokens[$current]['nested_parenthesis']) === false && $targetNestingLevel === 0)
                || \count($tokens[$current]['nested_parenthesis']) === $targetNestingLevel
            ) {
                $phpcsFile->addError(
                    'Constant arrays using define are not allowed in PHP 5.6 or earlier',
                    $current,
                    'Found'
                );
                break;
            }
        } while ($current <= $valueParam['end']);
    }
}
