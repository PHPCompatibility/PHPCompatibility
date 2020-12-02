<?php
/*
 * Test spl_autoload_register() PHP 8.0 change in accepted values.
 */

// OK.
spl_autoload_register();
spl_autoload_register($autoload_function);
spl_autoload_register($autoload_function, true, false);
spl_autoload_register($autoload_function, $throw, $prepend);
spl_autoload_register(callback: $autoload_function, prepend: false);

// Not OK.
spl_autoload_register($autoload_function, false);
spl_autoload_register(prepend: false, callback: $autoload_function, throw: false);
