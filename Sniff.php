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
         * var $testVersion will hold an array containing min/max version of PHP
         *   that we are checking against (see above).  If only a single version
         *   number is specified, then this is used as both the min and max.
         */
        static $arrTestVersions;

        if (!isset($testVersion)) {
            $testVersion = PHP_CodeSniffer::getConfigData('testVersion');
            $testVersion = trim($testVersion);

            $arrTestVersions = null;
            if (preg_match('/^\d+\.\d+$/', $testVersion)) {
                $arrTestVersions = array($testVersion, $testVersion);
            }
            elseif (preg_match('/^(\d+\.\d+)\s*-\s*(\d+\.\d+)$/', $testVersion,
                               $matches))
            {
                if (version_compare($matches[1], $matches[2], ">")) {
                    trigger_error("Invalid range in testVersion setting: '"
                                  . $testVersion . "'", E_USER_WARNING);
                }
                else {
                    $arrTestVersions = array($matches[1], $matches[2]);
                }
            }
            elseif (!$testVersion == "") {
                trigger_error("Invalid testVersion setting: '" . $testVersion
                              . "'", E_USER_WARNING);
            }
        }

        return $arrTestVersions;
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

}//end class
