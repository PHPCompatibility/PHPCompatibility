<?php

// These are ok.
mktime(1, 2, 3, 4, 5, 6);
gmmktime(1, 2, 3, 4, 5, 6);

// These are not.
mktime(1, 2, 3, 4, 5, 6, true);
gmmktime(1, 2, 3, 4, 5, 6, true);
