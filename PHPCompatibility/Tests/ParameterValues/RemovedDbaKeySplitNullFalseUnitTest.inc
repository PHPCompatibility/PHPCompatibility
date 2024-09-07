<?php

// Not our target.
$obj?->dba_key_split(false);
MyClass::dba_key_split(null);
My\Ns\dba_key_split(false);
$class = new dba_key_split(\dba_firstkey($dba));

// Ignore, required param(s) missing, but that's not our concern.
dba_key_split();
dba_key_split( keys: false); // Typo in param name.
dba_key_split( /*comment*/ );

// OK.
dba_key_split('[key1]name1');
\dba_key_split( /*comment*/ '[key1]name1' . '[key2]name2');

// Undetermined. Ignore.
dba_key_split($key);
dba_key_split(KEY_IN_CONSTANT);
dba_key_split(myDbaGetKey());

// Not OK - deprecated in PHP >= 8.4.
dba_key_split(false);
DBA_KEY_SPLIT(key:   NULL );
dba_key_split(
    // phpcs:ignore Stnd.Cat.Sniff -- for (testing) reasons.
    FALSE
);
\dba_key_split( /*comment*/ null /*comment*/ );

// Typical real world code which would typically trigger the deprecation when the end of the DB is reached.
// Even though this code is fine while not at the end of the DB, the sniff should still flag it as
// without a check against the return value of `dba_[first|next]key()` before passing it to `dba_key_split()`,
// this code _will_ result in the deprecation notice once the end of the DB is reached (or when an empty DB
// is queried).
dba_key_split(dba_firstkey($dba));
dba_key_split(key: dba_nextkey($obj->get_dba()));
dba_key_split(\dba_firstkey($this->dba));
dba_key_split(
    // Comment.
    \dba_NextKey($dba)
);
