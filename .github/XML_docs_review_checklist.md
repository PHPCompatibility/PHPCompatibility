XML Docs review checklist
-----------------

Use the below list as a basic check for PRs submitting XML docs for sniffs.

Add one of the following emoji's to each item to indicate compliance.
:heavy_check_mark: - Complies.
:warning: - Mostly complies, but could be improved.
:x: - Does not comply and needs to be fixed.

* Only space indentation is used.
* Indentation is consistent (4 space based).
* The title matches the sniff name (or is close enough).
* Separate error codes have a separate `<standard>` block with their own code samples.
* The "standard" description explains sufficiently what was changed in PHP.
* The "standard" description uses proper punctuation.
* Code sample descriptions use correct prefixes.
* `<` and `>` are encoded in the code sample descriptions.
* The code sample descriptions are descriptive enough.
* Code sample descriptions use proper punctuation.
* The code samples demonstrate the issue sufficiently.
* The code samples are valid PHP code.
* `<em>` tags are used to highlight in the code samples what the sniff is looking for.
* The line length of the code samples stays within the character limit (48 chars).
* The readability of the code samples is good.

A more detailed description of the requirements for XML docs can be found in the associated issue: https://github.com/PHPCompatibility/PHPCompatibility/issues/1285
