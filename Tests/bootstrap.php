<?php
/**
 * Bootstrap file for tests.
 *
 * @package PHPCompatibility
 */

if (defined('PHP_CODESNIFFER_IN_TESTS') === false) {
    define('PHP_CODESNIFFER_IN_TESTS', true);
}

// Get the PHPCS dir from an environment variable.
$phpcsDir = getenv('PHPCS_DIR');

if ($phpcsDir === false) {
    // Ok, no environment variable set, so this might be a PEAR install of PHPCS.
    $phpcsDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';
}
$phpcsDir .= DIRECTORY_SEPARATOR;

if (file_exists($phpcsDir . 'CodeSniffer.php')) {
    require $phpcsDir . 'CodeSniffer.php';
}
else {
    // Otherwise we must be in a composer install.
    $vendorDir = dirname(__FILE__) . '/../vendor';

    if (!@include($vendorDir . '/autoload.php')) {
        echo 'You must set up the project dependencies, run the following commands:
    wget http://getcomposer.org/composer.phar
    php composer.phar install
    ';
        die(1);
    }
}

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseSniffTest.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseClass' . DIRECTORY_SEPARATOR . 'MethodTestFrame.php';
