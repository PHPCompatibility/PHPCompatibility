<?php

// OK.
namespace MyPHPApp;
namespace My\PHP\App;
namespace {
    // Do something.
}

// Warning.
namespace PHP;
namespace PHP\App;
namespace PHP\Classes {
    // Do something.
}

// Error PHP 7.4+.
namespace FFI;
namespace FFI\MyClass;

// Error PHP 8.2+.
namespace Random;
namespace Random\Generator;

// Verify the sniff handles case-insensitivety correctly.
namespace php\something; // Warning.
namespace rANdOm; // Error.

// Verify the sniff ignores use of the namespace keyword when not used for a declaration.
namespace My\namespace; // Allowed since PHP 8.0.
namespace\php\function_call(); // Use of the keyword as an operator.
echo namespace\random\SomeConstant; // Use of the keyword as an operator.

// Intentional parse error. This has to be the last test in the file.
namespace PHP\Cli
