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

use PHPCompatibility\Tests\BaseSniffTest;

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
class RemovedClassesUnitTest extends BaseSniffTest
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
    public function dataRemovedClass()
    {
        return array(
            array('HW_API', '5.2', array(59), '5.1'),
            array('HW_API_Object', '5.2', array(60), '5.1'),
            array('HW_API_Attribute', '5.2', array(61), '5.1'),
            array('HW_API_Error', '5.2', array(62), '5.1'),
            array('HW_API_Content', '5.2', array(63), '5.1'),
            array('HW_API_Reason', '5.2', array(64), '5.1'),

            array('SWFAction', '5.3', array(32, 33, 34, 35), '5.2'),
            array('SWFBitmap', '5.3', array(37), '5.2'),
            array('SWFButton', '5.3', array(38), '5.2'),
            array('SWFDisplayItem', '5.3', array(39), '5.2'),
            array('SWFFill', '5.3', array(40), '5.2'),
            array('SWFFont', '5.3', array(41), '5.2'),
            array('SWFFontChar', '5.3', array(44), '5.2'),
            array('SWFGradient', '5.3', array(45), '5.2'),
            array('SWFMorph', '5.3', array(46), '5.2'),
            array('SWFMovie', '5.3', array(47), '5.2'),
            array('SWFPrebuiltClip', '5.3', array(48), '5.2'),
            array('SWFShape', '5.3', array(49), '5.2'),
            array('SWFSound', '5.3', array(50), '5.2'),
            array('SWFSoundInstance', '5.3', array(51), '5.2'),
            array('SWFSprite', '5.3', array(52), '5.2'),
            array('SWFText', '5.3', array(55), '5.2'),
            array('SWFTextField', '5.3', array(56), '5.2'),
            array('SWFVideoStream', '5.3', array(57), '5.2'),

            array('SQLiteDatabase', '5.4', array(66), '5.3'),
            array('SQLiteResult', '5.4', array(67), '5.3'),
            array('SQLiteUnbuffered', '5.4', array(68), '5.3'),
            array('SQLiteException', '5.4', array(69), '5.3'),
        );
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
    public function dataNoFalsePositives()
    {
        // No errors expected on the first 26 lines.
        $data = array();
        for ($line = 1; $line <= 26; $line++) {
            $data[] = array($line);
        }

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
