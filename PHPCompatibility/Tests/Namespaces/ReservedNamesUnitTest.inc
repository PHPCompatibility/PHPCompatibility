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

// Intentional parse error. This has to be the last test in the file.
namespace PHP\Cli
