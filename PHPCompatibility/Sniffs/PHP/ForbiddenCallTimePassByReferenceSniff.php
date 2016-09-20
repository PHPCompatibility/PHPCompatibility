<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenCallTimePassByReference.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Gary Rogers <gmrwebde@gmail.com>
 * @author    Florian Grandel <jerico.dev@gmail.com>
 * @copyright 2009 Florian Grandel
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenCallTimePassByReference.
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
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenCallTimePassByReferenceSniff extends PHPCompatibility_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING);

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('5.3') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Skip tokens that are the names of functions or classes
        // within their definitions. For example: function myFunction...
        // "myFunction" is T_STRING but we should skip because it is not a
        // function or method *call*.
        $findTokens = array_merge(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            array(T_BITWISE_AND)
        );

        $prevNonEmpty = $phpcsFile->findPrevious(
            $findTokens,
            ($stackPtr - 1),
            null,
            true
        );

        if ($prevNonEmpty !== false && in_array($tokens[$prevNonEmpty]['code'], array(T_FUNCTION, T_CLASS, T_INTERFACE, T_TRAIT), true)) {
            return;
        }

        // If the next non-whitespace token after the function or method call
        // is not an opening parenthesis then it can't really be a *call*.
        $openBracket = $phpcsFile->findNext(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($stackPtr + 1),
            null,
            true
        );

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
                $error = 'Using a call-time pass-by-reference is deprecated since PHP 5.3';
                if($this->supportsAbove('5.4')) {
                    $error .= ' and prohibited since PHP 5.4';
                }
                $phpcsFile->addError($error, $parameter['start'], 'NotAllowed');
            }
        }
    }//end process()


    protected function isCallTimePassByReferenceParam(PHP_CodeSniffer_File $phpcsFile, $parameter, $nestingLevel)
    {
        $tokens   = $phpcsFile->getTokens();

        $searchStartToken = $parameter['start'] - 1;
        $searchEndToken   = $parameter['end'] + 1;
        $nextVariable     = $searchStartToken;
        do {
            $nextVariable = $phpcsFile->findNext(T_VARIABLE, ($nextVariable + 1), $searchEndToken);
            if ($nextVariable === false) {
                return false;
            }

            // Make sure the variable belongs directly to this function call
            // and is not inside a nested function call or array.
            if (isset($tokens[$nextVariable]['nested_parenthesis']) === false ||
               (count($tokens[$nextVariable]['nested_parenthesis']) !== $nestingLevel)
            ) {
                continue;
            }


            // Checking this: $value = my_function(...[*]$arg...).
            $tokenBefore = $phpcsFile->findPrevious(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($nextVariable - 1),
                $searchStartToken,
                true
            );

            if ($tokenBefore === false || $tokens[$tokenBefore]['code'] !== T_BITWISE_AND) {
                // Nothing before the token or no &.
                continue;
            }

            // Checking this: $value = my_function(...[*]&$arg...).
            $tokenBefore = $phpcsFile->findPrevious(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($tokenBefore - 1),
                $searchStartToken,
                true
            );

            // We have to exclude all uses of T_BITWISE_AND that are not
            // references. We use a blacklist approach as we prefer false
            // positives to not identifying a pass-by-reference call at all.
            // The blacklist may not yet be complete.
            switch ($tokens[$tokenBefore]['code']) {
                // In these cases T_BITWISE_AND represents
                // the bitwise and operator.
                case T_LNUMBER:
                case T_VARIABLE:
                case T_CLOSE_SQUARE_BRACKET:
                case T_CLOSE_PARENTHESIS:
                    break;

                // Unfortunately the tokenizer fails to recognize global constants,
                // class-constants and -attributes. Any of these are returned is
                // treated as T_STRING.
                // So we step back another token and check if it is a class
                // operator (-> or ::), which means we have a false positive.
                // Global constants still remain uncovered.
                case T_STRING:
                    $tokenBeforePlus = $phpcsFile->findPrevious(
                        PHP_CodeSniffer_Tokens::$emptyTokens,
                        ($tokenBefore - 1),
                        $searchStartToken,
                        true
                    );
                    if ($tokens[$tokenBeforePlus]['code'] === T_DOUBLE_COLON ||
                        $tokens[$tokenBeforePlus]['code'] === T_OBJECT_OPERATOR
                    ) {
                        break;
                    }
                    // If not a class constant: fall through.

                default:
                    // The found T_BITWISE_AND represents a pass-by-reference.
                    return true;
            }

        } while($nextVariable < $searchEndToken);

        // This code should never be reached, but here in case of weird bugs ;-)
        return false;
    }

}//end class
