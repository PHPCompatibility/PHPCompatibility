PHP Compatibility Coding Standard for PHP_CodeSniffer
=====================================================
[![Latest Stable Version](https://poser.pugx.org/wimg/php-compatibility/v/stable.png)](https://packagist.org/packages/wimg/php-compatibility)
[![Latest Unstable Version](https://poser.pugx.org/wimg/php-compatibility/v/unstable.png)](https://packagist.org/packages/wimg/php-compatibility)
![Awesome](https://img.shields.io/badge/awesome%3F-yes!-brightgreen.svg)
[![License](https://poser.pugx.org/wimg/php-compatibility/license.png)](https://github.com/wimg/PHPCompatibility/blob/master/LICENSE)
[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=wimg&url=https://github.com/wimg/PHPCompatibility&title=PHPCompatibility&language=&tags=github&category=software)

[![Build Status](https://travis-ci.org/wimg/PHPCompatibility.png?branch=master)](https://travis-ci.org/wimg/PHPCompatibility)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wimg/PHPCompatibility/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wimg/PHPCompatibility/)
[![Coverage Status](https://coveralls.io/repos/github/wimg/PHPCompatibility/badge.svg?branch=master)](https://coveralls.io/github/wimg/PHPCompatibility?branch=master)
[![Dependency Status](https://www.versioneye.com/php/wimg:php-compatibility/badge)](https://www.versioneye.com/php/wimg:php-compatibility)

[![Tested Runtime Badge](http://php-eye.com/badge/wimg/php-compatibility/tested.svg?branch=dev-master)](http://php-eye.com/package/wimg/php-compatibility)
[![Not Tested Runtime Badge](http://php-eye.com/badge/wimg/php-compatibility/not-tested.svg?branch=dev-master)](http://php-eye.com/package/wimg/php-compatibility)


This is a set of sniffs for [PHP_CodeSniffer](http://pear.php.net/PHP_CodeSniffer) that checks for PHP version compatibility.
It will allow you to analyse your code for compatibility with higher and lower versions of PHP. 


PHP Version Support
-------

The project aims to cover all PHP compatibility changes introduced since PHP 5.0 up to the latest PHP release.  This is an ongoing process and coverage is not yet 100% (if, indeed, it ever could be).  Progress is tracked on [our Github issue tracker](https://github.com/wimg/PHPCompatibility/issues).

Pull requests that check for compatibility issues in PHP4 code - in particular between PHP 4 and PHP 5.0 - are very welcome as there are still situations where people need help upgrading legacy systems. However, coverage for changes introduced before PHP 5.1 will remain patchy as sniffs for this are not actively being developed at this time.

Requirements
-------

The sniffs are designed to give the same results regardless of which PHP version you are using to run CodeSniffer. You should get reasonably consistent results independently of the PHP version used in your test environment, though for the best results it is recommended to run the sniffs on PHP 5.4 or higher.

PHP CodeSniffer 1.5.1 is required for 90% of the sniffs, PHPCS 2.6 or later is required for full support, notices may be thrown on older versions.

**_The PHPCompatibility standard is currently not compatible with PHPCS 3.0, though the [intention is to fix this](https://github.com/wimg/PHPCompatibility/issues/367) in the near future._**

Thank you
---------
Thanks to all [contributors](https://github.com/wimg/PHPCompatibility/graphs/contributors) for their valuable contributions.

[![WPEngine](https://cu.be/img/wpengine.png)](https://wpengine.com)

Thanks to [WP Engine](https://wpengine.com) for their support on the PHP 7.0 sniffs.


Installation using PEAR (method 1)
-----------------------

* Install [PHP_CodeSniffer](http://pear.php.net/PHP_CodeSniffer) with `pear install PHP_CodeSniffer-2.9.0`.
* Checkout the latest release from https://github.com/wimg/PHPCompatibility/releases into the `PHP/CodeSniffer/Standards/PHPCompatibility` directory.


Installation in Composer project (method 2)
-------------------------------------------

* Add the following lines to the `require-dev` section of your composer.json file.

```json
"require-dev": {
   "squizlabs/php_codesniffer": "^2.0",
   "wimg/php-compatibility": "*",
   "simplyadmire/composer-plugins" : "@dev"
},
"prefer-stable" : true

```
* Run `composer update --lock` to install both phpcs and PHPCompatibility coding standard.
* Use the coding standard with `./vendor/bin/phpcs --standard=PHPCompatibility`


Installation via a git check-out to an arbitrary directory (method 3)
-----------------------

* Install the latest `2.x` version of [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) via [your preferred method](https://github.com/squizlabs/PHP_CodeSniffer#installation) (Composer, PEAR, Phar file, Git checkout).
* Checkout the latest release from https://github.com/wimg/PHPCompatibility/releases into an arbitrary directory.
* Add the path to the directory **_above_** the directory in which you cloned the PHPCompability repo to the PHPCS configuration using the below command.
   ```bash
   phpcs --config-set installed_paths /path/to/dir/above
   ```
   I.e. if you cloned the `PHPCompatibility` repository to the `/my/custom/standards/PHPCompatibility` directory, you will need to add the `/my/custom/standards` directory to the PHPCS [`installed_paths` configuration variable](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Configuration-Options#setting-the-installed-standard-paths).

   **Pro-tip:** Alternatively, _and only if you use PHPCS version 2.6.0 or higher_, you can tell PHP_CodeSniffer the path to the PHPCompatibility standard by adding the following snippet to your custom ruleset:
   ```xml
   <config name="installed_paths" value="/path/to/dir/above" />
   ```


Using the compatibility sniffs
------------------------------
* Run the coding standard from the command-line with `phpcs --standard=PHPCompatibility`
* You can specify which PHP version you want to test against by specifying `--runtime-set testVersion 5.5`.
* You can also specify a range of PHP versions that your code needs to support. In this situation, compatibility issues that affect any of the PHP versions in that range will be reported: `--runtime-set testVersion 5.3-5.5`.
    You can omit one or other part of the range if you want to support everything above/below a particular version, i.e. `--runtime-set testVersion 7.0-` to support PHP 7 and above.

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


License
-------
This code is released under the GNU Lesser General Public License (LGPL). For more information, visit http://www.gnu.org/copyleft/lesser.html
