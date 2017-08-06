<?php
/**
 * New use group declaration sniff tests
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Sniffs\PHP;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New use group declaration sniff tests
 *
 * @group newGroupUseDeclarations
 *
 * @covers \PHPCompatibility\Sniffs\PHP\NewGroupUseDeclarationsSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Wim Godden <wim@cu.be>
 */
class NewGroupUseDeclarationsSniffTest extends BaseSniffTest
{

    const TEST_FILE = 'sniff-examples/new_group_use_declarations.php';


    /**
     * testGroupUseDeclaration
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
            array(26),
            array(33),
            array(34),
            array(35),
            array(36),
        );
    }


    /**
     * testGroupUseTrailingComma
     *
     * @dataProvider dataGroupUseTrailingComma
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testGroupUseTrailingComma($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertError($file, $line, 'Trailing comma\'s are not allowed in group use statements in PHP 7.1 or earlier');
    }

    /**
     * Data provider.
     *
     * @see testGroupUseTrailingComma()
     *
     * @return array
     */
    public function dataGroupUseTrailingComma()
    {
        return array(
            array(33),
            array(34),
            array(35),
            array(39),
        );
    }


    /**
     * testNoFalsePositivesTrailingComma
     *
     * @dataProvider dataNoFalsePositivesTrailingComma
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositivesTrailingComma($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '7.1');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositivesTrailingComma()
     *
     * @return array
     */
    public function dataNoFalsePositivesTrailingComma()
    {
        return array(
            array(23),
            array(24),
            array(25),
            array(29),
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
        $file = $this->sniffFile(self::TEST_FILE, '7.2');
        $this->assertNoViolation($file);
    }

}
