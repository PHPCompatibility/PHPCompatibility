<?php
/**
 * \PHPCompatibility\Sniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2014 Cu.be Solutions bvba
 */

namespace PHPCompatibility;

use PHPCompatibility\PHPCSHelper;
use PHPCompatibility\Util\TestVersionException;

/**
 * \PHPCompatibility\Sniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2014 Cu.be Solutions bvba
 */
abstract class Sniff implements \PHP_CodeSniffer_Sniff
{

    const REGEX_COMPLEX_VARS = '`(?:(\{)?(?<!\\\\)\$)?(\{)?(?<!\\\\)\$(\{)?(?P<varname>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(?:->\$?(?P>varname)|\[[^\]]+\]|::\$?(?P>varname)|\([^\)]*\))*(?(3)\}|)(?(2)\}|)(?(1)\}|)`';

    /**
     * List of superglobals as an array of strings.
     *
     * Used by the ParameterShadowSuperGlobals and ForbiddenClosureUseVariableNames sniffs.
     *
     * @var array
     */
    protected $superglobals = array(
        '$GLOBALS'  => true,
        '$_SERVER'  => true,
        '$_GET'     => true,
        '$_POST'    => true,
        '$_FILES'   => true,
        '$_COOKIE'  => true,
        '$_SESSION' => true,
        '$_REQUEST' => true,
        '$_ENV'     => true,
    );

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


    /**
     * List of functions which take an ini directive as parameter (always the first parameter).
     *
     * Used by the new/removed ini directives sniffs.
     * Key is the function name, value is the 1-based parameter position in the function call.
     *
     * @var array
     */
    protected $iniFunctions = array(
        'ini_get' => 1,
        'ini_set' => 1,
    );


    /**
     * Get the testVersion configuration variable.
     *
     * The testVersion configuration variable may be in any of the following formats:
     * 1) Omitted/empty, in which case no version is specified. This effectively
     *    disables all the checks for new PHP features provided by this standard.
     * 2) A single PHP version number, e.g. "5.4" in which case the standard checks that
     *    the code will run on that version of PHP (no deprecated features or newer
     *    features being used).
     * 3) A range, e.g. "5.0-5.5", in which case the standard checks the code will run
     *    on all PHP versions in that range, and that it doesn't use any features that
     *    were deprecated by the final version in the list, or which were not available
     *    for the first version in the list.
     *    We accept ranges where one of the components is missing, e.g. "-5.6" means
     *    all versions up to PHP 5.6, and "7.0-" means all versions above PHP 7.0.
     * PHP version numbers should always be in Major.Minor format.  Both "5", "5.3.2"
     * would be treated as invalid, and ignored.
     *
     * @return array $arrTestVersions will hold an array containing min/max version
     *               of PHP that we are checking against (see above).  If only a
     *               single version number is specified, then this is used as
     *               both the min and max.
     *
     * @throws \PHP_CodeSniffer_Exception If testVersion is invalid.
     */
    private function getTestVersion()
    {
        static $arrTestVersions = array();

        $default     = array(null, null);
        $testVersion = trim(PHPCSHelper::getConfigData('testVersion'));

        if (empty($testVersion) === false && isset($arrTestVersions[$testVersion]) === false) {

            $arrTestVersions[$testVersion] = $default;

            if (preg_match('`^\d+\.\d+$`', $testVersion)) {
                $arrTestVersions[$testVersion] = array($testVersion, $testVersion);
                return $arrTestVersions[$testVersion];
            }

            if (preg_match('`^(\d+\.\d+)?\s*-\s*(\d+\.\d+)?$`', $testVersion, $matches)) {
                if (empty($matches[1]) === false || empty($matches[2]) === false) {
                    // If no lower-limit is set, we set the min version to 4.0.
                    // Whilst development focuses on PHP 5 and above, we also accept
                    // sniffs for PHP 4, so we include that as the minimum.
                    // (It makes no sense to support PHP 3 as this was effectively a
                    // different language).
                    $min = empty($matches[1]) ? '4.0' : $matches[1];

                    // If no upper-limit is set, we set the max version to 99.9.
                    $max = empty($matches[2]) ? '99.9' : $matches[2];

                    if (version_compare($min, $max, '>')) {
                        throw new TestVersionException(
                            "Invalid range in testVersion setting: '" . $testVersion . "'"
                        );
                        return $default;
                    } else {
                        $arrTestVersions[$testVersion] = array($min, $max);
                        return $arrTestVersions[$testVersion];
                    }
                }
            }

            throw new TestVersionException(
                "Invalid testVersion setting: '" . $testVersion . "'"
            );
            return $default;
        }

        if (isset($arrTestVersions[$testVersion])) {
            return $arrTestVersions[$testVersion];
        }

        return $default;
    }


    /**
     * Check whether a specific PHP version is equal to or higher than the maximum
     * supported PHP version as provided by the user in `testVersion`.
     *
     * Should be used when sniffing for *old* PHP features (deprecated/removed).
     *
     * @param string $phpVersion A PHP version number in 'major.minor' format.
     *
     * @return bool True if testVersion has not been provided or if the PHP version
     *              is equal to or higher than the highest supported PHP version
     *              in testVersion. False otherwise.
     */
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


    /**
     * Check whether a specific PHP version is equal to or lower than the minimum
     * supported PHP version as provided by the user in `testVersion`.
     *
     * Should be used when sniffing for *new* PHP features.
     *
     * @param string $phpVersion A PHP version number in 'major.minor' format.
     *
     * @return bool True if the PHP version is equal to or lower than the lowest
     *              supported PHP version in testVersion.
     *              False otherwise or if no testVersion is provided.
     */
    public function supportsBelow($phpVersion)
    {
        $testVersion = $this->getTestVersion();
        $testVersion = $testVersion[0];

        if (is_null($testVersion) === false
            && version_compare($testVersion, $phpVersion) <= 0
        ) {
            return true;
        } else {
            return false;
        }
    }//end supportsBelow()


    /**
     * Add a PHPCS message to the output stack as either a warning or an error.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file the message applies to.
     * @param string                $message   The message.
     * @param int                   $stackPtr  The position of the token
     *                                         the message relates to.
     * @param bool                  $isError   Whether to report the message as an
     *                                         'error' or 'warning'.
     *                                         Defaults to true (error).
     * @param string                $code      The error code for the message.
     *                                         Defaults to 'Found'.
     * @param array                 $data      Optional input for the data replacements.
     *
     * @return void
     */
    public function addMessage(\PHP_CodeSniffer_File $phpcsFile, $message, $stackPtr, $isError, $code = 'Found', $data = array())
    {
        if ($isError === true) {
            $phpcsFile->addError($message, $stackPtr, $code, $data);
        } else {
            $phpcsFile->addWarning($message, $stackPtr, $code, $data);
        }
    }


    /**
     * Convert an arbitrary string to an alphanumeric string with underscores.
     *
     * Pre-empt issues with arbitrary strings being used as error codes in XML and PHP.
     *
     * @param string $baseString Arbitrary string.
     *
     * @return string
     */
    public function stringToErrorCode($baseString)
    {
        return preg_replace('`[^a-z0-9_]`i', '_', strtolower($baseString));
    }


    /**
     * Strip quotes surrounding an arbitrary string.
     *
     * Intended for use with the contents of a T_CONSTANT_ENCAPSED_STRING / T_DOUBLE_QUOTED_STRING.
     *
     * @param string $string The raw string.
     *
     * @return string String without quotes around it.
     */
    public function stripQuotes($string)
    {
        return preg_replace('`^([\'"])(.*)\1$`Ds', '$2', $string);
    }


    /**
     * Strip variables from an arbitrary double quoted string.
     *
     * Intended for use with the contents of a T_DOUBLE_QUOTED_STRING.
     *
     * @param string $string The raw string.
     *
     * @return string String without variables in it.
     */
    public function stripVariables($string)
    {
        if (strpos($string, '$') === false) {
            return $string;
        }

        return preg_replace(self::REGEX_COMPLEX_VARS, '', $string);
    }


    /**
     * Make all top level array keys in an array lowercase.
     *
     * @param array $array Initial array.
     *
     * @return array Same array, but with all lowercase top level keys.
     */
    public function arrayKeysToLowercase($array)
    {
        $keys = array_keys($array);
        $keys = array_map('strtolower', $keys);
        return array_combine($keys, $array);
    }


    /**
     * Checks if a function call has parameters.
     *
     * Expects to be passed the T_STRING or T_VARIABLE stack pointer for the function call.
     * If passed a T_STRING which is *not* a function call, the behaviour is unreliable.
     *
     * Extra feature: If passed an T_ARRAY or T_OPEN_SHORT_ARRAY stack pointer, it
     * will detect whether the array has values or is empty.
     *
     * @link https://github.com/PHPCompatibility/PHPCompatibility/issues/120
     * @link https://github.com/PHPCompatibility/PHPCompatibility/issues/152
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the function call token.
     *
     * @return bool
     */
    public function doesFunctionCallHaveParameters(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        // Is this one of the tokens this function handles ?
        if (in_array($tokens[$stackPtr]['code'], array(T_STRING, T_ARRAY, T_OPEN_SHORT_ARRAY, T_VARIABLE), true) === false) {
            return false;
        }

        $nextNonEmpty = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);

        // Deal with short array syntax.
        if ($tokens[$stackPtr]['code'] === T_OPEN_SHORT_ARRAY) {
            if (isset($tokens[$stackPtr]['bracket_closer']) === false) {
                return false;
            }

            if ($nextNonEmpty === $tokens[$stackPtr]['bracket_closer']) {
                // No parameters.
                return false;
            } else {
                return true;
            }
        }

        // Deal with function calls & long arrays.
        // Next non-empty token should be the open parenthesis.
        if ($nextNonEmpty === false && $tokens[$nextNonEmpty]['code'] !== T_OPEN_PARENTHESIS) {
            return false;
        }

        if (isset($tokens[$nextNonEmpty]['parenthesis_closer']) === false) {
            return false;
        }

        $closeParenthesis = $tokens[$nextNonEmpty]['parenthesis_closer'];
        $nextNextNonEmpty = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, $nextNonEmpty + 1, $closeParenthesis + 1, true);

        if ($nextNextNonEmpty === $closeParenthesis) {
            // No parameters.
            return false;
        }

        return true;
    }


    /**
     * Count the number of parameters a function call has been passed.
     *
     * Expects to be passed the T_STRING or T_VARIABLE stack pointer for the function call.
     * If passed a T_STRING which is *not* a function call, the behaviour is unreliable.
     *
     * Extra feature: If passed an T_ARRAY or T_OPEN_SHORT_ARRAY stack pointer,
     * it will return the number of values in the array.
     *
     * @link https://github.com/PHPCompatibility/PHPCompatibility/issues/111
     * @link https://github.com/PHPCompatibility/PHPCompatibility/issues/114
     * @link https://github.com/PHPCompatibility/PHPCompatibility/issues/151
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the function call token.
     *
     * @return int
     */
    public function getFunctionCallParameterCount(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->doesFunctionCallHaveParameters($phpcsFile, $stackPtr) === false) {
            return 0;
        }

        return count($this->getFunctionCallParameters($phpcsFile, $stackPtr));
    }


    /**
     * Get information on all parameters passed to a function call.
     *
     * Expects to be passed the T_STRING or T_VARIABLE stack pointer for the function call.
     * If passed a T_STRING which is *not* a function call, the behaviour is unreliable.
     *
     * Will return an multi-dimentional array with the start token pointer, end token
     * pointer and raw parameter value for all parameters. Index will be 1-based.
     * If no parameters are found, will return an empty array.
     *
     * Extra feature: If passed an T_ARRAY or T_OPEN_SHORT_ARRAY stack pointer,
     * it will tokenize the values / key/value pairs contained in the array call.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the function call token.
     *
     * @return array
     */
    public function getFunctionCallParameters(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->doesFunctionCallHaveParameters($phpcsFile, $stackPtr) === false) {
            return array();
        }

        // Ok, we know we have a T_STRING, T_VARIABLE, T_ARRAY or T_OPEN_SHORT_ARRAY with parameters
        // and valid open & close brackets/parenthesis.
        $tokens = $phpcsFile->getTokens();

        // Mark the beginning and end tokens.
        if ($tokens[$stackPtr]['code'] === T_OPEN_SHORT_ARRAY) {
            $opener = $stackPtr;
            $closer = $tokens[$stackPtr]['bracket_closer'];

            $nestedParenthesisCount = 0;

        } else {
            $opener = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
            $closer = $tokens[$opener]['parenthesis_closer'];

            $nestedParenthesisCount = 1;
        }

        // Which nesting level is the one we are interested in ?
        if (isset($tokens[$opener]['nested_parenthesis'])) {
            $nestedParenthesisCount += count($tokens[$opener]['nested_parenthesis']);
        }

        $parameters = array();
        $nextComma  = $opener;
        $paramStart = $opener + 1;
        $cnt        = 1;
        while (($nextComma = $phpcsFile->findNext(array(T_COMMA, $tokens[$closer]['code'], T_OPEN_SHORT_ARRAY, T_CLOSURE), $nextComma + 1, $closer + 1)) !== false) {
            // Ignore anything within short array definition brackets.
            if ($tokens[$nextComma]['type'] === 'T_OPEN_SHORT_ARRAY'
                && (isset($tokens[$nextComma]['bracket_opener'])
                    && $tokens[$nextComma]['bracket_opener'] === $nextComma)
                && isset($tokens[$nextComma]['bracket_closer'])
            ) {
                // Skip forward to the end of the short array definition.
                $nextComma = $tokens[$nextComma]['bracket_closer'];
                continue;
            }

            // Skip past closures passed as function parameters.
            if ($tokens[$nextComma]['type'] === 'T_CLOSURE'
                && (isset($tokens[$nextComma]['scope_condition'])
                    && $tokens[$nextComma]['scope_condition'] === $nextComma)
                && isset($tokens[$nextComma]['scope_closer'])
            ) {
                // Skip forward to the end of the closure declaration.
                $nextComma = $tokens[$nextComma]['scope_closer'];
                continue;
            }

            // Ignore comma's at a lower nesting level.
            if ($tokens[$nextComma]['type'] === 'T_COMMA'
                && isset($tokens[$nextComma]['nested_parenthesis'])
                && count($tokens[$nextComma]['nested_parenthesis']) !== $nestedParenthesisCount
            ) {
                continue;
            }

            // Ignore closing parenthesis/bracket if not 'ours'.
            if ($tokens[$nextComma]['type'] === $tokens[$closer]['type'] && $nextComma !== $closer) {
                continue;
            }

            // Ok, we've reached the end of the parameter.
            $parameters[$cnt]['start'] = $paramStart;
            $parameters[$cnt]['end']   = $nextComma - 1;
            $parameters[$cnt]['raw']   = trim($phpcsFile->getTokensAsString($paramStart, ($nextComma - $paramStart)));

            // Check if there are more tokens before the closing parenthesis.
            // Prevents code like the following from setting a third parameter:
            // functionCall( $param1, $param2, );
            $hasNextParam = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, $nextComma + 1, $closer, true, null, true);
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
     * Expects to be passed the T_STRING or T_VARIABLE stack pointer for the function call.
     * If passed a T_STRING which is *not* a function call, the behaviour is unreliable.
     *
     * Will return a array with the start token pointer, end token pointer and the raw value
     * of the parameter at a specific offset.
     * If the specified parameter is not found, will return false.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                   $stackPtr    The position of the function call token.
     * @param int                   $paramOffset The 1-based index position of the parameter to retrieve.
     *
     * @return array|false
     */
    public function getFunctionCallParameter(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, $paramOffset)
    {
        $parameters = $this->getFunctionCallParameters($phpcsFile, $stackPtr);

        if (isset($parameters[$paramOffset]) === false) {
            return false;
        } else {
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
     * @param \PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                   $stackPtr    The position of the token.
     * @param array|int             $validScopes Optional. Array of valid scopes
     *                                           or int value of a valid scope.
     *                                           Pass the T_.. constant(s) for the
     *                                           desired scope to this parameter.
     *
     * @return bool Without the optional $scopeTypes: True if within a scope, false otherwise.
     *              If the $scopeTypes are set: True if *one* of the conditions is a
     *              valid scope, false otherwise.
     */
    public function tokenHasScope(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, $validScopes = null)
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

        return $phpcsFile->hasCondition($stackPtr, $validScopes);
    }


    /**
     * Verify whether a token is within a class scope.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the token.
     * @param bool                  $strict    Whether to strictly check for the T_CLASS
     *                                         scope or also accept interfaces and traits
     *                                         as scope.
     *
     * @return bool True if within class scope, false otherwise.
     */
    public function inClassScope(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, $strict = true)
    {
        $validScopes = array(T_CLASS);
        if (defined('T_ANON_CLASS') === true) {
            $validScopes[] = T_ANON_CLASS;
        }

        if ($strict === false) {
            $validScopes[] = T_INTERFACE;
            $validScopes[] = T_TRAIT;
        }

        return $phpcsFile->hasCondition($stackPtr, $validScopes);
    }


    /**
     * Returns the fully qualified class name for a new class instantiation.
     *
     * Returns an empty string if the class name could not be reliably inferred.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of a T_NEW token.
     *
     * @return string
     */
    public function getFQClassNameFromNewToken(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== T_NEW) {
            return '';
        }

        $start = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        if ($start === false) {
            return '';
        }

        // Bow out if the next token is a variable as we don't know where it was defined.
        if ($tokens[$start]['code'] === T_VARIABLE) {
            return '';
        }

        // Bow out if the next token is the class keyword.
        if ($tokens[$start]['type'] === 'T_ANON_CLASS' || $tokens[$start]['code'] === T_CLASS) {
            return '';
        }

        $find = array(
            T_NS_SEPARATOR,
            T_STRING,
            T_NAMESPACE,
            T_WHITESPACE,
        );

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
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of a T_CLASS token.
     *
     * @return string
     */
    public function getFQExtendedClassName(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== T_CLASS
            && $tokens[$stackPtr]['type'] !== 'T_ANON_CLASS'
            && $tokens[$stackPtr]['type'] !== 'T_INTERFACE'
        ) {
            return '';
        }

        $extends = PHPCSHelper::findExtendedClassName($phpcsFile, $stackPtr);
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
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of a T_NEW token.
     *
     * @return string
     */
    public function getFQClassNameFromDoubleColonToken(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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

        $start = $phpcsFile->findPrevious($find, $stackPtr - 1, null, true, null, true);
        if ($start === false || isset($tokens[($start + 1)]) === false) {
            return '';
        }

        $start     = ($start + 1);
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
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the token.
     * @param string                $name      The class / function / constant name.
     *
     * @return string
     */
    public function getFQName(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, $name)
    {
        if (strpos($name, '\\') === 0) {
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
        } else {
            return '\\' . $namespace . '\\' . $name;
        }
    }


    /**
     * Is the class/function/constant name namespaced or global ?
     *
     * @param string $FQName Fully Qualified name of a class, function etc.
     *                       I.e. should always start with a `\`.
     *
     * @return bool True if namespaced, false if global.
     */
    public function isNamespaced($FQName)
    {
        if (strpos($FQName, '\\') !== 0) {
            throw new \PHP_CodeSniffer_Exception('$FQName must be a fully qualified name');
        }

        return (strpos(substr($FQName, 1), '\\') !== false);
    }


    /**
     * Determine the namespace name an arbitrary token lives in.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                   $stackPtr  The token position for which to determine the namespace.
     *
     * @return string Namespace name or empty string if it couldn't be determined or no namespace applies.
     */
    public function determineNamespace(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        // Check for scoped namespace {}.
        if (empty($tokens[$stackPtr]['conditions']) === false) {
            $namespacePtr = $phpcsFile->getCondition($stackPtr, T_NAMESPACE);
            if ($namespacePtr !== false) {
                $namespace = $this->getDeclaredNamespaceName($phpcsFile, $namespacePtr);
                if ($namespace !== false) {
                    return $namespace;
                }

                // We are in a scoped namespace, but couldn't determine the name. Searching for a global namespace is futile.
                return '';
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
            $previousNSToken = $phpcsFile->findPrevious(T_NAMESPACE, ($previousNSToken - 1));

            // Stop if we encounter a scoped namespace declaration as we already know we're not in one.
            if (empty($tokens[$previousNSToken]['scope_condition']) === false && $tokens[$previousNSToken]['scope_condition'] === $previousNSToken) {
                break;
            }

            $namespace = $this->getDeclaredNamespaceName($phpcsFile, $previousNSToken);

        } while ($namespace === false && $previousNSToken !== false);

        // If we still haven't got a namespace, return an empty string.
        if ($namespace === false) {
            return '';
        } else {
            return $namespace;
        }
    }

    /**
     * Get the complete namespace name for a namespace declaration.
     *
     * For hierarchical namespaces, the name will be composed of several tokens,
     * i.e. MyProject\Sub\Level which will be returned together as one string.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int|bool              $stackPtr  The position of a T_NAMESPACE token.
     *
     * @return string|false Namespace name or false if not a namespace declaration.
     *                      Namespace name can be an empty string for global namespace declaration.
     */
    public function getDeclaredNamespaceName(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if ($stackPtr === false || isset($tokens[$stackPtr]) === false) {
            return false;
        }

        if ($tokens[$stackPtr]['code'] !== T_NAMESPACE) {
            return false;
        }

        if ($tokens[($stackPtr + 1)]['code'] === T_NS_SEPARATOR) {
            // Not a namespace declaration, but use of, i.e. namespace\someFunction();
            return false;
        }

        $nextToken = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true, null, true);
        if ($tokens[$nextToken]['code'] === T_OPEN_CURLY_BRACKET) {
            // Declaration for global namespace when using multiple namespaces in a file.
            // I.e.: namespace {}
            return '';
        }

        // Ok, this should be a namespace declaration, so get all the parts together.
        $validTokens = array(
            T_STRING       => true,
            T_NS_SEPARATOR => true,
            T_WHITESPACE   => true,
        );

        $namespaceName = '';
        while (isset($validTokens[$tokens[$nextToken]['code']]) === true) {
            $namespaceName .= trim($tokens[$nextToken]['content']);
            $nextToken++;
        }

        return $namespaceName;
    }


    /**
     * Get the stack pointer for a return type token for a given function.
     *
     * Compatible layer for older PHPCS versions which don't recognize
     * return type hints correctly.
     *
     * Expects to be passed T_RETURN_TYPE, T_FUNCTION or T_CLOSURE token.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the token.
     *
     * @return int|false Stack pointer to the return type token or false if
     *                   no return type was found or the passed token was
     *                   not of the correct type.
     */
    public function getReturnTypeHintToken(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (defined('T_RETURN_TYPE') && $tokens[$stackPtr]['code'] === T_RETURN_TYPE) {
            return $stackPtr;
        }

        if ($tokens[$stackPtr]['code'] !== T_FUNCTION && $tokens[$stackPtr]['code'] !== T_CLOSURE) {
            return false;
        }

        if (isset($tokens[$stackPtr]['parenthesis_closer']) === false) {
            return false;
        }

        // Allow for interface and abstract method declarations.
        $endOfFunctionDeclaration = null;
        if (isset($tokens[$stackPtr]['scope_opener'])) {
            $endOfFunctionDeclaration = $tokens[$stackPtr]['scope_opener'];
        } else {
            $nextSemiColon = $phpcsFile->findNext(T_SEMICOLON, ($tokens[$stackPtr]['parenthesis_closer'] + 1), null, false, null, true);
            if ($nextSemiColon !== false) {
                $endOfFunctionDeclaration = $nextSemiColon;
            }
        }

        if (isset($endOfFunctionDeclaration) === false) {
            return false;
        }

        $hasColon = $phpcsFile->findNext(
            array(T_COLON, T_INLINE_ELSE),
            ($tokens[$stackPtr]['parenthesis_closer'] + 1),
            $endOfFunctionDeclaration
        );
        if ($hasColon === false) {
            return false;
        }

        /*
         * - `self`, `parent` and `callable` are not being recognized as return types in PHPCS < 2.6.0.
         * - Return types are not recognized at all in PHPCS < 2.4.0.
         * - The T_RETURN_TYPE token is defined, but no longer in use since PHPCS 3.3.0+.
         *   The token will now be tokenized as T_STRING.
         * - An `array` (return) type declaration was tokenized as `T_ARRAY_HINT` in PHPCS 2.3.3 - 3.2.3
         *   to prevent confusing sniffs looking for array declarations.
         *   As of PHPCS 3.3.0 `array` as a type declaration will be tokenized as `T_STRING`.
         */
        $unrecognizedTypes = array(
            T_CALLABLE,
            T_SELF,
            T_PARENT,
            T_ARRAY, // PHPCS < 2.4.0.
            T_STRING,
        );

        return $phpcsFile->findPrevious($unrecognizedTypes, ($endOfFunctionDeclaration - 1), $hasColon);
    }


    /**
     * Get the complete return type declaration for a given function.
     *
     * Cross-version compatible way to retrieve the complete return type declaration.
     *
     * For a classname-based return type, PHPCS, as well as the Sniff::getReturnTypeHintToken()
     * method will mark the classname as the return type token.
     * This method will find preceeding namespaces and namespace separators and will return a
     * string containing the qualified return type declaration.
     *
     * Expects to be passed a T_RETURN_TYPE token or the return value from a call to
     * the Sniff::getReturnTypeHintToken() method.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the return type token.
     *
     * @return string|false The name of the return type token.
     */
    public function getReturnTypeHintName(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // In older PHPCS versions, the nullable indicator will turn a return type colon into a T_INLINE_ELSE.
        $colon = $phpcsFile->findPrevious(array(T_COLON, T_INLINE_ELSE, T_FUNCTION, T_CLOSE_PARENTHESIS), ($stackPtr - 1));
        if ($colon === false
            || ($tokens[$colon]['code'] !== T_COLON && $tokens[$colon]['code'] !== T_INLINE_ELSE)
        ) {
            // Shouldn't happen, just in case.
            return;
        }

        $returnTypeHint = '';
        for ($i = ($colon + 1); $i <= $stackPtr; $i++) {
            // As of PHPCS 3.3.0+, all tokens are tokenized as "normal", so T_CALLABLE, T_SELF etc are
            // all possible, just exclude anything that's regarded as empty and the nullable indicator.
            if (isset(\PHP_CodeSniffer_Tokens::$emptyTokens[$tokens[$i]['code']])) {
                continue;
            }

            if ($tokens[$i]['type'] === 'T_NULLABLE') {
                continue;
            }

            if (defined('T_NULLABLE') === false && $tokens[$i]['code'] === T_INLINE_THEN) {
                // Old PHPCS.
                continue;
            }

            $returnTypeHint .= $tokens[$i]['content'];
        }

        return $returnTypeHint;
    }


    /**
     * Check whether a T_VARIABLE token is a class property declaration.
     *
     * Compatibility layer for PHPCS cross-version compatibility
     * as PHPCS 2.4.0 - 2.7.1 does not have good enough support for
     * anonymous classes. Along the same lines, the`getMemberProperties()`
     * method does not support the `var` prefix.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                   $stackPtr  The position in the stack of the
     *                                         T_VARIABLE token to verify.
     *
     * @return bool
     */
    public function isClassProperty(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]) === false || $tokens[$stackPtr]['code'] !== T_VARIABLE) {
            return false;
        }

        // Note: interfaces can not declare properties.
        $validScopes = array(
            'T_CLASS'      => true,
            'T_ANON_CLASS' => true,
            'T_TRAIT'      => true,
        );
        if ($this->validDirectScope($phpcsFile, $stackPtr, $validScopes) === true) {
            // Make sure it's not a method parameter.
            if (empty($tokens[$stackPtr]['nested_parenthesis']) === true) {
                return true;
            }
        }

        return false;
    }


    /**
     * Check whether a T_CONST token is a class constant declaration.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                   $stackPtr  The position in the stack of the
     *                                         T_CONST token to verify.
     *
     * @return bool
     */
    public function isClassConstant(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]) === false || $tokens[$stackPtr]['code'] !== T_CONST) {
            return false;
        }

        // Note: traits can not declare constants.
        $validScopes = array(
            'T_CLASS'      => true,
            'T_ANON_CLASS' => true,
            'T_INTERFACE'  => true,
        );
        if ($this->validDirectScope($phpcsFile, $stackPtr, $validScopes) === true) {
            return true;
        }

        return false;
    }


    /**
     * Check whether the direct wrapping scope of a token is within a limited set of
     * acceptable tokens.
     *
     * Used to check, for instance, if a T_CONST is a class constant.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile   Instance of phpcsFile.
     * @param int                   $stackPtr    The position in the stack of the
     *                                           T_CONST token to verify.
     * @param array                 $validScopes Array of token types.
     *                                           Keys should be the token types in string
     *                                           format to allow for newer token types.
     *                                           Value is irrelevant.
     *
     * @return bool
     */
    protected function validDirectScope(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, $validScopes)
    {
        $tokens = $phpcsFile->getTokens();

        if (empty($tokens[$stackPtr]['conditions']) === true) {
            return false;
        }

        /*
         * Check only the direct wrapping scope of the token.
         */
        $conditions = array_keys($tokens[$stackPtr]['conditions']);
        $ptr        = array_pop($conditions);

        if (isset($tokens[$ptr]) === false) {
            return false;
        }

        if (isset($validScopes[$tokens[$ptr]['type']]) === true) {
            return true;
        }

        return false;
    }


    /**
     * Get an array of just the type hints from a function declaration.
     *
     * Expects to be passed T_FUNCTION or T_CLOSURE token.
     *
     * Strips potential nullable indicator and potential global namespace
     * indicator from the type hints before returning them.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the token.
     *
     * @return array Array with type hints or an empty array if
     *               - the function does not have any parameters
     *               - no type hints were found
     *               - or the passed token was not of the correct type.
     */
    public function getTypeHintsFromFunctionDeclaration(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['code'] !== T_FUNCTION && $tokens[$stackPtr]['code'] !== T_CLOSURE) {
            return array();
        }

        $parameters = PHPCSHelper::getMethodParameters($phpcsFile, $stackPtr);
        if (empty($parameters) || is_array($parameters) === false) {
            return array();
        }

        $typeHints = array();

        foreach ($parameters as $param) {
            if ($param['type_hint'] === '') {
                continue;
            }

            // Strip off potential nullable indication.
            $typeHint = ltrim($param['type_hint'], '?');

            // Strip off potential (global) namespace indication.
            $typeHint = ltrim($typeHint, '\\');

            if ($typeHint !== '') {
                $typeHints[] = $typeHint;
            }
        }

        return $typeHints;
    }


    /**
     * Get the hash algorithm name from the parameter in a hash function call.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                   $stackPtr  The position of the T_STRING function token.
     *
     * @return string|false The algorithm name without quotes if this was a relevant hash
     *                      function call or false if it was not.
     */
    public function getHashAlgorithmParameter(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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

        // Algorithm is a text string, so we need to remove the quotes.
        $algo = strtolower(trim($algoParam['raw']));
        $algo = $this->stripQuotes($algo);

        return $algo;
    }


    /**
     * Determine whether an arbitrary T_STRING token is the use of a global constant.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the function call token.
     *
     * @return bool
     */
    public function isUseOfGlobalConstant(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        static $isLowPHPCS, $isLowPHP;

        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        // Is this one of the tokens this function handles ?
        if ($tokens[$stackPtr]['code'] !== T_STRING) {
            return false;
        }

        // Check for older PHP, PHPCS version so we can compensate for misidentified tokens.
        if (isset($isLowPHPCS, $isLowPHP) === false) {
            $isLowPHP   = false;
            $isLowPHPCS = false;
            if (version_compare(PHP_VERSION_ID, '50400', '<')) {
                $isLowPHP   = true;
                $isLowPHPCS = version_compare(PHPCSHelper::getVersion(), '2.4.0', '<');
            }
        }

        $next = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($next !== false
            && ($tokens[$next]['code'] === T_OPEN_PARENTHESIS
                || $tokens[$next]['code'] === T_DOUBLE_COLON)
        ) {
            // Function call or declaration.
            return false;
        }

        // Array of tokens which if found preceding the $stackPtr indicate that a T_STRING is not a global constant.
        $tokensToIgnore = array(
            'T_NAMESPACE'       => true,
            'T_USE'             => true,
            'T_CLASS'           => true,
            'T_TRAIT'           => true,
            'T_INTERFACE'       => true,
            'T_EXTENDS'         => true,
            'T_IMPLEMENTS'      => true,
            'T_NEW'             => true,
            'T_FUNCTION'        => true,
            'T_DOUBLE_COLON'    => true,
            'T_OBJECT_OPERATOR' => true,
            'T_INSTANCEOF'      => true,
            'T_INSTEADOF'       => true,
            'T_GOTO'            => true,
            'T_AS'              => true,
            'T_PUBLIC'          => true,
            'T_PROTECTED'       => true,
            'T_PRIVATE'         => true,
        );

        $prev = $phpcsFile->findPrevious(\PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($prev !== false
            && (isset($tokensToIgnore[$tokens[$prev]['type']]) === true
                || ($tokens[$prev]['code'] === T_STRING
                    && (($isLowPHPCS === true
                        && $tokens[$prev]['content'] === 'trait')
                    || ($isLowPHP === true
                        && $tokens[$prev]['content'] === 'insteadof'))))
        ) {
            // Not the use of a constant.
            return false;
        }

        if ($prev !== false
            && $tokens[$prev]['code'] === T_NS_SEPARATOR
            && $tokens[($prev - 1)]['code'] === T_STRING
        ) {
            // Namespaced constant of the same name.
            return false;
        }

        if ($prev !== false
            && $tokens[$prev]['code'] === T_CONST
            && $this->isClassConstant($phpcsFile, $prev) === true
        ) {
            // Class constant declaration of the same name.
            return false;
        }

        /*
         * Deal with a number of variations of use statements.
         */
        for ($i = $stackPtr; $i > 0; $i--) {
            if ($tokens[$i]['line'] !== $tokens[$stackPtr]['line']) {
                break;
            }
        }

        $firstOnLine = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, ($i + 1), null, true);
        if ($firstOnLine !== false && $tokens[$firstOnLine]['code'] === T_USE) {
            $nextOnLine = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, ($firstOnLine + 1), null, true);
            if ($nextOnLine !== false) {
                if (($tokens[$nextOnLine]['code'] === T_STRING && $tokens[$nextOnLine]['content'] === 'const')
                    || $tokens[$nextOnLine]['code'] === T_CONST // Happens in some PHPCS versions.
                ) {
                    $hasNsSep = $phpcsFile->findNext(T_NS_SEPARATOR, ($nextOnLine + 1), $stackPtr);
                    if ($hasNsSep !== false) {
                        // Namespaced const (group) use statement.
                        return false;
                    }
                } else {
                    // Not a const use statement.
                    return false;
                }
            }
        }

        return true;
    }


    /**
     * Determine whether the tokens between $start and $end together form a positive number
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for `true`, `false` should be regarded as
     * "undetermined".
     *
     * Note: Zero is *not* regarded as a positive number.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                   $start       Start of the snippet (inclusive), i.e. this
     *                                           token will be examined as part of the snippet.
     * @param int                   $end         End of the snippet (inclusive), i.e. this
     *                                           token will be examined as part of the snippet.
     * @param bool                  $allowFloats Whether to only consider integers, or also floats.
     *
     * @return bool True if PHP would evaluate the snippet as a positive number.
     *              False if not or if it could not be reliably determined
     *              (variable or calculations and such).
     */
    public function isPositiveNumber(\PHP_CodeSniffer_File $phpcsFile, $start, $end, $allowFloats = false)
    {
        $number = $this->isNumber($phpcsFile, $start, $end, $allowFloats);

        if ($number === false) {
            return false;
        }

        return ($number > 0);
    }


    /**
     * Determine whether the tokens between $start and $end together form a negative number
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for `true`, `false` should be regarded as
     * "undetermined".
     *
     * Note: Zero is *not* regarded as a negative number.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                   $start       Start of the snippet (inclusive), i.e. this
     *                                           token will be examined as part of the snippet.
     * @param int                   $end         End of the snippet (inclusive), i.e. this
     *                                           token will be examined as part of the snippet.
     * @param bool                  $allowFloats Whether to only consider integers, or also floats.
     *
     * @return bool True if PHP would evaluate the snippet as a negative number.
     *              False if not or if it could not be reliably determined
     *              (variable or calculations and such).
     */
    public function isNegativeNumber(\PHP_CodeSniffer_File $phpcsFile, $start, $end, $allowFloats = false)
    {
        $number = $this->isNumber($phpcsFile, $start, $end, $allowFloats);

        if ($number === false) {
            return false;
        }

        return ($number < 0);

    }

    /**
     * Determine whether the tokens between $start and $end together form a number
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for "true-ish" values, `false` should
     * be regarded as "undetermined".
     *
     * @link https://3v4l.org/npTeM
     *
     * Mainly intended for examining variable assignments, function call parameters, array values
     * where the start and end of the snippet to examine is very clear.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                   $start       Start of the snippet (inclusive), i.e. this
     *                                           token will be examined as part of the snippet.
     * @param int                   $end         End of the snippet (inclusive), i.e. this
     *                                           token will be examined as part of the snippet.
     * @param bool                  $allowFloats Whether to only consider integers, or also floats.
     *
     * @return int|float|bool The number found if PHP would evaluate the snippet as a number.
     *                        The return type will be int if $allowFloats is false, if
     *                        $allowFloats is true, the return type will be float.
     *                        False will be returned when the snippet does not evaluate to a
     *                        number or if it could not be reliably determined
     *                        (variable or calculations and such).
     */
    protected function isNumber(\PHP_CodeSniffer_File $phpcsFile, $start, $end, $allowFloats = false)
    {
        $stringTokens  = \PHP_CodeSniffer_Tokens::$heredocTokens + \PHP_CodeSniffer_Tokens::$stringTokens;

        $validTokens            = array();
        $validTokens[T_LNUMBER] = true;
        $validTokens[T_TRUE]    = true; // Evaluates to int 1.
        $validTokens[T_FALSE]   = true; // Evaluates to int 0.
        $validTokens[T_NULL]    = true; // Evaluates to int 0.

        if ($allowFloats === true) {
            $validTokens[T_DNUMBER] = true;
        }

        $maybeValidTokens = $stringTokens + $validTokens;

        $tokens         = $phpcsFile->getTokens();
        $searchEnd      = ($end + 1);
        $negativeNumber = false;

        if (isset($tokens[$start], $tokens[$searchEnd]) === false) {
            return false;
        }

        $nextNonEmpty = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, $start, $searchEnd, true);
        while ($nextNonEmpty !== false
            && ($tokens[$nextNonEmpty]['code'] === T_PLUS
            || $tokens[$nextNonEmpty]['code'] === T_MINUS)
        ) {

            if ($tokens[$nextNonEmpty]['code'] === T_MINUS) {
                $negativeNumber = ($negativeNumber === false) ? true : false;
            }

            $nextNonEmpty = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, ($nextNonEmpty + 1), $searchEnd, true);
        }

        if ($nextNonEmpty === false || isset($maybeValidTokens[$tokens[$nextNonEmpty]['code']]) === false) {
            return false;
        }

        $content = false;
        if ($tokens[$nextNonEmpty]['code'] === T_LNUMBER
            || $tokens[$nextNonEmpty]['code'] === T_DNUMBER
        ) {
            $content = (float) $tokens[$nextNonEmpty]['content'];
        } elseif ($tokens[$nextNonEmpty]['code'] === T_TRUE) {
            $content = 1.0;
        } elseif ($tokens[$nextNonEmpty]['code'] === T_FALSE
            || $tokens[$nextNonEmpty]['code'] === T_NULL
        ) {
            $content = 0.0;
        } elseif (isset($stringTokens[$tokens[$nextNonEmpty]['code']]) === true) {

            if ($tokens[$nextNonEmpty]['code'] === T_START_HEREDOC
                || $tokens[$nextNonEmpty]['code'] === T_START_NOWDOC
            ) {
                // Skip past heredoc/nowdoc opener to the first content.
                $firstDocToken = $phpcsFile->findNext(array(T_HEREDOC, T_NOWDOC), ($nextNonEmpty + 1), $searchEnd);
                if ($firstDocToken === false) {
                    // Live coding or parse error.
                    return false;
                }

                $stringContent = $content = $tokens[$firstDocToken]['content'];

                // Skip forward to the end in preparation for the next part of the examination.
                $nextNonEmpty = $phpcsFile->findNext(array(T_END_HEREDOC, T_END_NOWDOC), ($nextNonEmpty + 1), $searchEnd);
                if ($nextNonEmpty === false) {
                    // Live coding or parse error.
                    return false;
                }
            } else {
                // Gather subsequent lines for a multi-line string.
                for ($i = $nextNonEmpty; $i < $searchEnd; $i++) {
                    if ($tokens[$i]['code'] !== $tokens[$nextNonEmpty]['code']) {
                        break;
                    }
                    $content .= $tokens[$i]['content'];
                }

                $nextNonEmpty  = --$i;
                $content       = $this->stripQuotes($content);
                $stringContent = $content;
            }

            /*
             * Regexes based on the formats outlined in the manual, created by JRF.
             * @link http://php.net/manual/en/language.types.float.php
             */
            $regexInt   = '`^\s*[0-9]+`';
            $regexFloat = '`^\s*(?:[+-]?(?:(?:(?P<LNUM>[0-9]+)|(?P<DNUM>([0-9]*\.(?P>LNUM)|(?P>LNUM)\.[0-9]*)))[eE][+-]?(?P>LNUM))|(?P>DNUM))`';

            $intString   = preg_match($regexInt, $content, $intMatch);
            $floatString = preg_match($regexFloat, $content, $floatMatch);

            // Does the text string start with a number ? If so, PHP would juggle it and use it as a number.
            if ($allowFloats === false) {
                if ($intString !== 1 || $floatString === 1) {
                    if ($floatString === 1) {
                        // Found float. Only integers targetted.
                        return false;
                    }

                    $content = 0.0;
                } else {
                    $content = (float) trim($intMatch[0]);
                }
            } else {
                if ($intString !== 1 && $floatString !== 1) {
                    $content = 0.0;
                } else {
                    $content = ($floatString === 1) ? (float) trim($floatMatch[0]) : (float) trim($intMatch[0]);
                }
            }

            // Allow for different behaviour for hex numeric strings between PHP 5 vs PHP 7.
            if ($intString === 1 && trim($intMatch[0]) === '0'
                && preg_match('`^\s*(0x[A-Fa-f0-9]+)`', $stringContent, $hexNumberString) === 1
                && $this->supportsBelow('5.6') === true
            ) {
                // The filter extension still allows for hex numeric strings in PHP 7, so
                // use that to get the numeric value if possible.
                // If the filter extension is not available, the value will be zero, but so be it.
                if (function_exists('filter_var')) {
                    $filtered = filter_var($hexNumberString[1], FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_HEX);
                    if ($filtered !== false) {
                        $content = $filtered;
                    }
                }
            }
        }

        // OK, so we have a number, now is there still more code after it ?
        $nextNonEmpty = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$emptyTokens, ($nextNonEmpty + 1), $searchEnd, true);
        if ($nextNonEmpty !== false) {
            return false;
        }

        if ($negativeNumber === true) {
            $content = -$content;
        }

        if ($allowFloats === false) {
            return (int) $content;
        }

        return $content;
    }


    /**
     * Determine whether the tokens between $start and $end together form a numberic calculation
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for `true`, `false` should be regarded as "undetermined".
     *
     * Mainly intended for examining variable assignments, function call parameters, array values
     * where the start and end of the snippet to examine is very clear.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $start     Start of the snippet (inclusive), i.e. this
     *                                         token will be examined as part of the snippet.
     * @param int                   $end       End of the snippet (inclusive), i.e. this
     *                                         token will be examined as part of the snippet.
     *
     * @return bool
     */
    protected function isNumericCalculation(\PHP_CodeSniffer_File $phpcsFile, $start, $end)
    {
        $arithmeticTokens = \PHP_CodeSniffer_Tokens::$arithmeticTokens;

        // phpcs:disable PHPCompatibility.Constants.NewConstants.t_powFound
        if (defined('T_POW') && isset($arithmeticTokens[T_POW]) === false) {
            // T_POW was not added to the arithmetic array until PHPCS 2.9.0.
            $arithmeticTokens[T_POW] = T_POW;
        }
        // phpcs:enable

        $skipTokens   = \PHP_CodeSniffer_Tokens::$emptyTokens;
        $skipTokens[] = T_MINUS;
        $skipTokens[] = T_PLUS;

        // Find the first arithmetic operator, but skip past +/- signs before numbers.
        $nextNonEmpty = ($start - 1);
        do {
            $nextNonEmpty       = $phpcsFile->findNext($skipTokens, ($nextNonEmpty + 1), ($end + 1), true);
            $arithmeticOperator = $phpcsFile->findNext($arithmeticTokens, ($nextNonEmpty + 1), ($end + 1));
        } while ($nextNonEmpty !== false && $arithmeticOperator !== false && $nextNonEmpty === $arithmeticOperator);

        if ($arithmeticOperator === false) {
            return false;
        }

        $tokens      = $phpcsFile->getTokens();
        $subsetStart = $start;
        $subsetEnd   = ($arithmeticOperator - 1);

        while ($this->isNumber($phpcsFile, $subsetStart, $subsetEnd, true) !== false
            && isset($tokens[($arithmeticOperator + 1)]) === true
        ) {
            // Recognize T_POW for PHPCS < 2.4.0 on low PHP versions.
            if (defined('T_POW') === false
                && $tokens[$arithmeticOperator]['code'] === T_MULTIPLY
                && $tokens[($arithmeticOperator + 1)]['code'] === T_MULTIPLY
                && isset($tokens[$arithmeticOperator + 2]) === true
            ) {
                // Move operator one forward to the second * in T_POW.
                ++$arithmeticOperator;
            }

            $subsetStart  = ($arithmeticOperator + 1);
            $nextNonEmpty = $arithmeticOperator;
            do {
                $nextNonEmpty       = $phpcsFile->findNext($skipTokens, ($nextNonEmpty + 1), ($end + 1), true);
                $arithmeticOperator = $phpcsFile->findNext($arithmeticTokens, ($nextNonEmpty + 1), ($end + 1));
            } while ($nextNonEmpty !== false && $arithmeticOperator !== false && $nextNonEmpty === $arithmeticOperator);

            if ($arithmeticOperator === false) {
                // Last calculation operator already reached.
                if ($this->isNumber($phpcsFile, $subsetStart, $end, true) !== false) {
                    return true;
                }

                return false;
            }

            $subsetEnd = ($arithmeticOperator - 1);
        }

        return false;
    }


    /**
     * Determine whether a T_OPEN/CLOSE_SHORT_ARRAY token is a list() construct.
     *
     * Note: A variety of PHPCS versions have bugs in the tokenizing of short arrays.
     * In that case, the tokens are identified as T_OPEN/CLOSE_SQUARE_BRACKET.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the function call token.
     *
     * @return bool
     */
    public function isShortList(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        // Is this one of the tokens this function handles ?
        if ($tokens[$stackPtr]['code'] !== T_OPEN_SHORT_ARRAY
            && $tokens[$stackPtr]['code'] !== T_CLOSE_SHORT_ARRAY
        ) {
            return false;
        }

        switch ($tokens[$stackPtr]['code']) {
            case T_OPEN_SHORT_ARRAY:
                if (isset($tokens[$stackPtr]['bracket_closer']) === true) {
                    $opener = $stackPtr;
                    $closer = $tokens[$stackPtr]['bracket_closer'];
                }
                break;

            case T_CLOSE_SHORT_ARRAY:
                if (isset($tokens[$stackPtr]['bracket_opener']) === true) {
                    $opener = $tokens[$stackPtr]['bracket_opener'];
                    $closer = $stackPtr;
                }
                break;
        }

        if (isset($opener, $closer) === false) {
            // Parse error, live coding or real square bracket.
            return false;
        }

        /*
         * PHPCS cross-version compatibility: work around for square brackets misidentified
         * as short array when preceded by a variable variable in older PHPCS versions.
         */
        $prevNonEmpty = $phpcsFile->findPrevious(
            \PHP_CodeSniffer_Tokens::$emptyTokens,
            ($opener - 1),
            null,
            true,
            null,
            true
        );

        if ($prevNonEmpty !== false
            && $tokens[$prevNonEmpty]['code'] === T_CLOSE_CURLY_BRACKET
            && isset($tokens[$prevNonEmpty]['bracket_opener']) === true
        ) {
            $maybeVariableVariable = $phpcsFile->findPrevious(
                \PHP_CodeSniffer_Tokens::$emptyTokens,
                ($tokens[$prevNonEmpty]['bracket_opener'] - 1),
                null,
                true,
                null,
                true
            );

            if ($tokens[$maybeVariableVariable]['code'] === T_VARIABLE
                || $tokens[$maybeVariableVariable]['code'] === T_DOLLAR
            ) {
                return false;
            }
        }

        $nextNonEmpty = $phpcsFile->findNext(
            \PHP_CodeSniffer_Tokens::$emptyTokens,
            ($closer + 1),
            null,
            true,
            null,
            true
        );

        if ($nextNonEmpty !== false && $tokens[$nextNonEmpty]['code'] === T_EQUAL) {
            return true;
        }

        if ($prevNonEmpty !== false
            && $tokens[$prevNonEmpty]['code'] === T_AS
            && isset($tokens[$prevNonEmpty]['nested_parenthesis']) === true
        ) {
            $parentheses = array_reverse($tokens[$prevNonEmpty]['nested_parenthesis'], true);
            foreach ($parentheses as $open => $close) {
                if (isset($tokens[$open]['parenthesis_owner'])
                    && $tokens[$tokens[$open]['parenthesis_owner']]['code'] === T_FOREACH
                ) {
                    return true;
                }
            }
        }

        // Maybe this is a short list syntax nested inside another short list syntax ?
        $parentOpener = $opener;
        do {
            $parentOpener = $phpcsFile->findPrevious(
                array(T_OPEN_SHORT_ARRAY, T_OPEN_SQUARE_BRACKET),
                ($parentOpener - 1),
                null,
                false,
                null,
                true
            );

            if ($parentOpener === false) {
                return false;
            }

        } while (isset($tokens[$parentOpener]['bracket_closer']) === true
            && $tokens[$parentOpener]['bracket_closer'] < $opener
        );

        if (isset($tokens[$parentOpener]['bracket_closer']) === true
            && $tokens[$parentOpener]['bracket_closer'] > $closer
        ) {
            // Work around tokenizer issue in PHPCS 2.0 - 2.7.
            $phpcsVersion = PHPCSHelper::getVersion();
            if ((version_compare($phpcsVersion, '2.0', '>') === true
                && version_compare($phpcsVersion, '2.8', '<') === true)
                && $tokens[$parentOpener]['code'] === T_OPEN_SQUARE_BRACKET
            ) {
                $nextNonEmpty = $phpcsFile->findNext(
                    \PHP_CodeSniffer_Tokens::$emptyTokens,
                    ($tokens[$parentOpener]['bracket_closer'] + 1),
                    null,
                    true,
                    null,
                    true
                );

                if ($nextNonEmpty !== false && $tokens[$nextNonEmpty]['code'] === T_EQUAL) {
                    return true;
                }

                return false;
            }

            return $this->isShortList($phpcsFile, $parentOpener);
        }

        return false;
    }


    /**
     * Determine whether the tokens between $start and $end could together represent a variable.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile          The file being scanned.
     * @param int                   $start              Starting point stack pointer. Inclusive.
     *                                                  I.e. this token should be taken into account.
     * @param int                   $end                End point stack pointer. Exclusive.
     *                                                  I.e. this token should not be taken into account.
     * @param int                   $targetNestingLevel The nesting level the variable should be at.
     *
     * @return bool
     */
    public function isVariable($phpcsFile, $start, $end, $targetNestingLevel)
    {
        static $tokenBlackList, $bracketTokens;

        // Create the token arrays only once.
        if (isset($tokenBlackList, $bracketTokens) === false) {

            $tokenBlackList  = array(
                T_OPEN_PARENTHESIS => T_OPEN_PARENTHESIS,
                T_STRING_CONCAT    => T_STRING_CONCAT,
            );
            $tokenBlackList += \PHP_CodeSniffer_Tokens::$assignmentTokens;
            $tokenBlackList += \PHP_CodeSniffer_Tokens::$equalityTokens;
            $tokenBlackList += \PHP_CodeSniffer_Tokens::$comparisonTokens;
            $tokenBlackList += \PHP_CodeSniffer_Tokens::$operators;
            $tokenBlackList += \PHP_CodeSniffer_Tokens::$booleanOperators;
            $tokenBlackList += \PHP_CodeSniffer_Tokens::$castTokens;

            /*
             * List of brackets which can be part of a variable variable.
             *
             * Key is the open bracket token, value the close bracket token.
             */
            $bracketTokens = array(
                T_OPEN_CURLY_BRACKET  => T_CLOSE_CURLY_BRACKET,
                T_OPEN_SQUARE_BRACKET => T_CLOSE_SQUARE_BRACKET,
            );
        }

        $tokens = $phpcsFile->getTokens();

        // If no variable at all was found, then it's definitely a no-no.
        $hasVariable = $phpcsFile->findNext(T_VARIABLE, $start, $end);
        if ($hasVariable === false) {
            return false;
        }

        // Check if the variable found is at the right level. Deeper levels are always an error.
        if (isset($tokens[$hasVariable]['nested_parenthesis'])
            && count($tokens[$hasVariable]['nested_parenthesis']) !== $targetNestingLevel
        ) {
                return false;
        }

        // Ok, so the first variable is at the right level, now are there any
        // blacklisted tokens within the empty() ?
        $hasBadToken = $phpcsFile->findNext($tokenBlackList, $start, $end);
        if ($hasBadToken === false) {
            return true;
        }

        // If there are also bracket tokens, the blacklisted token might be part of a variable
        // variable, but if there are no bracket tokens, we know we have an error.
        $hasBrackets = $phpcsFile->findNext($bracketTokens, $start, $end);
        if ($hasBrackets === false) {
            return false;
        }

        // Ok, we have both a blacklisted token as well as brackets, so we need to walk
        // the tokens of the variable variable.
        for ($i = $start; $i < $end; $i++) {
            // If this is a bracket token, skip to the end of the bracketed expression.
            if (isset($bracketTokens[$tokens[$i]['code']], $tokens[$i]['bracket_closer'])) {
                $i = $tokens[$i]['bracket_closer'];
                continue;
            }

            // If it's a blacklisted token, not within brackets, we have an error.
            if (isset($tokenBlackList[$tokens[$i]['code']])) {
                return false;
            }
        }

        return true;
    }
}//end class
