<?php
/**
 * New use group declaration sniff tests
 *
 * @package PHPCompatibility
 */


/**
 * New use group declaration sniff tests
 *
 * @group newGroupUseDeclarations
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewGroupUseDeclarationsSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Wim Godden <wim@cu.be>
 */
class NewGroupUseDeclarationsSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/new_group_use_declarations.php';


    /**
     * testGroupUseDeclaration
     *
     * @requires PHP 5.3
     *
     * @dataProvider dataGroupUseDeclaration
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testGroupUseDeclaration($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
        $this->assertError($file, $line, 'Group use declarations are not allowed in PHP 5.6 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testGroupUseDeclaration()
     *
     * @return array
     */
    public function dataGroupUseDeclaration()
    {
        return array(
            array(23),
            array(24),
            array(25),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.6');
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
            array(6),
            array(8),
            array(9),
            array(11),
            array(13),
            array(15),
            array(19),
            array(20),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.0');
        $this->assertNoViolation($file);
    }

}
