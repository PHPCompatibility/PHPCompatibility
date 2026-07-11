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
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\PassedParameters;

/**
 * ...
 *
 * PHP version 8.6
 *
 * @link https://wiki.php.net/rfc/deprecations_php_8_6#deprecate_is_a_with_string_when_allow_string_is_false
 * @link https://www.php.net/is_a
 *
 * @since 10.0.0
 */
final class RemovedIsAAllowStringFalseSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'is_a' => true,
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
        $allowStringParam = PassedParameters::getParameterFromStack($parameters, 3, 'allow_string');
        if ($allowStringParam === false) {
            return;
        }

        if ($phpcsFile->findNext(\T_FALSE, $allowStringParam['start'], ($allowStringParam['end'] + 1)) === false) {
            // Value of $allow_string parameter does not contain "false".
            return;
        }

        // Make sure "false" was the only effective token in the parameter.
        $search                  = Tokens::EMPTY_TOKENS;
        $search[\T_FALSE]        = \T_FALSE;
        $search[\T_NS_SEPARATOR] = \T_NS_SEPARATOR;

        if ($phpcsFile->findNext($search, $allowStringParam['start'], ($allowStringParam['end'] + 1), true) !== false) {
            // 'allow_string' parameter contains more than just "false". Real value undetermined.
            return;
        }

        // Now check if the $object_or_class parameter contains a text string.
        $objectOrClassParam = PassedParameters::getParameterFromStack($parameters, 1, 'object_or_class');

        $search  = Tokens::STRING_TOKENS;
        $search += Tokens::HEREDOC_TOKENS;

        $hasTextString = $phpcsFile->findNext($search, $objectOrClassParam['start'], ($objectOrClassParam['end'] + 1));
        if ( $hasTextString === false) {
            // Value of $object_or_class parameter does not contain any text string tokens.
            return;
        }

        $search += Tokens::EMPTY_TOKENS;

        if ($phpcsFile->findNext($search, $objectOrClassParam['start'], ($objectOrClassParam['end'] + 1), true) !== false) {
            // 'object_or_class' parameter contains more than just text string tokens. Ignore.
            return;
        }

        $message   = 'Calling is_a() with a string, while $allow_string = false, is deprecated since PHP 8.6';
        $isError   = false;
        $errorCode = 'Deprecated';

        if (ScannedCode::shouldRunOnOrAbove('9.0') === true) {
            $message  .= ' and is removed since PHP 9.0';
            $isError   = true;
            $errorCode = 'Removed';
        }

        MessageHelper::addMessage($phpcsFile, $message, $hasTextString, $isError, $errorCode);
    }
}
