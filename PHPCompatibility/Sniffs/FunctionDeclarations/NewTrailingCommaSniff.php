<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\FunctionDeclarations;

/**
 * Detect trailing comma's in function declarations as allowed since PHP 8.
 *
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/trailing_comma_in_parameter_list
 *
 * @since 10.0.0
 */
class NewTrailingCommaSniff extends Sniff
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
        return Collections::functionDeclarationTokensBC();
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('7.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if (isset(Collections::arrowFunctionTokensBC()[$tokens[$stackPtr]['code']]) === true) {
            $arrowInfo = FunctionDeclarations::getArrowFunctionOpenClose($phpcsFile, $stackPtr);
            if ($arrowInfo === false) {
                // Not an arrow function.
                return;
            }

            $closer = $arrowInfo['parenthesis_closer'];
        } else {
            if (isset($tokens[$stackPtr]['parenthesis_closer']) === false) {
                // Live coding or parse error.
                return;
            }

            $closer = $tokens[$stackPtr]['parenthesis_closer'];
        }

        $lastInParenthesis = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($closer - 1), null, true);

        if ($tokens[$lastInParenthesis]['code'] !== \T_COMMA) {
            return;
        }

        $phpcsFile->addError(
            'Trailing comma\'s are not allowed in function declaration parameter lists in PHP 7.4 or earlier',
            $lastInParenthesis,
            'Found'
        );
    }
}
