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

use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * (Nested) Static property and constant fetches as well as method calls can be applied to
 * any dereferencable expression since PHP 7.0.
 *
 * PHP version 7.0
 *
 * @link https://wiki.php.net/rfc/uniform_variable_syntax
 *
 * @since 10.0.0
 */
class NewNestedStaticAccessSniff extends Sniff
{

    /**
     * Access operators.
     *
     * @since 10.0.0
     *
     * @var array
     */
    private $accessOperators = array(
        \T_OBJECT_OPERATOR => true,
        \T_DOUBLE_COLON    => true,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array
     */
    public function register()
    {
        return array(\T_DOUBLE_COLON);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.6') === false) {
            return;
        }

        $tokens       = $phpcsFile->getTokens();
        $prev         = $stackPtr;
        $seenBrackets = false;
        $prevOperator = false;

        do {
            $prev = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prev - 1), null, true);

            if ($prev === false) {
                return;
            }

            if ($tokens[$prev]['code'] === \T_CLOSE_SQUARE_BRACKET
                || $tokens[$prev]['code'] === \T_CLOSE_CURLY_BRACKET
            ) {
                if (isset($tokens[$prev]['bracket_opener']) === false) {
                    // Parse error.
                    return;
                }

                $prev         = $tokens[$prev]['bracket_opener'];
                $seenBrackets = true;
                continue;
            }

            if ($tokens[$prev]['code'] === \T_CLOSE_PARENTHESIS) {
                if (isset($tokens[$prev]['parenthesis_opener']) === false) {
                    // Parse error.
                    return;
                }

                $prev         = $tokens[$prev]['parenthesis_opener'];
                $seenBrackets = true;
                continue;
            }

            // Now this should either be a T_STRING or a T_VARIABLE.
            if ($tokens[$prev]['code'] !== \T_STRING && $tokens[$prev]['code'] !== \T_VARIABLE) {
                // Not sure what's happening, but this is outside the scope of this sniff.
                return;
            }

            // OK, we have the start of the access, let see if it's nested.
            $prevOperator = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($prev - 1), null, true);
            break;

        } while (true);

        if ($prevOperator === false
            || isset($this->accessOperators[$tokens[$prevOperator]['code']]) === false
        ) {
            return;
        }

        // Ignore the one form of nested static access which is still not supported: ?::CONST::?.
        if ($seenBrackets === false
            && $tokens[$prev]['code'] === \T_STRING
            && $tokens[$prevOperator]['code'] === \T_DOUBLE_COLON
        ) {
            return;
        }

        // This is nested static access.
        $phpcsFile->addError(
            'Nested access to static properties, constants and methods was not supported in PHP 5.6 or earlier.',
            $stackPtr,
            'Found'
        );
    }
}
