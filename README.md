PHP Compatibility Coding Standard for PHP_CodeSniffer
=====================================================
[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=wimg&url=https://github.com/wimg/PHPCompatibility&title=PHPCompatibility&language=&tags=github&category=software)
[![Build Status](https://travis-ci.org/wimg/PHPCompatibility.png?branch=master)](https://travis-ci.org/wimg/PHPCompatibility)
[![Coverage Status](https://coveralls.io/repos/github/wimg/PHPCompatibility/badge.svg?branch=master)](https://coveralls.io/github/wimg/PHPCompatibility?branch=master)
[![Latest Stable Version](https://poser.pugx.org/wimg/php-compatibility/v/stable.png)](https://packagist.org/packages/wimg/php-compatibility)
[![Latest Unstable Version](https://poser.pugx.org/wimg/php-compatibility/v/unstable.png)](https://packagist.org/packages/wimg/php-compatibility)
[![License](https://poser.pugx.org/wimg/php-compatibility/license.png)](https://packagist.org/packages/wimg/php-compatibility)

This is a set of sniffs for [PHP_CodeSniffer](http://pear.php.net/PHP_CodeSniffer) that checks for PHP version compatibility.
It will allow you to analyse your code for compatibility with higher and lower versions of PHP. 


PHP Version Support
-------

The project aims to cover all PHP compatibility changes introduced since PHP 5.0 up to the latest PHP release.  This is an ongoing process and coverage is not yet 100% (if, indeed, it ever could be).  Progress is tracked on [our Github issue tracker](https://github.com/wimg/PHPCompatibility/issues).

Pull requests that check for compatibility issues in PHP4 code - in particular between PHP 4 and PHP 5.0 - are very welcome as there are still situations where people need help upgrading legacy systems. However, coverage for changes introduced before PHP 5.1 will remain patchy as sniffs for this are not actively being developed at this time.

The sniffs are designed to give the same results regardless of which PHP version you are using to run CodeSniffer.  Therefore you should get consistent results independently of the PHP version used in your test environment.

Thank you
---------
Thanks to all contributors for their valuable contributions.

[![WPEngine](https://cu.be/img/wpengine.png)](https://wpengine.com)

Thanks to [WP Engine](https://wpengine.com) for their support on the PHP 7.0 sniffs.


Installation (method 1)
-----------------------

* Install [PHP_CodeSniffer](http://pear.php.net/PHP_CodeSniffer) with `pear install PHP_CodeSniffer` (PHP_CodeSniffer 1.5.1 is required for 90% of the sniffs, 2.6 or later is required for full support, notices may be thrown on older versions).
* Checkout the latest release from https://github.com/wimg/PHPCompatibility/releases into the `PHP/CodeSniffer/Standards/PHPCompatibility` directory.


Installation in Composer project (method 2)
-------------------------------------------

* Add the following lines to the `require-dev` section of your composer.json file.

```json
"require-dev": {
   "squizlabs/php_codesniffer": "*",
   "wimg/php-compatibility": "*",
   "simplyadmire/composer-plugins" : "@dev",
   "prefer-stable" : true
},

```
* Run `composer update --lock` to install both phpcs and PHPCompatibility coding standard.
* Use the coding standard with `./vendor/bin/phpcs --standard=PHPCompatibility`


Using the compatibility sniffs
------------------------------
* Use the coding standard with `phpcs --standard=PHPCompatibility`
* You can specify which PHP version you want to test against by specifying `--runtime-set testVersion 5.5`.
* You can also specify a range of PHP versions that your code needs to support.  In this situation, compatibility issues that affect any of the PHP versions in that range will be reported:
`--runtime-set testVersion 5.3-5.5`

More information can be found on Wim Godden's [blog](http://techblog.wimgodden.be/tag/codesniffer).

Using a custom ruleset
------------------------------
Alternatively, you can add PHPCompatibility to a custom PHPCS ruleset.

```xml
<?xml version="1.0"?>
<ruleset name="Custom ruleset">
	<description>My rules for PHP_CodeSniffer</description>

	<!-- Run against the PHPCompatibility ruleset -->
	<rule ref="PHPCompatibility" />
	
	<!-- Run against a second ruleset -->
	<rule ref="PSR2" />

</ruleset>
```

You can also set the `testVersion` from within the ruleset:
```xml
	<config name="testVersion" value="5.3-5.5"/>
```

Other advanced options, such as changing the message type or severity of select sniffs, as described in the [PHPCS Annotated ruleset](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Annotated-ruleset.xml) wiki page are, of course, also supported.


#### PHPCompatibility specific options

At this moment, there is one sniff which has a property which can be set via the ruleset. More custom properties may become available in the future.

The `PHPCompatibility.PHP.RemovedExtensions` sniff checks for removed extensions based on the function prefix used for these extensions.
This might clash with userland functions using the same function prefix.

To whitelist userland functions, you can pass a comma-delimited list of function names to the sniff.
```xml
	<!-- Whitelist the mysql_to_rfc3339() and mysql_another_function() functions. -->
	<rule ref="PHPCompatibility.PHP.RemovedExtensions">
		<properties>
			<property name="functionWhitelist" type="array" value="mysql_to_rfc3339,mysql_another_function" />
		</properties>
	</rule>
```


Running the Sniff Tests
-----------------------
All the sniffs are fully tested with PHPUnit tests. In order to run the tests
on the sniffs, the following installation steps are required.

1. Install the master branch of `PHP_CodeSniffer`
   [https://github.com/squizlabs/PHP_CodeSniffer.git].

   This can be done with composer by adding the following into
   `~/.composer/composer.json`:

        {
            "require": {
                "phpunit/phpunit": "3.7.*",
                "squizlabs/php_codesniffer": ">=2.0"
            }
        }

2. Run the following command to compose in the versions indicated in the above
   global composer.json file:

        $ composer.phar global install

3. Update your system `$PATH` to include the globally composed files:

        $ export PATH=~/.composer/vendor/bin:$PATH

4. Be sure that the `PHPCompatibility` directory is symlinked into
   `PHP_Codesniffer`'s standards directory:

        $ ln -s /path/to/PHPCompatibility ~/.composer/vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/PHPCompatibility

5. Verify standard is available with `phpcs -i`. The output should include
   `PHPCompatibility`

6. Run the tests by running `phpunit` in the root directory of
   PHPCompatibility. It will read the `phpunit.xml` file and execute the tests


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

License
-------
This code is released under the GNU Lesser General Public License (LGPL). For more information, visit http://www.gnu.org/copyleft/lesser.html
