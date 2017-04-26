<?php

namespace foo {
    class bar {}

    echo bar::class; // foo\bar
}

namespace MyNameSpace {
	class xyz {}

	remove_filter('theme_filter', [\namespace\xyz::class, 'methodName'], 30);
}

/*
 * False positives check.
 */
new class {} // Anonymous class, not the keyword.
echo bar::classProp; // Not the keyword.

/*
 * Invalid use check.
 */
echo foobar::class; // Invalid: Used outside of a namespace + class foobar does not exist in this file.
