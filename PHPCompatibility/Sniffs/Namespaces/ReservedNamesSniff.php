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

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\Namespaces;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Detect declarations of namespace names reserved or in use by PHP.
 *
 * > The Namespace name PHP, and compound names starting with this name (like PHP\Classes) are reserved
 * > for internal language use and should not be used in the userspace code.
 *
 * Also includes names actually in use by PHP.
 *
 * PHP version 5.3+
 *
 * @link https://www.php.net/manual/en/language.namespaces.rationale.php
 *
 * @since 10.0.0
 */
class ReservedNamesSniff extends Sniff
{

    /**
     * A list of new reserved namespace names, which prohibits the ability of userland
     * code to use these names.
     *
     * @since 10.0.0
     *
     * @var array<string, string>
     */
    protected $reservedNames = [
        /*
         * The top-level namespace name "PHP" is reserved by PHP since the introduction
         * of namespaces in PHP 5.3, but not yet in use.
         */
        'PHP'    => '5.3',
        'FFI'    => '7.4',
        'Random' => '8.2',
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
        // Handle case-insensitivity of namespace names.
        $this->reservedNames = \array_change_key_case($this->reservedNames, \CASE_LOWER);

        return [
            \T_NAMESPACE,
        ];
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
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('5.3') === false) {
            // Namespaces were introduced in PHP 5.3.
            return;
        }

        $name = Namespaces::getDeclaredName($phpcsFile, $stackPtr);
        if (empty($name)) {
            // Use of namespace operator, global namespace or parse error/live coding.
            return;
        }

        $nameParts = \explode('\\', $name);
        $firstPart = \strtolower($nameParts[0]);
        if (isset($this->reservedNames[$firstPart]) === false) {
            return;
        }

        if (ScannedCode::shouldRunOnOrAbove($this->reservedNames[$firstPart]) === false) {
            return;
        }

        // Throw the message on the first part of the namespace name.
        $firstNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);

        // Special case "PHP" to a warning with a custom message.
        if ($firstPart === 'php') {
            $phpcsFile->addWarning(
                'Namespace name "%s" is discouraged; PHP has reserved the namespace name "PHP" and compound names starting with "PHP" for internal language use.',
                $firstNonEmpty,
                'phpFound',
                [$name]
            );
            return;
        }

        // Throw an error for other reserved names.
        $phpcsFile->addError(
            'The top-level namespace name "%s" is reserved by and in use by PHP since PHP version %s. Found: %s',
            $firstNonEmpty,
            MessageHelper::stringToErrorCode($firstPart, true) . 'Found',
            [
                $nameParts[0],
                $this->reservedNames[$firstPart],
                $name,
            ]
        );
    }
}
