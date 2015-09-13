<?php
/**
 * New keywords sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New keywords sniff tests
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class NewKeywordsSniffTest extends BaseSniffTest
{
    /**
     * Test allow_url_include
     *
     * @return void
     */
    public function testDirMagicConstant()
    {
        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.2');

        $this->assertError($file, 3, "__DIR__ magic constant is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test insteadof
     *
     * @requires PHP 5.4
     * @return void
     */
    public function testInsteadOf()
    {
        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.3');

        $this->assertError($file, 15, "\"insteadof\" keyword (for traits) is not present in PHP version 5.3 or earlier");
    }

    /**
     * Test namespace keyword
     *
     * @return void
     */
    public function testNamespaceKeyword()
    {
        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.2');

        $this->assertError($file, 20, "\"namespace\" keyword is not present in PHP version 5.2 or earlier");
    }

    /**
     * testNamespaceConstant
     *
     * @return void
     */
    public function testNamespaceConstant()
    {
        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.2');

        $this->assertError($file, 22, "__NAMESPACE__ magic constant is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test trait keyword
     *
     * @requires PHP 5.4
     * @return void
     */
    public function testTraitKeyword()
    {
        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.3');

        $this->assertError($file, 24, "\"trait\" keyword is not present in PHP version 5.3 or earlier");
    }

    /**
     * Test trait magic constant
     *
     * @requires PHP 5.4
     * @return void
     */
    public function testTraitConstant()
    {
        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.3');

        $this->assertError($file, 26, "__TRAIT__ magic constant is not present in PHP version 5.3 or earlier");
    }

    /**
     * Test the use keyword
     *
     * @return void
     */
    public function testUse()
    {
        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.2');

        $this->assertError($file, 14, "\"use\" keyword (for traits/namespaces) is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test yield
     *
     * @requires PHP 5.5
     * @return void
     */
    public function testYield()
    {
        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.4');

        $this->assertError($file, 33, "\"yield\" keyword (for generators) is not present in PHP version 5.4 or earlier");
    }

    /**
     * testFinally
     *
     * @requires PHP 5.5
     * @return void
     */
    public function testFinally()
    {
        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.4');

        $this->assertError($file, 9, "\"finally\" keyword (in exception handling) is not present in PHP version 5.4 or earlier");
    }

}
