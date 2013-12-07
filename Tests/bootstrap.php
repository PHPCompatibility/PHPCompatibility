<?php
/**
 * Bootstrap file for tests
 *
 * @package PHPCompatibility
 */

if (defined('PHP_CODESNIFFER_IN_TESTS') === false) {
    define('PHP_CODESNIFFER_IN_TESTS', true);
}


defined('TRAVIS_CI_TEST') || define('TRAVIS_CI_TEST', 0);

if (TRAVIS_CI_TEST == 1) {
    $vendorDir = __DIR__ . '/../../../../../';
    if (!@include($vendorDir . '/autoload.php')) {
        die("You must set up the project dependencies, run the following commands:
    wget http://getcomposer.org/composer.phar
    php composer.phar install
    ");
    }
}

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseSniffTest.php';

