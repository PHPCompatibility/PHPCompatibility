<?php

// OK.
$binarydata = pack("nvc*", 0x1234, 0x5678, 65, 66);
$binarydata = \unpack("n{$e}vc*", 0x1234, 0x5678, 65, 66);

// Not OK.
$binarydata = unpack("nZc*", 0x1234, 0x5678, 65, 66);
$binarydata = pack("nvq*", 0x1234, 0x5678, 65, 66);
$binarydata = \pack("Qnvc*", 0x1234, 0x5678, 65, 66);
$binarydata = unpack("nJc*", 0x1234, 0x5678, 65, 66);
$binarydata = pack('nvPc*', 0x1234, 0x5678, 65, 66);
$binarydata = unpack("nec*", 0x1234, 0x5678, 65, 66);
$binarydata = PACK("Envc*", 0x1234, 0x5678, 65, 66);
$binarydata = pack('nvcgG*', 0x1234, 0x5678, 65, 66);
$binarydata = \unpack("n{$s}vGc*", 0x1234, 0x5678, 65, 66);

$binarydata = pack("ZJE*", 0x1234, 0x5678, 65, 66); // Error x 3.

$binarydata = unpack('nv' . /*comment*/ 'Pc*', 0x1234, 0x5678, 65, 66); // Testing multi-token.

// Issue #1043 - OK, text in brackets, not the actual format.
$binarydata = pack($foo['test'], 0x1234, 0x5678, 65, 66);
$binarydata = unpack($obj->getModes('type'), 0x1234, 0x5678, 65, 66);

// Test handling of more complex embedded variables and expressions.
$binarydata = pack("n${(eq)}vc*", 0x1234, 0x5678, 65, 66); // OK.

// More tests related to issue #1043 - all OK.
pack(namespace\setFormat('type'));
unpack(\Fully\Qualified\setFormat('type'));
\pack(Partially\Qualified\setFormat('type'));

// Safeguard against false positives. Not our target.
$obj->pack("nZc*", 0x1234, 0x5678, 65, 66);
$obj?->unpack("nZc*", 0x1234, 0x5678, 65, 66);
MyClass::pack("nZc*", 0x1234, 0x5678, 65, 66);
\Fully\Qualified\unpack("nZc*", 0x1234, 0x5678, 65, 66);
Partially\Qualified\pack("nZc*", 0x1234, 0x5678, 65, 66);
namespace\unpack("nZc*", 0x1234, 0x5678, 65, 66);
