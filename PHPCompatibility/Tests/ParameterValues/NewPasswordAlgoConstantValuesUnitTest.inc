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
