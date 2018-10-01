<?php
/**
 * \PHPCompatibility\Sniffs\Syntax\ForbiddenCallTimePassByReference.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Gary Rogers <gmrwebde@gmail.com>
 * @author    Florian Grandel <jerico.dev@gmail.com>
 * @copyright 2009 Florian Grandel
 */

namespace PHPCompatibility\Sniffs\Syntax;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * \PHPCompatibility\Sniffs\Syntax\ForbiddenCallTimePassByReference.
 *
 * Discourages the use of call time pass by references
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Gary Rogers <gmrwebde@gmail.com>
 * @author    Florian Grandel <jerico.dev@gmail.com>
 * @copyright 2009 Florian Grandel
 */
class ForbiddenCallTimePassByReferenceSniff extends Sniff
{

    /**
     * Tokens that represent assignments or equality comparisons.
     *
     * Near duplicate of Tokens::$assignmentTokens + Tokens::$equalityTokens.
     * Copied in for PHPCS cross-version compatibility.
     *
     * @var array
     */
    private $assignOrCompare = array(
        // Equality tokens.
        'T_IS_EQUAL'            => true,
        'T_IS_NOT_EQUAL'        => true,
        'T_IS_IDENTICAL'        => true,
        'T_IS_NOT_IDENTICAL'    => true,
        'T_IS_SMALLER_OR_EQUAL' => true,
        'T_IS_GREATER_OR_EQUAL' => true,

        // Assignment tokens.
        'T_EQUAL'          => true,
        'T_AND_EQUAL'      => true,
        'T_OR_EQUAL'       => true,
        'T_CONCAT_EQUAL'   => true,
        'T_DIV_EQUAL'      => true,
        'T_MINUS_EQUAL'    => true,
        'T_POW_EQUAL'      => true,
        'T_MOD_EQUAL'      => true,
        'T_MUL_EQUAL'      => true,
        'T_PLUS_EQUAL'     => true,
        'T_XOR_EQUAL'      => true,
        'T_DOUBLE_ARROW'   => true,
        'T_SL_EQUAL'       => true,
        'T_SR_EQUAL'       => true,
        'T_COALESCE_EQUAL' => true,
        'T_ZSR_EQUAL'      => true,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_STRING,
            T_VARIABLE,
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('5.3') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Skip tokens that are the names of functions or classes
        // within their definitions. For example: function myFunction...
        // "myFunction" is T_STRING but we should skip because it is not a
        // function or method *call*.
        $findTokens   = Tokens::$emptyTokens;
        $findTokens[] = T_BITWISE_AND;

        $prevNonEmpty = $phpcsFile->findPrevious(
            $findTokens,
            ($stackPtr - 1),
            null,
            true
        );

        if ($prevNonEmpty !== false && in_array($tokens[$prevNonEmpty]['type'], array('T_FUNCTION', 'T_CLASS', 'T_INTERFACE', 'T_TRAIT'), true)) {
            return;
        }

        // If the next non-whitespace token after the function or method call
        // is not an opening parenthesis then it can't really be a *call*.
        $openBracket = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);

        if ($openBracket === false || $tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS
            || isset($tokens[$openBracket]['parenthesis_closer']) === false
        ) {
            return;
        }

        // Get the function call parameters.
        $parameters = $this->getFunctionCallParameters($phpcsFile, $stackPtr);
        if (count($parameters) === 0) {
            return;
        }

        // Which nesting level is the one we are interested in ?
        $nestedParenthesisCount = 1;
        if (isset($tokens[$openBracket]['nested_parenthesis'])) {
            $nestedParenthesisCount = count($tokens[$openBracket]['nested_parenthesis']) + 1;
        }

        foreach ($parameters as $parameter) {
            if ($this->isCallTimePassByReferenceParam($phpcsFile, $parameter, $nestedParenthesisCount) === true) {
                // T_BITWISE_AND represents a pass-by-reference.
                $error     = 'Using a call-time pass-by-reference is deprecated since PHP 5.3';
                $isError   = false;
                $errorCode = 'Deprecated';

                if ($this->supportsAbove('5.4')) {
                    $error    .= ' and prohibited since PHP 5.4';
                    $isError   = true;
                    $errorCode = 'NotAllowed';
                }

                $this->addMessage($phpcsFile, $error, $parameter['start'], $isError, $errorCode);
            }
        }
    }


    /**
     * Determine whether a parameter is passed by reference.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile    The file being scanned.
     * @param array                 $parameter    Information on the current parameter
     *                                            to be examined.
     * @param int                   $nestingLevel Target nesting level.
     *
     * @return bool
     */
    protected function isCallTimePassByReferenceParam(File $phpcsFile, $parameter, $nestingLevel)
    {
        $tokens = $phpcsFile->getTokens();

        $searchStartToken = $parameter['start'] - 1;
        $searchEndToken   = $parameter['end'] + 1;
        $nextVariable     = $searchStartToken;
        do {
            $nextVariable = $phpcsFile->findNext(array(T_VARIABLE, T_OPEN_SHORT_ARRAY, T_CLOSURE), ($nextVariable + 1), $searchEndToken);
            if ($nextVariable === false) {
                return false;
            }

            // Ignore anything within short array definition brackets.
            if ($tokens[$nextVariable]['type'] === 'T_OPEN_SHORT_ARRAY'
                && (isset($tokens[$nextVariable]['bracket_opener'])
                    && $tokens[$nextVariable]['bracket_opener'] === $nextVariable)
                && isset($tokens[$nextVariable]['bracket_closer'])
            ) {
                // Skip forward to the end of the short array definition.
                $nextVariable = $tokens[$nextVariable]['bracket_closer'];
                continue;
            }

            // Skip past closures passed as function parameters.
            if ($tokens[$nextVariable]['type'] === 'T_CLOSURE'
                && (isset($tokens[$nextVariable]['scope_condition'])
                    && $tokens[$nextVariable]['scope_condition'] === $nextVariable)
                && isset($tokens[$nextVariable]['scope_closer'])
            ) {
                // Skip forward to the end of the closure declaration.
                $nextVariable = $tokens[$nextVariable]['scope_closer'];
                continue;
            }

            // Make sure the variable belongs directly to this function call
            // and is not inside a nested function call or array.
            if (isset($tokens[$nextVariable]['nested_parenthesis']) === false
                || (count($tokens[$nextVariable]['nested_parenthesis']) !== $nestingLevel)
            ) {
                continue;
            }

            // Checking this: $value = my_function(...[*]$arg...).
            $tokenBefore = $phpcsFile->findPrevious(
                Tokens::$emptyTokens,
                ($nextVariable - 1),
                $searchStartToken,
                true
            );

            if ($tokenBefore === false || $tokens[$tokenBefore]['code'] !== T_BITWISE_AND) {
                // Nothing before the token or no &.
                continue;
            }

            if ($phpcsFile->isReference($tokenBefore) === false) {
                continue;
            }

            // Checking this: $value = my_function(...[*]&$arg...).
            $tokenBefore = $phpcsFile->findPrevious(
                Tokens::$emptyTokens,
                ($tokenBefore - 1),
                $searchStartToken,
                true
            );

            // Prevent false positive on assign by reference and compare with reference
            // within function call parameters.
            if (isset($this->assignOrCompare[$tokens[$tokenBefore]['type']])) {
                continue;
            }

            // The found T_BITWISE_AND represents a pass-by-reference.
            return true;

        } while ($nextVariable < $searchEndToken);

        // This code should never be reached, but here in case of weird bugs.
        return false;
    }
}
