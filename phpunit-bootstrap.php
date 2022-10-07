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

// Get the PHPCS dir from an environment variable.
$phpcsDir = getenv('PHPCS_DIR');

// Get the PHPCSUtils dir from an environment variable.
$phpcsUtilsDir = getenv('PHPCSUTILS_DIR');

// Make sure that `composer install` has always been run.
if (is_dir(__DIR__ . '/vendor') && file_exists(__DIR__ . '/vendor/autoload.php')) {
    $vendorDir = __DIR__ . '/vendor';
    if ($phpcsDir === false && is_dir($vendorDir . '/squizlabs/php_codesniffer')) {
        $phpcsDir = $vendorDir . '/squizlabs/php_codesniffer';
    }
    if ($phpcsUtilsDir === false && is_dir($vendorDir . '/phpcsstandards/phpcsutils')) {
        $phpcsUtilsDir = $vendorDir . '/phpcsstandards/phpcsutils';
    }
} else {
    echo 'Please run `composer install` before attempting to run the unit tests.
You can still run the tests using a PHPUnit phar file, but some test dependencies need to be available.

Please read the contributors guidelines for more information:
https://is.gd/PHPCompatibilityContrib
';

    die(1);
}

if ($phpcsDir !== false) {
    $phpcsDir = realpath($phpcsDir);
}

if ($phpcsUtilsDir !== false) {
    $phpcsUtilsDir = realpath($phpcsUtilsDir);
}

// Try and load the PHPCS autoloader.
if ($phpcsDir !== false && file_exists($phpcsDir . '/autoload.php')) {
    // PHPCS 3.x.
    require_once $phpcsDir . '/autoload.php';

    /*
     * Provide a custom autoloader for our abstract base classes as the PHPCS native autoloader
     * has trouble with them in combination with the PHPCompatibility custom unit test suite.
     */
    require_once __DIR__ . '/PHPCSAliases.php';

    // Pre-load the token back-fills to prevent undefined constant notices.
    require_once $phpcsDir . '/src/Util/Tokens.php';

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
if ($phpcsUtilsDir !== false && file_exists($phpcsUtilsDir . '/phpcsutils-autoload.php')) {
    require_once $phpcsUtilsDir . '/phpcsutils-autoload.php';
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

if (defined('__PHPUNIT_PHAR__')) {
    // Testing via a PHPUnit phar.

    // Load the PHPUnit Polyfills autoloader.
    require_once $vendorDir . '/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';
} else {
    // Testing via a Composer setup.
    require_once $vendorDir . '/autoload.php';
}

// PHPUnit cross version compatibility.
if (class_exists('PHPUnit_Framework_TestCase') === true
    && class_exists('PHPUnit\Framework\TestCase') === false
) {
    class_alias('PHPUnit_Framework_TestCase', 'PHPUnit\Framework\TestCase');
}

require_once __DIR__ . '/PHPCompatibility/Tests/BaseSniffTest.php';
require_once __DIR__ . '/PHPCompatibility/Util/Tests/CoreMethodTestFrame.php';
unset($phpcsUtilsDir, $phpcsDir, $vendorDir);
