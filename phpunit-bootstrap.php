<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * Bootstrap file for tests.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 *
 * @since 5.5
 */

if (defined('PHP_CODESNIFFER_IN_TESTS') === false) {
    define('PHP_CODESNIFFER_IN_TESTS', true);
}

// The below two defines are needed for PHPCS 3.x.
if (defined('PHP_CODESNIFFER_CBF') === false) {
    define('PHP_CODESNIFFER_CBF', false);
}

if (defined('PHP_CODESNIFFER_VERBOSITY') === false) {
    define('PHP_CODESNIFFER_VERBOSITY', 0);
}

$ds = DIRECTORY_SEPARATOR;

// Get the PHPCS dir from an environment variable.
$phpcsDir = getenv('PHPCS_DIR');

// Get the PHPCSUtils dir from an environment variable.
$phpcsUtilsDir = getenv('PHPCSUTILS_DIR');

// This may be a Composer install.
if (is_dir(__DIR__ . $ds . 'vendor')) {
    $vendorDir = __DIR__ . $ds . 'vendor';
    if ($phpcsDir === false && is_dir($vendorDir . $ds . 'squizlabs' . $ds . 'php_codesniffer')) {
        $phpcsDir = $vendorDir . $ds . 'squizlabs' . $ds . 'php_codesniffer';
    }
    if ($phpcsUtilsDir === false && is_dir($vendorDir . $ds . 'phpcsstandards' . $ds . 'phpcsutils')) {
        $phpcsUtilsDir = $vendorDir . $ds . 'phpcsstandards' . $ds . 'phpcsutils';
    }
}

if ($phpcsDir !== false) {
    $phpcsDir = realpath($phpcsDir);
}

if ($phpcsUtilsDir !== false) {
    $phpcsUtilsDir = realpath($phpcsUtilsDir);
}

// Try and load the PHPCS autoloader.
if ($phpcsDir !== false && file_exists($phpcsDir . $ds . 'autoload.php')) {
    // PHPCS 3.x.
    require_once $phpcsDir . $ds . 'autoload.php';

    /*
     * Alias the PHPCS 3.x classes to their PHPCS 2.x equivalent if necessary.
     * Also provide a custom autoloader for our abstract base classes as the PHPCS native autoloader
     * has trouble with them in combination with the PHPCompatibility custom unit test suite.
     */
    require_once __DIR__ . $ds . 'PHPCSAliases.php';

} elseif ($phpcsDir !== false && file_exists($phpcsDir . $ds . 'CodeSniffer.php')) {
    // PHPCS 2.x.
    require_once $phpcsDir . $ds . 'CodeSniffer.php';

    if (isset($vendorDir) && file_exists($vendorDir . $ds . 'autoload.php')) {
        require_once $vendorDir . $ds . 'autoload.php';
    }

} else {
    echo 'Uh oh... can\'t find PHPCS.

If you use Composer, please run `composer install --prefer-source`.
Otherwise, make sure you set a `PHPCS_DIR` environment variable in your phpunit.xml file
pointing to the PHPCS directory.

Please read the contributors guidelines for more information:
https://is.gd/PHPCompatibilityContrib
';

    die(1);
}

// Try and load the PHPCSUtils autoloader.
if ($phpcsUtilsDir !== false && file_exists($phpcsUtilsDir . $ds . 'phpcsutils-autoload.php')) {
    require_once $phpcsUtilsDir . $ds . 'phpcsutils-autoload.php';
} else {
    echo 'Uh oh... can\'t find PHPCSUtils.

If you use Composer, please run `composer install --prefer-source`.
Otherwise, make sure you set a `PHPCSUTILS_DIR` environment variable in your phpunit.xml file
pointing to the PHPCSUtils directory.

Please read the contributors guidelines for more information:
https://is.gd/PHPCompatibilityContrib
';

    die(1);
}

// PHPUnit cross version compatibility.
if (class_exists('PHPUnit_Framework_TestCase') === true
    && class_exists('PHPUnit\Framework\TestCase') === false
) {
    class_alias('PHPUnit_Framework_TestCase', 'PHPUnit\Framework\TestCase');
}

require_once __DIR__ . $ds . 'PHPCompatibility' . $ds . 'Tests' . $ds . 'BaseSniffTest.php';
require_once __DIR__ . $ds . 'PHPCompatibility' . $ds . 'Util' . $ds . 'Tests' . $ds . 'CoreMethodTestFrame.php';
unset($ds, $phpcsDir, $vendorDir);
