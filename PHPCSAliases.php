<?php
/**
 * PHPCS cross-version compatibility helper.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */


/*
 * Alias a number of PHPCS 3.x classes to their PHPCS 2.x equivalents.
 *
 * This file is auto-loaded by PHPCS 3.x before any sniffs are loaded
 * through the PHPCS 3.x `<autoload>` ruleset directive.
 *
 * {@internal The PHPCS file have been reorganized in PHPCS 3.x, quite
 * a few "old" classes have been split and spread out over several "new"
 * classes. In other words, this will only work for a limited number
 * of classes.}}
 *
 * {@internal The `class_exists` wrappers are needed to play nice with other
 * external PHPCS standards creating cross-version compatibility in the same
 * manner.}}
 */
if (defined('PHPCOMPATIBILITY_PHPCS_ALIASES_SET') === false) {
    if (class_exists('\PHP_CodeSniffer_Sniff') === false) {
        class_alias('PHP_CodeSniffer\Sniffs\Sniff', '\PHP_CodeSniffer_Sniff');
    }
    if (class_exists('\PHP_CodeSniffer_File') === false) {
        class_alias('PHP_CodeSniffer\Files\File', '\PHP_CodeSniffer_File');
    }
    if (class_exists('\PHP_CodeSniffer_Tokens') === false) {
        class_alias('PHP_CodeSniffer\Util\Tokens', '\PHP_CodeSniffer_Tokens');
    }
    if (class_exists('\PHP_CodeSniffer_Exception') === false) {
        class_alias('PHP_CodeSniffer\Exceptions\RuntimeException', '\PHP_CodeSniffer_Exception');
    }

    define('PHPCOMPATIBILITY_PHPCS_ALIASES_SET', true);
}
