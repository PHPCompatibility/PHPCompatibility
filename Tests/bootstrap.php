<?php
/**
 * Bootstrap file for tests
 *
 * @package PHPCompatibility
 */

if (defined('PHP_CODESNIFFER_IN_TESTS') === false) {
    define('PHP_CODESNIFFER_IN_TESTS', true);
}

// See if we are in a PEAR install or PHPCS.
$phpcsDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
if (file_exists($phpcsDir . 'CodeSniffer.php')) {
    require $phpcsDir . 'CodeSniffer.php';
}
else {
    // Otherwise we must be in a composer install.
    $vendorDir = __DIR__ . '/../vendor';

    if (!@include($vendorDir . '/autoload.php')) {
        die("You must set up the project dependencies, run the following commands:
    wget http://getcomposer.org/composer.phar
    php composer.phar install
    ");
    }
}

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseSniffTest.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseAbstractClassMethodTest.php';
