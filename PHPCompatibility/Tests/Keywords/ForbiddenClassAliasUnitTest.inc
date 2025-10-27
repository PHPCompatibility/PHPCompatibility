<?php

/*
 * OK, not our target.
 * Safeguard against false positives on method calls and namespaced function calls.
 */
ClassName::class_alias('MyClass', 'bool');
$obj->class_alias('MyClass', 'iterable');
$obj?->class_alias('MyClass', 'parent');
namespace\Sub\class_alias('MyClass', 'int');
\Fully\Qualified\class_alias('MyClass', 'callable');
Partially\Qualified\class_alias('MyClass', 'mixed');


/*
 * OK: cross-version compatible.
 */
class_alias('MyClass', 'NotReservedKeyword');
class_alias(alias: 'MyClass', class: 'iterable'); // Error for other reasons (forbidden class name), but that's not the concern of this sniff.
class_alias('MyClass', 'MyNamespace\null');

// Invalid alias name due to the spaces, but PHP won't error on this and will just create an usuable class name, so not our concern.
class_alias('MyClass', ' mixed ');

// Safeguard against false positives when target param not found.
class_alias();
\class_alias($class, autoload: false);
class_alias($class, aliass: 'string'); // Typo in param name, not our concern.

// Ignore as undetermined.
class_alias('MyClass', "$false");
class_alias('MyClass', <<<EOD
$float
EOD
);

\class_alias('MyClass', $alias, $autoload);
class_alias('MyClass', get_alias('void'));
class_alias('MyClass', ClassName::MY_ALIAS);
class_alias('MyClass', namespace\MY_ALIAS);

class_alias('MyClass', __NAMESPACE__ . '\null');
class_alias('MyClass', $something . 'false');


/*
 * Reserved names used as a class alias.
 */

// Forbidden since PHP 7.0.
class_alias('MyClass', 'bool');
class_alias('MyClass', 'FALSE');
\class_alias('MyClass', "float");
class_alias('MyClass', <<<'EOD'
int
EOD
);
\Class_Alias('MyClass', <<<"EOD"
null
EOD
);
class_alias(
    'MyClass',
    // Also testing case handling.
    '\Parent' // phpcs:ignore Stnd.Cat.SniffName
);

class_alias(alias: 'self', class: 'MyClass');
CLASS_ALIAS('MyClass', /*comment*/ 'static'/*comment*/);
class_alias('MyClass', <<<EOD
string
EOD
);
class_alias('MyClass', "\True");

// Forbidden since PHP 7.1.
class_alias('MyClass', 'void');
class_alias('MyClass', 'iterable');

// Forbidden since PHP 7.2.
\class_alias('\MyClass', '\object');

// Forbidden since PHP 8.0.
class_alias('MyClass', 'mixed');

// Forbidden since PHP 8.1.
class_alias('MyClass', 'never');

// Forbidden since PHP 8.5.
\class_alias('MyClass', 'Array');
class_alias('MyClass', 'callable');
