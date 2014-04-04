PHP Compatibility Coding Standard for PHP_CodeSniffer
=====================================================
[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=wimg&url=https://github.com/wimg/PHPCompatibility&title=PHPCompatibility&language=&tags=github&category=software)
[![Build Status](https://travis-ci.org/wimg/PHPCompatibility.png?branch=master)](https://travis-ci.org/wimg/PHPCompatibility)
[![Latest Stable Version](https://poser.pugx.org/wimg/php-compatibility/v/stable.png)](https://packagist.org/packages/wimg/php-compatibility)
[![Latest Unstable Version](https://poser.pugx.org/wimg/php-compatibility/v/unstable.png)](https://packagist.org/packages/wimg/php-compatibility)
[![License](https://poser.pugx.org/wimg/php-compatibility/license.png)](https://packagist.org/packages/wimg/php-compatibility)

This is a set of sniffs for [PHP_CodeSniffer](http://pear.php.net/PHP_CodeSniffer) that checks for PHP version compatibility.

Installation
------------

* Install [PHP_CodeSniffer](http://pear.php.net/PHP_CodeSniffer) with `pear install PHP_CodeSniffer` (PHP_CodeSniffer 1.5.1 or later is required for full support, notices may be thrown on older versions).
* Checkout this repository as `PHPCompatibility` into the `PHP/CodeSniffer/Standards` directory.
* Use the coding standard with `phpcs --standard=PHPCompatibility`
* You can specify which PHP version you want to test against by specifying `--runtime-set testVersion 5.5`.

More information can be found on Wim Godden's [blog](http://techblog.wimgodden.be/tag/codesniffer).

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
                "squizlabs/php_codesniffer": "dev-master"
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


License
-------
This code is released under the GNU Lesser General Public License (LGPL). For more information, visit http://www.gnu.org/copyleft/lesser.html
