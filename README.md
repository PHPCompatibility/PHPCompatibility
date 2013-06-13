PHP Compatibility Coding Standard for PHP_CodeSniffer
=====================================================

This is a set of sniffs for [PHP_CodeSniffer](http://pear.php.net/PHP_CodeSniffer) that checks for PHP version compatibility.

Installation
------------

* Install [PHP_CodeSniffer](http://pear.php.net/PHP_CodeSniffer) with `pear install PHP_CodeSniffer` (PHP_CodeSniffer 1.3 or later is required).
  If pear installs PHP_CodeSniffer 1.2.x, use `pear install PHP_CodeSniffer-alpha`
* Checkout this repository as `PHPCompatibility` into the `PHP/CodeSniffer/Standards` directory.
* Use the coding standard with `phpcs --standard=PHPCompatibility`.

More information can be found on Wim Godden's [blog](http://techblog.wimgodden.be/tag/codesniffer).

License
-------
This code is released under the GNU Lesser General Public License (LGPL). For more information, visit http://www.gnu.org/copyleft/lesser.html
