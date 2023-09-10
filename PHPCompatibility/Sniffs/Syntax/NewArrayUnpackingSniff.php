<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Syntax;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\Arrays;
use PHPCSUtils\Utils\GetTokensAsString;

/**
 * Using the spread operator for unpacking arrays in array expressions is available since PHP 7.4.
 *
 * PHP version 7.4
 *
 * @link https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.unpack-inside-array
 * @link https://wiki.php.net/rfc/spread_operator_for_array
 *
 * @since 9.2.0
 */
class NewArrayUnpackingSniff extends Sniff
{

    /**
     * Array target tokens.
     *
     * @since 10.0.0
     *
     * @var array<int|string, int|string>
     */
    private $arrayTokens = [
        \T_ARRAY               => \T_ARRAY,
        \T_OPEN_SHORT_ARRAY    => \T_OPEN_SHORT_ARRAY,
        \T_OPEN_SQUARE_BRACKET => \T_OPEN_SQUARE_BRACKET,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.2.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return $this->arrayTokens;
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('7.3') === false) {
            return;
        }

        /*
         * Determine the array opener & closer.
         */
        $openClose = Arrays::getOpenClose($phpcsFile, $stackPtr);
        if ($openClose === false) {
            // Parse error, live coding or short list, not short array.
            return;
        }

        $opener = $openClose['opener'];
        $closer = $openClose['closer'];
        $tokens = $phpcsFile->getTokens();

        $nestingLevel = 0;
        if (isset($tokens[($opener + 1)]['nested_parenthesis'])) {
            $nestingLevel = \count($tokens[($opener + 1)]['nested_parenthesis']);
        }

        $find              = $this->arrayTokens;
        $find[\T_ELLIPSIS] = \T_ELLIPSIS;

        for ($i = $opener; $i < $closer;) {
            $i = $phpcsFile->findNext($find, ($i + 1), $closer);
            if ($i === false) {
                return;
            }

            if (isset($tokens[$i]['bracket_closer']) === true) {
                // Skip over nested short arrays. These will be handled when the array opener
                // of the nested array is passed.
                $i = $tokens[$i]['bracket_closer'];
                continue;
            }

            if (isset($tokens[$i]['parenthesis_closer']) === true) {
                // Skip over nested long arrays. These will be handled when the array opener
                // of the nested array is passed.
                $i = $tokens[$i]['parenthesis_closer'];
                continue;
            }

            if ($tokens[$i]['code'] !== \T_ELLIPSIS) {
                // Shouldn't be possible. Live coding or parse error.
                continue;
            }

            // Ensure this is not function call variable unpacking.
            if (isset($tokens[$i]['nested_parenthesis'])
                && \count($tokens[$i]['nested_parenthesis']) > $nestingLevel
            ) {
                continue;
            }

            // Ok, found one.
            $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($i + 1), null, true);
            $snippet      = GetTokensAsString::compact($phpcsFile, $i, $nextNonEmpty, true);
            $phpcsFile->addError(
                'Array unpacking within array declarations using the spread operator is not supported in PHP 7.3 or earlier. Found: %s',
                $i,
                'Found',
                [$snippet]
            );
        }
    }
}
