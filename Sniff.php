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

    private function getTestVersion()
	{
        static $testVersion;

        if (!isset($testVersion)) {
            $testVersion = PHP_CodeSniffer::getConfigData('testVersion');
        }

        return $testVersion;
    }

    public function supportsAbove($phpVersion)
    {

		$testVersion = $this->getTestVersion();

        if (
            is_null($testVersion)
            ||
            (
                !is_null($testVersion)
                &&
                version_compare($testVersion, $phpVersion) >= 0
            )
        ) {
            return true;
        } else {
            return false;
        }
    }//end supportsAbove()

    public function supportsBelow($phpVersion)
    {

		$testVersion = $this->getTestVersion();

        if (
            !is_null($testVersion)
            &&
            version_compare($testVersion, $phpVersion) <= 0
        ) {
            return true;
        } else {
            return false;
        }
    }//end supportsBelow()

}//end class
