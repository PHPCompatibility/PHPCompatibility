<?php
/**
 * \PHPCompatibility\AbstractFunctionCallParameterSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility;

use PHPCompatibility\Sniff;

/**
 * \PHPCompatibility\AbstractFunctionCallParameterSniff.
 *
 * Abstract class to use as a base for examining the parameter values passed to function calls.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
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
     * @var bool False (default) if the sniff is looking for function calls.
     *           True if the sniff is looking for method calls.
     */
    protected $isMethod = false;

    /**
     * Functions the sniff is looking for. Should be defined in the child class.
     *
     * @var array The only requirement for this array is that the top level
     *            array keys are the names of the functions you're looking for.
     *            Other than that, the array can have arbitrary content
     *            depending on your needs.
     */
    protected $targetFunctions = array();

    /**
     * List of tokens which when they preceed the $stackPtr indicate that this
     * is not a function call.
     *
     * @var array
     */
    private $ignoreTokens = array(
        T_DOUBLE_COLON    => true,
        T_OBJECT_OPERATOR => true,
        T_FUNCTION        => true,
        T_NEW             => true,
        T_CONST           => true,
        T_USE             => true,
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of function names.
        $this->targetFunctions = $this->arrayKeysToLowercase($this->targetFunctions);

        return array(T_STRING);
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->bowOutEarly() === true) {
            return;
        }

        $tokens     = $phpcsFile->getTokens();
        $function   = $tokens[$stackPtr]['content'];
        $functionLc = strtolower($function);

        if (isset($this->targetFunctions[$functionLc]) === false) {
            return;
        }

        $prevNonEmpty = $phpcsFile->findPrevious(\PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);

        if ($this->isMethod === true) {
            if ($tokens[$prevNonEmpty]['code'] !== T_DOUBLE_COLON
                && $tokens[$prevNonEmpty]['code'] !== T_OBJECT_OPERATOR
            ) {
                // Not a call to a PHP method.
                return;
            }
        } else {
            if (isset($this->ignoreTokens[$tokens[$prevNonEmpty]['code']]) === true) {
                // Not a call to a PHP function.
                return;
            }

            if ($tokens[$prevNonEmpty]['code'] === T_NS_SEPARATOR
                && $tokens[$prevNonEmpty - 1]['code'] === T_STRING
            ) {
                // Namespaced function.
                return;
            }
        }

        $parameters = $this->getFunctionCallParameters($phpcsFile, $stackPtr);

        if (empty($parameters)) {
            return $this->processNoParameters($phpcsFile, $stackPtr, $function);
        } else {
            return $this->processParameters($phpcsFile, $stackPtr, $function, $parameters);
        }
    }


    /**
     * Do a version check to determine if this sniff needs to run at all.
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
     * @param \PHP_CodeSniffer_File $phpcsFile    The file being scanned.
     * @param int                   $stackPtr     The position of the current token in the stack.
     * @param string                $functionName The token content (function name) which was matched.
     * @param array                 $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    abstract public function processParameters(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, $functionName, $parameters);


    /**
     * Process the function if no parameters were found.
     *
     * Defaults to doing nothing. Can be overloaded in child classes to handle functions
     * were parameters are expected, but none found.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile    The file being scanned.
     * @param int                   $stackPtr     The position of the current token in the stack.
     * @param string                $functionName The token content (function name) which was matched.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processNoParameters(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, $functionName)
    {
        return;
    }
}//end class
