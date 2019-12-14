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

use PHP_CodeSniffer_Exception as PHPCS_Exception;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Sniff as PHPCS_Sniff;
use PHP_CodeSniffer_Tokens as Tokens;
use PHPCSUtils\BackCompat\Helper;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\Lists;
use PHPCSUtils\Utils\Namespaces;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\Operators;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\Scopes;
use PHPCSUtils\Utils\TextStrings;

/**
 * Base class from which all PHPCompatibility sniffs extend.
 *
 * @since 5.6
 */
abstract class Sniff implements PHPCS_Sniff
{

    /**
     * Regex to match variables in a double quoted string.
     *
     * This matches plain variables, but also more complex variables, such
     * as $obj->prop, self::prop and $var[].
     *
     * @since 7.1.2
     *
     * @var string
     */
    const REGEX_COMPLEX_VARS = '`(?:(\{)?(?<!\\\\)\$)?(\{)?(?<!\\\\)\$(\{)?(?P<varname>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(?:->\$?(?P>varname)|\[[^\]]+\]|::\$?(?P>varname)|\([^\)]*\))*(?(3)\}|)(?(2)\}|)(?(1)\}|)`';

    /**
     * List of superglobals as an array of strings.
     *
     * Used by the ForbiddenParameterShadowSuperGlobals and ForbiddenClosureUseVariableNames sniffs.
     *
     * @since 7.0.0
     * @since 7.1.4 Moved from the `ForbiddenParameterShadowSuperGlobals` sniff to the base `Sniff` class.
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
     * @since 5.5
     * @since 7.0.7 Moved from the `RemovedHashAlgorithms` sniff to the base `Sniff` class.
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
     * @since 7.1.0
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
     * @since 7.0.0
     * @since 7.1.3  Now allows for partial ranges such as `5.2-`.
     * @since 10.0.0 Will allow for "testVersion" config in lowercase.
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
        $testVersion = trim(Helper::getConfigData('testVersion'));

        // Case-sensitivity tolerance.
        if (empty($testVersion) === true) {
            $testVersion = trim(Helper::getConfigData('testversion'));
        }

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
                        trigger_error(
                            "Invalid range in testVersion setting: '" . $testVersion . "'",
                            \E_USER_WARNING
                        );
                        return $default;
                    } else {
                        $arrTestVersions[$testVersion] = array($min, $max);
                        return $arrTestVersions[$testVersion];
                    }
                }
            }

            trigger_error(
                "Invalid testVersion setting: '" . $testVersion . "'",
                \E_USER_WARNING
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
     * @since 5.6
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

        if (\is_null($testVersion)
            || version_compare($testVersion, $phpVersion) >= 0
        ) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Check whether a specific PHP version is equal to or lower than the minimum
     * supported PHP version as provided by the user in `testVersion`.
     *
     * Should be used when sniffing for *new* PHP features.
     *
     * @since 5.6
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

        if (\is_null($testVersion) === false
            && version_compare($testVersion, $phpVersion) <= 0
        ) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Add a PHPCS message to the output stack as either a warning or an error.
     *
     * @since 7.1.0
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
    public function addMessage(File $phpcsFile, $message, $stackPtr, $isError, $code = 'Found', $data = array())
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
     * @since 7.1.0
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
     * Strip variables from an arbitrary double quoted string.
     *
     * Intended for use with the contents of a T_DOUBLE_QUOTED_STRING.
     *
     * @since 7.1.2
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
     * Verify whether a token is within a class scope.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the token.
     * @param bool                  $strict    Whether to strictly check for the T_CLASS
     *                                         scope or also accept interfaces and traits
     *                                         as scope.
     *
     * @return bool True if within class scope, false otherwise.
     */
    public function inClassScope(File $phpcsFile, $stackPtr, $strict = true)
    {
        $validScopes = array(\T_CLASS, \T_ANON_CLASS);

        if ($strict === false) {
            $validScopes[] = \T_INTERFACE;
            $validScopes[] = \T_TRAIT;
        }

        return $phpcsFile->hasCondition($stackPtr, $validScopes);
    }


    /**
     * Returns the fully qualified class name for a new class instantiation.
     *
     * Returns an empty string if the class name could not be reliably inferred.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of a T_NEW token.
     *
     * @return string
     */
    public function getFQClassNameFromNewToken(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== \T_NEW) {
            return '';
        }

        $start = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        if ($start === false) {
            return '';
        }

        // Bow out if the next token is a variable as we don't know where it was defined.
        if ($tokens[$start]['code'] === \T_VARIABLE) {
            return '';
        }

        // Bow out if the next token is the class keyword.
        if ($tokens[$start]['code'] === \T_ANON_CLASS || $tokens[$start]['code'] === \T_CLASS) {
            return '';
        }

        $find = array(
            \T_NS_SEPARATOR,
            \T_STRING,
            \T_NAMESPACE,
            \T_WHITESPACE,
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
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of a T_CLASS token.
     *
     * @return string
     */
    public function getFQExtendedClassName(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== \T_CLASS
            && $tokens[$stackPtr]['code'] !== \T_ANON_CLASS
            && $tokens[$stackPtr]['code'] !== \T_INTERFACE
        ) {
            return '';
        }

        $extends = ObjectDeclarations::findExtendedClassName($phpcsFile, $stackPtr);
        if (empty($extends) || \is_string($extends) === false) {
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
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of a T_NEW token.
     *
     * @return string
     */
    public function getFQClassNameFromDoubleColonToken(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return '';
        }

        if ($tokens[$stackPtr]['code'] !== \T_DOUBLE_COLON) {
            return '';
        }

        // Nothing to do if previous token is a variable as we don't know where it was defined.
        if ($tokens[$stackPtr - 1]['code'] === \T_VARIABLE) {
            return '';
        }

        // Nothing to do if 'parent' or 'static' as we don't know how far the class tree extends.
        if (\in_array($tokens[$stackPtr - 1]['code'], array(\T_PARENT, \T_STATIC), true)) {
            return '';
        }

        // Get the classname from the class declaration if self is used.
        if ($tokens[$stackPtr - 1]['code'] === \T_SELF) {
            $classDeclarationPtr = $phpcsFile->findPrevious(\T_CLASS, $stackPtr - 1);
            if ($classDeclarationPtr === false) {
                return '';
            }
            $className = $phpcsFile->getDeclarationName($classDeclarationPtr);
            return $this->getFQName($phpcsFile, $classDeclarationPtr, $className);
        }

        $find = array(
            \T_NS_SEPARATOR,
            \T_STRING,
            \T_NAMESPACE,
            \T_WHITESPACE,
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
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the token.
     * @param string                $name      The class / function / constant name.
     *
     * @return string
     */
    public function getFQName(File $phpcsFile, $stackPtr, $name)
    {
        if (strpos($name, '\\') === 0) {
            // Already fully qualified.
            return $name;
        }

        // Remove the namespace keyword if used.
        if (strpos($name, 'namespace\\') === 0) {
            $name = substr($name, 10);
        }

        $namespace = Namespaces::determineNamespace($phpcsFile, $stackPtr);

        if ($namespace === '') {
            return '\\' . $name;
        } else {
            return '\\' . $namespace . '\\' . $name;
        }
    }


    /**
     * Is the class/function/constant name namespaced or global ?
     *
     * @since 7.0.3
     *
     * @param string $FQName Fully Qualified name of a class, function etc.
     *                       I.e. should always start with a `\`.
     *
     * @return bool True if namespaced, false if global.
     *
     * @throws \PHP_CodeSniffer_Exception If the name in the passed parameter
     *                                    is not fully qualified.
     */
    public function isNamespaced($FQName)
    {
        if (strpos($FQName, '\\') !== 0) {
            throw new PHPCS_Exception('$FQName must be a fully qualified name');
        }

        return (strpos(substr($FQName, 1), '\\') !== false);
    }


    /**
     * Get the stack pointer for a return type token for a given function.
     *
     * Compatible layer for older PHPCS versions which don't recognize
     * return type hints correctly.
     *
     * Expects to be passed T_RETURN_TYPE, T_FUNCTION or T_CLOSURE token.
     *
     * @since 7.1.2
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the token.
     *
     * @return int|false Stack pointer to the return type token or false if
     *                   no return type was found or the passed token was
     *                   not of the correct type.
     */
    public function getReturnTypeHintToken(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['code'] === \T_RETURN_TYPE) {
            return $stackPtr;
        }

        if ($tokens[$stackPtr]['code'] !== \T_FUNCTION && $tokens[$stackPtr]['code'] !== \T_CLOSURE) {
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
            $nextSemiColon = $phpcsFile->findNext(\T_SEMICOLON, ($tokens[$stackPtr]['parenthesis_closer'] + 1), null, false, null, true);
            if ($nextSemiColon !== false) {
                $endOfFunctionDeclaration = $nextSemiColon;
            }
        }

        if (isset($endOfFunctionDeclaration) === false) {
            return false;
        }

        $hasColon = $phpcsFile->findNext(
            array(\T_COLON, \T_INLINE_ELSE),
            ($tokens[$stackPtr]['parenthesis_closer'] + 1),
            $endOfFunctionDeclaration
        );
        if ($hasColon === false) {
            return false;
        }

        /*
         * - Prior to PHPCS 3.3.0, the return type would mostly be tokenized as T_RETURN_TYPE.
         *   As of PHPCS 3.3.0, the T_RETURN_TYPE token is defined, but no longer in use.
         *   The token will now be tokenized as T_STRING, T_SELF or T_CALLABLE.
         * - An `array` (return) type declaration was tokenized as `T_ARRAY_HINT` in PHPCS 2.3.3 - 3.2.3
         *   to prevent confusing sniffs looking for array declarations.
         *   As of PHPCS 3.3.0 `array` as a type declaration will be tokenized as `T_STRING`.
         */
        $unrecognizedTypes = array(
            \T_CALLABLE,
            \T_SELF,
            \T_PARENT,
            \T_STRING,
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
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the return type token.
     *
     * @return string The name of the return type token.
     */
    public function getReturnTypeHintName(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // In older PHPCS versions, the nullable indicator will turn a return type colon into a T_INLINE_ELSE.
        $colon = $phpcsFile->findPrevious(array(\T_COLON, \T_INLINE_ELSE, \T_FUNCTION, \T_CLOSE_PARENTHESIS), ($stackPtr - 1));
        if ($colon === false
            || ($tokens[$colon]['code'] !== \T_COLON && $tokens[$colon]['code'] !== \T_INLINE_ELSE)
        ) {
            // Shouldn't happen, just in case.
            return '';
        }

        $returnTypeHint = '';
        for ($i = ($colon + 1); $i <= $stackPtr; $i++) {
            // As of PHPCS 3.3.0+, all tokens are tokenized as "normal", so T_CALLABLE, T_SELF etc are
            // all possible, just exclude anything that's regarded as empty and the nullable indicator.
            if (isset(Tokens::$emptyTokens[$tokens[$i]['code']])) {
                continue;
            }

            if ($tokens[$i]['type'] === 'T_NULLABLE') {
                continue;
            }

            if (\defined('T_NULLABLE') === false && $tokens[$i]['code'] === \T_INLINE_THEN) {
                // PHPCS < 2.8.0.
                continue;
            }

            $returnTypeHint .= $tokens[$i]['content'];
        }

        return $returnTypeHint;
    }


    /**
     * Check whether a T_CONST token is a class constant declaration.
     *
     * @since      7.1.4
     * @deprecated 10.0.0 Use {@see PHPCSUtils\Utils\Scopes::isOOConstant()} instead.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                   $stackPtr  The position in the stack of the
     *                                         T_CONST token to verify.
     *
     * @return bool
     */
    public function isClassConstant(File $phpcsFile, $stackPtr)
    {
        return Scopes::isOOConstant($phpcsFile, $stackPtr);
    }


    /**
     * Check whether the direct wrapping scope of a token is within a limited set of
     * acceptable tokens.
     *
     * Used to check, for instance, if a T_CONST is a class constant.
     *
     * @since      7.1.4
     * @deprecated 10.0.0 Use {@see PHPCSUtils\Utils\Scopes::validDirectScope()} instead.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile   Instance of phpcsFile.
     * @param int                   $stackPtr    The position in the stack of the
     *                                           token to verify.
     * @param array                 $validScopes Array of token types.
     *                                           Keys should be the token types in string
     *                                           format to allow for newer token types.
     *                                           Value is irrelevant.
     *
     * @return int|bool StackPtr to the scope if valid, false otherwise.
     */
    protected function validDirectScope(File $phpcsFile, $stackPtr, $validScopes)
    {
        $types = array();
        foreach ($validScopes as $type => $ignore) {
            if (defined($type)) {
                $types[] = constant($type);
            }
        }

        return Scopes::validDirectScope($phpcsFile, $stackPtr, $types);
    }


    /**
     * Get an array of just the type hints from a function declaration.
     *
     * Expects to be passed T_FUNCTION or T_CLOSURE token.
     *
     * Strips potential nullable indicator and potential global namespace
     * indicator from the type hints before returning them.
     *
     * @since 7.1.4
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the token.
     *
     * @return array Array with type hints or an empty array if
     *               - the function does not have any parameters
     *               - no type hints were found
     *               - or the passed token was not of the correct type.
     */
    public function getTypeHintsFromFunctionDeclaration(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['code'] !== \T_FUNCTION && $tokens[$stackPtr]['code'] !== \T_CLOSURE) {
            return array();
        }

        $parameters = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        if (empty($parameters) || \is_array($parameters) === false) {
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
     * @since 7.0.7 Logic was originally contained in the `RemovedHashAlgorithms` sniff.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile Instance of phpcsFile.
     * @param int                   $stackPtr  The position of the T_STRING function token.
     *
     * @return string|false The algorithm name without quotes if this was a relevant hash
     *                      function call or false if it was not.
     */
    public function getHashAlgorithmParameter(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        if ($tokens[$stackPtr]['code'] !== \T_STRING) {
            return false;
        }

        $functionName   = $tokens[$stackPtr]['content'];
        $functionNameLc = strtolower($functionName);

        // Bow out if not one of the functions we're targetting.
        if (isset($this->hashAlgoFunctions[$functionNameLc]) === false) {
            return false;
        }

        // Get the parameter from the function call which should contain the algorithm name.
        $algoParam = PassedParameters::getParameter($phpcsFile, $stackPtr, $this->hashAlgoFunctions[$functionNameLc]);
        if ($algoParam === false) {
            return false;
        }

        // Algorithm is a text string, so we need to remove the quotes.
        $algo = strtolower(trim($algoParam['raw']));
        $algo = TextStrings::stripQuotes($algo);

        return $algo;
    }


    /**
     * Determine whether an arbitrary T_STRING token is the use of a global constant.
     *
     * @since 8.1.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the T_STRING token.
     *
     * @return bool
     */
    public function isUseOfGlobalConstant(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check for the existence of the token.
        if (isset($tokens[$stackPtr]) === false) {
            return false;
        }

        // Is this one of the tokens this function handles ?
        if ($tokens[$stackPtr]['code'] !== \T_STRING) {
            return false;
        }

        $next = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($next !== false
            && ($tokens[$next]['code'] === \T_OPEN_PARENTHESIS
                || $tokens[$next]['code'] === \T_DOUBLE_COLON)
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

        $prev = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($prev !== false && isset($tokensToIgnore[$tokens[$prev]['type']]) === true
        ) {
            // Not the use of a constant.
            return false;
        }

        if ($prev !== false
            && $tokens[$prev]['code'] === \T_NS_SEPARATOR
            && $tokens[($prev - 1)]['code'] === \T_STRING
        ) {
            // Namespaced constant of the same name.
            return false;
        }

        if ($prev !== false
            && $tokens[$prev]['code'] === \T_CONST
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

        $firstOnLine = $phpcsFile->findNext(Tokens::$emptyTokens, ($i + 1), null, true);
        if ($firstOnLine !== false && $tokens[$firstOnLine]['code'] === \T_USE) {
            $nextOnLine = $phpcsFile->findNext(Tokens::$emptyTokens, ($firstOnLine + 1), null, true);
            if ($nextOnLine !== false) {
                if (($tokens[$nextOnLine]['code'] === \T_STRING && $tokens[$nextOnLine]['content'] === 'const')
                    || $tokens[$nextOnLine]['code'] === \T_CONST // Happens in some PHPCS versions.
                ) {
                    $hasNsSep = $phpcsFile->findNext(\T_NS_SEPARATOR, ($nextOnLine + 1), $stackPtr);
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
     * @since 8.2.0
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
    public function isPositiveNumber(File $phpcsFile, $start, $end, $allowFloats = false)
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
     * @since 8.2.0
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
    public function isNegativeNumber(File $phpcsFile, $start, $end, $allowFloats = false)
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
     * @since 8.2.0
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
    protected function isNumber(File $phpcsFile, $start, $end, $allowFloats = false)
    {
        $stringTokens = Tokens::$heredocTokens + Tokens::$stringTokens;

        $validTokens             = array();
        $validTokens[\T_LNUMBER] = true;
        $validTokens[\T_TRUE]    = true; // Evaluates to int 1.
        $validTokens[\T_FALSE]   = true; // Evaluates to int 0.
        $validTokens[\T_NULL]    = true; // Evaluates to int 0.

        if ($allowFloats === true) {
            $validTokens[\T_DNUMBER] = true;
        }

        $maybeValidTokens = $stringTokens + $validTokens;

        $tokens         = $phpcsFile->getTokens();
        $searchEnd      = ($end + 1);
        $negativeNumber = false;

        if (isset($tokens[$start], $tokens[$searchEnd]) === false) {
            return false;
        }

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $start, $searchEnd, true);
        while ($nextNonEmpty !== false
            && ($tokens[$nextNonEmpty]['code'] === \T_PLUS
            || $tokens[$nextNonEmpty]['code'] === \T_MINUS)
        ) {

            if ($tokens[$nextNonEmpty]['code'] === \T_MINUS) {
                $negativeNumber = ($negativeNumber === false) ? true : false;
            }

            $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonEmpty + 1), $searchEnd, true);
        }

        if ($nextNonEmpty === false || isset($maybeValidTokens[$tokens[$nextNonEmpty]['code']]) === false) {
            return false;
        }

        $content = false;
        if ($tokens[$nextNonEmpty]['code'] === \T_LNUMBER
            || $tokens[$nextNonEmpty]['code'] === \T_DNUMBER
        ) {
            $content = (float) $tokens[$nextNonEmpty]['content'];
        } elseif ($tokens[$nextNonEmpty]['code'] === \T_TRUE) {
            $content = 1.0;
        } elseif ($tokens[$nextNonEmpty]['code'] === \T_FALSE
            || $tokens[$nextNonEmpty]['code'] === \T_NULL
        ) {
            $content = 0.0;
        } elseif (isset($stringTokens[$tokens[$nextNonEmpty]['code']]) === true) {

            if ($tokens[$nextNonEmpty]['code'] === \T_START_HEREDOC
                || $tokens[$nextNonEmpty]['code'] === \T_START_NOWDOC
            ) {
                // Skip past heredoc/nowdoc opener to the first content.
                $firstDocToken = $phpcsFile->findNext(array(\T_HEREDOC, \T_NOWDOC), ($nextNonEmpty + 1), $searchEnd);
                if ($firstDocToken === false) {
                    // Live coding or parse error.
                    return false;
                }

                $stringContent = $content = $tokens[$firstDocToken]['content'];

                // Skip forward to the end in preparation for the next part of the examination.
                $nextNonEmpty = $phpcsFile->findNext(array(\T_END_HEREDOC, \T_END_NOWDOC), ($nextNonEmpty + 1), $searchEnd);
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
                $content       = TextStrings::stripQuotes($content);
                $stringContent = $content;
            }

            /*
             * Regexes based on the formats outlined in the manual, created by JRF.
             * @link https://www.php.net/manual/en/language.types.float.php
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
                    $filtered = filter_var($hexNumberString[1], \FILTER_VALIDATE_INT, \FILTER_FLAG_ALLOW_HEX);
                    if ($filtered !== false) {
                        $content = $filtered;
                    }
                }
            }
        }

        // OK, so we have a number, now is there still more code after it ?
        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonEmpty + 1), $searchEnd, true);
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
     * @since 9.0.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $start     Start of the snippet (inclusive), i.e. this
     *                                         token will be examined as part of the snippet.
     * @param int                   $end       End of the snippet (inclusive), i.e. this
     *                                         token will be examined as part of the snippet.
     *
     * @return bool
     */
    protected function isNumericCalculation(File $phpcsFile, $start, $end)
    {
        $arithmeticTokens = Tokens::$arithmeticTokens;

        // T_POW was not added to the arithmetic array until PHPCS 2.9.0.
        // phpcs:ignore PHPCompatibility.Constants.NewConstants.t_powFound
        $arithmeticTokens[\T_POW] = \T_POW;

        $skipTokens   = Tokens::$emptyTokens;
        $skipTokens[] = \T_MINUS;
        $skipTokens[] = \T_PLUS;

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
     * Determine whether a ternary is a short ternary, i.e. without "middle".
     *
     * @since      9.2.0
     * @deprecated 10.0.0 Use {@see PHPCSUtils\Utils\Operators::isShortTernary()} instead.
     *
     * @codeCoverageIgnore Method as pulled upstream is accompanied by unit tests.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the ternary operator
     *                                         in the stack.
     *
     * @return bool True if short ternary, or false otherwise.
     */
    public function isShortTernary(File $phpcsFile, $stackPtr)
    {
        return Operators::isShortTernary($phpcsFile, $stackPtr);
    }


    /**
     * Determine whether a T_OPEN/CLOSE_SHORT_ARRAY token is a list() construct.
     *
     * Note: A variety of PHPCS versions have bugs in the tokenizing of short arrays.
     * In that case, the tokens are identified as T_OPEN/CLOSE_SQUARE_BRACKET.
     *
     * @since      8.2.0
     * @deprecated 10.0.0 Use {@see PHPCSUtils\Utils\Lists::isShortList()} instead.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the function call token.
     *
     * @return bool
     */
    public function isShortList(File $phpcsFile, $stackPtr)
    {
        return Lists::isShortList($phpcsFile, $stackPtr);
    }


    /**
     * Determine whether the tokens between $start and $end could together represent a variable.
     *
     * @since 9.0.0
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
    public function isVariable(File $phpcsFile, $start, $end, $targetNestingLevel)
    {
        static $tokenBlackList, $bracketTokens;

        // Create the token arrays only once.
        if (isset($tokenBlackList, $bracketTokens) === false) {

            $tokenBlackList  = array(
                \T_OPEN_PARENTHESIS => \T_OPEN_PARENTHESIS,
                \T_STRING_CONCAT    => \T_STRING_CONCAT,
            );
            $tokenBlackList += Tokens::$assignmentTokens;
            $tokenBlackList += Tokens::$equalityTokens;
            $tokenBlackList += Tokens::$comparisonTokens;
            $tokenBlackList += Tokens::$operators;
            $tokenBlackList += Tokens::$booleanOperators;
            $tokenBlackList += Tokens::$castTokens;

            /*
             * List of brackets which can be part of a variable variable.
             *
             * Key is the open bracket token, value the close bracket token.
             */
            $bracketTokens = array(
                \T_OPEN_CURLY_BRACKET  => \T_CLOSE_CURLY_BRACKET,
                \T_OPEN_SQUARE_BRACKET => \T_CLOSE_SQUARE_BRACKET,
            );
        }

        $tokens = $phpcsFile->getTokens();

        // If no variable at all was found, then it's definitely a no-no.
        $hasVariable = $phpcsFile->findNext(\T_VARIABLE, $start, $end);
        if ($hasVariable === false) {
            return false;
        }

        // Check if the variable found is at the right level. Deeper levels are always an error.
        if (isset($tokens[$hasVariable]['nested_parenthesis'])
            && \count($tokens[$hasVariable]['nested_parenthesis']) !== $targetNestingLevel
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

    /**
     * Determine whether a T_MINUS/T_PLUS token is a unary operator.
     *
     * @since      9.2.0
     * @deprecated 10.0.0 Use {@see PHPCSUtils\Utils\Operators::isUnaryPlusMinus()} instead.
     *
     * @codeCoverageIgnore Method as pulled upstream is accompanied by unit tests.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the plus/minus token.
     *
     * @return bool True if the token passed is a unary operator.
     *              False otherwise or if the token is not a T_PLUS/T_MINUS token.
     */
    public static function isUnaryPlusMinus(File $phpcsFile, $stackPtr)
    {
        return Operators::isUnaryPlusMinus($phpcsFile, $stackPtr);
    }

    /**
     * Get the complete contents of a multi-line text string.
     *
     * @since      9.3.0
     * @deprecated 10.0.0 Use {@see PHPCSUtils\Utils\TextStrings::getCompleteTextString()} instead.
     *
     * @codeCoverageIgnore Method as pulled upstream is accompanied by unit tests.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                   $stackPtr    Pointer to the first text string token
     *                                           of a multi-line text string or to a
     *                                           Nowdoc/Heredoc opener.
     * @param bool                  $stripQuotes Optional. Whether to strip text delimiter
     *                                           quotes off the resulting text string.
     *                                           Defaults to true.
     *
     * @return string
     *
     * @throws \PHP_CodeSniffer_Exception If the specified position is not a
     *                                    valid text string token or if the
     *                                    token is not the first text string token.
     */
    public function getCompleteTextString(File $phpcsFile, $stackPtr, $stripQuotes = true)
    {
        return TextStrings::getCompleteTextString($phpcsFile, $stackPtr, $stripQuotes);
    }
}
