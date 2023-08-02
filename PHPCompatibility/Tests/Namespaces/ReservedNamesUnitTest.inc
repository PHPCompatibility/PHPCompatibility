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

// Intentional parse error. This has to be the last test in the file.
namespace PHP\Cli
