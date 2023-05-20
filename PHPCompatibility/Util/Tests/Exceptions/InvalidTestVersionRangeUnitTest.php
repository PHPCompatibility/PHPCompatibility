<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Util\Tests\Exceptions;

use PHPCompatibility\Exceptions\InvalidTestVersionRange;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Test class.
 *
 * @covers \PHPCompatibility\Exceptions\InvalidTestVersionRange
 *
 * @since 1.0.0
 */
final class InvalidTestVersionRangeUnitTest extends TestCase
{

    /**
     * Test that the text of the exception is as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        $this->expectException('PHPCompatibility\Exceptions\InvalidTestVersionRange');
        $this->expectExceptionMessage('Invalid range in provided PHPCompatibility testVersion: \'7.0-5.6\'');

        throw InvalidTestVersionRange::create('7.0-5.6');
    }
}
