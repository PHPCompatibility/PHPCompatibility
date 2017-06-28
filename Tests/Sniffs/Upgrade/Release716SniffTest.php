<?php
/**
 * Temporary warning about the upcoming 7.1.6 release.
 *
 * @package PHPCompatibility
 */


/**
 * Temporary warning about the upcoming 7.1.6 release.
 *
 * @group upgrade
 *
 * @covers PHPCompatibility_Sniffs_Upgrade_Release716Sniff
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class Release716SniffTest extends BaseSniffTest
{
    /**
     * testUpgradeNotice
     *
     * Ensures that the upgrade notice is only thrown for the first file.
     *
     * @return void
     */
    public function testUpgradeNotice()
    {
        $file = $this->sniffFile('sniff-examples/forbidden_closure_use_variable_names.php');
        $this->assertWarning($file, 1, 'IMPORTANT NOTICE:');

        $file = $this->sniffFile('sniff-examples/empty_non_variable.php');
        $this->assertNoViolation($file, 1);
    }

    /**
     * Get the sniff code for the current sniff being tested.
     *
     * @return string
     */
    protected function getSniffCode()
    {
        return self::STANDARD_NAME . '.Upgrade.' . str_replace('SniffTest', '', get_class($this));
    }
}
