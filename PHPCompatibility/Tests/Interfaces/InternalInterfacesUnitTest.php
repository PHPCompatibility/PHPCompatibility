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
     * Sniffed file
     *
     * @var \PHP_CodeSniffer\Files\File
     */
    protected $sniffResult;

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
        $this->sniffResult = $this->sniffFile(__FILE__);
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
        $this->assertError($this->sniffResult, $line, $this->messages[$type]);
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
        $this->assertError($this->sniffResult, 9, 'The interface DATETIMEINTERFACE is intended for type hints only and is not implementable or extendable.');
        $this->assertError($this->sniffResult, 10, 'The interface datetimeinterface is intended for type hints only and is not implementable or extendable.');
    }

    /**
     * Test the sniff doesn't throw false positives for valid code.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $this->assertNoViolation($this->sniffResult, $line);
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
            [13],
            [14],
            [23],
            [24],
            [27],
            [28],
            [32],
            [33],
        ];
    }


    /*
     * `testNoViolationsInFileOnValidVersion` test omitted as this sniff is version independent.
     */

    /**
     * If classes with same name are used in other namespaces, they should not be flagged.
     *
     * @return void
     */
    public function testNoViolationsInFileIfOtherNamespace()
    {
        $file          = $this->sniffFile(__DIR__ . '/InternalInterfacesUsesUnitTest.inc', '4.4');
        $sharedRuleSet = $file->ruleset;
        $sharedConfig  = $file->config;

        $forgedLocalFile = new \PHP_CodeSniffer\Files\LocalFile(
            realpath(__DIR__ . '/InternalInterfacesUnitTest.inc'),
            $sharedRuleSet,
            $sharedConfig
        );
        $forgedLocalFile->parse();
        $forgedLocalFile->process();

        $this->assertNoViolation($file);
        $this->assertError(
            $forgedLocalFile,
            3,
            'The interface Traversable shouldn\'t be implemented directly, implement the Iterator or IteratorAggregate interface instead.'
        );
    }
}
