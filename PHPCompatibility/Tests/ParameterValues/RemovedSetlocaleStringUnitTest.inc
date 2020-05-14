<?php

// OK.
setlocale();
setlocale(LC_ALL, 'nl_NL');
setlocale($category, $lang); // Can't be determined.

// Not OK.
setlocale('LC_ALL', 'nl_NL');
setlocale('LC_'.$category, $lang);

// Issue #1043 - ignore function calls, constants etc.
setlocale(getMyLocale('text'), 'nl_NL');
setlocale($array['LC_ALL'], 'nl_NL');
