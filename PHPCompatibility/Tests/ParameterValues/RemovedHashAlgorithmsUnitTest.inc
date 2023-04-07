<?php

/**
 * These should all be fine.
 */
hash_file("something"); // Not one of the targetted algorithms.
hash(algo: "1st param", "salsa10"); // Not the right parameter.
hash_hmac; // Not a function call.

/**
 * These should all be flagged.
 */
hash_file("salsa10");
hash_file("salsa20");
hash_file('salsa10');
hash_file('salsa20');

hash_hmac_file(algo: "salsa10");
hash_hmac( "salsa20" /*comment*/ );
hash_init(   'salsa10'  );

hash(/*comment*/ "salsa10");
hash("salsa10", "2nd param", 3, false);

hash_pbkdf2('salsa20');

// Safeguard handling of function call missing required param / live coding.
hash_init();
hash_hmac( data: 'salsa20', key: $key ); // Missing $algo param.
hash_pbkdf2(algorithm: 'salsa20'); // Incorrect param name.
