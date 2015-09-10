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
     * @return void
     */
    public function testInsteadOf()
    {
        $this->requiresPHPVersion("5.4");

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
     * testNamespaceSeparator
     *
     * @return void
     */
    public function testNamespaceSeparator()
    {
        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.2');

        $this->assertError($file, 24, "the \ operator (for namespaces) is not present in PHP version 5.2 or earlier");
    }

    /**
     * Test trait keyword
     *
     * @return void
     */
    public function testTraitKeyword()
    {
        $this->requiresPHPVersion("5.4");

        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.3');

        $this->assertError($file, 26, "\"trait\" keyword is not present in PHP version 5.3 or earlier");
    }

    /**
     * Test trait magic constant
     *
     * @return void
     */
    public function testTraitConstant()
    {
        $this->requiresPHPVersion("5.4");

        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.3');

        $this->assertError($file, 28, "__TRAIT__ magic constant is not present in PHP version 5.3 or earlier");
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
     * @return void
     */
    public function testYield()
    {
        $this->requiresPHPVersion("5.5");

        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.4');

        $this->assertError($file, 35, "\"yield\" keyword (for generators) is not present in PHP version 5.4 or earlier");
    }

    /**
     * testFinally
     *
     * @return void
     */
    public function testFinally()
    {
        $this->requiresPHPVersion("5.5");

        $file = $this->sniffFile('sniff-examples/new_keywords.php', '5.4');

        $this->assertError($file, 9, "\"finally\" keyword (in exception handling) is not present in PHP version 5.4 or earlier");
    }

}
