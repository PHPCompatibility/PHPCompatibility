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

/*
 * PHP 8.6: endianness modifiers on integers.
 */
// Examples taken straight from the RFC.
// These should all be flagged.
$data = pack('s<l<q<', -258, -16909060, -72340172838076673); // Little-endian signed integers.
$data = pack('s>l>q>', -258, -16909060, -72340172838076673); // Big-endian signed integers.
$data = pack('S<L>Q<', 258, 16909060, 72340172838076673); // Unsigned integers with explicit endianness.
$data = pack('s<2l>2', 258, -2, 16909060, -16909060); // Mixed endianness (little-endian 16-bit, big-endian 32-bit).
[$int16_le, $int32_le] = array_values(unpack('s<a/l<b', $data));
[$uint16_be, $uint32_le] = array_values(unpack('S>a/L<b', $data));

// Prevent false positives for unsupported format letters or inherent endianness.
// These should *not* be flagged.
pack('a<', 'test'); // ValueError: Endianness modifier '<' is not supported for format code 'a'.
pack('H>', 'test'); // ValueError: Endianness modifier '>' is not supported for format code 'H'.
pack('v<', 42); // ValueError: Endianness modifier '<' cannot be applied to format code 'v' which already has inherent endianness.
pack('N>', 42); // ValueError: Endianness modifier '>' cannot be applied to format code 'N' which already has inherent endianness.

/*
 * PHP 8.6: endianness modifiers on floats.
 */
// Examples taken straight from the RFC.
$data = pack('f<d<', 3.14159, 2.71828); // Little endian.
$data = pack('f>d>', 3.14159, 2.71828); // Big endian.
[$float, $double] = array_values(unpack('f<a/d<b', $data));

// Prevent false positives for unsupported format letters.
// These should *not* be flagged.
pack('g<', 3.14); // ValueError: Endianness modifier '<' cannot be applied to format code 'g' which already has inherent endianness.
pack('G>', 3.14); // ValueError: Endianness modifier '>' cannot be applied to format code 'G' which already has inherent endianness.
pack('e<', 3.14); // ValueError: Endianness modifier '<' cannot be applied to format code 'e' which already has inherent endianness.
pack('E>', 3.14); // ValueError: Endianness modifier '>' cannot be applied to format code 'E' which already has inherent endianness.

// Safeguard handling of live coding/missing target parameter.
$data = pack(formatcode: 'Z'); // Wrong parameter name. Also named params not supported as function takes variable nr of arguments.
