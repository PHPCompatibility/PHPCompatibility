<?php

/*
 * OK, not our target.
 * Safeguard against false positives on method calls and namespaced function calls.
 */
ClassName::class_alias('ArrayObject', 'MyAlias');
$obj->class_alias('RecursiveIteratorIterator', 'MyAlias');
$obj?->class_alias('Reflection', 'MyAlias');
namespace\Sub\class_alias('SoapClient', 'MyAlias');
\Fully\Qualified\class_alias('DOMDocument', 'MyAlias');
Partially\Qualified\class_alias('SimpleXMLElement', 'MyAlias');


/*
 * OK.
 */
// Not PHP internal class.
class_alias('MyClass', 'MyAlias');
class_alias(alias: 'MyAlias', class: '\MyClass');
class_alias('MyNS\DOMDocument', 'MyAlias');
class_alias('PHP_CodeSniffer\Util\Tokens', 'MyAlias'); // Make sure PHPCS native classes being aliased don't get flagged.

// Safeguard against false positives when target param not found.
class_alias();
\class_alias(alias: $alias, autoload: false);
class_alias(classs: $class, alias: 'string'); // Typo in param name, not our concern.

// Ignore as undetermined.
class_alias("$className", 'MyAlias');
class_alias(<<<EOD
$className
EOD
, 'MyAlias'
);

\class_alias($className, 'MyAlias', $autoload);
class_alias(get_className('AddressInfo'), 'MyAlias');
class_alias(ClassName::CLASS_NAME_TO_ALIAS, 'MyAlias');
class_alias(namespace\CLASS_NAME_TO_ALIAS, 'MyAlias', );

class_alias(__NAMESPACE__ . '\GMP', 'MyAlias');
class_alias($namespace . '\\' . 'ReflectionIntersectionType', 'MyAlias');

// At this moment we don't handle this (yet), but we should start handling this once the Utils namespace and use trackers are available.
class_alias(GMP::class, 'MyAlias');
class_alias(Partial\GMP::class, 'MyAlias');
class_alias(namespace\GMP::class, 'MyAlias');


/*
 * PHP 8.3: Aliasing PHP internal classes.
 *
 * Mind: use PHP native classes which are available in the lowest PHP version supported by PHPCompatibility for these tests!
 */
class_alias('FilterIterator', 'MyAlias');
\class_alias("IteratorIterator", 'MyAlias');
class_alias(<<<'EOD'
SplObjectStorage
EOD
, 'MyAlias'
);
\Class_Alias(<<<"EOD"
datetime
EOD
, 'MyAlias'
);
class_alias(
    // Also testing case handling.
    '\DateINTERVAL', // phpcs:ignore Stnd.Cat.SniffName
    'MyAlias',
);

class_alias(alias: 'MyAlias', class: 'ResourceBundle');
CLASS_ALIAS(/*comment*/ 'TransLiterator'/*comment*/, 'MyAlias');
class_alias(<<<EOD
Phar
EOD
, 'MyAlias'
);

\class_alias('\Traversable', 'MyAlias'); // Also applies to Internal interfaces.

// This test will only be flagged if the mysqli extension is loaded.
class_alias('MySqlI', 'MyAlias'); // Also testing use of different case than case used by PHP internally.

// This test will only be flagged if the tests are run on PHP 7.2+.
class_alias('HashContext', 'MyAlias');

// This test will only be flagged if the tests are run on PHP 8.0+.
class_alias("\WeakMap", 'MyAlias');
