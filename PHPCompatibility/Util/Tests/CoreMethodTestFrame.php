<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests;

use PHPCSUtils\TestUtils\UtilityMethodTestCase;
use PHPCompatibility\Util\Tests\TestHelperPHPCompatibility;

/**
 * Base class to use when testing utility methods.
 *
 * Set up and Tear down methods for testing methods in the Sniff.php file.
 *
 * @since 7.0.3
 * @since 7.0.5  Renamed from `BaseAbstractClassMethodTest` to `CoreMethodTestFrame`.
 * @since 7.1.2  No longer extends the `BaseSniffTest` class.
 * @since 10.0.0 Now extends the PHPCSUtils `UtilityMethodTestCase`.
 */
abstract class CoreMethodTestFrame extends UtilityMethodTestCase
{

    /**
     * Instance of the PHPCompatibility Sniff test double.
     *
     * @since 7.0.3
     *
     * @var \PHPCompatibility\Util\Tests\TestHelperPHPCompatibility
     */
    public static $helperClass;

    /**
     * Initialize PHPCS & tokenize the test case file.
     *
     * @beforeClass
     *
     * @since 10.0.0
     *
     * @return void
     */
    public static function setUpTestFile()
    {
        parent::setUpTestFile();
        self::$helperClass = new TestHelperPHPCompatibility();
    }
}
