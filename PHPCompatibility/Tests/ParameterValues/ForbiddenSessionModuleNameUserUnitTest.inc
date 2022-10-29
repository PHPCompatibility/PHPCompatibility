<?php
/*
 * Test session_module_name() PHP 7.2 change in accepted values.
 */

// OK.
session_module_name(module: $module);
session_module_name();
session_module_name('user' . $something);
session_module_name(<<<EOD
user-nonsense
EOD
);

// Not OK.
session_module_name('user');
session_module_name(module: "USER");
session_module_name(<<<'EOD'
user
EOD
);
