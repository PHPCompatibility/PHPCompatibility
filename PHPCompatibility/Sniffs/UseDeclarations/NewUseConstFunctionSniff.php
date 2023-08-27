<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\UseDeclarations;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\UseStatements;

/**
 * Detect importing constants and functions via a `use` statement.
 *
 * The `use` operator has been extended to support importing functions and
 * constants in addition to classes. This is achieved via the `use function`
 * and `use const` constructs, respectively.
 *
 * PHP version 5.6
 *
 * @link https://www.php.net/manual/en/migration56.new-features.php#migration56.new-features.use
 * @link https://wiki.php.net/rfc/use_function
 * @link https://www.php.net/manual/en/language.namespaces.importing.php
 *
 * @since 7.1.4
 */
class NewUseConstFunctionSniff extends Sniff
{

    /**
     * A list of keywords that can follow use statements.
     *
     * @since 7.1.4
     *
     * @var array<string, bool>
     */
    protected $validUseNames = [
        'const'    => true,
        'function' => true,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.1.4
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_USE];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.1.4
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('5.5') === false) {
            return;
        }

        if (UseStatements::isImportUse($phpcsFile, $stackPtr) === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Note: $nextNonEmpty will never be `false` as otherwise `isImportUse()` would have returned `false`.
        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if (isset($this->validUseNames[\strtolower($tokens[$nextNonEmpty]['content'])]) === false) {
            // Not a `use const` or `use function` statement.
            return;
        }

        // `use const` and `use function` have to be followed by the function/constant name.
        $functionOrConstName = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonEmpty + 1), null, true);
        if ($functionOrConstName === false
            || ($tokens[$functionOrConstName]['code'] === \T_AS
            || $tokens[$functionOrConstName]['code'] === \T_COMMA)
        ) {
            // Live coding or incorrect use of reserved keyword, but that is
            // covered by the ForbiddenNames sniff.
            return;
        }

        // Still here ? In that case we have encountered a `use const` or `use function` statement.
        $phpcsFile->addError(
            'Importing functions and constants through a "use" statement is not supported in PHP 5.5 or lower.',
            $nextNonEmpty,
            'Found'
        );
    }
}
