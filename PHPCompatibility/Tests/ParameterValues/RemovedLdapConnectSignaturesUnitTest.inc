<?php
/*
 * Test ldap_connect() alternative signatures.
 */

/*
 * OK.
 */
// Not our target.
echo LDAP_CONNECT;
$obj->ldap_connect;
$obj->ldap_connect();
$obj?->ldap_connect();
MyClass::ldap_connect();
My\Vendor\ldap_connect();
#[Ldap_Connect()]
function do_something() {}

ldap_connect();
ldap_connect( /*comment */ );
LDAP_Connect($uri);
\ldap_connect( "ldap://$host:$port??369" );

// Signature still supported in PHP 8.3, will be deprecated in PHP 8.4.
ldap_connect($uri, null, $wallet, $password, $auth_mode); // Port value null is okay.
ldap_connect(
    $uri,
    $port, // Value undetermined, so ignore.
    $wallet,
    $password,
    $auth_mode,
);
ldap_connect($uri, MY_PORT, $wallet, $password, $auth_mode); // Port value undetermined, so ignore.
ldap_connect($uri, get_port(), $wallet, $password, $auth_mode); // Port value undetermined, so ignore.
ldap_connect($uri, 369 + $offset, $wallet, $password, $auth_mode); // Port value undetermined, so ignore.

// Invalid function calls (missing required param(s)). Ignore as not our target.
\ldap_connect(port: $port);
\ldap_connect(port: $port, wallet: $wallet);
\ldap_connect(uri: $uri, wallet: $wallet);
\ldap_connect(auth_mode: $auth_mode, wallet: $wallet);
\ldap_connect(auth_mode: $auth_mode, wallet: $wallet, password: $password);

/*
 * PHP 8.3: calling ldap_connect() with 2 parameters.
 */
ldap_connect($host, $port);

// Safeguard support for PHP 8 named parameters.
// The function itself does not support this for this signature, but that's not the concern of this sniff.
\ldap_connect(uri: $host, port: $port);
Ldap_Connect(port: $port, host: $host);

// Three-plus param signature passing a port is also deprecated.
ldap_connect($uri, 369, $wallet, $password, $auth_mode);
