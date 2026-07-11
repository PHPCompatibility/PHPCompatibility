<?php

/*
 * OK.
 */
// Safeguard against false positives on method calls and namespaced function calls.
ClassName::is_a('ClassName', $class, false);
$obj->is_subclass_of('ClassName', $class, false);
$obj?->is_a('ClassName', $class, false);
namespace\is_subclass_of('ClassName', $class, false);
\Fully\Qualified\is_a('ClassName', $class, false);
Partially\Qualified\is_subclass_of('ClassName', $class, false);

// Missing required parameters. Live coding/not our concern.
is_a();
is_subclass_of('ClassName');

// Required $object_or_class parameter not set. This is a coding error, but not our concern.
is_a(class: $class, allow_string: \false);
Is_subclass_of(allow_string: false, class: $class);

// Target $allow_string parameter not passed, so falls back to default value of `true` (for is_subclass_of() only). This is fine.
is_subclass_of('ClassName', $class);

// Target $allow_string parameter explicitly set to `true`. This is fine.
is_a('ClassName', $class, true);
is_a(
    ClassName::class,
    $class,
    \true
);
\Is_Subclass_Of( /*comment*/ '\My\ClassName', $class, TRUE /*comment*/);

// Ignore non-hard-coded $allow_string values as undetermined.
is_a('ClassName', $class, $allow_string);
\is_subclass_of('ClassName', $class, $obj->allow_string);
is_a(
    'ClassName',
    $class,
    // Comment.
    get_allow_string(false)
);
is_subclass_of('ClassName', $class, ALLOW_STRING);
is_a('ClassName', $class, self::ALLOW_STRING);
is_subclass_of('ClassName', $class, $obj->allowed(false));
\is_a('ClassName', $class, $allow_string[false]);
is_subclass_of('ClassName', $class, namespace\allowed(\false));
is_a('ClassName', $class, \Fully\Qualified\ALLOW_STRING);

// Ignore non-hard-coded $object_or_class values as undetermined, with the exception of ::class resolution.
is_a($object_or_class, $class, false);
\is_subclass_of($object->or_class, $class, FALSE);
is_a(get_object_or_class('Name'), $class, \false);
is_subclass_of(OBJECT_OR_CLASS, $class, false);
is_a(self::OBJECT_OR_CLASS, $class, FALSE);
is_subclass_of(new ClassName, $class, \false);
\is_a($obj->get('Name'), $class, false);
is_subclass_of(namespace\get('Name'), $class, \false);
is_a(\Fully\Qualified\get('Name'), $class, false);
is_subclass_of(Partially\Qualified\get('Name'), $class, false);
is_subclass_of( $object_names['name'], $obj::class, false );
is_a( $ns . '\MyClass', $class, false )
is_subclass_of(
    <<<"EOD"
$className
EOD
,
    $class,
    false
);

// False negative, but this is a theoretical edge case which I don't expect to ever see in the wild anyway.
is_subclass_of(
    <<<"EOD"
\My\ClassName
EOD
,
    $class,
    false
);

// Safeguard handling of PHP 8 named parameters.
is_a(class: $class, allow_string: \true, object_or_class: 'Name'); // Valid.
is_subclass_of(allow_string: false, object_or_class: $obj, class: $class); // Valid.

is_a(class: $class, allow_string: false, object_or_classes: 'Name'); // OK, well not really, but using incorrect parameter name.
is_subclass_of(allow_strings: false, object_or_class: 'Name', class: $class); // OK, well not really, but using incorrect parameter name.


/*
 * PHP 8.6: setting $allow_string to false while passing a text string for $object_or_class is deprecated.
 */
is_a('ClassName', $class); // Default for $allow_string for is_a() is `false`.
is_a('ClassName', $class, false);
is_a(
    // Comment
    "ClassName",
    /* Also comment */
    $class,
);
\Is_Subclass_Of( /*comment*/ '\My\ClassName', $class, FALSE );
is_subclass_of(
    <<<'EOD'
\My\ClassName
EOD
,
    $class,
    // Comment
    \false
    /* Also comment */
);

IS_A( __NAMESPACE__ . '\MyClass', $obj::class, false )
is_subclass_of( __CLASS__, $class, false )

\is_a($obj::class, '\My\NS\Name');
is_subclass_of(Name::CLASS, '\My\NS\Name', false);
is_a(PartiallyQualified\Name::Class, '\My\NS\Name', false);
\is_subclass_of(\Fully\Qualified\Name  ::  class, '\My\NS\Name', false);
is_a(namespace\Name /*comment*/ :: /*comment*/ class, '\My\NS\Name');

// Safeguard handling of PHP 8 named parameters.
is_a(class: $class, object_or_class: 'Name');
is_subclass_of(allow_string: \false, object_or_class: 'Name', class: $class);
