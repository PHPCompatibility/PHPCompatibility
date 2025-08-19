<?php
/*
 * Test hash functions PHP 7.2 change in accepted values.
 */

// OK.
hash_init( 'fnv132');
hash_init( 'sha1', HASH_HMAC, $key);
hash_init( algo: 'gost-crypto', 1);

// Not OK.
hash_hmac('adler32');
\hash_hmac_file("crc32");
hash_pbkdf2('crc32b');
hash_init( flags: HASH_HMAC, algo: 'fnv132', key: $key );
hash_hmac('fnv1a32');
hash_hmac_file("fnv164",);
hash_pbkdf2('fnv1a64');
\hash_init( 'joaat', 1);
hash_pbkdf2(
    'adler32' // Comment.
);
HASH_INIT( 'crc32b', HASH_HMAC /*comment*/);

// Safeguard against false positives when target param not found.
hash_init( algorithm: 'gost-crypto', flags: 1); // OK, well not really, but incorrect algo param name used.

// Safeguard against false negatives for hash_init with FQN HASH_HMAC flag.
hash_init( 'joaat', \HASH_HMAC, $key);
hash_init( 'joaat', \  HASH_HMAC, $key); // No longer valid since PHP 8.0, but that's not the concern of this sniff.

// Safeguard against false positives when $flag for hash_init is not HASH_HMAC.
hash_init( 'joaat', 0, $key);
hash_init( 'joaat', $unknownValue, $key);

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::hash_file('fnv164');
$obj->hash("crc32");
$obj?->hash_hmac_file('joaat');
namespace\hash('adler32');
\Fully\Qualified\hash_init('crc32b');
Partially\Qualified\hash('fnv132');
