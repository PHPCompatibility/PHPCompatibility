<?php
function foo($x) {
    $x++;
    var_dump(func_get_arg(0));
    var_dump(func_get_args);
    var_dump(debug_backtrace());
}
foo(1);
