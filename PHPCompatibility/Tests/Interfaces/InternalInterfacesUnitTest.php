<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Interfaces;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the InternalInterfaces sniff.
 *
 * @group internalInterfaces
 * @group interfaces
 *
 * @covers \PHPCompatibility\Sniffs\Interfaces\InternalInterfacesSniff
 *
 * @since 7.0.3
 */
class InternalInterfacesUnitTest extends BaseSniffTestCase
{

    /**
     * The name of the main test case file.
     *
     * @var string
     */
    const TEST_FILE = 'InternalInterfacesUnitTest.inc';

    /**
     *  The name of a test file containing reused internal interface names.
     *
     * @var string
     */
    const TEST_FILE_NAMESPACED = 'InternalInterfacesUnitTest2.inc';

    /**
     * Sniffed files.
     *
     * @var \PHP_CodeSniffer\Files\File[]
     */
    protected $sniffResults;

    /**
     * Interface error messages.
     *
     * @var array<string, string>
     */
    protected $messages = [
        'Traversable'       => 'The interface Traversable shouldn\'t be implemented directly, implement the Iterator or IteratorAggregate interface instead.',
        'DateTimeInterface' => 'The interface DateTimeInterface is intended for type hints only and is not implementable or extendable.',
        'Throwable'         => 'The interface Throwable cannot be implemented directly, extend the Exception class instead.',
        'UnitEnum'          => 'is intended for type hints only and is not implementable or extendable.',
        'BackedEnum'        => 'is intended for type hints only and is not implementable or extendable.',
    ];

    /**
     * Set up the test file for this unit test.
     *
     * @before
     *
     * @return void
     */
    protected function setUpPHPCS()
    {
        // Sniff file without testVersion as all checks run independently of testVersion being set.
        foreach ([self::TEST_FILE, self::TEST_FILE_NAMESPACED] as $file) {
            $this->sniffResults[$file] = $this->sniffFile(__DIR__ . '/' . $file);
        }
    }

    /**
     * Test detection of use of internal interfaces.
     *
     * @dataProvider dataInternalInterfaces
     *
     * @param string $type Interface name.
     * @param int    $line The line number in the test file.
     *
     * @return void
     */
    public function testInternalInterfaces($type, $line)
    {
        $this->assertError($this->sniffResults[self::TEST_FILE], $line, $this->messages[$type]);
    }

    /**
     * Data provider.
     *
     * @see testInternalInterfaces()
     *
     * @return array
     */
    public static function dataInternalInterfaces()
    {
        return [
            ['Traversable', 3],
            ['DateTimeInterface', 4],
            ['Throwable', 5],
            ['Traversable', 7],
            ['Throwable', 7],

            // Anonymous classes.
            ['Traversable', 17],
            ['DateTimeInterface', 18],
            ['Throwable', 19],
            ['Traversable', 20],
            ['Throwable', 20],

            // Interface extends ...
            ['DateTimeInterface', 29],

            // Enums.
            ['Traversable', 34],
            ['DateTimeInterface', 35],
            ['Throwable', 36],
            ['Traversable', 37],
            ['Throwable', 37],

            ['UnitEnum', 40],
            ['UnitEnum', 41],
            ['BackedEnum', 42],
            ['BackedEnum', 43],
        ];
    }

    /**
     * Test interfaces in different cases.
     *
     * @return void
     */
    public function testCaseInsensitive()
    {
        $this->assertError($this->sniffResults[self::TEST_FILE], 9, 'The interface DATETIMEINTERFACE is intended for type hints only and is not implementable or extendable.');
        $this->assertError($this->sniffResults[self::TEST_FILE], 10, 'The interface datetimeinterface is intended for type hints only and is not implementable or extendable.');
    }

    /**
     * Test the sniff doesn't throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param string $file The file to test.
     * @param int    $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($file, $line)
    {
        $this->assertNoViolation($this->sniffResults[$file], $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        return [
            [self::TEST_FILE, 13],
            [self::TEST_FILE, 14],
            [self::TEST_FILE, 23],
            [self::TEST_FILE, 24],
            [self::TEST_FILE, 27],
            [self::TEST_FILE, 28],
            [self::TEST_FILE, 32],
            [self::TEST_FILE, 33],
            [self::TEST_FILE_NAMESPACED, 8],
            [self::TEST_FILE_NAMESPACED, 9],
            [self::TEST_FILE_NAMESPACED, 10],
            [self::TEST_FILE_NAMESPACED, 11],
            [self::TEST_FILE_NAMESPACED, 12],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff is version independent.
     */
}
