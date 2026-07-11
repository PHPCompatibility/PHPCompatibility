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

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Passing a class name as a string as the `$object_or_class` parameter for `is_a()` or `is_subclass_of()`,
 * when the `$allow_string` parameter is set to `false`, is deprecated since PHP 8.6.
 *
 * PHP version 8.6
 *
 * @link https://wiki.php.net/rfc/deprecations_php_8_6#deprecate_is_a_with_string_when_allow_string_is_false
 * @link https://wiki.php.net/rfc/deprecations_php_8_6#deprecate_is_subclass_of_with_string_when_allow_string_is_false
 * @link https://www.php.net/is_a
 * @link https://www.php.net/is_subclass_of
 *
 * @since 10.0.0
 */
final class RemovedIsASubclassOfAllowStringFalseSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, bool> Function name => Default value for the $allow_string parameter.
     */
    protected $targetFunctions = [
        'is_a'           => false,
        'is_subclass_of' => true,
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
        return (ScannedCode::shouldRunOnOrAbove('8.6') === false);
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
        $functionName            = \strtolower($functionName);
        $defaultAllowStringValue = $this->targetFunctions[$functionName];

        // The parameter position and name is hard-coded as both function have the parameter at the same position
        // and use the same name. If this would change, this will need to be made dynamic.
        $allowStringParam = PassedParameters::getParameterFromStack($parameters, 3, 'allow_string');

        if ($allowStringParam === false && $defaultAllowStringValue === true) {
            return;
        } elseif ($allowStringParam !== false) {
            if ($phpcsFile->findNext(\T_FALSE, $allowStringParam['start'], ($allowStringParam['end'] + 1)) === false) {
                // Value of $allow_string parameter does not contain "false".
                return;
            }

            // Make sure "false" was the only effective token in the parameter.
            $search           = Tokens::EMPTY_TOKENS;
            $search[\T_FALSE] = \T_FALSE;

            if ($phpcsFile->findNext($search, $allowStringParam['start'], ($allowStringParam['end'] + 1), true) !== false) {
                // 'allow_string' parameter contains more than just "false". Real value undetermined.
                return;
            }
        }

        // Now check if the $object_or_class parameter contains a text string.
        $objectOrClassParam = PassedParameters::getParameterFromStack($parameters, 1, 'object_or_class');
        if ($objectOrClassParam === false) {
            return;
        }

        $isClassResolution = (\preg_match('`\s*::\s*class$`i', $objectOrClassParam['clean']) === 1);
        if ($isClassResolution === false) {
            // Check for a hard-coded text string.
            $allowed  = Tokens::STRING_TOKENS;
            $allowed += [
                // We don't support heredocs for this sniff, as we'd need to check for vars inside.
                \T_START_NOWDOC  => \T_START_NOWDOC,
                \T_NOWDOC        => \T_NOWDOC,
                \T_END_NOWDOC    => \T_END_NOWDOC,

                \T_STRING_CONCAT => \T_STRING_CONCAT,
                \T_CLASS_C       => \T_CLASS_C,
                \T_NS_C          => \T_NS_C,
            ];

            $hasTextString = $phpcsFile->findNext($allowed, $objectOrClassParam['start'], ($objectOrClassParam['end'] + 1));
            if ($hasTextString === false) {
                // Value of $object_or_class parameter does not contain any text string tokens.
                return;
            }

            $allowed += Tokens::EMPTY_TOKENS;

            if ($phpcsFile->findNext($allowed, $objectOrClassParam['start'], ($objectOrClassParam['end'] + 1), true) !== false) {
                // 'object_or_class' parameter contains more than just text string tokens. Ignore.
                return;
            }
        }

        $message   = 'Calling %s() with a string, while $allow_string is false, is deprecated since PHP 8.6';
        $errorCode = 'Deprecated';
        $data      = [$functionName];

        $phpcsFile->addWarning($message, $stackPtr, $errorCode, $data);
    }
}
