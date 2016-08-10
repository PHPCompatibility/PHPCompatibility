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
    public function findImplementedInterfaceNames($phpcsFile, $stackPtr)
    {
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

        // Ok, we know we have a T_STRING with parameters and valid open & close parenthesis.
        $tokens = $phpcsFile->getTokens();

        $openParenthesis = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true);
        $closeParenthesis = $tokens[$openParenthesis]['parenthesis_closer'];

        // Which nesting level is the one we are interested in ?
        $nestedParenthesisCount = 1;
        if (isset($tokens[$openParenthesis]['nested_parenthesis'])) {
            $nestedParenthesisCount = count($tokens[$openParenthesis]['nested_parenthesis']) + 1;
        }

        $nextComma = $openParenthesis;
        $cnt = 0;
        while ($nextComma = $phpcsFile->findNext(array(T_COMMA, T_CLOSE_PARENTHESIS), $nextComma + 1, $closeParenthesis + 1)) {
            // Ignore comma's at a lower nesting level.
            if (
                $tokens[$nextComma]['type'] == 'T_COMMA'
                &&
                isset($tokens[$nextComma]['nested_parenthesis'])
                &&
                count($tokens[$nextComma]['nested_parenthesis']) != $nestedParenthesisCount
            ) {
                continue;
            }

            // Ignore closing parenthesis if not 'ours'.
            if ($tokens[$nextComma]['type'] == 'T_CLOSE_PARENTHESIS' && $nextComma != $closeParenthesis) {
                continue;
            }

            $cnt++;
        }

        return $cnt;
    }

}//end class
