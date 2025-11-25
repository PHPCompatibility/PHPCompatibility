<?php

// Live coding/parse error. This test should be the only test in this test case file.
// Sample contains extra curly brackets to make sure we match the correct closer, and skip it as a parse error.
Foo::{{$bar->name}()};
