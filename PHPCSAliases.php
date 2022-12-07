<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * PHPCS cross-version compatibility helper.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 *
 * @since 8.0.0
 */

/*
 * Alias a number of PHPCS 3.x classes to their PHPCS 2.x equivalents.
 *
 * This file is auto-loaded by PHPCS 3.x before any sniffs are loaded
 * through the PHPCS 3.x `<autoload>` ruleset directive.
 */
if (defined('PHPCOMPATIBILITY_PHPCS_ALIASES_SET') === false) {
    define('PHPCOMPATIBILITY_PHPCS_ALIASES_SET', true);

    /*
     * Register an autoloader.
     *
     * {@internal When `installed_paths` is set via the ruleset, this autoloader
     * is needed to run the sniffs.
     * Upstream issue: {@link https://github.com/squizlabs/PHP_CodeSniffer/issues/1591} }
     *
     * @since 8.0.0
     */
    spl_autoload_register(function ($className) {
        // Only try & load our own classes.
        if (stripos($className, 'PHPCompatibility') !== 0) {
            return;
        }

        $file = realpath(__DIR__) . DIRECTORY_SEPARATOR . strtr($className, '\\', DIRECTORY_SEPARATOR) . '.php';

        if (file_exists($file)) {
            include_once $file;
        }
    });
}
