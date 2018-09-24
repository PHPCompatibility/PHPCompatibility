<?php
/**
 * Removed Constants Sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Constants;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Removed Constants Sniff tests
 *
 * @group removedConstants
 * @group constants
 *
 * @covers \PHPCompatibility\Sniffs\Constants\RemovedConstantsSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class RemovedConstantsUnitTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/removed_constants.php';

    /**
     * testDeprecatedConstant
     *
     * @dataProvider dataDeprecatedConstant
     *
     * @param string $constantName      Name of the PHP constant.
     * @param string $deprecatedIn      The PHP version in which the constant was deprecated.
     * @param array  $lines             The line numbers in the test file which apply to this constant.
     * @param string $okVersion         A PHP version in which the constant was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     *
     * @return void
     */
    public function testDeprecatedConstant($constantName, $deprecatedIn, $lines, $okVersion, $deprecatedVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(self::TEST_FILE, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn}";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedConstant()
     *
     * @return array
     */
    public function dataDeprecatedConstant()
    {
        return array(
            array('INTL_IDNA_VARIANT_2003', '7.2', array(16), '7.1'),
            array('FILTER_FLAG_SCHEME_REQUIRED', '7.3', array(73), '7.2'),
            array('FILTER_FLAG_HOST_REQUIRED', '7.3', array(74), '7.2'),
        );
    }


    /**
     * testRemovedConstant
     *
     * @dataProvider dataRemovedConstant
     *
     * @param string $constantName   Name of the PHP constant.
     * @param string $removedIn      The PHP version in which the constant was removed.
     * @param array  $lines          The line numbers in the test file which apply to this constant.
     * @param string $okVersion      A PHP version in which the constant was still valid.
     * @param string $removedVersion Optional PHP version to test removed message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedConstant($constantName, $removedIn, $lines, $okVersion, $removedVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(self::TEST_FILE, $errorVersion);
        $error        = "The constant \"{$constantName}\" is removed since PHP {$removedIn}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedConstant()
     *
     * @return array
     */
    public function dataRemovedConstant()
    {
        return array(
            array('FILEINFO_COMPRESS', '5.3', array(8), '5.2'),
            array('CURLOPT_CLOSEPOLICY', '5.6', array(9), '5.5'),
            array('CURLCLOSEPOLICY_LEAST_RECENTLY_USED', '5.6', array(10), '5.5'),
            array('CURLCLOSEPOLICY_LEAST_TRAFFIC', '5.6', array(11), '5.5'),
            array('CURLCLOSEPOLICY_SLOWEST', '5.6', array(12), '5.5'),
            array('CURLCLOSEPOLICY_CALLBACK', '5.6', array(13), '5.5'),
            array('CURLCLOSEPOLICY_OLDEST', '5.6', array(14), '5.5'),
            array('PGSQL_ATTR_DISABLE_NATIVE_PREPARED_STATEMENT', '7.0', array(15), '5.6'),
            array('PHPDBG_FILE', '7.3', array(69), '7.2'),
            array('PHPDBG_METHOD', '7.3', array(70), '7.2'),
            array('PHPDBG_LINENO', '7.3', array(71), '7.2'),
            array('PHPDBG_FUNC', '7.3', array(72), '7.2'),
        );
    }


    /**
     * testDeprecatedRemovedConstant
     *
     * @dataProvider dataDeprecatedRemovedConstant
     *
     * @param string $constantName      Name of the PHP constant.
     * @param string $deprecatedIn      The PHP version in which the constant was deprecated.
     * @param string $removedIn         The PHP version in which the constant was removed.
     * @param array  $lines             The line numbers in the test file which apply to this constant.
     * @param string $okVersion         A PHP version in which the constant was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $removedVersion    Optional PHP version to test removed message with -
     *                                  if different from the $removedIn version.
     *
     * @return void
     */
    public function testDeprecatedRemovedConstant($constantName, $deprecatedIn, $removedIn, $lines, $okVersion, $deprecatedVersion = null, $removedVersion = null)
    {
        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(self::TEST_FILE, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn}";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(self::TEST_FILE, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedRemovedConstant()
     *
     * @return array
     */
    public function dataDeprecatedRemovedConstant()
    {
        return array(
            array('MCRYPT_MODE_ECB', '7.1', '7.2', array(17), '7.0'),
            array('MCRYPT_MODE_CBC', '7.1', '7.2', array(18), '7.0'),
            array('MCRYPT_MODE_CFB', '7.1', '7.2', array(19), '7.0'),
            array('MCRYPT_MODE_OFB', '7.1', '7.2', array(20), '7.0'),
            array('MCRYPT_MODE_NOFB', '7.1', '7.2', array(21), '7.0'),
            array('MCRYPT_MODE_STREAM', '7.1', '7.2', array(22), '7.0'),
            array('MCRYPT_ENCRYPT', '7.1', '7.2', array(23), '7.0'),
            array('MCRYPT_DECRYPT', '7.1', '7.2', array(24), '7.0'),
            array('MCRYPT_DEV_RANDOM', '7.1', '7.2', array(25), '7.0'),
            array('MCRYPT_DEV_URANDOM', '7.1', '7.2', array(26), '7.0'),
            array('MCRYPT_RAND', '7.1', '7.2', array(27), '7.0'),
            array('MCRYPT_3DES', '7.1', '7.2', array(28), '7.0'),
            array('MCRYPT_ARCFOUR_IV', '7.1', '7.2', array(29), '7.0'),
            array('MCRYPT_ARCFOUR', '7.1', '7.2', array(30), '7.0'),
            array('MCRYPT_BLOWFISH', '7.1', '7.2', array(31), '7.0'),
            array('MCRYPT_CAST_128', '7.1', '7.2', array(32), '7.0'),
            array('MCRYPT_CAST_256', '7.1', '7.2', array(33), '7.0'),
            array('MCRYPT_CRYPT', '7.1', '7.2', array(34), '7.0'),
            array('MCRYPT_DES', '7.1', '7.2', array(35), '7.0'),
            array('MCRYPT_DES_COMPAT', '7.1', '7.2', array(36), '7.0'),
            array('MCRYPT_ENIGMA', '7.1', '7.2', array(37), '7.0'),
            array('MCRYPT_GOST', '7.1', '7.2', array(38), '7.0'),
            array('MCRYPT_IDEA', '7.1', '7.2', array(39), '7.0'),
            array('MCRYPT_LOKI97', '7.1', '7.2', array(40), '7.0'),
            array('MCRYPT_MARS', '7.1', '7.2', array(41), '7.0'),
            array('MCRYPT_PANAMA', '7.1', '7.2', array(42), '7.0'),
            array('MCRYPT_RIJNDAEL_128', '7.1', '7.2', array(43), '7.0'),
            array('MCRYPT_RIJNDAEL_192', '7.1', '7.2', array(44), '7.0'),
            array('MCRYPT_RIJNDAEL_256', '7.1', '7.2', array(45), '7.0'),
            array('MCRYPT_RC2', '7.1', '7.2', array(46), '7.0'),
            array('MCRYPT_RC4', '7.1', '7.2', array(47), '7.0'),
            array('MCRYPT_RC6', '7.1', '7.2', array(48), '7.0'),
            array('MCRYPT_RC6_128', '7.1', '7.2', array(49), '7.0'),
            array('MCRYPT_RC6_192', '7.1', '7.2', array(50), '7.0'),
            array('MCRYPT_RC6_256', '7.1', '7.2', array(51), '7.0'),
            array('MCRYPT_SAFER64', '7.1', '7.2', array(52), '7.0'),
            array('MCRYPT_SAFER128', '7.1', '7.2', array(53), '7.0'),
            array('MCRYPT_SAFERPLUS', '7.1', '7.2', array(54), '7.0'),
            array('MCRYPT_SERPENT', '7.1', '7.2', array(55), '7.0'),
            array('MCRYPT_SERPENT_128', '7.1', '7.2', array(56), '7.0'),
            array('MCRYPT_SERPENT_192', '7.1', '7.2', array(57), '7.0'),
            array('MCRYPT_SERPENT_256', '7.1', '7.2', array(58), '7.0'),
            array('MCRYPT_SKIPJACK', '7.1', '7.2', array(59), '7.0'),
            array('MCRYPT_TEAN', '7.1', '7.2', array(60), '7.0'),
            array('MCRYPT_THREEWAY', '7.1', '7.2', array(61), '7.0'),
            array('MCRYPT_TRIPLEDES', '7.1', '7.2', array(62), '7.0'),
            array('MCRYPT_TWOFISH', '7.1', '7.2', array(63), '7.0'),
            array('MCRYPT_TWOFISH128', '7.1', '7.2', array(64), '7.0'),
            array('MCRYPT_TWOFISH192', '7.1', '7.2', array(65), '7.0'),
            array('MCRYPT_TWOFISH256', '7.1', '7.2', array(66), '7.0'),
            array('MCRYPT_WAKE', '7.1', '7.2', array(67), '7.0'),
            array('MCRYPT_XTEA', '7.1', '7.2', array(68), '7.0'),
        );
    }


    /**
     * Test functions that shouldn't be flagged by this sniff.
     *
     * These are either userland methods or namespaced functions.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '99.0'); // High version beyond latest deprecation.
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
        return array(
            array(4),
            array(5),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.0'); // Low version below the first deprecation.
        $this->assertNoViolation($file);
    }

}
