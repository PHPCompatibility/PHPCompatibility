# PHP Compatibility Coding Standard for PHP_CodeSniffer

<div aria-hidden="true">

[![Latest Stable Version](https://img.shields.io/packagist/v/phpcompatibility/php-compatibility?label=stable)](https://packagist.org/packages/phpcompatibility/php-compatibility)
[![Latest Unstable Version](https://img.shields.io/badge/unstable-dev--develop-e68718.svg?maxAge=2419200)](https://packagist.org/packages/phpcompatibility/php-compatibility#dev-develop)
![Awesome](https://img.shields.io/badge/awesome%3F-yes!-brightgreen.svg)
[![License](https://img.shields.io/github/license/PHPCompatibility/PHPCompatibility?color=00a7a7)](https://github.com/PHPCompatibility/PHPCompatibility/blob/master/LICENSE)

[![CS Build Status](https://github.com/PHPCompatibility/PHPCompatibility/actions/workflows/basics.yml/badge.svg?branch=develop)](https://github.com/PHPCompatibility/PHPCompatibility/actions/workflows/basics.yml)
[![Test Build Status](https://github.com/PHPCompatibility/PHPCompatibility/actions/workflows/test.yml/badge.svg?branch=develop)](https://github.com/PHPCompatibility/PHPCompatibility/actions/workflows/test.yml)
[![Coverage Status](https://coveralls.io/repos/github/PHPCompatibility/PHPCompatibility/badge.svg?branch=develop)](https://coveralls.io/github/PHPCompatibility/PHPCompatibility?branch=develop)

[![Minimum PHP Version](https://img.shields.io/packagist/php-v/phpcompatibility/php-compatibility.svg?maxAge=3600)](https://packagist.org/packages/phpcompatibility/php-compatibility)
[![Tested on PHP 5.4 to nightly](https://img.shields.io/badge/tested%20on-PHP%205.4%20|%205.5%20|%205.6%20|%207.0%20|%207.1%20|%207.2%20|%207.3%20|%207.4%20|%208.0%20|%208.1%20|%208.2%20|%208.3%20|%208.4%20|%208.5%20|%20nightly%20-brightgreen.svg?maxAge=2419200)](https://github.com/PHPCompatibility/PHPCompatibility/actions/workflows/test.yml)

</div>

This is a set of sniffs for [PHP_CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer) that checks for PHP cross-version compatibility.
It will allow you to analyse your code for compatibility with higher and lower versions of PHP.

* [PHP Version Support](#php-version-support)
* [Funding](#funding)
* [Requirements](#requirements)
* [Installation](#installation)
    - [Composer Project-based Installation](#composer-project-based-installation)
    - [Composer Global Installation](#composer-global-installation)
    - [Updating your PHPCompatibility install to a newer version](#updating-your-phpcompatibility-install-to-a-newer-version)
    - [Using your PHPCompatibility install](#using-your-phpcompatibility-install)
* [Sniffing your code for compatibility with specific PHP version(s)](#sniffing-your-code-for-compatibility-with-specific-php-versions)
    - [Using a framework/CMS/polyfill specific ruleset](#using-a-frameworkcmspolyfill-specific-ruleset)
* [Using a custom ruleset](#using-a-custom-ruleset)
    - [`testVersion` in the ruleset versus command-line](#testversion-in-the-ruleset-versus-command-line)
    - [PHPCompatibility specific options](#phpcompatibility-specific-options)
* [Projects extending PHPCompatibility](#projects-extending-phpcompatibility)
* [Contributing](#contributing)
* [License](#license)

## PHP Version Support

The project aims to cover all PHP compatibility changes introduced since PHP 5.0 up to the latest PHP release. This is an ongoing process and coverage is not yet 100% (if, indeed, it ever could be). Progress is tracked on [our GitHub issue tracker](https://github.com/PHPCompatibility/PHPCompatibility/issues).

Pull requests that check for compatibility issues in PHP 4 code - in particular between PHP 4 and PHP 5.0 - are welcome as there are still situations where people need help upgrading legacy systems. However, coverage for changes introduced before PHP 5.1 will remain patchy as sniffs for this are not actively being developed at this time.


## Funding

**This project needs funding.**

The project team has spend thousands of hours creating and maintaining this package. This is unsustainable without funding.

If you use PHPCompatibility, please fund this work by donating to the [PHP_CodeSniffer Open Collective](https://opencollective.com/php_codesniffer).


## Requirements

* PHP 5.4+
* PHP_CodeSniffer: 3.13.3+ / 4.0.0+.
* PHPCSUtils: 1.1.2+

The sniffs are designed to give the same results regardless of which PHP version you are using to run PHP_CodeSniffer. You should get consistent results independently of the PHP version used in your test environment, though for the best results it is recommended to run the sniffs on a recent PHP version in combination with a recent PHP_CodeSniffer version.

As of version 8.0.0, the PHPCompatibility standard can also be used with PHP_CodeSniffer 3.x.  
As of version 9.0.0, support for PHP_CodeSniffer 1.5.x and low 2.x versions < 2.3.0 has been dropped.  
As of version 10.0.0, support for PHP < 5.4 and PHP_CodeSniffer < 3.13.3 has been dropped and support for PHP_CodeSniffer 4.x has been added.


## Installation

As of PHPCompatibility 10.0.0, installation via [Composer](https://getcomposer.org/) using the below instructions is the only supported type of installation.

Composer will automatically install the project dependencies and register the PHPCompatibility ruleset with PHP_CodeSniffer using the [Composer PHPCS plugin](https://github.com/PHPCSStandards/composer-installer).

> If you are upgrading from an older PHPCompatibility version to version 10.0.0, please read the [Upgrade guide](https://github.com/PHPCompatibility/PHPCompatibility/wiki/Upgrading-to-PHPCompatibility-10.0) first!

### Composer Project-based Installation

Run the following from the root of your project:
```bash
composer config allow-plugins.dealerdirect/phpcodesniffer-composer-installer true
composer require --dev phpcompatibility/php-compatibility:"^10.0.0@dev"
```

### Composer Global Installation

Alternatively, you may want to install this standard globally:
```bash
composer global config allow-plugins.dealerdirect/phpcodesniffer-composer-installer true
composer global require --dev phpcompatibility/php-compatibility:"^10.0.0@dev"
```

### Updating your PHPCompatibility install to a newer version

If you installed PHPCompatibility using either of the above commands, you can upgrade to a newer version as follows:
```bash
# Project local install
composer update phpcompatibility/php-compatibility --with-dependencies

# Global install
composer global update phpcompatibility/php-compatibility --with-dependencies
```

### Using your PHPCompatibility install

You can verify that the PHPCompatibility standard is registered correctly by running `vendor/bin/phpcs -i` on the command line.
PHPCompatibility and PHPCSUtils should both be listed as available standards.

Once you have installed PHPCompatibility using either of the above commands, use it as follows:
```bash
# Project local install
vendor/bin/phpcs -ps . --standard=PHPCompatibility

# Global install
%USER_DIRECTORY%/Composer/vendor/bin/phpcs -ps . --standard=PHPCompatibility
```

> **Pro-tip**: For the convenience of using `phpcs` as a global command, use the _Global install_ method and add the path to the `%USER_DIRECTORY%/Composer/vendor/bin` directory to the `PATH` environment variable for your operating system.


## Sniffing your code for compatibility with specific PHP version(s)

* Run the coding standard from the command-line with `phpcs -p . --standard=PHPCompatibility`.
* By default, you will only receive notifications about deprecated and/or removed PHP features.
* To get the most out of the PHPCompatibility standard, you should specify a `testVersion` to check against. That will enable the checks for both deprecated/removed PHP features as well as the detection of code using new PHP features.
    - You can run the checks for just one specific PHP version by adding `--runtime-set testVersion 5.5` to your command line command.
    - You can also specify a range of PHP versions that your code needs to support. In this situation, compatibility issues that affect any of the PHP versions in that range will be reported: `--runtime-set testVersion 5.3-5.5`.
    - As of PHPCompatibility 7.1.3, you can omit one part of the range if you want to support everything above or below a particular version, i.e. use `--runtime-set testVersion 7.0-` to run all the checks for PHP 7.0 and above.
* By default the report will be sent to the console, if you want to save the report to a file, add the following to the command line command: `--report-full=path/to/report-file`.
    For more information and other reporting options, check the [PHP_CodeSniffer wiki](https://github.com/PHPCSStandards/PHP_CodeSniffer/wiki/Reporting).


### Using a framework/CMS/polyfill specific ruleset

As of mid 2018, a limited set of framework/CMS specific rulesets is available. These rulesets are hosted in their own repositories.
* `PHPCompatibilityJoomla` [GitHub](https://github.com/PHPCompatibility/PHPCompatibilityJoomla) | [Packagist](https://packagist.org/packages/phpcompatibility/phpcompatibility-joomla)
* `PHPCompatibilityWP` [GitHub](https://github.com/PHPCompatibility/PHPCompatibilityWP) | [Packagist](https://packagist.org/packages/phpcompatibility/phpcompatibility-wp)

Since the autumn of 2018, there are also a number of PHP polyfill specific rulesets available:
* `PHPCompatibilityPasswordCompat` [GitHub](https://github.com/PHPCompatibility/PHPCompatibilityPasswordCompat) | [Packagist](https://packagist.org/packages/phpcompatibility/phpcompatibility-passwordcompat): accounts for @ircmaxell's [`password_compat`](https://github.com/ircmaxell/password_compat) polyfill library.
* `PHPCompatibilityParagonie` [GitHub](https://github.com/PHPCompatibility/PHPCompatibilityParagonie) | [Packagist](https://packagist.org/packages/phpcompatibility/phpcompatibility-paragonie): contains two rulesets which account for the Paragonie [`random_compat`](https://github.com/paragonie/random_compat) and [`sodium_compat`](https://github.com/paragonie/sodium_compat) polyfill libraries respectively.
* `PHPCompatibilitySymfony` [GitHub](https://github.com/PHPCompatibility/PHPCompatibilitySymfony) | [Packagist](https://packagist.org/packages/phpcompatibility/phpcompatibility-symfony): contains a number of rulesets which account for various PHP polyfill libraries offered by the Symfony project. For more details about the available rulesets, please check out the [README of the PHPCompatibilitySymfony](https://github.com/PHPCompatibility/PHPCompatibilitySymfony/blob/master/README.md) repository.

If you want to make sure you have all PHPCompatibility rulesets available at any time, you can use the `PHPCompatibilityAll` package [GitHub](https://github.com/PHPCompatibility/PHPCompatibilityAll) | [Packagist](https://packagist.org/packages/phpcompatibility/phpcompatibility-all).

**IMPORTANT:** Framework/CMS/Polyfill specific rulesets do not set the minimum PHP version for your project, so you will still need to pass a `testVersion` to get the most accurate results.


## Using a custom ruleset

Like with any PHP_CodeSniffer standard, you can add PHPCompatibility to a custom PHP_CodeSniffer ruleset.

```xml
<?xml version="1.0"?>
<ruleset name="Custom ruleset">
    <description>My rules for PHP_CodeSniffer</description>

    <!-- Run against the PHPCompatibility ruleset -->
    <rule ref="PHPCompatibility"/>

    <!-- Run against a second ruleset -->
    <rule ref="PSR2"/>

</ruleset>
```

You can also set the `testVersion` from within the ruleset:
```xml
    <!-- Check for cross-version support for PHP 7.2 and higher. -->
    <config name="testVersion" value="7.2-"/>
```

Other advanced options, such as changing the message type or severity of select sniffs, as described in the [PHPCS Annotated ruleset](https://github.com/PHPCSStandards/PHP_CodeSniffer/wiki/Annotated-ruleset.xml) wiki page are, of course, also supported.

### `testVersion` in the ruleset versus command-line

Starting with PHP_CodeSniffer 3.3.0, a `testVersion` set via the command-line will overrule the `testVersion` in the ruleset.

This allows for more flexibility when, for instance, your project needs to comply with PHP `5.5-`, but you have a bootstrap file which needs to be compatible with PHP `5.2-`.

Additionally, as of PHP_CodeSniffer 4.0.0, a `testVersion` set in an included ruleset can now be overruled from your project (root) ruleset.


### PHPCompatibility specific options

At this moment, there are two sniffs which have a property which can be set via the ruleset. More custom properties may become available in the future.

The `PHPCompatibility.Extensions.RemovedExtensions` sniff checks for removed extensions based on the function prefix used for these extensions.
This might clash with userland functions using the same function prefix.

To whitelist userland functions, you can pass a comma-delimited list of function names to the sniff.
```xml
    <!-- Whitelist the mysql_to_rfc3339() and mysql_another_function() functions. -->
    <rule ref="PHPCompatibility.Extensions.RemovedExtensions">
        <properties>
            <property name="functionWhitelist" type="array">
                <element value="mysql_to_rfc3339"/>
                <element value="mysql_another_function"/>
            </property>
        </properties>
    </rule>
```

The `PHPCompatibility.Interfaces.RemovedSerializable` sniff needs to know about all interfaces which extend the `Serializable` interface to provide the most reliable results.
The sniff will warn when it encounters an interface extending the `Serializable` interface which is unknown to the sniff and recommend for the interface name to be added to the property.

To inform the sniff about additional interfaces providing the Serializable interface, add a snippet along the lines of the below to your custom ruleset:
```xml
    <rule ref="PHPCompatibility.Interfaces.RemovedSerializable">
        <properties>
            <property name="serializableInterfaces" type="array">
                <element value="MyCustomSerializableInterface"/>
                <element value="AnotherSerializableInterface"/>
            </property>
        </properties>
    </rule>
```

## Projects extending PHPCompatibility

There are hundreds of public projects using PHPCompatibility or extending on top of it. A short list of some that you might know or have a look at :
* [adamculp/php-code-quality](https://github.com/adamculp/php-code-quality) - a Docker image doing a lot of code quality checks
* PHPCompatibility Checker WordPress plugin : [Wordpress site](https://wordpress.org/plugins/php-compatibility-checker/) and [Github](https://github.com/wpengine/phpcompat/)
* [WordPress Tide project](https://wptide.org/)
* [PHPStorm has built-in support for PHPCompatibility](https://www.jetbrains.com/help/phpstorm/using-php-code-sniffer.html#788c81b6)
* [Moodle codechecker](https://github.com/moodlehq/moodle-local_codechecker) - A [plugin](https://moodle.org/plugins/local_codechecker) for Moodle [coding style](https://docs.moodle.org/dev/Coding_style), including PHPCompatibility.
* [Github Action](https://github.com/marketplace/actions/php-compatibility) - A Github Action that runs this PHPCS standard on your source code.

## Contributing

Contributions are very welcome. Please read the [CONTRIBUTING](.github/CONTRIBUTING.md) documentation to get started.

## License

This code is released under the GNU Lesser General Public License (LGPL). For more information, visit <http://www.gnu.org/copyleft/lesser.html>
