<?php
/**
 * Default timezone sniff test file
 *
 * @package PHPCompatibility
 */

/**
 * Default timezone required sniff test
 *
 * @group defaultTimezoneRequired
 * @group iniDirectives
 *
 * @covers PHPCompatibility_Sniffs_PHP_DefaultTimezoneRequiredSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class DefaultTimezoneRequiredSniffTest extends BaseSniffTest
{
    /**
     * Test ini timezone setting
     *
     * @return void
     */
    public function testIniTimezoneIsSet()
    {
        $timezone = ini_get('date.timezone');

        // We'll supress this so PHPunit wont catch the warning
        @ini_set('date.timezone', false);

        $file = $this->sniffFile('sniff-examples/timezone.php');

        $this->assertError($file, 1, 'Default timezone is required since PHP 5.4');

        ini_set('date.timezone', $timezone);
    }

    /**
     * Test setting the testVersion in the PHPCS object
     *
     * @return void
     */
    public function testSettingTestVersion()
    {
        $file = $this->sniffFile('sniff-examples/timezone.php', '5.3');

        $this->assertNoViolation($file, 1);
    }
}
