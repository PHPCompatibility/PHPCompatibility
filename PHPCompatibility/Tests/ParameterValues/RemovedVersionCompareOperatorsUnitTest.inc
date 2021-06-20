<?php

// OK cross-version.
version_compare($version1, $version2);
version_compare($version1, $version2, /*comment*/ );
version_compare($version1, $version2, '<');
version_compare($version1, $version2, 'lt');
version_compare($version1, $version2, '<=');
version_compare($version1, $version2, <<<'EOD'
le
EOD
);
version_compare($version1, $version2, '>');
version_compare($version1, $version2, 'gt');
version_compare($version1, $version2, ">=");
version_compare(operator: 'ge', version1: $version1, version2: $version2);
version_compare($version1, $version2, '==');
version_compare($version1, $version2, '=');
version_compare($version1, $version2, 'eq' /*comment*/);
version_compare($version1, $version2, '!=');
version_compare($version1, $version2, <<<EOD
<>
EOD
);
version_compare($version1, $version2, 'ne');

// Ignore as undetermined.
version_compare($version1, $version2, "$operator"); // Can't be determined.
version_compare($version1, $version2, $operator); // Can't be determined.
version_compare($version1, $version2, getOperator('e')); // Can't be determined.
version_compare($version1, $version2, $operators['e']); // Can't be determined.
version_compare($version1, $version2, g); // Lowercase constant. Can't be determined.

// Ignore as never supported.
version_compare($version1, $version2, 'GE');
version_compare($version1, $version2, 'E');
version_compare($version1, $version2, <<<'EOD'
g
e
EOD
);

// PHP 8.1: operators for which support has been removed.
version_compare($version1, $version2, '');
version_compare($version1, $version2, "");
version_compare(version1: $version1, operator: 'l', version2: $version2, );
version_compare($version1, $version2, /*comment*/ 'g');
version_compare($version1, $version2, <<<EOD
e
EOD
);
version_compare($version1, $version2, "!");
version_compare($version1, $version2, 'n');
