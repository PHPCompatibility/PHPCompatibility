<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2022 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Interfaces;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the RemovedSerializable sniff.
 *
 * @group removedSerializable
 * @group interfaces
 *
 * @covers \PHPCompatibility\Sniffs\Interfaces\RemovedSerializableSniff
 *
 * @since 10.0.0
 */
class RemovedSerializableUnitTest extends BaseSniffTest
{

    /**
     * Test deprecated use of Serializable only classes is flagged.
     *
     * @dataProvider dataIsDeprecated
     *
     * @param int $line Line number where the warning should occur.
     *
     * @return void
     */
    public function testIsDeprecated($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3-');
        $this->assertWarning($file, $line, 'Only Serializable" classes are deprecated since PHP 8.1. The magic __serialize() and __unserialize() methods need to be implemented for cross-version compatibility');
    }

    /**
     * Data provider.
     *
     * @see testIsDeprecated()
     *
     * @return array
     */
    public function dataIsDeprecated()
    {
        return [
            [89],
            [98],
            [103],
            [109],
            [116],
            [152],
            [157],
            [162],
        ];
    }


    /**
     * Test that there are no false positives when PHP < 7.4 and PHP 7.4+ both need to be supported.
     *
     * @dataProvider dataNoFalsePositivesDeprecated
     *
     * @param int $line Line number where no error should occur.
     *
     * @return void
     */
    public function testNoFalsePositivesDeprecated($line)
    {
        $file = $this->sniffFile(__FILE__, '7.3-');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesDeprecated()
     *
     * @return array
     */
    public function dataNoFalsePositivesDeprecated()
    {
        $data = [];

        // No errors expected on the first 75 lines.
        for ($line = 1; $line <= 75; $line++) {
            $data[] = [$line];
        }

        $data[] = [172];
        $data[] = [179];
        $data[] = [188];
        $data[] = [189];

        // Parse error.
        $data[] = [203];

        return $data;
    }


    /**
     * Test deprecated use of Serializable with enums is flagged.
     *
     * @dataProvider dataIsDeprecatedOnEnum
     *
     * @param int $line Line number where the warning should occur.
     *
     * @return void
     */
    public function testIsDeprecatedOnEnum($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1-');
        $this->assertWarning($file, $line, 'The Serializable interface is deprecated since PHP 8.1.');
    }

    /**
     * Data provider.
     *
     * @see testIsDeprecated()
     *
     * @return array
     */
    public function dataIsDeprecatedOnEnum()
    {
        return [
            [191],
            [200],
        ];
    }


    /**
     * Test that there are no false positives for enums.
     *
     * @dataProvider dataNoFalsePositivesDeprecatedOnEnum
     *
     * @param int $line Line number where no error should occur.
     *
     * @return void
     */
    public function testNoFalsePositivesDeprecatedOnEnum($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1-');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesDeprecated()
     *
     * @return array
     */
    public function dataNoFalsePositivesDeprecatedOnEnum()
    {
        return [
            [188],
            [189],
        ];
    }


    /**
     * Test that a warning is thrown when the Serializable interface implementation is not needed.
     *
     * @dataProvider dataRedundantImplementation
     *
     * @param int $line Line number where the warning should occur.
     *
     * @return void
     */
    public function testRedundantImplementation($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4-');
        $this->assertWarning($file, $line, 'When the magic __serialize() and __unserialize() methods are available and the code base does not need to support PHP < 7.4, the implementation of the Serializable interface can be removed');
    }

    /**
     * Data provider.
     *
     * @see testRedundantImplementation()
     *
     * @return array
     */
    public function dataRedundantImplementation()
    {
        return [
            [39],
            [54],
            [61],
            [69],
        ];
    }


    /**
     * Test that a warning is thrown when the Serializable interface extension is not needed.
     *
     * @dataProvider dataRedundantExtension
     *
     * @param int $line Line number where the warning should occur.
     *
     * @return void
     */
    public function testRedundantExtension($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4-');
        $this->assertWarning($file, $line, 'When an interface enforces implementation of the magic __serialize() and __unserialize() methods and the code base does not need to support PHP < 7.4, the interface no longer needs to extend the Serializable interface.');
    }

    /**
     * Data provider.
     *
     * @see testRedundantExtension()
     *
     * @return array
     */
    public function dataRedundantExtension()
    {
        return [
            [78],
        ];
    }


    /**
     * Test that there are no false positives for the "redundant" notice when only PHP 7.4+ need to be supported.
     *
     * @dataProvider dataNoFalsePositivesRedundant
     *
     * @param int $line Line number where no error should occur.
     *
     * @return void
     */
    public function testNoFalsePositivesRedundant($line)
    {
        $file = $this->sniffFile(__FILE__, '7.4-');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesRedundant()
     *
     * @return array
     */
    public function dataNoFalsePositivesRedundant()
    {
        $data = [];

        // Also no errors expected on the first 37 lines.
        for ($line = 1; $line <= 37; $line++) {
            $data[] = [$line];
        }

        return $data;
    }

    /**
     * Test that a warning is thrown an interface extending the Serializable interface is detected
     * and the interface is not in the extra interfaces list.
     *
     * @dataProvider dataMissingInterface
     *
     * @param int $line Line number where the warning should occur.
     *
     * @return void
     */
    public function testMissingInterface($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertWarning($file, $line, 'For the PHPCompatibility.Interface.RemovedSerializable sniff to be reliable, the name of this interface needs to be added to the list of interface implementations to find');
    }

    /**
     * Data provider.
     *
     * @see testMissingInterface()
     *
     * @return array
     */
    public function dataMissingInterface()
    {
        return [
            [78],
            [143],
            [145],
            [150],
            [180],
        ];
    }


    /**
     * Test that there are no false positives for the "missing interface" notice.
     *
     * @dataProvider dataNoFalsePositivesMissingInterface
     *
     * @param int $line Line number where no error should occur.
     *
     * @return void
     */
    public function testNoFalsePositivesMissingInterface($line)
    {
        $file = $this->sniffFile(__FILE__, '8.1');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesMissingInterface()
     *
     * @return array
     */
    public function dataNoFalsePositivesMissingInterface()
    {
        return [
            [12],
            [14],
            [141],
        ];
    }

    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '7.3-8.0');
        $this->assertNoViolation($file);
    }
}
