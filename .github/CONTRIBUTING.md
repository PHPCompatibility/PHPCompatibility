Hi, thank you for your interest in contributing to PHPCompatibility! We look forward to working with you.

Reporting bugs
--------------

Before reporting a bug, you should check what sniff an error is coming from.
Running `phpcs` with the `-s` flag will show the name of the sniff with each error.

Bug reports containing a minimal code sample which can be used to reproduce the issue are highly appreciated as those are most easily actionable.

Requesting features
-------------------

The PHPCompatibility standard only concerns itself with cross-version PHP compatibility of code.

When requesting a new feature, please add a link to a relevant page in the [PHP Manual](http://php.net/manual/en/) / PHP Changelog / [PHP RFC website](https://wiki.php.net/rfc) which illustrates the feature you are requesting.

Pull requests
-------------

Contributions in the form of pull requests are very welcome.

To start contributing, fork the repository, create a new branch in your fork, make your intended changes and pull the branch against the `master` branch of this repository.

Please make sure that your pull request contains unit tests covering what's being addressed by it.

* All code should be compatible with PHPCS 1.5.6, PHPCS 2.x and PHPCS 3.x.
* All code should be compatible with PHP 5.3 to PHP nightly.
* All code should comply with the PHPCompatibility coding standards.
    The [ruleset used by PHPCompatibility](https://github.com/PHPCompatibility/PHPCompatibility/blob/master/phpcs.xml.dist) is largely based on PSR-2 with minor variations and some additional checks for documentation and such.

### Framework/CMS specific rulesets

As of PHPCompatibility 8.2.0, framework/CMS specific rulesets will be accepted to be hosted from within this repository.

A framework/CMS specific ruleset will generally contain `<exclude ...>` directives for backfills/polyfills provided by the framework/CMS to prevent false positives.

> A backfill is a function/constant/class (etc) which has been added to PHP in a later version than the minimum supported version of the framework/CMS and for which a function/constant/class of the same name is included in the framework/CMS when a PHP version is detected in which the function/constant/class did not yet exist.

These rulesets will not be actively maintained by the maintainers of PHPCompatibility.

The communities behind these PHP frameworks/CMSes are strongly encouraged to maintain these rulesets and pull requests with updates will be accepted gladly.

**Note:**
* It is recommended to include a link to the framework/CMS source file where the backfill is declared when sending in a pull request adding a new backfill for one of these rulesets.
* If the backfills provided by different major versions of frameworks/CMSes are signficantly different, separate rulesets for the relevant major versions of frameworks/CMSes will be accepted.
* Framework/CMS specific rulesets should always contain the `<autoload>` directive as included in the [PHPCompatibility ruleset](https://github.com/PHPCompatibility/PHPCompatibility/blob/master/PHPCompatibility/ruleset.xml#L5).
* Framework/CMS specific ruleset should **_not_** contain a `<config name="testVersion" value="..."/>` directive.

    While a framework/CMS may have a certain minimum PHP version, projects based on the framework/CMS might have a different (higher) minimum PHP version.
    As support for overruling a `<config>` directive [is patchy](https://github.com/squizlabs/PHP_CodeSniffer/issues/1821), it should be recommended to set the desired `testVersion` either from the command line or in a project-specific custom ruleset.
* When adding a new framework/CMS specific ruleset, please make sure the file is added to the [Travis script](https://github.com/PHPCompatibility/PHPCompatibility/blob/master/.travis.yml#L116) to verify the XML consistency.


Running the Sniff Tests
-----------------------
All the sniffs are fully tested with PHPUnit tests. In order to run the tests on the sniffs, the following installation steps are required.

1. Install PHP CodeSniffer and PHP Compatibility by following the instructions in the Readme for either [installing with Composer](https://github.com/PHPCompatibility/PHPCompatibility/blob/master/README.md#installation-in-a-composer-project-method-1) or via a [Git Checkout to an arbitrary directory](https://github.com/PHPCompatibility/PHPCompatibility/blob/master/README.md#installation-via-a-git-check-out-to-an-arbitrary-directory-method-2).

    If you install using Composer, make sure you run `composer install --prefer-source` to get access to the unit tests and other development related files.

    **Pro-tip**: If you develop regularly for the PHPCompatibility standard, it may be preferable to use a git clone based install of PHP CodeSniffer to allow you to easily test sniffs with different PHP CodeSniffer versions by switching between tags.

2. If you used Composer, PHPUnit should be installed automatically and you are done.

    Run the tests by running `phpunit` in the root directory of PHPCompatibility.
    It will read the `phpunit.xml.dist` file and execute the tests.

3. If you used any of the other installation methods and don't have PHPUnit installed on your system yet, download and [install PHPUnit](https://phpunit.de/getting-started.html).

4. To get the unit tests running with a non-Composer-based install, you need to set an environment variable so the PHPCompatibility unit test suite will know where to find PHPCS.

    The most flexible way to do this, is by setting this variable in a custom `phpunit.xml` file.
    
    1. Copy the existing `phpunit.xml.dist` file in the root directory of the PHPCompatibility repository and name it `phpunit.xml`.
    2. Add the following snippet to the new file, replacing the value `/path/to/PHPCS` with the path to the directory in which you installed PHP CodeSniffer on your system:
    ```xml
    <php>
        <env name="PHPCS_DIR" value="/path/to/PHPCS"/>
    </php>
    ```
    3. Run the tests by running `phpunit` from the root directory of your PHPCompatibility install.
       It will automatically read the `phpunit.xml` file and execute the tests.


#### Issues when running the PHPCS Unit tests for another standard

This sniff library uses its own PHPUnit setup rather than the PHP CodeSniffer native unit testing framework to allow for testing the sniffs with various settings for the `testVersion` config variable.

If you are running the PHPCS native unit tests or the unit tests for another sniff library which uses the PHPCS native unit testing framework, PHPUnit might throw errors related to this sniff library depending on your setup.

This will generally only happen if you have both PHPCompatibility as well as another custom sniff library in your PHPCS `installed_paths` setting.

To fix these errors, make sure you are running PHPCS 2.7.1 or higher and add the following to the `phpunit.xml` file for the sniff library you are testing:
```xml
    <php>
        <env name="PHPCS_IGNORE_TESTS" value="PHPCompatibility"/>
    </php>
```

This will prevent PHPCS trying to include the PHPCompatibility unit tests when creating the test suite.
