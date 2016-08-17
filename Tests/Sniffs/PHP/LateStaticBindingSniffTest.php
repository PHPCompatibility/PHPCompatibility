<?php
/**
 * Late static binding sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * Late static binding sniff test file
 *
 * @uses    BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class LateStaticBindingSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/late_static_binding.php';

    /**
     * testLateStaticBinding
     *
     * @group lateStaticBinding
     *
     * @dataProvider dataLateStaticBinding
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testLateStaticBinding($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, $line, 'Late static binding is not supported in PHP 5.2 or earlier.');

        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testLateStaticBinding()
     *
     * @return array
     */
    public function dataLateStaticBinding()
    {
        return array(
            array(8),
            array(9),
        );
    }


    /**
     * testLateStaticBindingOutsideClassScope
     *
     * @group lateStaticBinding
     *
     * @dataProvider dataLateStaticBindingOutsideClassScope
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testLateStaticBindingOutsideClassScope($line)
    {
        $file = $this->sniffFile(self::TEST_FILE);
        $this->assertError($file, $line, 'Late static binding is not supported outside of class scope.');
    }

    /**
     * Data provider.
     *
     * @see testLateStaticBindingOutsideClassScope()
     *
     * @return array
     */
    public function dataLateStaticBindingOutsideClassScope()
    {
        return array(
            array(19),
        );
    }


    /**
     * testNoViolation
     *
     * @group lateStaticBinding
     *
     * @dataProvider dataNoViolation
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoViolation($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoViolation()
     *
     * @return array
     */
    public function dataNoViolation()
    {
        return array(
            array(7),
            array(12),
            array(15),
            array(16),
        );
    }
}
