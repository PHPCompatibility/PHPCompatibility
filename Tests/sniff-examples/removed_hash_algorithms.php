<?php

hash_file("something");
hash_file("salsa10");
hash_file("salsa20");
hash_file('salsa10');
hash_file('salsa20');

hash_hmac_file("salsa10");

hash_hmac("salsa20");

hash_init("salsa20");

hash("salsa10");
hash("salsa10", "2nd param", 3, false);
hash("1st param", "salsa10");
hash_hmac;
