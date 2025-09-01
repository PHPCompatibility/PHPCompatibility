<?php

// OK.
implode();
implode($pieces);
implode(', ', $pieces);
\implode( "$glue", $pieces );
implode(PHP_EOL, $pieces);

join();
join( $pieces );
join(', ' /*comment*/, $pieces);
\join("$glue", $pieces);
join(DIRECTORY_SEPARATOR, $pieces);

// Undetermined, sniff stays silent.
implode($glue, $pieces);
implode( $settings['glue'], $pieces );
implode( $pieces, $settings['glue'] );
Implode( $pieces, get_glue( 'type' ) );
implode( $pieces, $obj->get_glue() );
implode( $pieces, MY_GLUE );
implode( Custom\array_map( 'strtolower', $pieces ), $glue );
\implode( $obj->array_map( 'strtolower', $pieces ), $glue );
implode( My_Class::array_map( 'strtolower', $pieces ), $glue );
implode( array_search( $glue_needle, $haystack ), $pieces );

// Not OK.
implode( $pieces, ', ' );
join($pieces, ' ');

implode(array('a', 'b'), $glue);
\join( [1, 2] /*comment*/, $glue);

implode(array('a', 'b'), '-' . '|');
join([1, 2], "$glue");
\implode($pieces, ('-' . '|'));
implode( $pieces, ( $type === 'a' ? '-' : '|') );

join ($pieces, <<<EOD
text
EOD
);

// Special cased functions.
implode( array_map( 'strtolower', $pieces ), $glue );
IMPLODE( array_value( $pieces ), $glue );
implode( explode( ',', $text ), $glue );
implode( compact( $piece1, $piece2, $piece3 ), $glue );

// Special cased PHP native constants.
implode( $pieces, PHP_EOL );
implode( $pieces, DIRECTORY_SEPARATOR );

// Issue #890 - Hardening for ternary as part of second param.
$defaults = [
    'class'    => implode(
        ' ',
        array_filter( [ 'a' => $type === 'a' ? '-' : '|', ] )
    ),
];

// Efficiency, second param doesn't even need to be examined here.
implode( ( $type === 'a' ? '-' : '|' ), (array) $pieces );

// Recognize array casts.
implode( $glue, (array) $object ); // OK.
implode( (array) $object, $glue ); // Error.

// Prevent false positives on PHP 8.0+ nullsafe method calls.
$obj?->implode( $pieces, ', ' );
implode( $obj?->array_map( 'strtolower', $pieces ), $glue ); // Undetermined, same as other params with unrecognized function calls.

// More safeguard against false positives on method calls and namespaced function calls.
ClassName::implode(array('a', 'b'), $glue);
$obj->join($pieces, ' ');
namespace\implode(array('a', 'b'), $glue);
\Fully\Qualified\join($pieces, ' ');
Partially\Qualified\implode(array('a', 'b'), $glue);

// Safeguard for fully qualified and uppercase special cased functions.
implode( \array_map( 'strtolower', $pieces ), $glue );
\join( ARRAY_VALUE( $pieces ), $glue );
implode( \Explode( ',', $text ), $glue );
\implode( \compact( $piece1, $piece2, $piece3 ), $glue );
implode( \array_pop( $array ), $pieces ); // OK.

// Safeguard for fully qualified special cased PHP native constants.
implode( \PHP_EOL, $pieces, ); // OK.
implode( \DIRECTORY_SEPARATOR, $pieces, ); // OK.
implode( $pieces, \PHP_EOL );
implode( $pieces, \DIRECTORY_SEPARATOR );
