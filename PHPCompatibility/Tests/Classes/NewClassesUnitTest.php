<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Classes;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the NewClasses sniff.
 *
 * @group newClasses
 * @group classes
 *
 * @covers \PHPCompatibility\Sniffs\Classes\NewClassesSniff
 * @covers \PHPCompatibility\Sniff::getReturnTypeHintName
 * @covers \PHPCompatibility\Sniff::getReturnTypeHintToken
 * @covers \PHPCompatibility\Sniff::getTypeHintsFromFunctionDeclaration
 *
 * @since 5.5
 */
class NewClassesUnitTest extends BaseSniffTest
{

    /**
     * testNewClass
     *
     * @dataProvider dataNewClass
     *
     * @param string $className         Class name.
     * @param string $lastVersionBefore The PHP version just *before* the class was introduced.
     * @param array  $lines             The line numbers in the test file which apply to this class.
     * @param string $okVersion         A PHP version in which the class was ok to be used.
     * @param string $testVersion       Optional. A PHP version in which to test for the error if different
     *                                  from the $lastVersionBefore.
     *
     * @return void
     */
    public function testNewClass($className, $lastVersionBefore, $lines, $okVersion, $testVersion = null)
    {
        $errorVersion = (isset($testVersion)) ? $testVersion : $lastVersionBefore;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The built-in class {$className} is not present in PHP version {$lastVersionBefore} or earlier";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewClass()
     *
     * @return array
     */
    public function dataNewClass()
    {
        return [
            ['ArrayObject', '4.4', [305], '5.0'],
            ['ArrayIterator', '4.4', [283], '5.0'],
            ['CachingIterator', '4.4', [284], '5.0'],
            ['DirectoryIterator', '4.4', [285], '5.0'],
            ['RecursiveDirectoryIterator', '4.4', [286], '5.0'],
            ['RecursiveIteratorIterator', '4.4', [287], '5.0'],
            ['php_user_filter', '4.4', [299], '5.0'],
            ['tidy', '4.4', [300], '5.0'],
            ['tidyNode', '4.4', [301], '5.0'],
            ['Reflection', '4.4', [354], '5.0'],
            ['ReflectionClass', '4.4', [355], '5.0'],
            ['ReflectionExtension', '4.4', [356], '5.0'],
            ['ReflectionFunction', '4.4', [357], '5.0'],
            ['ReflectionMethod', '4.4', [358], '5.0'],
            ['ReflectionObject', '4.4', [360], '5.0'],
            ['ReflectionParameter', '4.4', [361], '5.0'],
            ['ReflectionProperty', '4.4', [362], '5.0'],
            ['SoapClient', '4.4', [364], '5.0'],
            ['SoapServer', '4.4', [365], '5.0'],
            ['SoapHeader', '4.4', [366], '5.0'],
            ['SoapParam', '4.4', [368], '5.0'],
            ['SoapVar', '4.4', [369], '5.0'],
            ['COMPersistHelper', '4.4', [371], '5.0'],
            ['DOMAttr', '4.4', [373], '5.0'],
            ['DOMCdataSection', '4.4', [374], '5.0'],
            ['DOMCharacterData', '4.4', [375], '5.0'],
            ['DOMComment', '4.4', [376], '5.0'],
            ['DOMDocument', '4.4', [377], '5.0'],
            ['DOMDocumentFragment', '4.4', [378], '5.0'],
            ['DOMDocumentType', '4.4', [379], '5.0'],
            ['DOMElement', '4.4', [380], '5.0'],
            ['DOMEntity', '4.4', [381], '5.0'],
            ['DOMEntityReference', '4.4', [382], '5.0'],
            ['DOMImplementation', '4.4', [383], '5.0'],
            ['DOMNamedNodeMap', '4.4', [384], '5.0'],
            ['DOMNode', '4.4', [385], '5.0'],
            ['DOMNodeList', '4.4', [386], '5.0'],
            ['DOMNotation', '4.4', [387], '5.0'],
            ['DOMProcessingInstruction', '4.4', [388], '5.0'],
            ['DOMText', '4.4', [389], '5.0'],
            ['DOMXPath', '4.4', [390], '5.0'],
            ['SimpleXMLElement', '4.4', [310], '5.0'],
            ['XSLTProcessor', '4.4', [392], '5.0'],
            ['SQLiteDatabase', '4.4', [394], '5.0'],
            ['SQLiteResult', '4.4', [395], '5.0'],
            ['SQLiteUnbuffered', '4.4', [396], '5.0'],
            ['mysqli', '4.4', [398], '5.0'],
            ['mysqli_stmt', '4.4', [399], '5.0'],
            ['mysqli_result', '4.4', [400], '5.0'],
            ['mysqli_driver', '4.4', [401], '5.0'],
            ['mysqli_warning', '4.4', [402], '5.0'],
            // See: https://bugs.php.net/bug.php?id=79625
            //['OCI-Collection', '4.4', [404], '5.0'],
            //['OCI-Lob', '4.4', [405], '5.0'],

            ['libXMLError', '5.0', [61, 101, 141], '5.1'],
            ['PDO', '5.0', [314], '5.1'],
            ['PDOStatement', '5.0', [315], '5.1'],
            ['AppendIterator', '5.0', [288], '5.1'],
            ['EmptyIterator', '5.0', [289], '5.1'],
            ['FilterIterator', '5.0', [290], '5.1'],
            ['InfiniteIterator', '5.0', [291], '5.1'],
            ['IteratorIterator', '5.0', [292], '5.1'],
            ['LimitIterator', '5.0', [293], '5.1'],
            ['NoRewindIterator', '5.0', [294], '5.1'],
            ['ParentIterator', '5.0', [295], '5.1'],
            ['RecursiveArrayIterator', '5.0', [296], '5.1'],
            ['RecursiveCachingIterator', '5.0', [297], '5.1'],
            ['RecursiveFilterIterator', '5.0', [298], '5.1'],
            ['SimpleXMLIterator', '5.0', [311], '5.1'],
            ['XMLReader', '5.0', [312, 336], '5.1'],
            ['SplFileObject', '5.0', [302, 336], '5.1'],
            ['SplObjectStorage', '5.0', [282], '5.1'],
            ['SplFileInfo', '5.1.1', [303], '5.2', '5.1'],
            ['SplTempFileObject', '5.1.1', [304], '5.2', '5.1'],
            ['XMLWriter', '5.1.1', [313], '5.2', '5.1'],
            ['DateTime', '5.1', [25, 65, 105, 151, 318, 319, 321, 331], '5.2'],
            ['DateTimeZone', '5.1', [26, 66, 106, 162, 331], '5.2'],
            ['RegexIterator', '5.1', [27, 67, 107, 163], '5.2'],
            ['RecursiveRegexIterator', '5.1', [28, 68, 108], '5.2'],
            ['ReflectionFunctionAbstract', '5.1', [307], '5.2'],
            ['ZipArchive', '5.1', [268], '5.2'],
            ['Closure', '5.2', [279], '5.3'],
            ['DateInterval', '5.2', [17, 18, 19, 20, 29, 69, 109], '5.3'],
            ['DatePeriod', '5.2', [30, 70, 110, 173], '5.3'],
            ['finfo', '5.2', [278], '5.3'],
            ['Collator', '5.2', [269], '5.3'],
            ['NumberFormatter', '5.2', [270], '5.3'],
            ['Locale', '5.2', [271], '5.3'],
            ['Normalizer', '5.2', [272], '5.3'],
            ['MessageFormatter', '5.2', [273], '5.3'],
            ['IntlDateFormatter', '5.2', [274], '5.3'],
            ['Phar', '5.2', [31, 71, 111, 152], '5.3'],
            ['PharData', '5.2', [32, 72, 112], '5.3'],
            ['PharException', '5.2', [33, 73, 113], '5.3'],
            ['PharFileInfo', '5.2', [34, 74, 114], '5.3'],
            ['FilesystemIterator', '5.2', [35, 75, 115, 174], '5.3'],
            ['GlobIterator', '5.2', [36, 76, 116, 168], '5.3'],
            ['MultipleIterator', '5.2', [37, 77, 117, 178], '5.3'],
            ['RecursiveTreeIterator', '5.2', [38, 78, 118], '5.3'],
            ['SplDoublyLinkedList', '5.2', [39, 79, 119], '5.3'],
            ['SplFixedArray', '5.2', [40, 80, 120], '5.3'],
            ['SplHeap', '5.2', [41, 81, 121, 164], '5.3'],
            ['SplMaxHeap', '5.2', [42, 82, 122], '5.3'],
            ['SplMinHeap', '5.2', [43, 83, 123, 153], '5.3'],
            ['SplPriorityQueue', '5.2', [44, 84, 124], '5.3'],
            ['SplQueue', '5.2', [45, 85, 125], '5.3'],
            ['SplStack', '5.2', [46, 86, 126], '5.3'],
            ['sqlite3', '5.2', [407], '5.3'],
            ['Sqlite3Stmt', '5.2', [408], '5.3'],
            ['SQLite3Result', '5.2', [409], '5.3'],

            ['ResourceBundle', '5.3.1', [275], '5.4', '5.3'],
            ['CallbackFilterIterator', '5.3', [47, 87, 127], '5.4'],
            ['RecursiveCallbackFilterIterator', '5.3', [48, 88, 128, 179], '5.4'],
            ['ReflectionZendExtension', '5.3', [49, 89, 129], '5.4'],
            ['SessionHandler', '5.3', [50, 90, 130], '5.4'],
            ['SNMP', '5.3', [51, 91, 131, 180], '5.4'],
            ['Transliterator', '5.3', [52, 92, 132, 154], '5.4'],
            ['Generator', '5.4', [280], '5.5'],
            ['CURLFile', '5.4', [53, 93, 133], '5.5'],
            ['DateTimeImmutable', '5.4', [54, 94, 134], '5.5'],
            ['IntlCalendar', '5.4', [55, 95, 135, 165], '5.5'],
            ['IntlGregorianCalendar', '5.4', [56, 96, 136], '5.5'],
            ['IntlTimeZone', '5.4', [57, 97, 137], '5.5'],
            ['IntlBreakIterator', '5.4', [58, 98, 138], '5.5'],
            ['IntlRuleBasedBreakIterator', '5.4', [59, 99, 139], '5.5'],
            ['IntlCodePointBreakIterator', '5.4', [60, 100, 140], '5.5'],
            ['IntlPartsIterator', '5.4', [351], '5.5'],
            ['IntlIterator', '5.4', [352], '5.5'],
            ['UConverter', '5.4', [276], '5.5'],
            ['GMP', '5.5', [281], '5.6'],
            ['IntlChar', '5.6', [277], '7.0'],
            ['ReflectionType', '5.6', [308], '7.0'],
            ['ReflectionGenerator', '5.6', [309], '7.0'],
            ['ReflectionClassConstant', '7.0', [306], '7.1'],
            ['ReflectionNamedType', '7.0', [359], '7.1'],
            ['FFI', '7.3', [346], '7.4'],
            ['FFI\CData', '7.3', [347], '7.4'],
            ['FFI\CType', '7.3', [347], '7.4'],
            ['ReflectionReference', '7.3', [344], '7.4'],
            ['WeakReference', '7.3', [345], '7.4'],
            ['PhpToken', '7.4', [415], '8.0'],
            ['WeakMap', '7.4', [412], '8.0'],
            ['ReflectionUnionType', '7.4', [422], '8.0'],
            ['OCICollection', '7.4', [424], '8.0'],
            ['OCILob', '7.4', [425], '8.0'],
            ['Attribute', '7.4', [431], '8.0'],
            ['IntlDatePatternGenerator', '8.0', [433], '8.1'],
            ['Fiber', '8.0', [435], '8.1'],
            ['ReflectionFiber', '8.0', [436], '8.1'],

            ['DATETIME', '5.1', [146], '5.2'],
            ['datetime', '5.1', [147, 320], '5.2'],
            ['dATeTiMe', '5.1', [148], '5.2'],

            ['com_exception', '4.4', [343], '5.0'],
            ['DOMException', '4.4', [232, 260], '5.0'],
            ['Exception', '4.4', [190, 217], '5.0'],
            ['ReflectionException', '4.4', [187, 235], '5.0'],
            ['SoapFault', '4.4', [236], '5.0'],
            ['SQLiteException', '4.4', [340], '5.0'],
            ['mysqli_sql_exception', '4.4', [202, 233], '5.0'],
            ['ErrorException', '5.0', [194, 218], '5.1'],
            ['BadFunctionCallException', '5.0', [201, 219], '5.1'],
            ['BadMethodCallException', '5.0', [207, 220], '5.1'],
            ['DomainException', '5.0', [186, 221], '5.1'],
            ['InvalidArgumentException', '5.0', [222, 255], '5.1'],
            ['LengthException', '5.0', [195, 223], '5.1'],
            ['LogicException', '5.0', [224, 255], '5.1'],
            ['PDOException', '5.0', [198, 234], '5.1'],
            ['OutOfBoundsException', '5.0', [225, 255], '5.1'],
            ['OutOfRangeException', '5.0', [226, 255], '5.1'],
            ['OverflowException', '5.0', [196, 227], '5.1'],
            ['RangeException', '5.0', [208, 228], '5.1'],
            ['RuntimeException', '5.0', [229, 255], '5.1'],
            ['UnderflowException', '5.0', [197, 230], '5.1'],
            ['UnexpectedValueException', '5.0', [191, 231], '5.1'],
            ['PharException', '5.2', [237], '5.3'],
            ['SNMPException', '5.3', [238], '5.4'],
            ['IntlException', '5.4', [239], '5.5'],
            ['Error', '5.6', [214, 240], '7.0'],
            ['ArithmeticError', '5.6', [209, 241], '7.0'],
            ['AssertionError', '5.6', [242], '7.0'],
            ['DivisionByZeroError', '5.6', [203, 243], '7.0'],
            ['ParseError', '5.6', [244], '7.0'],
            ['TypeError', '5.6', [245], '7.0'],
            ['ClosedGeneratorException', '5.6', [341], '7.0'],
            ['ArgumentCountError', '7.0', [248], '7.1'],
            ['HashContext', '7.1', [350], '7.2'],
            ['SodiumException', '7.1', [342], '7.2'],
            ['CompileError', '7.2', [249], '7.3'],
            ['JsonException', '7.2', [250, 339], '7.3'],
            ['FFI\Exception', '7.3', [349], '7.4'],
            ['FFI\ParserException', '7.3', [349], '7.4'],
            ['UnhandledMatchError', '7.4', [428], '8.0'],
            ['ValueError', '7.4', [418, 419], '8.0'],
            ['FiberError', '8.0', [437], '8.1'],
        ];
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
        $file = $this->sniffFile(__FILE__, '5.1'); // TestVersion based on the specific classes being tested.
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
        return [
            [6],
            [7],
            [8],
            [9],
            [157],
            [158],
            [169],
            [170],
            [181],
            [265],
            [325],
            [326],
            [327],
        ];
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
