<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2021 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Exceptions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer_Tokens as Tokens;
use PHPCompatibility\PHPCSHelper;
use PHPCompatibility\Sniff;

/**
 * As of PHP 8.0, a throw keyword can be used as an expression.
 *
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/throw_expression
 * @link https://www.php.net/manual/en/language.exceptions.php
 *
 */
class NewThrowExpressionsSniff extends Sniff
{
    /**
     * A list of tokens indicates a context where the throw keyword is acceptable
     * to use as an expression.
     *
     * @var array
     */
    private $acceptedContextOperators = [
        \T_FN_ARROW,
        \T_COALESCE,
        \T_INLINE_ELSE,
        \T_BOOLEAN_AND,
        \T_BOOLEAN_OR,
        \T_LOGICAL_AND,
        \T_LOGICAL_OR,
        \T_LOGICAL_XOR,
    ];

    /**
     * List of tokens used to indicates context where throw becomes an expression and
     * operator precedence becomes relevant.
     *
     * @var array
     */
    private $acceptedPrecedenceOperator = [
        \T_OBJECT_OPERATOR,
        \T_DOUBLE_COLON,
        \T_COALESCE,
        \T_INLINE_THEN,
        \T_EQUAL,
        \T_COALESCE_EQUAL,
        \T_BOOLEAN_AND,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [\T_THROW];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('7.4') === false) {
            return;
        }

        $endOfStatement = PHPCSHelper::findEndOfStatement($phpcsFile, $stackPtr);
        $tokens = $phpcsFile->getTokens();
        $errorsCounter = 0;
        for ($i = $stackPtr; $i < $endOfStatement; $i++) {
            if ($tokens[$i]['code'] === \T_THROW) {
                $prevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, $i - 1, null, true, null, true);
                if (\in_array($tokens[$prevToken]['code'], $this->acceptedContextOperators)) {
                    $errorsCounter++;
                }

                if ($tokens[$prevToken]['code'] === \T_OPEN_PARENTHESIS) {
                    $afterEndOfStatementToken = $phpcsFile->findNext(Tokens::$emptyTokens, $endOfStatement + 1, null, true, null, true);
                    $afterAfterEndOfStatementToken = $phpcsFile->findNext(Tokens::$emptyTokens, $afterEndOfStatementToken + 1, null, true, null, true);
                    if ($tokens[$afterEndOfStatementToken]['code'] === \T_CLOSE_PARENTHESIS
                        && \in_array($tokens[$afterAfterEndOfStatementToken]['code'], $this->acceptedPrecedenceOperator)) {
                        $errorsCounter++;
                    }
                }
            }
            continue;
        }

        if ($errorsCounter > 0) {
            $this->addMessage($phpcsFile, 'Throw expression is only allowed since PHP 8.0.', $stackPtr, true);
        }
    }
}
