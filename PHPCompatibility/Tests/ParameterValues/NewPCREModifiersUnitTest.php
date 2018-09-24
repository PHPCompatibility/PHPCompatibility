<?php
/**
 * New PCRE regex modifiers sniff test file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New PCRE regex modifiers sniff tests.
 *
 * @group newPCREModifiers
 * @group parameterValues
 * @group regexModifiers
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\NewPCREModifiersSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewPCREModifiersUnitTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/pcre_new_modifiers.php';

    /**
     * testPCRENewModifier
     *
     * @dataProvider dataPCRENewModifier
     *
     * @param string $modifier          Regex modifier.
     * @param string $lastVersionBefore The PHP version just *before* the modifier was introduced.
     * @param array  $lines             The line numbers in the test file which apply to this modifier.
     * @param string $okVersion         A PHP version in which the modifier was ok to be used.
     * @param string $testVersion       Optional PHP version to use for testing the flagged case.
     *
     * @return void
     */
    public function testPCRENewModifier($modifier, $lastVersionBefore, $lines, $okVersion, $testVersion = null)
    {
        $errorVersion = (isset($testVersion)) ? $testVersion : $lastVersionBefore;
        $file         = $this->sniffFile(self::TEST_FILE, $errorVersion);
        $error        = "The PCRE regex modifier \"{$modifier}\" is not present in PHP version {$lastVersionBefore} or earlier";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }

        $file = $this->sniffFile(self::TEST_FILE, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * dataPCRENewModifier
     *
     * @see testPCRENewModifier()
     *
     * @return array
     */
    public function dataPCRENewModifier()
    {
        return array(
            array('J', '7.1', array(3, 4, 6, 17, 19, 25), '7.2'),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number where no error should occur.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertNoViolation($file, $line);
    }

    /**
     * dataNoFalsePositives
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return array(
            array(18),
            array(28),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '99.0');
        $this->assertNoViolation($file);
    }

}
