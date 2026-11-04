<?php

/*
 * Unchanged: block mode was never allowed when combining declare(encoding=...) with a namespace declaration.
 */
declare(encoding='utf8') {
    namespace Foo;

    function bar() {}
}

declare(encoding="ISO-8859-15"):
    namespace Foo;

    function bar() {}
enddeclare;
