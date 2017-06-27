Hi, thank you for your interest in contributing to PHPCompatibility! We look forward to working with you.

Reporting bugs
--------------

Before reporting a bug, you should check what sniff an error is coming from.
Running `phpcs` with the `-s` flag will show the names of the sniffs with each error.

Bug reports containing a minimal code sample which can be used to reproduce the issue are highly appreciated as those are most easily actionable.

Requesting features
-------------------

The PHPCompatibility standard only concerns itself with cross-version PHP compatibility of code.

When requesting a new feature, please add a link to a relevant page in the PHP Manual / PHP Changelog / PHP RFC website which illustrates the feature you are requesting.

Pull requests
-------------

Contributions in the form of pull requests are very welcome.

To start contributing, fork the repository, create a new branch in your fork, make your intended changes and pull the branch against the `master` branch of this repository.

Please make sure that your pull request contains unit tests covering what's being addressed by it.

All code should be compatible with PHPCS 1.5.6 and PHPCS 2.x.
All code should be compatible with PHP 5.1 to PHP nightly.
All code should comply with the PHPCompatibility coding standards. The ruleset used by PHPCompatibility is largely based on PSR2 with minor variations and some additional checks for documentation and such.


Running the Sniff Tests
-----------------------
All the sniffs are fully tested with PHPUnit tests. In order to run the tests
on the sniffs, the following installation steps are required.

1. Install the latest release from the `2.x` branch of [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer.git).

    This can be done with composer using the following command:

        $ composer require "squizlabs/php_codesniffer=^2.0"

    or by adding the following into `~/.composer/composer.json`:
    ```json
        {
            "require": {
                "phpunit/phpunit": ">=4.0",
                "squizlabs/php_codesniffer": "^2.0"
            }
        }
    ```

2. Run the following command to compose in the versions indicated in the above
   global composer.json file:

        $ composer global install

3. Update your system `$PATH` to include the globally composed files:

        $ export PATH=~/.composer/vendor/bin:$PATH

4. Be sure that the `PHPCompatibility` directory is symlinked into
   `PHP_Codesniffer`'s standards directory:

        $ ln -s /path/to/PHPCompatibility ~/.composer/vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/PHPCompatibility

5. Verify the standard is available with `phpcs -i`. The output should include `PHPCompatibility`.

6. Run the tests by running `phpunit` in the root directory of PHPCompatibility.
   It will read the `phpunit.xml` file and execute the tests.


#### Issues when running the PHPCS Unit tests for another standard

This sniff library uses its own PHPUnit setup rather than the PHPCS native unit testing framework to allow for testing the sniffs with various config settings for the `testVersion` variable.

If you are running the PHPCS native unit tests or the unit tests for another sniff library which uses the PHPCS native unit testing framework, PHPUnit might throw errors related to this sniff library depending on your setup.

This will generally only happen if you have both PHPCompatibility as well as another custom sniff library in your PHPCS `installed_paths` setting.

To fix these errors, make sure you are running PHPCS 2.7.1 or higher and add the following to the `phpunit.xml` file for the sniff library you are testing:
```xml
	<php>
		<env name="PHPCS_IGNORE_TESTS" value="PHPCompatibility"/>
	</php>
```

This will prevent PHPCS trying to include the PHPCompatibility unit tests when creating the test suite.
