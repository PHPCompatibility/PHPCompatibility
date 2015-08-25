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

    public function supportsAbove($phpVersion)
    {
        if (
            is_null(PHP_CodeSniffer::getConfigData('testVersion'))
            ||
            (
                !is_null(PHP_CodeSniffer::getConfigData('testVersion'))
                &&
                version_compare(PHP_CodeSniffer::getConfigData('testVersion'), $phpVersion) >= 0
            )
        ) {
            return true;
        } else {
            return false;
        }
    }//end supportsAbove()

    public function supportsBelow($phpVersion)
    {
        if (
            !is_null(PHP_CodeSniffer::getConfigData('testVersion'))
            &&
            version_compare(PHP_CodeSniffer::getConfigData('testVersion'), $phpVersion) <= 0
        ) {
            return true;
        } else {
            return false;
        }
    }//end supportsBelow()

}//end class
