<?php
/**
 * Default timezone sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Default timezone required sniff test
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
        $file = $this->sniffFile('sniff-examples/timezone.php');

        $this->assertError($file, 1, 'Default timezone is required since PHP 5.4');
    }
}
