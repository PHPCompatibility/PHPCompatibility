<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Context;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Abstract class to use as a base for examining the parameter values passed to function calls.
 *
 * @since 8.2.0
 */
abstract class AbstractFunctionCallParameterSniff extends Sniff
{

    /**
     * Is the sniff looking for a function call or a method call ?
     *
     * Note: the child class may need to do additional checks to make sure that
     * the method called is of the right class/object.
     * Checking that is outside of the scope of this abstract sniff.
     *
     * @since 8.2.0
     *
     * @var bool False (default) if the sniff is looking for function calls.
     *           True if the sniff is looking for method calls.
     */
    protected $isMethod = false;

    /**
     * Functions the sniff is looking for. Should be defined in the child class.
     *
     * @since 8.2.0
     *
     * @var array<string, mixed> The only requirement for this array is that the top level
     *                           array keys are the names of the functions you're looking for.
     *                           Other than that, the array can have arbitrary content
     *                           depending on your needs.
     */
    protected $targetFunctions = [];

    /**
     * List of tokens which when they preceed the $stackPtr indicate that this
     * is not a function call.
     *
     * {@internal The object operators are added to the array in the register() method.}
     *
     * @since 8.2.0
     *
     * @var array<int|string, true>
     */
    private $ignoreTokens = [
        \T_NEW => true,
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.2.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        $this->ignoreTokens += Collections::objectOperators();

        // Handle case-insensitivity of function names.
        $this->targetFunctions = \array_change_key_case($this->targetFunctions, \CASE_LOWER);

        return [\T_STRING];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->bowOutEarly() === true) {
            return;
        }

        $tokens     = $phpcsFile->getTokens();
        $function   = $tokens[$stackPtr]['content'];
        $functionLc = \strtolower($function);

        if (isset($this->targetFunctions[$functionLc]) === false) {
            return;
        }

        $nextToken = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextToken === false
            || $tokens[$nextToken]['code'] !== \T_OPEN_PARENTHESIS
            || isset($tokens[$nextToken]['parenthesis_owner']) === true
        ) {
            return;
        }

        if (Context::inAttribute($phpcsFile, $stackPtr) === true) {
            // Class instantiation or constant in attribute, not function call.
            return false;
        }

        $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);

        if ($this->isMethod === true) {
            if (isset(Collections::objectOperators()[$tokens[$prevNonEmpty]['code']]) === false) {
                // Not a call to a PHP method.
                return;
            }
        } else {
            if (isset($this->ignoreTokens[$tokens[$prevNonEmpty]['code']]) === true) {
                // Not a call to a PHP function.
                return;
            }

            if ($tokens[$prevNonEmpty]['code'] === \T_NS_SEPARATOR) {
                $prevPrevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prevNonEmpty - 1), null, true);
                if ($tokens[$prevPrevToken]['code'] === \T_STRING
                    || $tokens[$prevPrevToken]['code'] === \T_NAMESPACE
                ) {
                    // Namespaced function.
                    return;
                }
            }
        }

        $parameters = PassedParameters::getParameters($phpcsFile, $stackPtr);

        if (empty($parameters)) {
            return $this->processNoParameters($phpcsFile, $stackPtr, $function);
        } else {
            return $this->processParameters($phpcsFile, $stackPtr, $function, $parameters);
        }
    }


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 8.2.0
     *
     * If the check done in a child class is not specific to one PHP version,
     * this function should return `false`.
     *
     * @return bool
     */
    abstract protected function bowOutEarly();


    /**
     * Process the parameters of a matched function.
     *
     * This method has to be made concrete in child classes.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     * @param array                       $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    abstract public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters);


    /**
     * Process the function if no parameters were found.
     *
     * Defaults to doing nothing. Can be overloaded in child classes to handle functions
     * were parameters are expected, but none found.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processNoParameters(File $phpcsFile, $stackPtr, $functionName)
    {
        return;
    }
}
