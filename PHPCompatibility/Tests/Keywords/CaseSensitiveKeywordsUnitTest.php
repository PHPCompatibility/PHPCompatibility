<?php
/**
 * CaseSensitiveKeywordsSniff test file.
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\Keywords;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * self, parent and static were sometimes treated case sensitively prior to PHP 5.5.
 *
 * @group caseSensitiveKeywords
 * @group keywords
 *
 * @covers \PHPCompatibility\Sniffs\Keywords\CaseSensitiveKeywordsSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class CaseSensitiveKeywordsUnitTest extends BaseSniffTest
{

    /**
     * testCaseSensitiveKeywords
     *
     * @dataProvider dataCaseSensitiveKeywords
     *
     * @param int    $line    The line number.
     * @param string $keyword The keyword.
     *
     * @return void
     */
    public function testCaseSensitiveKeywords($line, $keyword)
    {
        $file = $this->sniffFile(__FILE__, '5.4');
        $this->assertError($file, $line, "The keyword '{$keyword}' was treated in a case-sensitive fashion in certain cases in PHP 5.4 or earlier. Use the lowercase version for consistent support.");
    }

    /**
     * Data provider dataCaseSensitiveKeywords.
     *
     * @see testCaseSensitiveKeywords()
     *
     * @return array
     */
    public function dataCaseSensitiveKeywords()
    {
        return array(
            array(18, 'self'),
            array(19, 'static'),
            array(20, 'parent'),
            array(21, 'self'),
            array(22, 'static'),
            array(23, 'parent'),
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
        $file = $this->sniffFile(__FILE__, '5.4');
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
            array(10),
            array(11),
            array(12),
            array(13),
            array(14),
            array(15),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertNoViolation($file);
    }
}
