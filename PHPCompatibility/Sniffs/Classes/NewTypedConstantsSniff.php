<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Classes;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Exceptions\ValueError;
use PHPCSUtils\Utils\Constants;
use PHPCSUtils\Utils\TypeString;

/**
 * Detect and verify the use of typed OO constant declarations.
 *
 * Typed class, interface, trait and enum constant declarations are available since PHP 8.3.
 *
 * PHP version 8.3+
 *
 * @link https://www.php.net/manual/en/migration83.new-features.php#migration83.new-features.core.typed-class-constants
 * @link https://wiki.php.net/rfc/typed_class_constants
 *
 * @since 10.0.0
 */
final class NewTypedConstantsSniff extends Sniff
{

    /**
     * Invalid types. These will throw an error.
     *
     * The array lists : the invalid type => what was probably intended/alternative
     * or false if no alternative available.
     *
     * @since 10.0.0
     *
     * @var array<string, string|false>
     */
    protected $invalidTypes = [
        'callable' => false,
        'void'     => false,
        'never'    => false,
    ];

    /**
     * Invalid "long" types which are likely typos. These will throw a warning.
     *
     * The array lists : the invalid type => what was probably intended/alternative
     * or false if no alternative available.
     *
     * @since 10.0.0
     *
     * @var array<string, string|false>
     */
    protected $invalidLongTypes = [
        'boolean' => 'bool',
        'integer' => 'int',
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_CONST];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        try {
            $properties = Constants::getProperties($phpcsFile, $stackPtr);
        } catch (ValueError $e) {
            // Not an OO constant or parse error.
            return;
        }

        if ($properties['type'] === '') {
            // Not a typed constant.
            return;
        }

        $origType = $properties['type'];
        $type     = \ltrim($origType, '?'); // Trim off potential nullability.

        if (ScannedCode::shouldRunOnOrBelow('8.2') === true) {
            $phpcsFile->addError(
                'Typed constants are not supported in PHP 8.2 or earlier. Found: %s',
                $properties['type_token'],
                'Found',
                [$origType]
            );
        } else {
            $types = TypeString::toArray($type); // Returns all types and normalizes PHP native types.

            foreach ($types as $type) {
                if (isset($this->invalidTypes[$type])) {
                    $error = '%s is not supported as a type declaration for constants';
                    $data  = [$type];

                    if ($this->invalidTypes[$type] !== false) {
                        $error .= '. Did you mean %s ?';
                        $data[] = $this->invalidTypes[$type];
                    }

                    $phpcsFile->addError($error, $properties['type_token'], 'InvalidType', $data);
                    continue;
                }

                $type = \strtolower($type);
                if (isset($this->invalidLongTypes[$type])) {
                    $error = '%s is not supported as a type declaration for constants';
                    $data  = [$type];

                    if ($this->invalidLongTypes[$type] !== false) {
                        $error .= '. Did you mean %s ?';
                        $data[] = $this->invalidLongTypes[$type];
                    }

                    $phpcsFile->addWarning($error, $properties['type_token'], 'InvalidLongType', $data);
                }
            }
        }
    }
}
