<?php

// OK.
ob_start($output_callback);
ob_start($output_callback, $chunk_size);

// Undetermined. Ignore.
ob_start($output_callback, $chunk_size, $flags);
ob_start($output_callback, $chunk_size, CUSTOM_CONSTANT);
ob_start($output_callback, $chunk_size, My::do_erase());

// Not OK - error PHP < 5.4.
ob_start($output_callback, $chunk_size, true);
ob_start($output_callback, $chunk_size, false);

// Not OK - error PHP >= 5.4.
ob_start($output_callback, $chunk_size, PHP_OUTPUT_HANDLER_CLEANABLE);
ob_start($output_callback, $chunk_size, PHP_OUTPUT_HANDLER_FLUSHABLE);
ob_start($output_callback, $chunk_size, PHP_OUTPUT_HANDLER_REMOVABLE);
ob_start($output_callback, $chunk_size, PHP_OUTPUT_HANDLER_STDFLAGS);
ob_start($output_callback, $chunk_size, PHP_OUTPUT_HANDLER_STDFLAGS | PHP_OUTPUT_HANDLER_FLUSHABLE);
ob_start($output_callback, $chunk_size, PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_FLUSHABLE);
ob_start($output_callback, $chunk_size, 10);

// Safeguard support for PHP 8 named parameters.
ob_start(flags: false);
ob_start(flags: 20);
ob_start(flags: $flags); // OK.
