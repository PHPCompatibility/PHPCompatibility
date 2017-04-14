<?php
/**
 * New keywords sniff test file
 *
 * @package PHPCompatibility
 */


/**
 * New keywords sniff tests
 *
 * @group newKeywords
 * @group reservedKeywords
 *
 * @covers PHPCompatibility_Sniffs_PHP_NewKeywordsSniff
 *
 * @uses BaseSniffTest
 * @package PHPCompatibility
 * @author Jansen Price <jansen.price@gmail.com>
 */
class NewKeywordsSniffTest extends BaseSniffTest
{
    const TEST_FILE = 'sniff-examples/new_keywords.php';

    /**
     * Test allow_url_include
     *
     * @return void
     */
    public function testDirMagicConstant()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, 3, '__DIR__ magic constant is not present in PHP version 5.2 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file, 3);
    }

    /**
     * Test insteadof
     *
     * @return void
     */
    public function testInsteadOf()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertError($file, 15, '"insteadof" keyword (for traits) is not present in PHP version 5.3 or earlier');
        $this->assertError($file, 16, '"insteadof" keyword (for traits) is not present in PHP version 5.3 or earlier');


        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertNoViolation($file, 15);
        $this->assertNoViolation($file, 16);
    }

    /**
     * Test namespace keyword
     *
     * @return void
     */
    public function testNamespaceKeyword()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, 20, '"namespace" keyword is not present in PHP version 5.2 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file, 20);
    }

    /**
     * testNamespaceConstant
     *
     * @return void
     */
    public function testNamespaceConstant()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, 22, '__NAMESPACE__ magic constant is not present in PHP version 5.2 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file, 22);
    }

    /**
     * Test trait keyword
     *
     * @return void
     */
    public function testTraitKeyword()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertError($file, 24, '"trait" keyword is not present in PHP version 5.3 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertNoViolation($file, 24);
    }

    /**
     * Test trait magic constant
     *
     * @return void
     */
    public function testTraitConstant()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertError($file, 26, '__TRAIT__ magic constant is not present in PHP version 5.3 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertNoViolation($file, 26);
    }

    /**
     * Test the use keyword
     *
     * @return void
     */
    public function testUse()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, 14, '"use" keyword (for traits/namespaces/anonymous functions) is not present in PHP version 5.2 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file, 14);
    }

    /**
     * Test yield
     *
     * @return void
     */
    public function testYield()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertError($file, 33, '"yield" keyword (for generators) is not present in PHP version 5.4 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertNoViolation($file, 33);
    }

    /**
     * testFinally
     *
     * @return void
     */
    public function testFinally()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertError($file, 9, '"finally" keyword (in exception handling) is not present in PHP version 5.4 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.5');
        $this->assertNoViolation($file, 9);
    }

    /**
     * testConst
     *
     * @return void
     */
    public function testConst()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, 37, '"const" keyword is not present in PHP version 5.2 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file, 37);
    }

    /**
     * testConstNoFalsePositives
     *
     * @dataProvider dataConstNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testConstNoFalsePositives($line)
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testConstNoFalsePositives()
     *
     * @return array
     */
    public function dataConstNoFalsePositives()
    {
        return array(
            array(40),
            array(41),
            array(45),
            array(46),
        );
    }


    /**
     * testCallable
     *
     * @return void
     */
    public function testCallable()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertError($file, 49, '"callable" keyword is not present in PHP version 5.3 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.4');
        $this->assertNoViolation($file, 49);
    }

    /**
     * testGoto
     *
     * @return void
     */
    public function testGoto()
    {
        $file = $this->sniffFile(self::TEST_FILE, '5.2');
        $this->assertError($file, 51, '"goto" keyword is not present in PHP version 5.2 or earlier');

        $file = $this->sniffFile(self::TEST_FILE, '5.3');
        $this->assertNoViolation($file, 51);
    }

    /**
     * testHaltCompiler
     *
     * @requires PHP 5.3
     *
     * @return void
     */
    public function testHaltCompiler()
    {
        if (version_compare(phpversion(), '5.3', '=')) {
            // PHP 5.3 actually shows the warning.
            $file = $this->sniffFile(self::TEST_FILE, '5.0');
            $this->assertError($file, 63, '"__halt_compiler" keyword is not present in PHP version 5.0 or earlier');
        }
        else {
            /*
             * Usage of `__halt_compiler()` cannot be tested on its own token as the compiler
             * will be halted...
             * So testing that any violations created *after* the compiler is halted will
             * not be reported.
             */
            $file = $this->sniffFile(self::TEST_FILE, '5.2');
            $this->assertNoViolation($file, 66);
        }
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(self::TEST_FILE, '99.0'); // High version beyond newest addition.
        $this->assertNoViolation($file);
    }

}
