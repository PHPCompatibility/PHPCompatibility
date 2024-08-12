<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Classes;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedClasses sniff.
 *
 * @group removedClasses
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\RemovedClassesSniff
 *
 * @since 10.0.0
 */
class RemovedClassesUnitTest extends BaseSniffTestCase
{

    /**
     * testRemovedClass
     *
     * @dataProvider dataRemovedClass
     *
     * @param string $className      Class name.
     * @param string $removedIn      The PHP version in which the class was removed.
     * @param array  $lines          The line numbers in the test file which apply to this class.
     * @param string $okVersion      A PHP version in which the class was still valid.
     * @param string $removedVersion Optional PHP version to test removed message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedClass($className, $removedIn, $lines, $okVersion, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The built-in class {$className} is removed since PHP {$removedIn}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedClass()
     *
     * @return array
     */
    public static function dataRemovedClass()
    {
        return [
            ['HW_API', '5.2', [59], '5.1'],
            ['HW_API_Object', '5.2', [60, 102, 115], '5.1'],
            ['HW_API_Attribute', '5.2', [61, 102, 115], '5.1'],
            ['HW_API_Error', '5.2', [62], '5.1'],
            ['HW_API_Content', '5.2', [63, 91], '5.1'],
            ['HW_API_Reason', '5.2', [64, 91], '5.1'],

            ['SWFAction', '5.3', [32, 33, 34, 35], '5.2'],
            ['SWFBitmap', '5.3', [37], '5.2'],
            ['SWFButton', '5.3', [38, 106], '5.2'],
            ['SWFDisplayItem', '5.3', [39, 84], '5.2'],
            ['SWFFill', '5.3', [40, 92], '5.2'],
            ['SWFFont', '5.3', [41, 92], '5.2'],
            ['SWFFontChar', '5.3', [44], '5.2'],
            ['SWFGradient', '5.3', [45], '5.2'],
            ['SWFMorph', '5.3', [46, 100, 113], '5.2'],
            ['SWFMovie', '5.3', [47, 100, 113], '5.2'],
            ['SWFPrebuiltClip', '5.3', [48], '5.2'],
            ['SWFShape', '5.3', [49], '5.2'],
            ['SWFSound', '5.3', [50], '5.2'],
            ['SWFSoundInstance', '5.3', [51], '5.2'],
            ['SWFSprite', '5.3', [52, 101, 114], '5.2'],
            ['SWFText', '5.3', [55, 101, 114], '5.2'],
            ['SWFTextField', '5.3', [56, 85], '5.2'],
            ['SWFVideoStream', '5.3', [57], '5.2'],

            ['SQLiteDatabase', '5.4', [66, 80], '5.3'],
            ['SQLiteResult', '5.4', [67, 93], '5.3'],
            ['SQLiteUnbuffered', '5.4', [68, 93], '5.3'],
            ['SQLiteException', '5.4', [69, 107], '5.3'],

            ['XmlRpcServer', '8.0', [71, 74], '7.4'],

            ['IMAP\Connection', '8.4', [118], '8.3'],
        ];
    }


    /**
     * Verify the sniff doesn't flag classes which aren't deprecated/removed.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond latest deprecation.
        $this->assertNoViolation($file, $line);
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
        // No errors expected on the first 26 lines.
        $data = [];
        for ($line = 1; $line <= 26; $line++) {
            $data[] = [$line];
        }

        $data[] = [77];
        $data[] = [89];
        $data[] = [98];
        $data[] = [111];

        return $data;
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.0'); // Low version below the first deprecation.
        $this->assertNoViolation($file);
    }
}
