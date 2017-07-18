<?php
/**
 * Bootstrap file for tests.
 *
 * @package PHPCompatibility
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

if ($phpcsDir === false) {
    // Ok, no environment variable set, so this might be a PEAR install of PHPCS.
    // @todo fix path for PEAR install!
    $phpcsDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';
}
$phpcsDir .= DIRECTORY_SEPARATOR;

if (file_exists($phpcsDir . 'autoload.php')) {
    // PHPCS 3.x.
    require $phpcsDir . 'autoload.php';

} elseif (file_exists($phpcsDir . 'CodeSniffer.php')) {
    // PHPCS 1.x, 2.x.
    require $phpcsDir . 'CodeSniffer.php';

} else {
    // Otherwise we must be in a composer install.
    $vendorDir        = __DIR__ . '/../../vendor';
    $composerAutoload = $vendorDir . DIRECTORY_SEPARATOR . 'autoload.php';
    $phpcsAutoload    = $vendorDir . DIRECTORY_SEPARATOR . 'squizlabs' . DIRECTORY_SEPARATOR . 'php_codesniffer' . DIRECTORY_SEPARATOR . 'autoload.php';

    if (!@include($composerAutoload)) {
        echo 'You must set up the project dependencies, run the following commands:
    wget http://getcomposer.org/composer.phar
    php composer.phar install
    ';
        die(1);
    }

    // PHPCS 3.x no longer uses Composer autoload, so include the PHPCS native autoloader.
    if (file_exists($phpcsAutoload)) {
        require_once $phpcsAutoload;
    }
}

// PHPUnit cross version compatibility.
if (class_exists('PHPUnit\Runner\Version')
    && version_compare(PHPUnit\Runner\Version::id(), '6.0', '>=')
    && class_exists('PHPUnit_Framework_TestCase') === false
) {
    class_alias('PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}


require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'PHPCSHelper.php';

/*
 * Alias the PHPCS 3.x classes to their PHPCS 2.x equivalent if necessary.
 * Also provide a custom autoloader for our abstract base classes as the PHPCS native autoloader
 * has trouble with them in combination with the PHPCompatibility custom unit test suite.
 */
if (version_compare(\PHPCompatibility\PHPCSHelper::getVersion(), '2.99.99', '>')) {
    include_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'PHPCSAliases.php';
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'BaseSniffTest.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'BaseClass' . DIRECTORY_SEPARATOR . 'MethodTestFrame.php';
