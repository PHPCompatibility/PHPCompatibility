<?php
/**
 * Bootstrap file for tests
 *
 * @package PHPCompatibility
 */

if (defined('PHP_CODESNIFFER_IN_TESTS') === false) {
    define('PHP_CODESNIFFER_IN_TESTS', true);
}

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseSniffTest.php';

