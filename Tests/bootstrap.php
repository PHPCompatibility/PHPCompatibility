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

$phpunitVersion = 0;
exec('phpunit --version', $output);
if (preg_match('`PHPUnit ([0-9\.]+) by Sebastian Bergmann`', implode(' ', $output), $matches) > 0 && isset($matches[1])) {
    $phpunitVersion = $matches[1];
}

if (version_compare($phpunitVersion, '6.0', '>') === true) {
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TestcaseWrapper_v6.php';
} else {
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TestcaseWrapper.php';
}

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseSniffTest.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseClass' . DIRECTORY_SEPARATOR . 'MethodTestFrame.php';
