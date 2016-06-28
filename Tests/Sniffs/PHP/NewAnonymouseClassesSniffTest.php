<?php
/**
 * New Anonymous Classes Sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New Anonymous Classes Sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewAnonymousClassesSniffTest extends BaseSniffTest
{
    /**
     * Test anonymous classes
     *
     * @return void
     */
    public function testAnonymousClasses()
    {
        if (version_compare(PHP_CodeSniffer::VERSION, '2.3.4') >= 0) {
            $file = $this->sniffFile('sniff-examples/new_anonymous_classes.php', '5.6');
            $this->assertError($file, 4, "Anonymous classes are not supported in PHP 5.6 or earlier");
    
            $file = $this->sniffFile('sniff-examples/new_anonymous_classes.php', '7.0');
            $this->assertNoViolation($file, 4);
        }
    }
}
