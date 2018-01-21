<?php
/**
 * Bootstrap file for tests.
 *
 * @package PHPCompatibility
 */

/*
 * When possible let Xdebug (2.6.0+) handle code coverage file filtering.
 *
 * @internal This MUST be run BEFORE any files are included for optimal effect!
 *
 * @link https://bugs.xdebug.org/view.php?id=1059
 * @link https://github.com/sebastianbergmann/php-code-coverage/issues/514
 */
$testingCodeCoverage = getenv('COVERALLS_VERSION');
if ($testingCodeCoverage !== false && $testingCodeCoverage !== 'notset') {
    $xdebugVersion = phpversion('xdebug');
    if (version_compare($xdebugVersion, '2.5.99', '>') === true) {
        xdebug_set_filter(
            XDEBUG_FILTER_CODE_COVERAGE,
            XDEBUG_PATH_WHITELIST,
            array(dirname(__DIR__) . '/Sniffs')
        );
    }
}

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

// This may be a Composer install.
if ($phpcsDir === false && is_dir(dirname(dirname(__DIR__)) . $ds . 'vendor' . $ds . 'squizlabs' . $ds . 'php_codesniffer')) {
    $vendorDir = dirname(dirname(__DIR__)) . $ds . 'vendor';
    $phpcsDir  = $vendorDir . $ds . 'squizlabs' . $ds . 'php_codesniffer';
} elseif ($phpcsDir !== false) {
    $phpcsDir = realpath($phpcsDir);
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
    include_once dirname(dirname(__DIR__)) . $ds . 'PHPCSAliases.php';

} elseif ($phpcsDir !== false && file_exists($phpcsDir . $ds . 'CodeSniffer.php')) {
    // PHPCS 1.x, 2.x.
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


// PHPUnit cross version compatibility.
if (class_exists('PHPUnit\Runner\Version')
    && version_compare(PHPUnit\Runner\Version::id(), '6.0', '>=')
    && class_exists('PHPUnit_Framework_TestCase') === false
) {
    class_alias('PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}

require_once __DIR__ . $ds . 'BaseSniffTest.php';
require_once __DIR__ . $ds . 'BaseClass' . $ds . 'MethodTestFrame.php';
unset($ds, $phpcsDir, $vendorDir);
