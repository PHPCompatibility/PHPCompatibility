<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Exceptions;

use PHP_CodeSniffer\Exceptions\RuntimeException;

/**
 * Exception thrown when an invalid `testVersion` value is passed.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is only intended for internal use by PHPCompatibility and is not part of the public API.
 * This also means that it has no promise of backward compatibility. Use at your own risk.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 10.0.0
 */
final class InvalidTestVersion extends RuntimeException
{

    /**
     * Create a new invalid `testVersion` exception with a standardized text.
     *
     * @since 10.0.0
     *
     * @param string $testVersion The `testVersion` received.
     *
     * @return \PHPCompatibility\Exceptions\InvalidTestVersion
     */
    public static function create($testVersion)
    {
        return new self(
            \sprintf(
                'Invalid PHPCompatibility testVersion provided: \'%s\'',
                $testVersion
            )
        );
    }
}
