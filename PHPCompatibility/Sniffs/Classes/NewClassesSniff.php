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

use PHPCompatibility\AbstractNewFeatureSniff;
use PHP_CodeSniffer\Files\File;

/**
 * Detect use of new PHP native classes.
 *
 * The sniff analyses the following constructs to find usage of new classes:
 * - Class instantiation using the `new` keyword.
 * - (Anonymous) Class declarations to detect new classes being extended by userland classes.
 * - Static use of class properties, constants or functions using the double colon.
 * - Function/closure declarations to detect new classes used as parameter type declarations.
 * - Function/closure declarations to detect new classes used as return type declarations.
 * - Try/catch statements to detect new exception classes being caught.
 *
 * PHP version All
 *
 * @since 5.5
 * @since 5.6   Now extends the base `Sniff` class.
 * @since 7.1.0 Now extends the `AbstractNewFeatureSniff` class.
 */
class NewClassesSniff extends AbstractNewFeatureSniff
{

    /**
     * A list of new classes, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the class appears.
     *
     * @since 5.5
     *
     * @var array(string => array(string => bool))
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
            '5.1' => false,
            '5.2' => true,
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
            '5.3' => false,
            '5.4' => true,
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
            '5.4' => false,
            '5.5' => true,
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
            '5.5' => false,
            '5.6' => true,
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
            '7.4' => false,
            '8.0' => true,
        ],
        'ReflectionUnionType' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'WeakMap' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'OCICollection' => [
            '7.4' => false,
            '8.0' => true,
        ],
        'OCILob' => [
            '7.4' => false,
            '8.0' => true,
        ],

        'IntlDatePatternGenerator' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'Fiber' => [
            '8.0' => false,
            '8.1' => true,
        ],
        'ReflectionFiber' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'reflection',
        ],
        'CURLStringFile' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'curl',
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
     * @var array(string => array(string => bool))
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
            '4.4' => false,
            '5.0' => true,
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
            '5.3' => false,
            '5.4' => true,
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
            '8.0' => false,
            '8.1' => true,
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
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of class names.
        $this->newClasses    = \array_change_key_case($this->newClasses, \CASE_LOWER);
        $this->newExceptions = \array_change_key_case($this->newExceptions, \CASE_LOWER);

        // Add the Exception classes to the Classes list.
        $this->newClasses = \array_merge($this->newClasses, $this->newExceptions);

        return [
            \T_NEW,
            \T_CLASS,
            \T_ANON_CLASS,
            \T_DOUBLE_COLON,
            \T_FUNCTION,
            \T_CLOSURE,
            \T_CATCH,
        ];
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

        switch ($tokens[$stackPtr]['type']) {
            case 'T_FUNCTION':
            case 'T_CLOSURE':
                $this->processFunctionToken($phpcsFile, $stackPtr);

                // Deal with older PHPCS version which don't recognize return type hints
                // as well as newer PHPCS versions (3.3.0+) where the tokenization has changed.
                $returnTypeHint = $this->getReturnTypeHintToken($phpcsFile, $stackPtr);
                if ($returnTypeHint !== false) {
                    $this->processReturnTypeToken($phpcsFile, $returnTypeHint);
                }
                break;

            case 'T_CATCH':
                $this->processCatchToken($phpcsFile, $stackPtr);
                break;

            default:
                $this->processSingularToken($phpcsFile, $stackPtr);
                break;
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

        if ($tokens[$stackPtr]['type'] === 'T_NEW') {
            $FQClassName = $this->getFQClassNameFromNewToken($phpcsFile, $stackPtr);

        } elseif ($tokens[$stackPtr]['type'] === 'T_CLASS' || $tokens[$stackPtr]['type'] === 'T_ANON_CLASS') {
            $FQClassName = $this->getFQExtendedClassName($phpcsFile, $stackPtr);

        } elseif ($tokens[$stackPtr]['type'] === 'T_DOUBLE_COLON') {
            $FQClassName = $this->getFQClassNameFromDoubleColonToken($phpcsFile, $stackPtr);
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
        // Retrieve typehints stripped of global NS indicator and/or nullable indicator.
        $typeHints = $this->getTypeHintsFromFunctionDeclaration($phpcsFile, $stackPtr);
        if (empty($typeHints) || \is_array($typeHints) === false) {
            return;
        }

        foreach ($typeHints as $hint) {

            $typeHintLc = \strtolower($hint);

            if (isset($this->newClasses[$typeHintLc]) === true) {
                $itemInfo = [
                    'name'   => $hint,
                    'nameLc' => $typeHintLc,
                ];
                $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
            }
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
        $tokens = $phpcsFile->getTokens();

        // Bow out during live coding.
        if (isset($tokens[$stackPtr]['parenthesis_opener'], $tokens[$stackPtr]['parenthesis_closer']) === false) {
            return;
        }

        $opener = $tokens[$stackPtr]['parenthesis_opener'];
        $closer = ($tokens[$stackPtr]['parenthesis_closer'] + 1);
        $name   = '';
        $listen = [
            // Parts of a (namespaced) class name.
            \T_STRING              => true,
            \T_NS_SEPARATOR        => true,
            // End/split tokens.
            \T_VARIABLE            => false,
            \T_BITWISE_OR          => false,
            \T_CLOSE_CURLY_BRACKET => false, // Shouldn't be needed as we expect a var before this.
        ];

        for ($i = ($opener + 1); $i < $closer; $i++) {
            if (isset($listen[$tokens[$i]['code']]) === false) {
                continue;
            }

            if ($listen[$tokens[$i]['code']] === true) {
                $name .= $tokens[$i]['content'];
                continue;
            } else {
                if (empty($name) === true) {
                    // Weird, we should have a name by the time we encounter a variable or |.
                    // So this may be the closer.
                    continue;
                }

                $name   = \ltrim($name, '\\');
                $nameLC = \strtolower($name);

                if (isset($this->newExceptions[$nameLC]) === true) {
                    $itemInfo = [
                        'name'   => $name,
                        'nameLc' => $nameLC,
                    ];
                    $this->handleFeature($phpcsFile, $i, $itemInfo);
                }

                // Reset for a potential multi-catch.
                $name = '';
            }
        }
    }


    /**
     * Processes this test for when a return type token is encountered.
     *
     * - Detect new classes when used as a return type declaration.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    private function processReturnTypeToken(File $phpcsFile, $stackPtr)
    {
        $returnTypeHint = $this->getReturnTypeHintName($phpcsFile, $stackPtr);
        if (empty($returnTypeHint)) {
            return;
        }

        $returnTypeHint   = \ltrim($returnTypeHint, '\\');
        $returnTypeHintLc = \strtolower($returnTypeHint);

        if (isset($this->newClasses[$returnTypeHintLc]) === false) {
            return;
        }

        // Still here ? Then this is a return type declaration using a new class.
        $itemInfo = [
            'name'   => $returnTypeHint,
            'nameLc' => $returnTypeHintLc,
        ];
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
    }


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @since 7.1.0
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->newClasses[$itemInfo['nameLc']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @since 7.1.0
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The built-in class ' . parent::getErrorMsgTemplate();
    }
}
