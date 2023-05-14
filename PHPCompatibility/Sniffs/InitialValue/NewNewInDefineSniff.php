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
use PHPCSUtils\Utils\Parentheses;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect declaration of constants using `define()` with an object (instantiation) as value
 * as supported since PHP 8.1.
 *
 * PHP version 8.1
 *
 * @link https://www.php.net/manual/en/migration81.new-features.php#migration81.new-features.core.new-in-initializer
 *
 * @since 10.0.0
 */
final class NewNewInDefineSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions the sniff is looking for.
     *
     * @since 10.0.0
     *
     * @var array<string, bool> Key is the function name, value irrelevant.
     */
    protected $targetFunctions = [
        'define' => true,
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
        $valueParam = PassedParameters::getParameterFromStack($parameters, 2, 'value');
        if ($valueParam === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Nesting level will always be set as the parameter is within the parenthesis of the define() function call.
        $targetNestingLevel = \count($tokens[$valueParam['start']]['nested_parenthesis']);

        $start = $valueParam['start'];
        while (($hasNew = $phpcsFile->findNext(\T_NEW, $start, ($valueParam['end'] + 1))) !== false) {
            // Handle nesting within arrays.
            $currentNestingLevel = 0;
            foreach ($tokens[$hasNew]['nested_parenthesis'] as $opener => $closer) {
                // Always count outer parentheses.
                if ($opener < $valueParam['start']) {
                    ++$currentNestingLevel;
                    continue;
                }

                // Only count inner parentheses when they are not for an array.
                $owner = Parentheses::getOwner($phpcsFile, $opener);
                if ($owner === false || $tokens[$owner]['code'] !== \T_ARRAY) {
                    ++$currentNestingLevel;
                }
            }

            if ($currentNestingLevel === $targetNestingLevel) {
                $phpcsFile->addError(
                    'Passing an object as the value when declaring constants using define is not allowed in PHP 8.0 or earlier',
                    $hasNew,
                    'Found'
                );
                return;
            }

            $start = ($hasNew + 1);
        }
    }
}
