<?php
/**
 * New language constructs sniff test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\LanguageConstructs;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New language constructs sniff tests
 *
 * @group newLanguageConstructs
 * @group languageConstructs
 *
 * @covers \PHPCompatibility\Sniffs\LanguageConstructs\NewLanguageConstructsSniff
 *
 * @uses    \PHPCompatibility\Tests\BaseSniffTest
 * @package PHPCompatibility
 * @author  Jansen Price <jansen.price@gmail.com>
 */
class NewLanguageConstructsUnitTest extends BaseSniffTest
{

    /**
     * testNamespaceSeparator
     *
     * @return void
     */
    public function testNamespaceSeparator()
    {
        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertError($file, 3, 'the \ operator (for namespaces) is not present in PHP version 5.2 or earlier');

        $file = $this->sniffFile(__FILE__, '5.3');
        $this->assertNoViolation($file, 3);
    }

    /**
     * Variadic functions using ...
     *
     * @return void
     */
    public function testEllipsis()
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertError($file, 5, 'the ... spread operator is not present in PHP version 5.5 or earlier');

        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file, 5);
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond newest addition.
        $this->assertNoViolation($file);
    }
}
