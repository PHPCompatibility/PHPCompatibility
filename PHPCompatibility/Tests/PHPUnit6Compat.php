<?php
/**
 * Compatibility layer for PHPUnit 6+.
 *
 * {@internal This file is only loaded when the `PHPUnit\Runner\Version`
 * class exists which automatically means we will be on PHP 5.6 or higher as
 * otherwise PHPUnit 6 would not be running in the first place, so use of
 * the PHP 5.3 `class_alias` function is safe here.}}
 *
 * @package PHPCompatibility
 */

if (version_compare(PHPUnit\Runner\Version::id(), '6.0', '>=')
    && class_exists('PHPUnit_Framework_TestCase') === false
) {
    class_alias('PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}
