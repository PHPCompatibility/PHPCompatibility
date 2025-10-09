<?php

// Live coding/parse error (missing closing scope brace). This test should be the only test in this test case file.
function LiveCoding($x) {
    $x = function_call($x);
    func_get_args(); // Ignored.
