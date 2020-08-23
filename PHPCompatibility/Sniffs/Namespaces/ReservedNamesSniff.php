<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Namespaces;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHPCSUtils\Utils\Namespaces;

/**
 * The namespace name PHP is reserved by PHP.
 *
 * > The Namespace name PHP, and compound names starting with this name (like PHP\Classes) are reserved
 * > for internal language use and should not be used in the userspace code.
 *
 * PHP version 5.3
 *
 * @link https://www.php.net/manual/en/language.namespaces.rationale.php
 *
 * @since 10.0.0
 */
class ReservedNamesSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array
     */
    public function register()
    {
        return [
            \T_NAMESPACE,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('5.3') === false) {
            // Namespaces were introduced in PHP 5.3.
            return;
        }

        $name = Namespaces::getDeclaredName($phpcsFile, $stackPtr);
        if (empty($name)) {
            // Use of namespace operator, global namespace or parse error/live coding.
            return;
        }

        $nameParts = explode('\\', $name);
        $firstPart = strtolower($nameParts[0]);
        if ($firstPart !== 'php') {
            return;
        }

        $phpcsFile->addWarning(
            'Namespace name "%s" is discouraged; PHP has reserved the namespace name "PHP" and compound names starting with "PHP" for internal language use.',
            $stackPtr,
            'Found',
            [$name]
        );
    }
}
