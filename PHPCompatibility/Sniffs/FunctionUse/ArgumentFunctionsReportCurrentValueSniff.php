<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionUse;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Helpers\TokenGroup;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\BackCompat\BCFile;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Context;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\Lists;
use PHPCSUtils\Utils\Numbers;
use PHPCSUtils\Utils\Operators;
use PHPCSUtils\Utils\Parentheses;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;

/**
 * Functions inspecting function arguments report the current parameter value
 * instead of the original since PHP 7.0.
 *
 * `func_get_arg()`, `func_get_args()`, `debug_backtrace()` and exception backtraces
 * will no longer report the original parameter value as was passed to the function,
 * but will instead provide the current value (which might have been modified).
 *
 * PHP version 7.0
 *
 * @link https://www.php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.func-parameter-modified
 *
 * @since 9.1.0
 * @since 10.0.0 This class is now `final`.
 */
final class ArgumentFunctionsReportCurrentValueSniff extends Sniff
{

    /**
     * A list of functions that, when called, can behave differently in PHP 7
     * when dealing with parameters of the function they're called in.
     *
     * @since 9.1.0
     *
     * @var array<string, true>
     */
    protected $changedFunctions = [
        'func_get_arg'          => true,
        'func_get_args'         => true,
        'debug_backtrace'       => true,
        'debug_print_backtrace' => true,
    ];

    /**
     * Tokens to ignore when determining the start of a statement for call to one of the functions.
     *
     * @since 9.1.0
     *
     * @var array<int|string>
     */
    private $ignoreForStartOfStatement = [
        \T_COMMA,
        \T_DOUBLE_ARROW,
        \T_OPEN_SQUARE_BRACKET,
        \T_OPEN_PARENTHESIS,
    ];

    /**
     * Tokens to ignore when determining the start of a statement for a used variable.
     *
     * @since 10.0.0
     *
     * @var array<int|string>
     */
    private $ignoreForStartOfStatementVarUse = [
        \T_COMMA,
        \T_DOUBLE_ARROW,
        \T_OPEN_SQUARE_BRACKET,
        \T_OPEN_PARENTHESIS,
        \T_OPEN_SHORT_ARRAY,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.1.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            \T_FUNCTION,
            \T_CLOSURE,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.1.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('7.0') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_opener'], $tokens[$stackPtr]['scope_closer']) === false) {
            // Abstract function, interface function, live coding or parse error.
            return;
        }

        $scopeOpener = $tokens[$stackPtr]['scope_opener'];
        $scopeCloser = $tokens[$stackPtr]['scope_closer'];

        // Does the function declaration have parameters ?
        $params = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        if (empty($params)) {
            // No named arguments found, so no risk of them being changed.
            return;
        }

        $paramNames = [];
        foreach ($params as $param) {
            $paramNames[] = $param['name'];
        }

        $prevNonEmpty = $scopeOpener;
        for ($i = ($scopeOpener + 1);
            $i < $scopeCloser;
            $prevNonEmpty = (isset(Tokens::$emptyTokens[$tokens[$i]['code']]) ? $prevNonEmpty : $i), $i++
        ) {
            if ((isset(Collections::closedScopes()[$tokens[$i]['code']]) || $tokens[$i]['code'] === \T_FN)
                && isset($tokens[$i]['scope_closer'])
            ) {
                // Skip past nested structures.
                $i = $tokens[$i]['scope_closer'];
                continue;
            }

            if ($tokens[$i]['code'] !== \T_STRING
                && $tokens[$i]['code'] !== \T_NAME_FULLY_QUALIFIED
            ) {
                continue;
            }

            $foundFunctionName = \strtolower(\ltrim($tokens[$i]['content'], '\\'));

            if (isset($this->changedFunctions[$foundFunctionName]) === false) {
                // Not one of the target functions.
                continue;
            }

            /*
             * Ok, so is this really a function call to one of the PHP native functions ?
             */
            $next = $phpcsFile->findNext(Tokens::$emptyTokens, ($i + 1), null, true);
            if ($next === false || $tokens[$next]['code'] !== \T_OPEN_PARENTHESIS) {
                // Live coding, parse error or not a function call.
                continue;
            }

            if ($this->isCallToGlobalFunction($phpcsFile, $i) === false) {
                continue;
            }

            /*
             * Check if the function is used as a PHP 8.1+ first class callable.
             *
             * Not allowed by PHP for the func_get_arg*() functions, so ignore.
             * Allowed for the debug_*backtrace() functions, but please don't do this....
             */
            if (isset($tokens[$next]['parenthesis_closer'])) {
                $hasEllipsis = $phpcsFile->findNext(Tokens::$emptyTokens, ($next + 1), null, true);
                if ($hasEllipsis !== false && $tokens[$hasEllipsis]['code'] === \T_ELLIPSIS) {
                    $isFirstClassCallable = $phpcsFile->findNext(Tokens::$emptyTokens, ($hasEllipsis + 1), null, true);
                    if ($isFirstClassCallable !== false && $isFirstClassCallable === $tokens[$next]['parenthesis_closer']) {
                        if ($foundFunctionName === 'debug_backtrace' || $foundFunctionName === 'debug_print_backtrace') {
                            $error = 'Since PHP 7.0, functions inspecting arguments, like %1$s(), no longer report the original value as passed to a parameter, but will instead provide the current value. Using this function as a first class callable is a really bad idea.';
                            $data  = [$foundFunctionName];

                            $phpcsFile->addWarning($error, $i, 'AsFirstClassCallable', $data);
                        }

                        continue;
                    }
                }
            }

            /*
             * Address some special cases.
             */
            if ($foundFunctionName !== 'func_get_args') {
                switch ($foundFunctionName) {
                    /*
                     * Check if `debug_(print_)backtrace()` is called with the
                     * `DEBUG_BACKTRACE_IGNORE_ARGS` option.
                     */
                    case 'debug_backtrace':
                    case 'debug_print_backtrace':
                        $optionsParam = PassedParameters::getParameter($phpcsFile, $i, 1, 'options');
                        if ($optionsParam !== false
                            && (\preg_match('`(^|\|)\s*\\\\?DEBUG_BACKTRACE_IGNORE_ARGS`', $optionsParam['clean']) === 1
                                || $optionsParam['clean'] === '2'
                                || $optionsParam['clean'] === '3')
                        ) {
                            // Debug_backtrace() called with ignore args option.
                            continue 2;
                        }
                        break;

                    /*
                     * Collect the necessary information to only throw a notice if the argument
                     * touched/changed is in line with the passed $arg_num.
                     *
                     * Also, we can ignore `func_get_arg()` if the argument offset passed is
                     * higher than the number of named parameters.
                     *
                     * {@internal Note: This does not take calculations into account!
                     *  Should be exceptionally rare and can - if needs be - be addressed at a later stage.}
                     */
                    case 'func_get_arg':
                        $positionParam = PassedParameters::getParameter($phpcsFile, $i, 1, 'position');
                        if ($positionParam !== false) {
                            $number = $phpcsFile->findNext(\T_LNUMBER, $positionParam['start'], ($positionParam['end'] + 1));
                            if ($number !== false) {
                                $argNumber = (int) Numbers::getCompleteNumber($phpcsFile, $number)['decimal'];

                                if (isset($paramNames[$argNumber]) === false) {
                                    // Requesting a non-named additional parameter. Ignore.
                                    continue 2;
                                }
                            }
                        }
                        break;
                }
            } else {
                /*
                 * Check if the call to func_get_args() happens to be in an array_slice() or
                 * array_splice() with an $offset higher than the number of named parameters.
                 * In that case, we can ignore it.
                 *
                 * {@internal Note: This does not take offset calculations into account!
                 *  Should be exceptionally rare and can - if needs be - be addressed at a later stage.}
                 */
                $lastParenthesesOpener = Parentheses::getLastOpener($phpcsFile, $i);
                if ($lastParenthesesOpener !== false) {

                    $maybeFunctionCall = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($lastParenthesesOpener - 1), null, true);
                    if (($tokens[$maybeFunctionCall]['code'] === \T_STRING
                        || $tokens[$maybeFunctionCall]['code'] === \T_NAME_FULLY_QUALIFIED)
                        && $this->isCallToGlobalFunction($phpcsFile, $maybeFunctionCall) === true
                    ) {
                        $functionNameLc = \strtolower(\ltrim($tokens[$maybeFunctionCall]['content'], '\\'));
                        if ($functionNameLc === 'array_slice'
                            || $functionNameLc === 'array_splice'
                        ) {
                            // Verify the `func_get_args()` was seen in the correct parameter for this check.
                            $parentFuncArrayParam = PassedParameters::getParameter($phpcsFile, $maybeFunctionCall, 1, 'array');
                            if ($parentFuncArrayParam !== false
                                && $parentFuncArrayParam['start'] <= $i && $i <= $parentFuncArrayParam['end']
                            ) {
                                $parentFuncOffsetParam = PassedParameters::getParameter($phpcsFile, $maybeFunctionCall, 2, 'offset');
                                if ($parentFuncOffsetParam !== false) {
                                    $offsetValue = TokenGroup::isNumber($phpcsFile, $parentFuncOffsetParam['start'], $parentFuncOffsetParam['end']);

                                    if (\is_int($offsetValue)) {
                                        $normalizedOffsetValue = ($offsetValue >= 0) ? $offsetValue : (\count($paramNames) + $offsetValue);
                                        if (isset($paramNames[$normalizedOffsetValue]) === false) {
                                            // Requesting non-named additional parameters. Ignore.
                                            continue ;
                                        }

                                        $targetLength          = null;
                                        $parentFuncLengthParam = PassedParameters::getParameter($phpcsFile, $maybeFunctionCall, 3, 'length');
                                        if ($parentFuncLengthParam !== false) {
                                            $lengthValue = TokenGroup::isNumber($phpcsFile, $parentFuncLengthParam['start'], $parentFuncLengthParam['end']);
                                            if (\is_int($lengthValue) && $lengthValue !== 0) {
                                                $targetLength = $lengthValue;
                                            }
                                        }

                                        // Slice starts at a named argument, but we know which params are being accessed.
                                        $paramNamesSubset = \array_slice($paramNames, $offsetValue, $targetLength);
                                    }
                                }
                            }
                        }
                    }
                }
                unset($lastParenthesesOpener, $functionNameLc);
            }

            /*
             * For debug_backtrace(), check if the result is being dereferenced and if so,
             * whether the `args` index is used.
             * I.e. whether `$index` in `debug_backtrace()[$stackFrame][$index]` is a string
             * with the content `args`.
             *
             * Note: We already know that $next is the open parenthesis of the function call.
             */
            if ($foundFunctionName === 'debug_backtrace' && isset($tokens[$next]['parenthesis_closer'])) {
                $afterParenthesis = $phpcsFile->findNext(
                    Tokens::$emptyTokens,
                    ($tokens[$next]['parenthesis_closer'] + 1),
                    null,
                    true
                );

                if ($tokens[$afterParenthesis]['code'] === \T_OPEN_SQUARE_BRACKET
                    && isset($tokens[$afterParenthesis]['bracket_closer'])
                ) {
                    $afterStackFrame = $phpcsFile->findNext(
                        Tokens::$emptyTokens,
                        ($tokens[$afterParenthesis]['bracket_closer'] + 1),
                        null,
                        true
                    );

                    if ($tokens[$afterStackFrame]['code'] === \T_OPEN_SQUARE_BRACKET
                        && isset($tokens[$afterStackFrame]['bracket_closer'])
                    ) {
                        $arrayIndex = $phpcsFile->findNext(
                            \T_CONSTANT_ENCAPSED_STRING,
                            ($afterStackFrame + 1),
                            $tokens[$afterStackFrame]['bracket_closer']
                        );

                        if ($arrayIndex !== false
                            && TextStrings::stripQuotes($tokens[$arrayIndex]['content']) !== 'args'
                        ) {
                            continue;
                        }
                    }
                }
            }

            /*
             * Only check for variables before the start of the statement to
             * prevent false positives on the return value of the function call
             * being assigned to one of the parameters, i.e.:
             * `$param = func_get_args();`.
             */
            $startOfStatement = BCFile::findStartOfStatement($phpcsFile, $i, $this->ignoreForStartOfStatement);

            /*
             * Ok, so we've found one of the target functions in the right scope.
             * Now, let's check if any of the passed parameters were touched.
             */
            $scanResult    = 'clean';
            $variableToken = null;
            $listsSeen     = [];
            for ($j = ($scopeOpener + 1); $j < $startOfStatement; $j++) {
                if (isset(Collections::closedScopes()[$tokens[$j]['code']])
                    && isset($tokens[$j]['scope_closer'])
                ) {
                    // Skip past nested structures.
                    $j = $tokens[$j]['scope_closer'];
                    continue;
                }

                // Ignore return, exit and throw statements completely.
                if ($tokens[$j]['code'] === \T_RETURN
                    || $tokens[$j]['code'] === \T_EXIT
                    || $tokens[$j]['code'] === \T_THROW
                ) {
                    $j = BCFile::findEndOfStatement($phpcsFile, $j);
                    continue;
                }

                // Ignore use of any of the passed parameters in isset() or empty().
                if ($tokens[$j]['code'] === \T_ISSET
                    || $tokens[$j]['code'] === \T_EMPTY
                ) {
                    $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($j + 1), null, true);
                    if ($nextNonEmpty !== false
                        && isset($tokens[$nextNonEmpty]['parenthesis_closer'])
                    ) {
                        $j = $tokens[$nextNonEmpty]['parenthesis_closer'];
                        continue;
                    }
                }

                // Keep track of the long/short lists structures seen.
                if (isset(Collections::listOpenTokensBC()[$tokens[$j]['code']])) {
                    $listOpenClose = Lists::getOpenClose($phpcsFile, $j);
                    if ($listOpenClose !== false) {
                        // Store in reverse order so we always have the last seen list first.
                        $listOpenClose['list_token'] = $j;
                        \array_unshift($listsSeen, $listOpenClose);
                    }
                }

                if ($tokens[$j]['code'] !== \T_VARIABLE) {
                    continue;
                }

                if ($foundFunctionName === 'func_get_arg' && isset($argNumber)) {
                    if (isset($paramNames[$argNumber])
                        && $tokens[$j]['content'] !== $paramNames[$argNumber]
                    ) {
                        // Different param than the one requested by func_get_arg().
                        continue;
                    }
                } elseif ($foundFunctionName === 'func_get_args' && isset($paramNamesSubset)) {
                    if (\in_array($tokens[$j]['content'], $paramNamesSubset, true) === false) {
                        // Different param than the ones requested by func_get_args().
                        continue;
                    }
                } elseif (\in_array($tokens[$j]['content'], $paramNames, true) === false) {
                    // Variable is not one of the function parameters.
                    continue;
                }

                /*
                 * Check if this is a variable in a list structure.
                 *
                 * For variables in a list, we need to:
                 * - Flag assignments.
                 * - Ignore list keys.
                 */
                if ($listsSeen !== []) {
                    foreach ($listsSeen as $openClose) {
                        if ($openClose['opener'] < $j && $j < $openClose['closer']) {
                            $listInfo = Lists::getAssignments($phpcsFile, $openClose['list_token']);
                            foreach ($listInfo as $listItem) {
                                if ($listItem['is_empty'] === false
                                    && $listItem['is_nested_list'] === false
                                ) {
                                    if ($listItem['assignment_token'] === $j) {
                                        // We found a definite assignment within a list.
                                        $scanResult    = 'error';
                                        $variableToken = $j;
                                        break 3;
                                    }

                                    if (isset($listItem['key_token'], $listItem['key_end_token'])
                                        && $listItem['key_token'] <= $j && $j <= $listItem['key_end_token']
                                    ) {
                                        // The variable is used in the key for a list. We can safely disregard it.
                                        continue 3;
                                    }
                                }
                            }

                            break;
                        }
                    }
                }

                $beforeVar                = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($j - 1), null, true);
                $startOfVariableStatement = BCFile::findStartOfStatement(
                    $phpcsFile,
                    $j,
                    $this->ignoreForStartOfStatementVarUse
                );

                /*
                 * Check if this is only a plain assignment. If so, ignore.
                 *
                 * A "plain assignment" for the purposes of this check is defined as:
                 * - Variable is not nested in parenthesis, i.e. not used in a potential function call.
                 * - Not preceded by a reference operator.
                 * - Has an assignment operator before it and none after.
                 *
                 * Additionally, we also check if this is a list assignment and if so, if there are no reference
                 * assignments within the list.
                 * If there are no references in the list, we can ignore this assignment as plain.
                 */
                if (empty($tokens[$j]['nested_parenthesis']) === true
                    && $beforeVar !== false
                    && Operators::isReference($phpcsFile, $beforeVar) === false
                    && ($tokens[$startOfVariableStatement]['code'] === \T_VARIABLE
                        || isset(Collections::listOpenTokensBC()[$tokens[$startOfVariableStatement]['code']]))
                ) {
                    // If this was a list assignment, we need to make sure there are no reference assignments.
                    $listHasReferenceAssignment = false;
                    if (isset(Collections::listOpenTokensBC()[$tokens[$startOfVariableStatement]['code']])) {
                        foreach ($listsSeen as $openClose) {
                            if ($openClose['list_token'] === $startOfVariableStatement) {
                                $listHasReferenceAssignment = $this->doesListHaveReferenceAssignments($phpcsFile, $openClose['list_token']);
                                break;
                            }
                        }
                    }

                    $endOfVariableStatement = $phpcsFile->findNext([\T_SEMICOLON, \T_CLOSE_TAG], ($j + 1));
                    $lastAssignmentOperator = $phpcsFile->findPrevious(
                        Tokens::$assignmentTokens,
                        ($endOfVariableStatement - 1),
                        $startOfVariableStatement
                    );

                    if ($lastAssignmentOperator !== false
                        && $lastAssignmentOperator < $j
                        && $listHasReferenceAssignment === false
                    ) {
                        continue;
                    }
                }

                /*
                 * Check if this variable is used in a control structure condition.
                 *
                 * For the purposes of this check, this type of usage is non-problematic if:
                 * - The variable is the only thing in the control structure condition,
                 *   so no analysis of more complex comparisons.
                 * - If the control structure is a `foreach()`, the variable is used in the "before as" part.
                 * - And we're going to ignore `for()` structures as too complex.
                 */

                $inForeach = Context::inForeachCondition($phpcsFile, $j);
                if ($inForeach === 'beforeAs') {
                    // Safe to ignore.
                    continue;
                } elseif ($inForeach === 'afterAs') {
                    // We know this is an assignment, so throw an error.
                    $scanResult    = 'error';
                    $variableToken = $j;
                    break;
                }

                if (Parentheses::getLastOwner($phpcsFile, $j, \T_CATCH) !== false) {
                    // The only variable in a catch statement is the one being assigned to, so throw an error.
                    $scanResult    = 'error';
                    $variableToken = $j;
                    break;
                }

                $afterVar              = $phpcsFile->findNext(Tokens::$emptyTokens, ($j + 1), null, true);
                $lastParenthesesOpener = Parentheses::getLastOpener($phpcsFile, $j);
                if ($lastParenthesesOpener !== false
                    && isset($tokens[$lastParenthesesOpener]['parenthesis_closer']) === true
                    && Parentheses::isOwnerIn($phpcsFile, $lastParenthesesOpener, Collections::controlStructureTokens())
                    && $beforeVar === $lastParenthesesOpener
                    && $afterVar === $tokens[$lastParenthesesOpener]['parenthesis_closer']
                ) {
                    continue;
                }

                // Check for $obj::class, which can be safely ignored.
                if ($tokens[$afterVar]['code'] === \T_DOUBLE_COLON) {
                    $nextAfterAfterVar = $phpcsFile->findNext(Tokens::$emptyTokens, ($afterVar + 1), null, true);
                    if ($tokens[$nextAfterAfterVar]['code'] === \T_STRING
                        && \strtolower($tokens[$nextAfterAfterVar]['content']) === 'class'
                    ) {
                        continue;
                    }
                }

                /*
                 * Ok, so we've found a variable which was passed as one of the parameters.
                 * Now, is this variable being changed, i.e. incremented, decremented, unset
                 * or assigned something ?
                 */
                $scanResult = 'warning';
                if (isset($variableToken) === false) {
                    $variableToken = $j;
                }

                if ($beforeVar !== false && isset(Collections::incrementDecrementOperators()[$tokens[$beforeVar]['code']])) {
                    // Variable is being (pre-)incremented/decremented.
                    $scanResult    = 'error';
                    $variableToken = $j;
                    break;
                }

                if ($afterVar === false) {
                    // Shouldn't be possible, but just in case.
                    continue; // @codeCoverageIgnore
                }

                if (isset(Collections::incrementDecrementOperators()[$tokens[$afterVar]['code']])) {
                    // Variable is being (post-)incremented/decremented.
                    $scanResult    = 'error';
                    $variableToken = $j;
                    break;
                }

                if (Context::inUnset($phpcsFile, $j)) {
                    // Variable is being unset.
                    $scanResult    = 'error';
                    $variableToken = $j;
                    break;
                }

                if ($tokens[$afterVar]['code'] === \T_OPEN_SQUARE_BRACKET
                    && isset($tokens[$afterVar]['bracket_closer'])
                ) {
                    // Skip past array access on the variable.
                    while (($afterVar = $phpcsFile->findNext(Tokens::$emptyTokens, ($tokens[$afterVar]['bracket_closer'] + 1), null, true)) !== false) {
                        if ($tokens[$afterVar]['code'] !== \T_OPEN_SQUARE_BRACKET
                            || isset($tokens[$afterVar]['bracket_closer']) === false
                        ) {
                            break;
                        }
                    }
                }

                if (isset(Tokens::$assignmentTokens[$tokens[$afterVar]['code']])
                    && $tokens[$afterVar]['code'] !== \T_COALESCE_EQUAL
                ) {
                    // Variable is being assigned something.
                    $scanResult    = 'error';
                    $variableToken = $j;
                    break;
                }
            }

            unset($argNumber, $paramNamesSubset);

            if ($scanResult === 'clean') {
                continue;
            }

            $error = 'Since PHP 7.0, functions inspecting arguments, like %1$s(), no longer report the original value as passed to a parameter, but will instead provide the current value. The parameter "%2$s" was %4$s on line %3$s.';
            $data  = [
                $foundFunctionName,
                $tokens[$variableToken]['content'],
                $tokens[$variableToken]['line'],
            ];

            if ($scanResult === 'error') {
                $data[] = 'changed';
                $phpcsFile->addError($error, $i, 'Changed', $data);
            } elseif ($scanResult === 'warning') {
                $data[] = 'used, and possibly changed (by reference),';
                $phpcsFile->addWarning($error, $i, 'NeedsInspection', $data);
            }

            unset($variableToken);
        }
    }

    /**
     * Check if a `T_STRING` token represents a function call to a global function.
     *
     * Note: not 100% precise, but should be sufficient for now. At a later point in
     * time there will probably be a PHPCSUtils function for this.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the potential function call
     *                                               token in the stack.
     *
     * @return bool
     */
    private function isCallToGlobalFunction(File $phpcsFile, $stackPtr)
    {
        if (Context::inAttribute($phpcsFile, $stackPtr) === true) {
            // Class instantiation in attribute, not function call.
            return false;
        }

        $tokens       = $phpcsFile->getTokens();
        $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);

        if (isset(Collections::objectOperators()[$tokens[$prevNonEmpty]['code']]) === true) {
            // Method call.
            return false;
        }

        if ($tokens[$prevNonEmpty]['code'] === \T_NEW
            || $tokens[$prevNonEmpty]['code'] === \T_FUNCTION
        ) {
            return false;
        }

        if ($tokens[$prevNonEmpty]['code'] === \T_NS_SEPARATOR) {
            $prevPrevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prevNonEmpty - 1), null, true);
            if ($tokens[$prevPrevToken]['code'] === \T_STRING
                || $tokens[$prevPrevToken]['code'] === \T_NAMESPACE
            ) {
                // Namespaced function.
                return false;
            }
        }

        return true;
    }

    /**
     * Check if there are any reference assignments in a list structure.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the list token.
     *
     * @return bool
     */
    private function doesListHaveReferenceAssignments(File $phpcsFile, $stackPtr)
    {
        $listInfo = Lists::getAssignments($phpcsFile, $stackPtr);
        foreach ($listInfo as $listItem) {
            if ($listItem['assign_by_reference'] === true) {
                return true;
            }

            if ($listItem['is_nested_list'] === true
                && $this->doesListHaveReferenceAssignments($phpcsFile, $listItem['assignment_token']) === true
            ) {
                return true;
            }
        }

        return false;
    }
}
