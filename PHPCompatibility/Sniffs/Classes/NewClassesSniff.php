<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Classes;

use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\ResolveHelper;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\ControlStructures;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\Scopes;
use PHPCSUtils\Utils\Variables;

/**
 * Detect use of new PHP native classes.
 *
 * The sniff analyses the following constructs to find usage of new classes:
 * - Class instantiation using the `new` keyword.
 * - (Anonymous) Class declarations to detect new classes being extended by userland classes.
 * - Static use of class properties, constants or functions using the double colon.
 * - Function/closure declarations to detect new classes used as parameter type declarations.
 * - Function/closure declarations to detect new classes used as return type declarations.
 * - Property declarations to detect new classes used as property type declarations.
 * - Try/catch statements to detect new exception classes being caught.
 *
 * PHP version All
 *
 * @since 5.5
 * @since 5.6    Now extends the base `Sniff` class.
 * @since 7.1.0  Now extends the `AbstractNewFeatureSniff` class.
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionNewFeatureTrait`.
 */
class NewClassesSniff extends Sniff
{
    use ComplexVersionNewFeatureTrait;

    /**
     * A list of new classes, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the class appears.
     *
     * @since 5.5
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $newClasses = [
        'ArrayObject' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ],
        'ArrayIterator' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ],
        'CachingIterator' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ],
        'DirectoryIterator' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ],
        'RecursiveDirectoryIterator' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ],
        'RecursiveIteratorIterator' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ],
        'php_user_filter' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'tidy' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'tidyNode' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'Reflection' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionClass' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionExtension' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionFunction' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionMethod' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionObject' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionParameter' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionProperty' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ],
        'SoapClient' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SoapServer' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SoapHeader' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SoapParam' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SoapVar' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'COMPersistHelper' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'DOMAttr' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMCdataSection' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMCharacterData' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMComment' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMDocument' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMDocumentFragment' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMDocumentType' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMElement' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMEntity' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMEntityReference' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMImplementation' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMNamedNodeMap' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMNode' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMNodeList' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMNotation' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMProcessingInstruction' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMText' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMXPath' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'SimpleXMLElement' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'simplexml',
        ],
        'XSLTProcessor' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'xsl',
        ],
        'SQLiteDatabase' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLiteResult' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLiteUnbuffered' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'mysqli' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_stmt' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_result' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_driver' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'mysqli_warning' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        /*
        See: https://bugs.php.net/bug.php?id=79625
        'OCI-Collection' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'OCI-Lob' => [
            '4.4' => false,
            '5.0' => true,
        ],
        */

        'libXMLError' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'PDO' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pdo',
        ],
        'PDOStatement' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pdo',
        ],
        'AppendIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'EmptyIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'FilterIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'InfiniteIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'IteratorIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'LimitIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'NoRewindIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'ParentIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'RecursiveArrayIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'RecursiveCachingIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'RecursiveFilterIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'SimpleXMLIterator' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'simplexml',
        ],
        'SplFileObject' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'SplObjectStorage' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'XMLReader' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'xmlreader',
        ],

        'SplFileInfo' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'spl',
        ],
        'SplTempFileObject' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'spl',
        ],
        'XMLWriter' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xmlwriter',
        ],

        'DateTime' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'DateTimeZone' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'RegexIterator' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'spl',
        ],
        'RecursiveRegexIterator' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'spl',
        ],
        'ReflectionFunctionAbstract' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'reflection',
        ],
        'ZipArchive' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'zip',
        ],

        'Closure' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'DateInterval' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'DatePeriod' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'finfo' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'Collator' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'NumberFormatter' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'Locale' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'Normalizer' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'MessageFormatter' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'IntlDateFormatter' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'Phar' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'phar',
        ],
        'PharData' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'phar',
        ],
        'PharFileInfo' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'phar',
        ],
        'FilesystemIterator' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'GlobIterator' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'MultipleIterator' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'RecursiveTreeIterator' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'SplDoublyLinkedList' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'SplFixedArray' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'SplHeap' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'SplMaxHeap' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'SplMinHeap' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'SplPriorityQueue' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'SplQueue' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'SplStack' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ],
        'SQLite3' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'sqlite3',
        ],
        'SQLite3Stmt' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'sqlite3',
        ],
        'SQLite3Result' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'sqlite3',
        ],

        'ResourceBundle' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ],

        'CallbackFilterIterator' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'spl',
        ],
        'RecursiveCallbackFilterIterator' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'spl',
        ],
        'ReflectionZendExtension' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'reflection',
        ],
        'SessionHandler' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'SNMP' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'snmp',
        ],
        'Transliterator' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'Spoofchecker' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],

        'Generator' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'CURLFile' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'DateTimeImmutable' => [
            '5.4' => false,
            '5.5' => true,
        ],
        'IntlCalendar' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'IntlGregorianCalendar' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'IntlTimeZone' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'IntlBreakIterator' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'IntlRuleBasedBreakIterator' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'IntlCodePointBreakIterator' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'IntlPartsIterator' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'IntlIterator' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],
        'UConverter' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],

        'GMP' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'gmp',
        ],

        'IntlChar' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'intl',
        ],
        'ReflectionType' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionGenerator' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'reflection',
        ],

        'ReflectionClassConstant' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionNamedType' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'reflection',
        ],

        'HashContext' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'hash',
        ],

        'FFI' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ],
        'FFI\CData' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ],
        'FFI\CType' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ],
        'ReflectionReference' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'reflection',
        ],
        'WeakReference' => [
            '7.3' => false,
            '7.4' => true,
        ],

        'Attribute' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'PhpToken' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'tokenizer',
        ],
        'ReflectionUnionType' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'reflection',
        ],
        'WeakMap' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'OCICollection' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'oci8',
        ],
        'OCILob' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'oci8',
        ],

        'IntlDatePatternGenerator' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'intl',
        ],
        'Fiber' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'fibers',
        ],
        'ReflectionEnum' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionEnumBackedCase' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionEnumUnitCase' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionFiber' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'reflection',
        ],
        'ReflectionIntersectionType' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'reflection',
        ],
        'CURLStringFile' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'curl',
        ],

        'Random\Randomizer' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'random',
        ],
        'Random\Engine\Secure' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'random',
        ],
        'Random\Engine\Mt19937' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'random',
        ],
        'Random\Engine\PcgOneseq128XslRr64' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'random',
        ],
        'Random\Engine\Xoshiro256StarStar' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'random',
        ],
    ];

    /**
     * A list of new Exception classes, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the class appears.
     *
     * {@internal Classes listed here do not need to be added to the $newClasses
     *            property as well.
     *            This list is automatically added to the $newClasses property
     *            in the `register()` method.}
     *
     * {@internal Helper to update this list: https://3v4l.org/MhlUp}
     *
     * @since 7.1.4
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $newExceptions = [
        'com_exception' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'DOMException' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'Exception' => [
            // According to the docs introduced in PHP 5.1, but this appears to be.
            // an error.  Class was introduced with try/catch keywords in PHP 5.0.
            '4.4' => false,
            '5.0' => true,
        ],
        'ReflectionException' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ],
        'SoapFault' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SQLiteException' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'mysqli_sql_exception' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],

        'ErrorException' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'BadFunctionCallException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'BadMethodCallException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'DomainException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'InvalidArgumentException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'LengthException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'LogicException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'OutOfBoundsException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'OutOfRangeException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'OverflowException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'PDOException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pdo',
        ],
        'RangeException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'RuntimeException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'UnderflowException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],
        'UnexpectedValueException' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ],

        'PharException' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'phar',
        ],

        'SNMPException' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'snmp',
        ],

        'IntlException' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ],

        'Error' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'ArithmeticError' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'AssertionError' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'DivisionByZeroError' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'ParseError' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'TypeError' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'ClosedGeneratorException' => [
            '5.6' => false,
            '7.0' => true,
        ],

        'ArgumentCountError' => [
            '7.0' => false,
            '7.1' => true,
        ],

        'SodiumException' => [
            '7.1' => false,
            '7.2' => true,
        ],

        'CompileError' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'JsonException' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'json',
        ],

        'FFI\Exception' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ],
        'FFI\ParserException' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ],

        'UnhandledMatchError' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'ValueError' => [
            '7.4' => false,
            '8.0' => true,
        ],

        'FiberError' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'fibers',
        ],

        'Random\RandomError' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'random',
        ],
        'Random\BrokenRandomEngineError' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'random',
        ],
        'Random\RandomException' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'random',
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.5
     * @since 7.0.3  - Now also targets the `class` keyword to detect extended classes.
     *               - Now also targets double colons to detect static class use.
     * @since 7.1.4  - Now also targets anonymous classes to detect extended classes.
     *               - Now also targets functions/closures to detect new classes used
     *                 as parameter type declarations.
     *               - Now also targets the `catch` control structure to detect new
     *                 exception classes being caught.
     * @since 8.2.0  Now also targets the `T_RETURN_TYPE` token to detect new classes used
     *               as return type declarations.
     * @since 10.0.0 `T_RETURN_TYPE` token removed after PHPCS < 3.7.1 version drop.
     *
     * @return array<int|string>
     */
    public function register()
    {
        // Handle case-insensitivity of class names.
        $this->newClasses    = \array_change_key_case($this->newClasses, \CASE_LOWER);
        $this->newExceptions = \array_change_key_case($this->newExceptions, \CASE_LOWER);

        // Add the Exception classes to the Classes list.
        $this->newClasses = \array_merge($this->newClasses, $this->newExceptions);

        $targets = [
            \T_NEW,
            \T_CLASS,
            \T_ANON_CLASS,
            \T_VARIABLE,
            \T_DOUBLE_COLON,
            \T_CATCH,
        ];

        $targets += Collections::functionDeclarationTokens();

        return $targets;
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        switch ($tokens[$stackPtr]['code']) {
            case \T_VARIABLE:
                $this->processVariableToken($phpcsFile, $stackPtr);
                break;

            case \T_CATCH:
                $this->processCatchToken($phpcsFile, $stackPtr);
                break;

            default:
                $this->processSingularToken($phpcsFile, $stackPtr);
                break;
        }

        if (isset(Collections::functionDeclarationTokens()[$tokens[$stackPtr]['code']]) === true) {
            $this->processFunctionToken($phpcsFile, $stackPtr);
        }
    }


    /**
     * Processes this test for when a token resulting in a singular class name is encountered.
     *
     * @since 7.1.4
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processSingularToken(File $phpcsFile, $stackPtr)
    {
        $tokens      = $phpcsFile->getTokens();
        $FQClassName = '';

        if ($tokens[$stackPtr]['code'] === \T_NEW) {
            $FQClassName = ResolveHelper::getFQClassNameFromNewToken($phpcsFile, $stackPtr);

        } elseif ($tokens[$stackPtr]['code'] === \T_CLASS || $tokens[$stackPtr]['code'] === \T_ANON_CLASS) {
            $FQClassName = ResolveHelper::getFQExtendedClassName($phpcsFile, $stackPtr);

        } elseif ($tokens[$stackPtr]['code'] === \T_DOUBLE_COLON) {
            $FQClassName = ResolveHelper::getFQClassNameFromDoubleColonToken($phpcsFile, $stackPtr);
        }

        if ($FQClassName === '') {
            return;
        }

        $className   = \substr($FQClassName, 1); // Remove global namespace indicator.
        $classNameLc = \strtolower($className);

        if (isset($this->newClasses[$classNameLc]) === false) {
            return;
        }

        $itemInfo = [
            'name'   => $className,
            'nameLc' => $classNameLc,
        ];
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
    }


    /**
     * Processes this test for when a function token is encountered.
     *
     * - Detect new classes when used as a parameter type declaration.
     * - Detect new classes when used as a return type declaration.
     *
     * @since 7.1.4
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processFunctionToken(File $phpcsFile, $stackPtr)
    {
        /*
         * Check parameter type declarations.
         */
        $parameters = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        if (empty($parameters) === false && \is_array($parameters) === true) {
            foreach ($parameters as $param) {
                if ($param['type_hint'] === '') {
                    continue;
                }

                $this->checkTypeDeclaration($phpcsFile, $param['type_hint_token'], $param['type_hint']);
            }
        }

        /*
         * Check return type declarations.
         */
        $properties = FunctionDeclarations::getProperties($phpcsFile, $stackPtr);
        if ($properties['return_type'] === '') {
            return;
        }

        $this->checkTypeDeclaration($phpcsFile, $properties['return_type_token'], $properties['return_type']);
    }


    /**
     * Processes this test for when a variable token is encountered.
     *
     * - Detect new classes when used as a property type declaration.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processVariableToken(File $phpcsFile, $stackPtr)
    {
        if (Scopes::isOOProperty($phpcsFile, $stackPtr) === false) {
            // Not a class property.
            return;
        }

        $properties = Variables::getMemberProperties($phpcsFile, $stackPtr);
        if ($properties['type'] === '') {
            return;
        }

        $this->checkTypeDeclaration($phpcsFile, $properties['type_token'], $properties['type']);
    }


    /**
     * Processes a type declaration.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile  The file being scanned.
     * @param int                         $stackPtr   The position of the current token in
     *                                                the stack passed in $tokens.
     * @param string                      $typeString The type declaration.
     *
     * @return void
     */
    private function checkTypeDeclaration($phpcsFile, $stackPtr, $typeString)
    {
        // Strip off potential nullable indication.
        $typeString = \ltrim($typeString, '?');
        $types      = \preg_split('`[|&]`', $typeString, -1, \PREG_SPLIT_NO_EMPTY);

        if (empty($types) === true) {
            return;
        }

        foreach ($types as $type) {
            // Strip off potential (global) namespace indication.
            $type = \ltrim($type, '\\');

            if ($type === '') {
                return;
            }

            $typeLc = \strtolower($type);
            if (isset($this->newClasses[$typeLc]) === false) {
                return;
            }

            $itemInfo = [
                'name'   => $type,
                'nameLc' => $typeLc,
            ];
            $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
        }
    }


    /**
     * Processes this test for when a catch token is encountered.
     *
     * - Detect exceptions when used in a catch statement.
     *
     * @since 7.1.4
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processCatchToken(File $phpcsFile, $stackPtr)
    {
        try {
            $exceptions = ControlStructures::getCaughtExceptions($phpcsFile, $stackPtr);
        } catch (RuntimeException $e) {
            // Parse error or live coding.
            return;
        }

        if (empty($exceptions) === true) {
            return;
        }

        foreach ($exceptions as $exception) {
            // Strip off potential (global) namespace indication.
            $name   = \ltrim($exception['type'], '\\');
            $nameLC = \strtolower($name);

            if (isset($this->newExceptions[$nameLC]) === true) {
                $itemInfo = [
                    'name'   => $name,
                    'nameLc' => $nameLC,
                ];
                $this->handleFeature($phpcsFile, $exception['type_token'], $itemInfo);
            }
        }
    }


    /**
     * Handle the retrieval of relevant information and - if necessary - throwing of an
     * error for a matched item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the relevant token in
     *                                               the stack.
     * @param array                       $itemInfo  Base information about the item.
     *
     * @return void
     */
    protected function handleFeature(File $phpcsFile, $stackPtr, array $itemInfo)
    {
        $itemArray   = $this->newClasses[$itemInfo['nameLc']];
        $versionInfo = $this->getVersionInfo($itemArray);

        if (empty($versionInfo['not_in_version'])
            || ScannedCode::shouldRunOnOrBelow($versionInfo['not_in_version']) === false
        ) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $itemInfo, $versionInfo);
    }


    /**
     * Generates the error for this item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param array                       $itemInfo    Base information about the item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, array $itemInfo, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'The built-in class %s is not present in PHP version %s or earlier';

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
