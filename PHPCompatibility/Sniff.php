<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility;

use PHP_CodeSniffer\Sniffs\Sniff as PHPCS_Sniff;

/**
 * Base class from which all PHPCompatibility sniffs extend.
 *
 * @since 5.6
 */
abstract class Sniff implements PHPCS_Sniff
{

}
