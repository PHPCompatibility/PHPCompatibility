<?php
/**
 * PHPCompatibility_Sniff.
 *
 * PHP version 5.6
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2014 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @version   1.1.0
 * @copyright 2014 Cu.be Solutions bvba
 */
abstract class PHPCompatibility_Sniff implements PHP_CodeSniffer_Sniff
{

    /**
     * List of functions using hash algorithm as parameter (always the first parameter).
     *
     * Used by the new/removed hash algorithm sniffs.
     * Key is the function name, value is the 1-based parameter position in the function call.
     *
     * @var array
     */
    protected $hashAlgoFunctions = array(
        'hash_file'      => 1,
        'hash_hmac_file' => 1,
        'hash_hmac'      => 1,
        'hash_init'      => 1,
        'hash_pbkdf2'    => 1,
        'hash'           => 1,
    );


/* The testVersion configuration variable may be in any of the following formats:
 * 1) Omitted/empty, in which case no version is specified.  This effectively
 *    disables all the checks provided by this standard.
 * 2) A single PHP version number, e.g. "5.4" in which case the standard checks that
 *    the code will run on that version of PHP (no deprecated features or newer
 *    features being used).
 * 3) A range, e.g. "5.0-5.5", in which case the standard checks the code will run
 *    on all PHP versions in that range, and that it doesn't use any features that
 *    were deprecated by the final version in the list, or which were not available
 *    for the first version in the list.
 * PHP version numbers should always be in Major.Minor format.  Both "5", "5.3.2"
 * would be treated as invalid, and ignored.
 * This standard doesn't support checking against PHP4, so the minimum version that
 * is recognised is "5.0".
 */

    private function getTestVersion()
    {
        /**
         * var $arrTestVersions will hold an array containing min/max version of PHP
         *   that we are checking against (see above).  If only a single version
         *   number is specified, then this is used as both the min and max.
         */
        static $arrTestVersions = array();

        $testVersion = trim(PHP_CodeSniffer::getConfigData('testVersion'));

        if (!isset($arrTestVersions[$testVersion]) && !empty($testVersion)) {

            $arrTestVersions[$testVersion] = array(null, null);
            if (preg_match('/^\d+\.\d+$/', $testVersion)) {
                $arrTestVersions[$testVersion] = array($testVersion, $testVersion);
            }
            elseif (preg_match('/^(\d+\.\d+)\s*-\s*(\d+\.\d+)$/', $testVersion,
                               $matches))
            {
                if (version_compare($matches[1], $matches[2], '>')) {
                    trigger_error("Invalid range in testVersion setting: '"
                                  . $testVersion . "'", E_USER_WARNING);
                }
                else {
                    $arrTestVersions[$testVersion] = array($matches[1], $matches[2]);
                }
            }
            elseif (!$testVersion == '') {
                trigger_error("Invalid testVersion setting: '" . $testVersion
                              . "'", E_USER_WARNING);
            }
        }

        if (isset($arrTestVersions[$testVersion])) {
            return $arrTestVersions[$testVersion];
        }
        else {
            return array(null, null);
        }
    }

    public function supportsAbove($phpVersion)
    {
        $testVersion = $this->getTestVersion();
        $testVersion = $testVersion[1];

        if (is_null($testVersion)
            || version_compare($testVersion, $phpVersion) >= 0
        ) {
            return true;
        } else {
            return false;
        }
    }//end supportsAbove()

    public function supportsBelow($phpVersion)
    {
        $testVersion = $this->getTestVersion();
        $testVersion = $testVersion[0];

        if (!is_null($testVersion)
            && version_compare($testVersion, $phpVersion) <= 0
        ) {
            return true;
        } else {
            return false;
        }
    }//end supportsBelow()


    /**
     * Strip quotes surrounding an arbitrary string.
     *
     * Intended for use with the content of a T_CONSTANT_ENCAPSED_STRING.
     *
     * @param string $string The raw string.
     *
     * @return string String without quotes around it.
     */
    public function stripQuotes($string) {
        return preg_replace('`^([\'"])(.*)\1$`Ds', '$2', $string);
    }


    /**
     * Returns the name(s) of the interface(s) that the specified class implements.
     *
     * Returns FALSE on error or if there are no implemented interface names.
     *
     * {@internal Duplicate of same method as introduced in PHPCS 2.7.
     * Once the minimum supported PHPCS version for this sniff library goes beyond
     * that, this method can be removed and call to it replaced with
     * `$phpcsFile->findImplementedInterfaceNames($stackPtr)` calls.}}
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the class token.
     *
     * @return array|false
     */
    public function findImplementedInterfaceNames(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if (method_exists($phpcsFile, 'findImplementedInterfaceNames')) {
            return $phpcsFile->findImplementedInterfaceNames($stackPtr);
        }

        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        if ($tokens[$stackPtr]['code'] !== T_CLASS) {
            return false;
        }

        if (isset($tokens[$stackPtr]['scope_closer']) === false) {
            return false;
        }

        $classOpenerIndex = $tokens[$stackPtr]['scope_opener'];
        $implementsIndex  = $phpcsFile->findNext(T_IMPLEMENTS, $stackPtr, $classOpenerIndex);
        if ($implementsIndex === false) {
            return false;
        }

        $find = array(
                 T_NS_SEPARATOR,
                 T_STRING,
                 T_WHITESPACE,
                 T_COMMA,
                );

        $end  = $phpcsFile->findNext($find, ($implementsIndex + 1), ($classOpenerIndex + 1), true);
        $name = $phpcsFile->getTokensAsString(($implementsIndex + 1), ($end - $implementsIndex - 1));
        $name = trim($name);

        if ($name === '') {
            return false;
        } else {
            $names = explode(',', $name);
            $names = array_map('trim', $names);
            return $names;
        }

    }//end findImplementedInterfaceNames()


    /**
     * Checks if a function call has parameters.
     *
     * Expects to be passed the T_STRING stack pointer for the function call.
     * If passed a T_STRING which is *not* a function call, the behaviour is unreliable.
     *
     * @link https://github.com/wimg/PHPCompatibility/issues/120
     * @link https://github.com/wimg/PHPCompatibility/issues/152
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the function call token.
     *
     * @return bool
     */
    public function doesFunctionCallHaveParameters(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        if ($tokens[$stackPtr]['code'] !== T_STRING) {
            return false;
        }

        // Next non-empty token should be the open parenthesis.
        $openParenthesis = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        if ($openParenthesis === false || $tokens[$openParenthesis]['code'] !== T_OPEN_PARENTHESIS) {
            return false;
        }

        if (isset($tokens[$openParenthesis]['parenthesis_closer']) === false) {
            return false;
        }

        $closeParenthesis = $tokens[$openParenthesis]['parenthesis_closer'];
        $nextNonEmpty     = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $openParenthesis + 1, $closeParenthesis + 1, true);

        if ($nextNonEmpty === $closeParenthesis) {
            // No parameters.
            return false;
        }

        return true;
    }


    /**
     * Count the number of parameters a function call has been passed.
     *
     * Expects to be passed the T_STRING stack pointer for the function call.
     * If passed a T_STRING which is *not* a function call, the behaviour is unreliable.
     *
     * @link https://github.com/wimg/PHPCompatibility/issues/111
     * @link https://github.com/wimg/PHPCompatibility/issues/114
     * @link https://github.com/wimg/PHPCompatibility/issues/151
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the function call token.
     *
     * @return int
     */
    public function getFunctionCallParameterCount(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->doesFunctionCallHaveParameters($phpcsFile, $stackPtr) === false) {
            return 0;
        }

        return count($this->getFunctionCallParameters($phpcsFile, $stackPtr));
    }


    /**
     * Get information on all parameters passed to a function call.
     *
     * Expects to be passed the T_STRING stack pointer for the function call.
     * If passed a T_STRING which is *not* a function call, the behaviour is unreliable.
     *
     * Will return an multi-dimentional array with the start token pointer, end token
     * pointer and raw parameter value for all parameters. Index will be 1-based.
     * If no parameters are found, will return an empty array.
     *
     * @param PHP_CodeSniffer_File $phpcsFile     The file being scanned.
     * @param int                  $stackPtr      The position of the function call token.
     *
     * @return array
     */
    public function getFunctionCallParameters(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->doesFunctionCallHaveParameters($phpcsFile, $stackPtr) === false) {
            return array();
        }

        // Ok, we know we have a T_STRING with parameters and valid open & close parenthesis.
        $tokens = $phpcsFile->getTokens();

        $openParenthesis  = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        $closeParenthesis = $tokens[$openParenthesis]['parenthesis_closer'];

        // Which nesting level is the one we are interested in ?
        $nestedParenthesisCount = 1;
        if (isset($tokens[$openParenthesis]['nested_parenthesis'])) {
            $nestedParenthesisCount = count($tokens[$openParenthesis]['nested_parenthesis']) + 1;
        }

        $parameters = array();
        $nextComma  = $openParenthesis;
        $paramStart = $openParenthesis + 1;
        $cnt        = 1;
        while ($nextComma = $phpcsFile->findNext(array(T_COMMA, T_CLOSE_PARENTHESIS, T_OPEN_SHORT_ARRAY), $nextComma + 1, $closeParenthesis + 1)) {
            // Ignore anything within short array definition brackets.
            if (
                $tokens[$nextComma]['type'] === 'T_OPEN_SHORT_ARRAY'
                &&
                ( isset($tokens[$nextComma]['bracket_opener']) && $tokens[$nextComma]['bracket_opener'] === $nextComma )
                &&
                isset($tokens[$nextComma]['bracket_closer'])
            ) {
                // Skip forward to the end of the short array definition.
                $nextComma = $tokens[$nextComma]['bracket_closer'];
                continue;
            }

            // Ignore comma's at a lower nesting level.
            if (
                $tokens[$nextComma]['type'] === 'T_COMMA'
                &&
                isset($tokens[$nextComma]['nested_parenthesis'])
                &&
                count($tokens[$nextComma]['nested_parenthesis']) !== $nestedParenthesisCount
            ) {
                continue;
            }

            // Ignore closing parenthesis if not 'ours'.
            if ($tokens[$nextComma]['type'] === 'T_CLOSE_PARENTHESIS' && $nextComma !== $closeParenthesis) {
                continue;
            }

            // Ok, we've reached the end of the parameter.
            $parameters[$cnt]['start'] = $paramStart;
            $parameters[$cnt]['end']   = $nextComma - 1;
            $parameters[$cnt]['raw']   = trim($phpcsFile->getTokensAsString($paramStart, ($nextComma - $paramStart)));

            // Check if there are more tokens before the closing parenthesis.
            // Prevents code like the following from setting a third parameter:
            // functionCall( $param1, $param2, );
            $hasNextParam = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $nextComma + 1, $closeParenthesis, true, null, true);
            if ($hasNextParam === false) {
                break;
            }

            // Prepare for the next parameter.
            $paramStart = $nextComma + 1;
            $cnt++;
        }

        return $parameters;
    }


    /**
     * Get information on a specific parameter passed to a function call.
     *
     * Expects to be passed the T_STRING stack pointer for the function call.
     * If passed a T_STRING which is *not* a function call, the behaviour is unreliable.
     *
     * Will return a array with the start token pointer, end token pointer and the raw value
     * of the parameter at a specific offset.
     * If the specified parameter is not found, will return false.
     *
     * @param PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                  $stackPtr    The position of the function call token.
     * @param int                  $paramOffset The 1-based index position of the parameter to retrieve.
     *
     * @return array|false
     */
    public function getFunctionCallParameter(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $paramOffset)
    {
        $parameters = $this->getFunctionCallParameters($phpcsFile, $stackPtr);

        if (isset($parameters[$paramOffset]) === false) {
            return false;
        }
        else {
            return $parameters[$paramOffset];
        }
    }


    /**
     * Verify whether a token is within a scoped condition.
     *
     * If the optional $validScopes parameter has been passed, the function
     * will check that the token has at least one condition which is of a
     * type defined in $validScopes.
     *
     * @param PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                  $stackPtr    The position of the token.
     * @param array|int            $validScopes Optional. Array of valid scopes
     *                                          or int value of a valid scope.
     *                                          Pass the T_.. constant(s) for the
     *                                          desired scope to this parameter.
     *
     * @return bool Without the optional $scopeTypes: True if within a scope, false otherwise.
     *              If the $scopeTypes are set: True if *one* of the conditions is a
     *              valid scope, false otherwise.
     */
    public function tokenHasScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $validScopes = null)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        // No conditions = no scope.
        if (empty($tokens[$stackPtr]['conditions'])) {
            return false;
        }

        // Ok, there are conditions, do we have to check for specific ones ?
        if (isset($validScopes) === false) {
            return true;
        }

        if (is_int($validScopes)) {
            // Received an integer, so cast to array.
            $validScopes = (array) $validScopes;
        }

        if (empty($validScopes) || is_array($validScopes) === false) {
            // No valid scope types received, so will not comply.
            return false;
        }

        // Check for required scope types.
        foreach ($tokens[$stackPtr]['conditions'] as $pointer => $tokenCode) {
            if (in_array($tokenCode, $validScopes, true)) {
                return true;
            }
        }

        return false;
    }


    /**
     * Verify whether a token is within a class scope.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the token.
     * @param bool                 $strict    Whether to strictly check for the T_CLASS
     *                                        scope or also accept interfaces and traits
     *                                        as scope.
     *
     * @return bool True if within class scope, false otherwise.
     */
    public function inClassScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $strict = true)
    {
        $validScopes = array(T_CLASS);
        if ($strict === false) {
            $validScopes[] = T_INTERFACE;
            $validScopes[] = T_TRAIT;
        }

        return $this->tokenHasScope($phpcsFile, $stackPtr, $validScopes);
    }


    /**
     * Verify whether a token is within a scoped use statement.
     *
     * PHPCS cross-version compatibility method.
     *
     * In PHPCS 1.x no conditions are set for a scoped use statement.
     * This method works around that limitation.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the token.
     *
     * @return bool True if within use scope, false otherwise.
     */
    public function inUseScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        static $isLowPHPCS, $ignoreTokens;

        if (isset($isLowPHPCS) === false) {
            $isLowPHPCS = version_compare(PHP_CodeSniffer::VERSION, '2.0', '<');
        }
        if (isset($ignoreTokens) === false) {
            $ignoreTokens              = PHP_CodeSniffer_Tokens::$emptyTokens;
            $ignoreTokens[T_STRING]    = T_STRING;
            $ignoreTokens[T_AS]        = T_AS;
            $ignoreTokens[T_PUBLIC]    = T_PUBLIC;
            $ignoreTokens[T_PROTECTED] = T_PROTECTED;
            $ignoreTokens[T_PRIVATE]   = T_PRIVATE;
        }

        // PHPCS 2.0.
        if ($isLowPHPCS === false) {
            return $this->tokenHasScope($phpcsFile, $stackPtr, T_USE);
        } else {
            // PHPCS 1.x.
            $tokens         = $phpcsFile->getTokens();
            $maybeCurlyOpen = $phpcsFile->findPrevious($ignoreTokens, ($stackPtr - 1), null, true);
            if ($tokens[$maybeCurlyOpen]['code'] === T_OPEN_CURLY_BRACKET) {
                $maybeUseStatement = $phpcsFile->findPrevious($ignoreTokens, ($maybeCurlyOpen - 1), null, true);
                if ($tokens[$maybeUseStatement]['code'] === T_USE) {
                    return true;
                }
            }
            return false;
        }
    }


    /**
     * Returns the fully qualified class name for a new class instantiation.
     *
     * Returns an empty string if the class name could not be reliably inferred.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of a T_NEW token.
     *
     * @return string
     */
    public function getFQClassNameFromNewToken(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== T_NEW) {
            return '';
        }

        $find = array(
                 T_NS_SEPARATOR,
                 T_STRING,
                 T_NAMESPACE,
                 T_WHITESPACE,
                );

        $start = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        // Bow out if the next token is a variable as we don't know where it was defined.
        if ($tokens[$start]['code'] === T_VARIABLE) {
            return '';
        }

        $end       = $phpcsFile->findNext($find, ($start + 1), null, true, null, true);
        $className = $phpcsFile->getTokensAsString($start, ($end - $start));
        $className = trim($className);

        return $this->getFQName($phpcsFile, $stackPtr, $className);
    }


    /**
     * Returns the fully qualified name of the class that the specified class extends.
     *
     * Returns an empty string if the class does not extend another class or if
     * the class name could not be reliably inferred.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of a T_CLASS token.
     *
     * @return string
     */
    public function getFQExtendedClassName(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== T_CLASS) {
            return '';
        }

        $extends = $phpcsFile->findExtendedClassName($stackPtr);
        if (empty($extends) || is_string($extends) === false) {
            return '';
        }

        return $this->getFQName($phpcsFile, $stackPtr, $extends);
    }


    /**
     * Returns the class name for the static usage of a class.
     * This can be a call to a method, the use of a property or constant.
     *
     * Returns an empty string if the class name could not be reliably inferred.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of a T_NEW token.
     *
     * @return string
     */
    public function getFQClassNameFromDoubleColonToken(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== T_DOUBLE_COLON) {
            return '';
        }

        // Nothing to do if previous token is a variable as we don't know where it was defined.
        if ($tokens[$stackPtr - 1]['code'] === T_VARIABLE) {
            return '';
        }

        // Nothing to do if 'parent' or 'static' as we don't know how far the class tree extends.
        if (in_array($tokens[$stackPtr - 1]['code'], array(T_PARENT, T_STATIC), true)) {
            return '';
        }

        // Get the classname from the class declaration if self is used.
        if ($tokens[$stackPtr - 1]['code'] === T_SELF) {
            $classDeclarationPtr = $phpcsFile->findPrevious(T_CLASS, $stackPtr - 1);
            if ($classDeclarationPtr === false) {
                return '';
            }
            $className = $phpcsFile->getDeclarationName($classDeclarationPtr);
            return $this->getFQName($phpcsFile, $classDeclarationPtr, $className);
        }

        $find = array(
                 T_NS_SEPARATOR,
                 T_STRING,
                 T_NAMESPACE,
                 T_WHITESPACE,
                );

        $start     = ($phpcsFile->findPrevious($find, $stackPtr - 1, null, true, null, true) + 1);
        $className = $phpcsFile->getTokensAsString($start, ($stackPtr - $start));
        $className = trim($className);

        return $this->getFQName($phpcsFile, $stackPtr, $className);
    }


    /**
     * Get the Fully Qualified name for a class/function/constant etc.
     *
     * Checks if a class/function/constant name is already fully qualified and
     * if not, enrich it with the relevant namespace information.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the token.
     * @param string               $name      The class / function / constant name.
     *
     * @return string
     */
    public function getFQName(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $name)
    {
        if (strpos($name, '\\' ) === 0) {
            // Already fully qualified.
            return $name;
        }

        // Remove the namespace keyword if used.
        if (strpos($name, 'namespace\\') === 0) {
            $name = substr($name, 10);
        }

        $namespace = $this->determineNamespace($phpcsFile, $stackPtr);

        if ($namespace === '') {
            return '\\' . $name;
        }
        else {
            return '\\' . $namespace . '\\' . $name;
        }
    }


    /**
     * Is the class/function/constant name namespaced or global ?
     *
     * @param string $FQName Fully Qualified name of a class, function etc.
     *                       I.e. should always start with a `\` !
     *
     * @return bool True if namespaced, false if global.
     */
    public function isNamespaced($FQName) {
        if (strpos($FQName, '\\') !== 0) {
            throw new PHP_CodeSniffer_Exception('$FQName must be a fully qualified name');
        }

        return (strpos(substr($FQName, 1), '\\') !== false);
    }


    /**
     * Determine the namespace name an arbitrary token lives in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                  $stackPtr  The token position for which to determine the namespace.
     *
     * @return string Namespace name or empty string if it couldn't be determined or no namespace applies.
     */
    public function determineNamespace(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        // Check for scoped namespace {}.
        if (empty($tokens[$stackPtr]['conditions']) === false) {
            foreach ($tokens[$stackPtr]['conditions'] as $pointer => $type) {
                if ($type === T_NAMESPACE) {
                    $namespace = $this->getDeclaredNamespaceName($phpcsFile, $pointer);
                    if ($namespace !== false) {
                        return $namespace;
                    }
                    break; // Nested namespaces is not possible.
                }
            }
        }

        /*
         * Not in a scoped namespace, so let's see if we can find a non-scoped namespace instead.
         * Keeping in mind that:
         * - there can be multiple non-scoped namespaces in a file (bad practice, but it happens).
         * - the namespace keyword can also be used as part of a function/method call and such.
         * - that a non-named namespace resolves to the global namespace.
         */
        $previousNSToken = $stackPtr;
        $namespace       = false;
        do {
            $previousNSToken = $phpcsFile->findPrevious(T_NAMESPACE, $previousNSToken -1);

            // Stop if we encounter a scoped namespace declaration as we already know we're not in one.
            if (empty($tokens[$previousNSToken]['scope_condition']) === false && $tokens[$previousNSToken]['scope_condition'] = $previousNSToken) {
                break;
            }
            $namespace = $this->getDeclaredNamespaceName($phpcsFile, $previousNSToken);

        } while ($namespace === false && $previousNSToken !== false);

        // If we still haven't got a namespace, return an empty string.
        if ($namespace === false) {
            return '';
        }
        else {
            return $namespace;
        }
    }

    /**
     * Get the complete namespace name for a namespace declaration.
     *
     * For hierarchical namespaces, the name will be composed of several tokens,
     * i.e. MyProject\Sub\Level which will be returned together as one string.
     *
     * @param PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int|bool             $stackPtr  The position of a T_NAMESPACE token.
     *
     * @return string|false Namespace name or false if not a namespace declaration.
     *                      Namespace name can be an empty string for global namespace declaration.
     */
    public function getDeclaredNamespaceName(PHP_CodeSniffer_File $phpcsFile, $stackPtr )
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if ($stackPtr === false || isset($tokens[$stackPtr]) === false) {
            return false;
        }

        if ($tokens[$stackPtr]['code'] !== T_NAMESPACE) {
            return false;
        }

        if ($tokens[$stackPtr + 1]['code'] === T_NS_SEPARATOR) {
            // Not a namespace declaration, but use of, i.e. namespace\someFunction();
            return false;
        }

        $nextToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        if ($tokens[$nextToken]['code'] === T_OPEN_CURLY_BRACKET) {
            // Declaration for global namespace when using multiple namespaces in a file.
            // I.e.: namespace {}
            return '';
        }

        // Ok, this should be a namespace declaration, so get all the parts together.
        $validTokens = array(
                        T_STRING,
                        T_NS_SEPARATOR,
                        T_WHITESPACE,
                       );

        $namespaceName = '';
        while(in_array($tokens[$nextToken]['code'], $validTokens, true) === true) {
            $namespaceName .= trim($tokens[$nextToken]['content']);
            $nextToken++;
        }

        return $namespaceName;
    }


    /**
     * Returns the method parameters for the specified T_FUNCTION token.
     *
     * Each parameter is in the following format:
     *
     * <code>
     *   0 => array(
     *         'name'              => '$var',  // The variable name.
     *         'pass_by_reference' => false,   // Passed by reference.
     *         'type_hint'         => string,  // Type hint for array or custom type
     *         'nullable_type'     => bool,    // Whether the type given in the type hint is nullable
     *         'type_hint'         => string,  // Type hint for array or custom type
     *         'raw'               => string,  // Raw content of the tokens for the parameter
     *        )
     * </code>
     *
     * Parameters with default values have an additional array index of
     * 'default' with the value of the default as a string.
     *
     * {@internal Duplicate of same method as contained in the `PHP_CodeSniffer_File`
     * class, but with some improvements which will probably be introduced in
     * PHPCS 2.7.1/2.8. {@see https://github.com/squizlabs/PHP_CodeSniffer/pull/1117}
     * and {@see https://github.com/squizlabs/PHP_CodeSniffer/pull/1193}
     *
     * Once the minimum supported PHPCS version for this sniff library goes beyond
     * that, this method can be removed and calls to it replaced with
     * `$phpcsFile->getMethodParameters($stackPtr)` calls.
     *
     * Last synced with PHPCS version: PHPCS 2.7.}}
     *
     * @param PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                  $stackPtr  The position in the stack of the
     *                                        T_FUNCTION token to acquire the
     *                                        parameters for.
     *
     * @return array|false
     * @throws PHP_CodeSniffer_Exception If the specified $stackPtr is not of
     *                                   type T_FUNCTION.
     */
    public function getMethodParameters(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        if ($tokens[$stackPtr]['code'] !== T_FUNCTION) {
            throw new PHP_CodeSniffer_Exception('$stackPtr must be of type T_FUNCTION');
        }

        $opener = $tokens[$stackPtr]['parenthesis_opener'];
        $closer = $tokens[$stackPtr]['parenthesis_closer'];

        $vars            = array();
        $currVar         = null;
        $paramStart      = ($opener + 1);
        $defaultStart    = null;
        $paramCount      = 0;
        $passByReference = false;
        $variableLength  = false;
        $typeHint        = '';
        $nullableType    = false;

        for ($i = $paramStart; $i <= $closer; $i++) {
            // Check to see if this token has a parenthesis or bracket opener. If it does
            // it's likely to be an array which might have arguments in it. This
            // could cause problems in our parsing below, so lets just skip to the
            // end of it.
            if (isset($tokens[$i]['parenthesis_opener']) === true) {
                // Don't do this if it's the close parenthesis for the method.
                if ($i !== $tokens[$i]['parenthesis_closer']) {
                    $i = ($tokens[$i]['parenthesis_closer'] + 1);
                }
            }

            if (isset($tokens[$i]['bracket_opener']) === true) {
                // Don't do this if it's the close parenthesis for the method.
                if ($i !== $tokens[$i]['bracket_closer']) {
                    $i = ($tokens[$i]['bracket_closer'] + 1);
                }
            }

            switch ($tokens[$i]['code']) {
            case T_BITWISE_AND:
                $passByReference = true;
                break;
            case T_VARIABLE:
                $currVar = $i;
                break;
            case T_ELLIPSIS:
                $variableLength = true;
                break;
            case T_ARRAY_HINT:
            case T_CALLABLE:
                $typeHint = $tokens[$i]['content'];
                break;
            case T_SELF:
            case T_PARENT:
            case T_STATIC:
                // Self is valid, the others invalid, but were probably intended as type hints.
                if ($defaultStart === null) {
                    $typeHint = $tokens[$i]['content'];
                }
                break;
            case T_STRING:
                // This is a string, so it may be a type hint, but it could
                // also be a constant used as a default value.
                $prevComma = false;
                for ($t = $i; $t >= $opener; $t--) {
                    if ($tokens[$t]['code'] === T_COMMA) {
                        $prevComma = $t;
                        break;
                    }
                }

                if ($prevComma !== false) {
                    $nextEquals = false;
                    for ($t = $prevComma; $t < $i; $t++) {
                        if ($tokens[$t]['code'] === T_EQUAL) {
                            $nextEquals = $t;
                            break;
                        }
                    }

                    if ($nextEquals !== false) {
                        break;
                    }
                }

                if ($defaultStart === null) {
                    $typeHint .= $tokens[$i]['content'];
                }
                break;
            case T_NS_SEPARATOR:
                // Part of a type hint or default value.
                if ($defaultStart === null) {
                    $typeHint .= $tokens[$i]['content'];
                }
                break;
            case T_INLINE_THEN:
                if ($defaultStart === null) {
                    $nullableType = true;
                    $typeHint    .= $tokens[$i]['content'];
                }
                break;
            case T_CLOSE_PARENTHESIS:
            case T_COMMA:
                // If it's null, then there must be no parameters for this
                // method.
                if ($currVar === null) {
                    continue;
                }

                $vars[$paramCount]         = array();
                $vars[$paramCount]['name'] = $tokens[$currVar]['content'];

                if ($defaultStart !== null) {
                    $vars[$paramCount]['default']
                        = $phpcsFile->getTokensAsString(
                            $defaultStart,
                            ($i - $defaultStart)
                        );
                }

                $rawContent = trim($phpcsFile->getTokensAsString($paramStart, ($i - $paramStart)));

                $vars[$paramCount]['pass_by_reference'] = $passByReference;
                $vars[$paramCount]['variable_length']   = $variableLength;
                $vars[$paramCount]['type_hint']         = $typeHint;
                $vars[$paramCount]['nullable_type']     = $nullableType;
                $vars[$paramCount]['raw']               = $rawContent;

                // Reset the vars, as we are about to process the next parameter.
                $defaultStart    = null;
                $paramStart      = ($i + 1);
                $passByReference = false;
                $variableLength  = false;
                $typeHint        = '';
                $nullableType    = false;

                $paramCount++;
                break;
            case T_EQUAL:
                $defaultStart = ($i + 1);
                break;
            }//end switch
        }//end for

        return $vars;

    }//end getMethodParameters()


    /**
     * Get the hash algorithm name from the parameter in a hash function call.
     *
     * @param PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                  $stackPtr  The position of the T_STRING function token.
     *
     * @return string|false The algorithm name without quotes if this was a relevant hash
     *                      function call or false if it was not.
     */
    public function getHashAlgorithmParameter(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        if ($tokens[$stackPtr]['code'] !== T_STRING) {
            return false;
        }

        $functionName   = $tokens[$stackPtr]['content'];
        $functionNameLc = strtolower($functionName);

        // Bow out if not one of the functions we're targetting.
        if (isset($this->hashAlgoFunctions[$functionNameLc]) === false) {
            return false;
        }

        // Get the parameter from the function call which should contain the algorithm name.
        $algoParam = $this->getFunctionCallParameter($phpcsFile, $stackPtr, $this->hashAlgoFunctions[$functionNameLc]);
        if ($algoParam === false) {
            return false;
        }

        /**
         * Algorithm is a T_CONSTANT_ENCAPSED_STRING, so we need to remove the quotes.
         */
        $algo = strtolower(trim($algoParam['raw']));
        $algo = $this->stripQuotes($algo);

        return $algo;
    }

}//end class
