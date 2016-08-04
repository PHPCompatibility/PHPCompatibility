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


}//end class
