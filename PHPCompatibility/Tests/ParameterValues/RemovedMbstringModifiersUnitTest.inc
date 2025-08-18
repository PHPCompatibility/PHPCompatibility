<?php

// These should all be ignored as we're passing in a variable.
mb_ereg_replace( $pattern, $replace, $subject, $options );
mb_eregi_replace( $pattern, $replace, $subject, $options );
\mb_regex_set_options( $options );

// These should be ignored as they contain valid options.
mb_ereg_replace( $pattern, $replace, $subject, 'msi' );
mb_eregi_replace( $pattern, $replace, $subject, 'sim' );
mb_regex_set_options( 'ims' );

// These should all be flagged.
mb_ereg_replace( $pattern, $replace, $subject, 'e' );
MB_eregi_replace( $pattern, $replace, $subject, "seim" );
\mb_regex_set_options( 'im' . 'se' );

// Interpolated strings: These should NOT be flagged.
mb_ereg_replace( $pattern, $replace, $subject, "${se->{$b}}" );
mb_eregi_replace( $pattern, $replace, $subject, "s$eim" );
mb_regex_set_options( 'im' . "$se" );

// Interpolated strings: These should all be flagged.
mb_ereg_replace( $pattern, $replace, $subject, "e$m" );
\mb_eregi_replace( $pattern, $replace, $subject, "me$i" );
Mb_Regex_Set_Options( 'im' . "{$se}e" );

// Verify the sniff also picks up on the function aliases.
mbereg_replace( $pattern, $replace, $subject, 'e' );
mberegi_replace( $pattern, $replace, $subject, "me$i" );

// Issue #1043 - ignore function calls, constants etc.
mb_ereg_replace( $pattern, $replace, $subject, getOptions('type'));
mb_eregi_replace( $pattern, $replace, $subject, CONSTANT_NAME['array_access'] );

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::mb_ereg_replace( $pattern, $replace, $subject, 'e' );
$obj->mb_ereg_replace( $pattern, $replace, $subject, 'e' );
$obj?->mb_ereg_replace( $pattern, $replace, $subject, 'e' );
namespace\mb_ereg_replace( $pattern, $replace, $subject, 'e' );
\Fully\Qualified\mb_ereg_replace( $pattern, $replace, $subject, 'e' );
Partially\Qualified\mb_ereg_replace( $pattern, $replace, $subject, 'e' );

// More issue #1043, undetermined. Ignore.
mb_ereg_replace( $pattern, $replace, $subject, namespace\getOptions('type'));
\mb_ereg_replace( $pattern, $replace, $subject, \Fully\Qualified\getOptions('type'));
mb_ereg_replace( $pattern, $replace, $subject, Partially\Qualified\getOptions('type'));
