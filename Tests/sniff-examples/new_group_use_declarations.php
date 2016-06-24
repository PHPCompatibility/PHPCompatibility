<?php

// Pre PHP 7 code
use some\oddnamespace\ClassA;
use some\oddnamespace\ClassB;
use some\oddnamespace\ClassC as C;

use somefunction some\oddnamespace\fn_a;
use somefunction some\oddnamespace\fn_b;
use somefunction some\oddnamespace\fn_c;

// PHP 7+ code
use some\oddnamespace\{ClassA, ClassB, ClassC as C};
use somefunction some\oddnamespace\{fn_a, fn_b, fn_c};
