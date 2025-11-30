# Contributing to PHPCompatibility

Hi, thank you for your interest in contributing to PHPCompatibility! We look forward to working with you.

## Reporting bugs

Before reporting a bug, you should check what sniff an error is coming from.
Running `phpcs` with the `-s` flag will show the name of the sniff with each error.

Bug reports containing a minimal code sample which can be used to reproduce the issue are highly appreciated as those are most easily actionable.

## Requesting features

The PHPCompatibility standard only concerns itself with cross-version PHP compatibility of code.

When requesting a new feature, please add a link to a relevant page in the [PHP Manual](http://php.net/manual/) / PHP Changelog / [PHP RFC website](https://wiki.php.net/rfc) which illustrates the feature you are requesting.

## Pull requests

Contributions in the form of pull requests are very welcome.

To start contributing, fork the repository, create a new branch in your fork, make your intended changes and pull the branch against the `develop` branch of this repository.

Please make sure that your pull request contains unit tests covering what's being addressed by it.

* All new sniffs should be accompanied by an XML documentation file describing the change in PHP.
* All code should be compatible with PHP_CodeSniffer >= 4.0.1 _(checked in CI)_.
* All code should be compatible with PHP 7.2 to PHP nightly _(checked in CI)_.
* Try and avoid code duplication by using the utility functions from [PHPCSUtils] whenever relevant.
* All code should comply with the PHPCompatibility coding standards _(checked in CI)_.
    The [ruleset used by PHPCompatibility][phpdevcs] is largely based on PSR-12 with minor variations and some additional checks for array layout and documentation and such.
* When refering to the PHP manual, like in a sniff class docblock, preferably use language-agnostic short links to the PHP manual as described on the [PHP Manual URL How to page][php-urlhowto].

[PHPCSUtils]:   https://phpcsutils.com/phpdoc/
[php-urlhowto]: https://www.php.net/urlhowto.php
[phpdevcs]:     https://github.com/PHPCSStandards/PHPCSDevCS

### Use descriptive commit messages

All things being detected by PHPCompatibility should be backed up by references to the PHP source code, changelog and/or migration guides.

To be able to backtrace (`git blame`) why detection for something was added to PHPCompatibility, it helps a lot if all commit messages are accompanied by descriptive commit messages containing this information.

Adding this information in the commit message also ensures that this information will still be available if the package would be hosted via another code hosting platform in the future.

A good commit message for a commit to PHPCompatibility would typically look something like this:
```txt
PHP #.# | SniffName: detect something specific

> Quote the PHP changelog entry about the change made in PHP

Describe the change you made in the sniff.

Justify any arbitrary choices you made, like why a new sniff was added instead of updating an existing sniff,
or why some logic was changed in a particular way to account for some specific behaviour in PHP.

Refs (add whichever links are available/relevant):
* [Optional] RFC link.
* [Required] Permalink to the PHP changelog/news entry quoted above.
* [Optional] Link to the migration guide.
* [Required] Link(s) to the PR(s) in the PHP codebase which made change.
* [Required] Link(s) to the final commit(s) which made it into PHP for this change.
* [Optional] Link to a relevant PHP manual page.

Related to ### (PHPCompatibility meta ticket for that PHP version)
```

### Typical sources of information about changes in PHP
* The [PHP RFC wiki](https://wiki.php.net/rfc)
* The [UPGRADING](https://github.com/php/php-src/blob/master/UPGRADING) document of each release
* The [NEWS](https://github.com/php/php-src/blob/master/NEWS) document of each release
* The [Migrating from PHP x.x.x to PHP x.x.x section](https://www.php.net/manual/en/appendices.php) in the manual for each release (once published)
* The [Changelog](https://www.php.net/doc.changelog) in the manual
* The official [PHP manual](https://www.php.net/manual/) in general
* The legacy [PHP 5 manual](https://php-legacy-docs.zend.com/manual/php5/en/index)
* The legacy [PHP 4 manual](https://php-legacy-docs.zend.com/manual/php4/en/index)
* The [PHP source code](https://github.com/php/php-src) in general


## Framework/CMS specific rulesets

Since mid 2018, framework/CMS/polyfill specific rulesets will be accepted to be hosted in separate repositories in the PHPCompatibility organisation. If you are interested in adding a ruleset for a framework/CMS/PHP polyfill library, you can request a repository for it by [opening an issue](https://github.com/PHPCompatibility/PHPCompatibility/issues/new) in this repo.

### Guidelines for framework/CMS specific rulesets

A framework/CMS/polyfill specific ruleset will generally contain `<exclude ...>` directives for backfills/polyfills provided by the framework/CMS/polyfill to prevent false positives.

> A backfill is a function/constant/class (etc) which has been added to PHP in a later version than the minimum supported version of the framework/CMS and for which a function/constant/class of the same name is included in the framework/CMS when a PHP version is detected in which the function/constant/class did not yet exist.

These rulesets will not be actively maintained by the maintainers of PHPCompatibility.

The communities behind these PHP frameworks/CMSes/polyfill libraries are strongly encouraged to maintain these rulesets and pull requests with updates will be accepted gladly.

**Note:**
* It is recommended to include a link to the framework/CMS/polyfill source file where the backfill is declared when sending in a pull request adding a new backfill for one of these rulesets.
* If the backfills provided by different major versions of frameworks/CMSes/polyfill libraries are significantly different, separate rulesets for the relevant major versions of frameworks/CMSes/polyfill libraries will be accepted.
* Framework/CMS specific ruleset should **_not_** contain a `<config name="testVersion" value="..."/>` directive.
    While a framework/CMS/polyfill may have a certain minimum PHP version, projects based on or using the framework/CMS/polyfill might have a different (higher) minimum PHP version.
    As support for overruling a `<config>` directive [is patchy](https://github.com/squizlabs/PHP_CodeSniffer/issues/1821), it should be recommended to set the desired `testVersion` either from the command line or in a project-specific custom ruleset.


## Naming conventions and repository structure

### Regarding sniff names
* Per PHPCS convention, sniff files and class names have the `Sniff` suffix.
* The name of sniffs relating to new PHP features should start with `New`.
* The name of sniffs relating to deprecated or removed PHP features should start with `Removed` - as everything which has been deprecated is slated for removal in a later PHP version -.
* Sniffs in the `ParameterValue` category which relate to a specific function, should have the function name and parameter name in the sniff name, like so: `NewFunctionnameParameternameSniff`.
* All sniffs should be placed in a category which relates to the type of PHP construct the sniff is checking for.
    Most existing categories will be clear cut, however, to prevent confusion, here is some additional information about some closely related categories:
    - `FunctionDeclarations` should be used for sniffs relating to the actual function declaration statement, i.e. `function functionName($param) {`.
    - `FunctionNameRestrictions` can be regarded as a sub-category of `FunctionDeclaration` in so far as that sniffs which check for invalid function names in certain contexts, should be placed here.
    - `FunctionUse` should be used for sniffs inspecting calls to certain PHP functions.
    - `ParameterValues` can be regarded as a sub-category of `FunctionUse` in so far as that sniffs which check for calls to specific PHP functions and subsequently inspect the _value_ of parameters passed to that function call, should be placed here.
    Additionally:
    - The `Miscellaneous` category should be avoided if at all possible and should only be used as a last resort.

### About the unit tests
* Unit test files should be named the same as the sniff, replacing the `Sniff` suffix with `UnitTest`.
* The test case file for the unit tests should be named the same as the unit test file, but should use the `.inc` file extension.
    If several test case files are needed to test a sniff, the convention is to number the files starting with `1`, i.e. `SniffNameUnitTest.1.inc`, `SniffNameUnitTest.2.inc` etc.
* Tests specifically testing the handling of live coding/parse error code which could impact the token stream for any code after that code sample, should be placed in a stand-alone, separate test case file with a `ParseError` + number annotation between the sniff name name and the `UnitTest` suffix.
    This means that for a live coding/parse error test for the `NewLateStaticBinding` sniff, the test case file could be called `NewLateStaticBindingParseError1UnitTest.inc` with the associated test code in a `NewLateStaticBindingParseError1UnitTest.php` file.
* Test case files should be placed in the same directory as the unit test file.


## Writing Sniff Tests

Each sniff has its own remit and should function independently of other sniffs.

With that in mind, sniffs are, and should be, tested in isolation.
The tests for each sniff should only test the functionality of that specific sniff, independently of that the code used to test the sniff may also trigger errors from other sniffs.

Tests for PHPCompatibility sniffs will always consist of sets of two files:
1. A test case file (the `.inc` file) containing code snippets that the sniff will scan for the test.
    Typically, a test case file should/could contain four closely related categories of code snippets:
    1. "Not our target" - code which could confuse the sniff, but is not what the sniff is looking for.
        For example, if a sniff is looking for a function call to a global PHP function, like `count()`, typical "not our target" code snippets would be a function call to a namespaced function with the same name, like `\Foo\count()`, and calls to methods with the same name, like `$foo->count()`.
    2. "OK/valid cross version" - code which the sniff is targeting, but which is not problematic (for the purposes of this particular sniff).
        For example, if a sniff is looking for a new modifier keyword being allowed for OO properties, such as `readonly`, property declarations with unrelated modifiers, like `private`, would fall into this category.
    3. "Undetermined/ignored by the sniff" - code for which PHPCS is not typically suitable to determine whether the code is cross-version compatible or not.
        For example, if a sniff is looking for a particular parameter type being passed to a function call, the sniff can flag this if the parameter is passed as a hard-coded value, like `10` or `false`, but the sniff cannot determine the return type of a function call, or find the type of a class constant declared elsewhere. In that case, the sniff should stay silent.
    4. "The issue" - code which should be flagged by the sniff for having a PHP cross-version compatibility issue.
        Please make sure the code snippets in this category use plenty of variation in whitespace, comment use and otherwise variation in how code can be written.
        Sniffs should not expect to be run on "clean" codebases, so make sure to include code snippets written in the most horrible and most unreadable way you can imagine to make sure the sniff is properly tested.
2. A test class for use with PHPUnit.
    This test class should extend the `PHPCompatibility\Tests\BaseSniffTestCase` class. This base class handles the triggering of PHP_CodeSniffer to run the sniff over the test case file with various `testVersion` settings.
    Typically, the test class will have three (types of) test methods:
    1. `testTheIssueTheSniffShouldFind($line)` with a data provider passing the line numbers in the test case file on which errors/warnings should be found coming from this sniff.
        The `testVersion` used in this test method should be one on which the sniff should throw an error/warning.
        This method tests the snippets from "The issue" category above.
    2. `testNoFalsePositives($line)` with a data provider passing the line number in the test case file on which we explicitly do **not** expect errors/warnings to be found from this sniff.
        This test should normally use the same `testVersion` setting as the `testTheIssueTheSniffShouldFind()` method and verifies that the sniff does not throw false positives using `$this->assertNoViolation($file, $line);` for code in snippet from all categories except "The issue" above.
    3. `testNoViolationsInFileOnValidVersion()` with a `$this->assertNoViolation($file);` assertion to ensure the sniff does not throw false positives on any line in the file, using a `testVersion` on which the issue doesn't exist.

Example:
For a sniff which detects a new feature in PHP 8.5:
* The first two test methods would set a `testVersion` of 8.4, the last PHP version _before_ the feature was introduced.
* The third `testNoViolationsInFileOnValidVersion()` method would set a `testVersion` of 8.5, the version in which the PHP feature was introduced and therefore available.

A good way to find code snippets to (anonymize and) add to the test case file is to run the sniff over a selection of popular PHP packages.
This is also a great way to test your sniff on real life code and find & fix any false positives before the sniff goes live.

### Parse error/live coding tests

PHP_CodeSniffer is often used as a plugin in code editors. In that case, the user may not have finished typing the code yet when PHPCS runs.

As a general rule of thumb, PHPCS sniffs should be written to take the possibility that they will encounter parse errors into account and the sniffs should silently ignore this type of parse error/live coding.

As these type of "live coding" code snippets can confuse/break the token stream which PHP_CodeSniffer creates, thereby breaking any tests placed _after_ the live coding code snippet, "live coding" code snippets should always be placed in their own test case file and the test class should test that the sniff stays silent.

For an example of a typical parse error test file set, see this [test case file](https://github.com/PHPCompatibility/PHPCompatibility/blob/develop/PHPCompatibility/Tests/ControlStructures/ForbiddenSwitchWithMultipleDefaultBlocksParseError1UnitTest.inc) with [this associated test class](https://github.com/PHPCompatibility/PHPCompatibility/blob/develop/PHPCompatibility/Tests/ControlStructures/ForbiddenSwitchWithMultipleDefaultBlocksParseError1UnitTest.php)


### Running the Sniff Tests

All the sniffs are fully tested with PHPUnit tests and have `@group` annotations matching their categorization to allow for running subsets of the unit tests more easily.

In order to run the tests on the sniffs, the following installation steps are required.

1. Install PHP CodeSniffer and PHP Compatibility by following the [installation instructions in the Readme](https://github.com/PHPCompatibility/PHPCompatibility/blob/master/README.md#installating-phpcompatibility).

    Run `composer install --prefer-source` to get access to the unit tests and other development related files.

    **Pro-tip**: If you develop regularly for the PHPCompatibility standard, it may be preferable to use a git clone based install of PHP CodeSniffer to allow you to easily test sniffs with different PHP CodeSniffer versions by switching between tags/branches.

2. If you used Composer, PHPUnit should be installed automatically and you are done.

    Run the tests by running `composer test` (PHP < 8.1) or `composer test10` (PHP 8.1+) in the root directory of PHPCompatibility.
    It will read the appropriate `phpunit.xml.dist` file and execute the tests.

3. Alternatively, you can run the tests using a [PHPUnit PHAR file](https://phpunit.de/getting-started.html), though you would still need to run `composer install` on the project before that will work.

4. To get the unit tests running with a non-Composer-based install, you need to set an environment variable so the PHPCompatibility unit test suite will know where to find PHPCS.

    The most flexible way to do this, is by setting this variable in a custom `phpunit.xml` file.

    1. Copy the existing `phpunit[10].xml.dist` file in the root directory of the PHPCompatibility repository and name it `phpunit.xml`.
    2. Add the following snippet to the new file, replacing the value `/path/to/PHPCS` with the path to the directory in which you installed PHP CodeSniffer on your system:
        ```xml
        <php>
            <env name="PHPCS_DIR" value="/path/to/PHPCS"/>
        </php>
        ```
    3. Run the tests by running `phpunit` from the root directory of your PHPCompatibility install.
       It will automatically read the `phpunit.xml` file and execute the tests.


### Issues when running the PHPCS Unit tests for another standard

This sniff library uses its own PHPUnit setup rather than the PHP CodeSniffer native unit testing framework to allow for testing the sniffs with various settings for the `testVersion` config variable.

If you are running the PHPCS native unit tests or the unit tests for another sniff library which uses the PHPCS native unit testing framework, PHPUnit might throw errors related to this sniff library depending on your setup.

This will generally only happen if you have both PHPCompatibility as well as another custom sniff library in your PHPCS `installed_paths` setting.

To fix these errors, add the following to the `phpunit.xml` file for the sniff library you are testing:
```xml
    <php>
        <env name="PHPCS_IGNORE_TESTS" value="PHPCompatibility"/>
    </php>
```

This will prevent PHPCS trying to include the PHPCompatibility unit tests when creating the test suite.


## Checking Code Style Locally

PHPCompatibility uses the [PHPCSDevCS](https://github.com/PHPCSStandards/PHPCSDevCS) standard for code style.

If you have a Composer based local test environment setup, there are helper scripts available to check the code style.
* `composer checkcs`
* `composer fixcs`

If the PHPCS run exits with errors, fix those and run `composer checkcs` again to verify.
