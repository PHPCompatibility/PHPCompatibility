<?php
/*
 * Test spl_autoload_register() PHP 8.0 change in accepted values.
 */

// OK.
spl_autoload_register();
spl_autoload_register($autoload_function);
\spl_autoload_register($autoload_function, true, false);
spl_autoload_register($autoload_function, $throw, $prepend);
spl_autoload_register(callback: $autoload_function, prepend: false);

// Not OK.
spl_autoload_register($autoload_function, false);
\SPL_Autoload_Register(prepend: false, callback: $autoload_function, throw: false);

// Safeguard against false positives on method calls and namespaced function calls.
ClassName::spl_autoload_register($autoload_function, false);
$obj->spl_autoload_register($autoload_function, false);
$obj?->spl_autoload_register($autoload_function, false);
namespace\spl_autoload_register($autoload_function, false);
\Fully\Qualified\spl_autoload_register($autoload_function, false);
Partially\Qualified\spl_autoload_register($autoload_function, false);
