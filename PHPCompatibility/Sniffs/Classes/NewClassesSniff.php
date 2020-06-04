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
use PHP_CodeSniffer_File as File;

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
    protected $newClasses = array(
        'ArrayObject' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ),
        'ArrayIterator' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ),
        'CachingIterator' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ),
        'DirectoryIterator' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ),
        'RecursiveDirectoryIterator' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ),
        'RecursiveIteratorIterator' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'spl',
        ),
        'php_user_filter' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'tidy' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ),
        'tidyNode' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ),
        'Reflection' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ),
        'ReflectionClass' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ),
        'ReflectionExtension' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ),
        'ReflectionFunction' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ),
        'ReflectionMethod' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ),
        'ReflectionObject' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ),
        'ReflectionParameter' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ),
        'ReflectionProperty' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ),
        'SoapClient' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ),
        'SoapServer' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ),
        'SoapHeader' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ),
        'SoapParam' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ),
        'SoapVar' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ),
        'COMPersistHelper' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'DOMAttr' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMCdataSection' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMCharacterData' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMComment' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMDocument' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMDocumentFragment' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMDocumentType' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMElement' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMEntity' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMEntityReference' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMImplementation' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMNamedNodeMap' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMNode' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMNodeList' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMNotation' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMProcessingInstruction' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMText' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'DOMXPath' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'SimpleXMLElement' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'simplexml',
        ),

        'libXMLError' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ),
        'PDO' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pdo',
        ),
        'PDOStatement' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pdo',
        ),
        'AppendIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'EmptyIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'FilterIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'InfiniteIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'IteratorIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'LimitIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'NoRewindIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'ParentIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'RecursiveArrayIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'RecursiveCachingIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'RecursiveFilterIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'SimpleXMLIterator' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'simplexml',
        ),
        'SplFileObject' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'SplObjectStorage' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'XMLReader' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'xmlreader',
        ),

        'SplFileInfo' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'spl',
        ),
        'SplTempFileObject' => array(
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'spl',
        ),
        'XMLWriter' => array(
            '5.1.1' => false,
            '5.1.2' => true,
        ),

        'DateTime' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'DateTimeZone' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'RegexIterator' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'spl',
        ),
        'RecursiveRegexIterator' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'spl',
        ),
        'ReflectionFunctionAbstract' => array(
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'reflection',
        ),
        'ZipArchive' => array(
            '5.1' => false,
            '5.2' => true,
        ),

        'Closure' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'DateInterval' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'DatePeriod' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'finfo' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ),
        'Collator' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'NumberFormatter' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'Locale' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'Normalizer' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'MessageFormatter' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'IntlDateFormatter' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ),
        'Phar' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'phar',
        ),
        'PharData' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'phar',
        ),
        'PharFileInfo' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'phar',
        ),
        'FilesystemIterator' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),
        'GlobIterator' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),
        'MultipleIterator' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),
        'RecursiveTreeIterator' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),
        'SplDoublyLinkedList' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),
        'SplFixedArray' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),
        'SplHeap' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),
        'SplMaxHeap' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),
        'SplMinHeap' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),
        'SplPriorityQueue' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),
        'SplQueue' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),
        'SplStack' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'spl',
        ),

        'ResourceBundle' => array(
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'intl',
        ),

        'CallbackFilterIterator' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'spl',
        ),
        'RecursiveCallbackFilterIterator' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'spl',
        ),
        'ReflectionZendExtension' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'reflection',
        ),
        'SessionHandler' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'SNMP' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'Transliterator' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ),
        'Spoofchecker' => array(
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ),

        'Generator' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'CURLFile' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'DateTimeImmutable' => array(
            '5.4' => false,
            '5.5' => true,
        ),
        'IntlCalendar' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'IntlGregorianCalendar' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'IntlTimeZone' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'IntlBreakIterator' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'IntlRuleBasedBreakIterator' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'IntlCodePointBreakIterator' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'IntlPartsIterator' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'IntlIterator' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),
        'UConverter' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),

        'GMP' => array(
            '5.5' => false,
            '5.6' => true,
        ),

        'IntlChar' => array(
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'intl',
        ),
        'ReflectionType' => array(
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'reflection',
        ),
        'ReflectionGenerator' => array(
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'reflection',
        ),

        'ReflectionClassConstant' => array(
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'reflection',
        ),
        'ReflectionNamedType' => array(
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'reflection',
        ),

        'HashContext' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'hash',
        ),

        'FFI' => array(
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ),
        'FFI\CData' => array(
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ),
        'FFI\CType' => array(
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ),
        'ReflectionReference' => array(
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'reflection',
        ),
        'WeakReference' => array(
            '7.3' => false,
            '7.4' => true,
        ),
    );

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
    protected $newExceptions = array(
        'com_exception' => array(
            '4.4' => false,
            '5.0' => true,
        ),
        'DOMException' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ),
        'Exception' => array(
            // According to the docs introduced in PHP 5.1, but this appears to be.
            // an error.  Class was introduced with try/catch keywords in PHP 5.0.
            '4.4' => false,
            '5.0' => true,
        ),
        'ReflectionException' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'reflection',
        ),
        'SoapFault' => array(
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ),
        'SQLiteException' => array(
            '4.4' => false,
            '5.0' => true,
        ),

        'ErrorException' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'BadFunctionCallException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'BadMethodCallException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'DomainException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'InvalidArgumentException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'LengthException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'LogicException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'mysqli_sql_exception' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'OutOfBoundsException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'OutOfRangeException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'OverflowException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'PDOException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'pdo',
        ),
        'RangeException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'RuntimeException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'UnderflowException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),
        'UnexpectedValueException' => array(
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'spl',
        ),

        'PharException' => array(
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'phar',
        ),

        'SNMPException' => array(
            '5.3' => false,
            '5.4' => true,
        ),

        'IntlException' => array(
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'intl',
        ),

        'Error' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'ArithmeticError' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'AssertionError' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'DivisionByZeroError' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'ParseError' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'TypeError' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'ClosedGeneratorException' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'UI\Exception\InvalidArgumentException' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'UI\Exception\RuntimeException' => array(
            '5.6' => false,
            '7.0' => true,
        ),

        'ArgumentCountError' => array(
            '7.0' => false,
            '7.1' => true,
        ),

        'SodiumException' => array(
            '7.1' => false,
            '7.2' => true,
        ),

        'CompileError' => array(
            '7.2' => false,
            '7.3' => true,
        ),
        'JsonException' => array(
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'json',
        ),

        'FFI\Exception' => array(
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ),
        'FFI\ParserException' => array(
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'ffi',
        ),
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.5
     * @since 7.0.3 - Now also targets the `class` keyword to detect extended classes.
     *              - Now also targets double colons to detect static class use.
     * @since 7.1.4 - Now also targets anonymous classes to detect extended classes.
     *              - Now also targets functions/closures to detect new classes used
     *                as parameter type declarations.
     *              - Now also targets the `catch` control structure to detect new
     *                exception classes being caught.
     * @since 8.2.0 Now also targets the `T_RETURN_TYPE` token to detect new classes used
     *              as return type declarations.
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of class names.
        $this->newClasses    = \array_change_key_case($this->newClasses, \CASE_LOWER);
        $this->newExceptions = \array_change_key_case($this->newExceptions, \CASE_LOWER);

        // Add the Exception classes to the Classes list.
        $this->newClasses = array_merge($this->newClasses, $this->newExceptions);

        return array(
            \T_NEW,
            \T_CLASS,
            \T_ANON_CLASS,
            \T_DOUBLE_COLON,
            \T_FUNCTION,
            \T_CLOSURE,
            \T_CATCH,
            \T_RETURN_TYPE,
        );
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
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

            case 'T_RETURN_TYPE':
                $this->processReturnTypeToken($phpcsFile, $stackPtr);
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
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
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

        $className   = substr($FQClassName, 1); // Remove global namespace indicator.
        $classNameLc = strtolower($className);

        if (isset($this->newClasses[$classNameLc]) === false) {
            return;
        }

        $itemInfo = array(
            'name'   => $className,
            'nameLc' => $classNameLc,
        );
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
    }


    /**
     * Processes this test for when a function token is encountered.
     *
     * - Detect new classes when used as a parameter type declaration.
     *
     * @since 7.1.4
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
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

            $typeHintLc = strtolower($hint);

            if (isset($this->newClasses[$typeHintLc]) === true) {
                $itemInfo = array(
                    'name'   => $hint,
                    'nameLc' => $typeHintLc,
                );
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
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
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
        $listen = array(
            // Parts of a (namespaced) class name.
            \T_STRING              => true,
            \T_NS_SEPARATOR        => true,
            // End/split tokens.
            \T_VARIABLE            => false,
            \T_BITWISE_OR          => false,
            \T_CLOSE_CURLY_BRACKET => false, // Shouldn't be needed as we expect a var before this.
        );

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

                $name   = ltrim($name, '\\');
                $nameLC = strtolower($name);

                if (isset($this->newExceptions[$nameLC]) === true) {
                    $itemInfo = array(
                        'name'   => $name,
                        'nameLc' => $nameLC,
                    );
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
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    private function processReturnTypeToken(File $phpcsFile, $stackPtr)
    {
        $returnTypeHint = $this->getReturnTypeHintName($phpcsFile, $stackPtr);
        if (empty($returnTypeHint)) {
            return;
        }

        $returnTypeHint   = ltrim($returnTypeHint, '\\');
        $returnTypeHintLc = strtolower($returnTypeHint);

        if (isset($this->newClasses[$returnTypeHintLc]) === false) {
            return;
        }

        // Still here ? Then this is a return type declaration using a new class.
        $itemInfo = array(
            'name'   => $returnTypeHint,
            'nameLc' => $returnTypeHintLc,
        );
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
