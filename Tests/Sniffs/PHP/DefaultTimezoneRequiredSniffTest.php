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
    const TEST_FILE = 'sniff-examples/timezone.php';

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

        $file = $this->sniffFile(self::TEST_FILE, '5.4');

        $this->assertError($file, 1, 'Default timezone is required since PHP 5.4');

        ini_set('date.timezone', $timezone);
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file);
    }

}
