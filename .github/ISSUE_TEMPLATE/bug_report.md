---
name: "\U0001F41B Bug report for sniffs"
about: I got unexpected behavior and think it is a bug.

---

<!--
PLEASE FILL OUT THE TEMPLATE COMPLETELY.
BUG REPORTS WHICH CANNOT BE REPRODUCED BASED ON THE INFORMATION PROVIDED WILL BE CLOSED.
-->

## Bug Description
<!-- Provide a clear and concise description of the problem you are experiencing. -->


## Given the following reproduction Scenario
<!-- Please provide example code that allows us to reproduce the issue. Do NOT paste screenshots of code! -->

The issue happens when running this command:
<!-- Adjust the below command as appropriate. Please include the testVersion setting you are using! -->
```bash
phpcs -ps file.php --standard=PHPCompatibility --runtime-set testVersion #.#-
```

... over a file containing this code:
```php
// Place your code sample here.
```

<!-- Optionally post a *minimal* version of your custom ruleset here if needed to reproduce the issue. -->
... with this custom ruleset:
```xml
<?xml version="1.0"?>
<ruleset name="My Custom Standard">
  ...
</ruleset>
```


### I'd expect the following behaviour
<!-- What was the expected (correct) behavior? -->


### Instead this happened
<!--
What is the current (buggy) behavior?
Please provide as much information as possible and relevant.

Whenever possible, include the error message and the error code for the sniff that is being
(or should have been) triggered.
You can see the sniff error codes by running `phpcs` with the `-s` flag.
Example: `PHPCompatibility.Interfaces.RemovedSerializable.Deprecated`
-->


## Environment
<!-- Please include as many details as relevant about the environment you experienced the bug in. -->

| Environment              | Answer
| ------------------------ | -------
| PHP version              | x.y.z
| PHP_CodeSniffer version  | x.y.z
| PHPCompatibility version | x.y.z
| Install type             | e.g. Composer global, Composer project local, git clone, other (please expand)


## Additional Context (optional)
<!-- Add any other context about the problem here. -->


## Tested Against `develop` branch?
- [ ] I have verified the issue still exists in the `develop` branch of PHPCompatibility.
