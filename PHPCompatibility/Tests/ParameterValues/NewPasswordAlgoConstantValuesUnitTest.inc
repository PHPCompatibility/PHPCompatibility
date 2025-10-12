<?php

// OK.
$hash = password_hash( $password, PASSWORD_DEFAULT, $options );
$hash = password_hash( $password, \PASSWORD_BCRYPT, $options );
$hash = \password_needs_rehash( $password, PASSWORD_ARGON2I, $options );
$hash = password_hash(
    password: $password,
    options: $options
    // comment.
    algo: PASSWORD_ARGON2ID,
);

// Undetermined. Ignore.
$hash = password_hash( $password, $algo, $options );
$hash = password_hash( $password, $this->get_algo(), $options );
$hash = password_hash( $password, static::ALGO, $options );
$hash = password_hash( $password, MyClass::PASSWORD_BCRYPT, $options );
$hash = password_hash( $password, \MyNamespace\PASSWORD_BCRYPT, $options );

// Not OK - error.
$hash = PassWord_hash( $password, null, $options );
$hash = password_hash( $password, +1, $options );
$hash = password_needs_rehash( $password, 2, $options );
$hash = password_hash( algo: 3, password: $password, options: $options );
$hash = \password_hash( $password, '2y', $options );
$hash = password_HASH( $password, "argon{$type}" /*comment*/, $options );
$hash = password_needs_rehash( $password, 'argon2id', $options );

// Prevent false positives on parameters passed to a function call used to retrieve the algo name
// or for array access.
$hash = password_hash( $password, get_algo( 'argon' ), $options );
$hash = password_hash( $password, $obj?->get_algo( 10 ), $options );
$hash = password_hash( $password, $algos['argon'], $options );
$hash = password_hash( $password, $algos{'argon'}, $options );
$hash = \password_hash( $password, namespace\get_algo( 'argon' ), $options );
$hash = password_hash( $password, \Fully\Qualified\get_algo( 'argon' ), $options );
$hash = password_hash( $password, Partially\Qualified\get_algo( 'argon' ), $options );

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::password_hash( $password, +1, $options );
$obj->password_needs_rehash( $password, 2, $options );
$obj?->password_hash( $password, '2y', $options );
namespace\password_hash( $password, +1, $options );
\Fully\Qualified\password_needs_rehash( $password, 2, $options );
Partially\Qualified\password_hash( $password, '2y', $options );

// Safeguard that fully qualified and uppercase true/false/null are handled correctly, not OK.
password_hash( $password, NULL, $options );
password_hash( $password, \null, $options );
password_hash( $password, true, $options );
password_hash( $password, TRUE, $options );
password_hash( $password, \true, $options );
password_hash( $password, false, $options );
password_hash( $password, FALSE, $options );
password_hash( $password, \false, $options );
