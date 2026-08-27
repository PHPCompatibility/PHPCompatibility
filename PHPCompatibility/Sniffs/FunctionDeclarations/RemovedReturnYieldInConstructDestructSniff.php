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

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\ObjectDeclarations;

/**
 * Returning or yielding a value from a constructor or destructor is deprecated since PHP 8.6.
 *
 * PHP version 8.6
 *
 * @link https://wiki.php.net/rfc/deprecate-return-value-from-construct
 * @link https://www.php.net/language.oop5.decon
 *
 * @since 10.0.0
 */
final class RemovedReturnYieldInConstructDestructSniff extends Sniff
{

    /**
     * Magic methods this sniff needs to examine.
     *
     * @since 10.0.0
     *
     * @var array<string, string> Method name => Type of method for use in the error message.
     */
    private const TARGET_FUNCTIONS = [
        '__construct' => 'constructor',
        '__destruct'  => 'destructor',
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
        // Enums can't have constructors or destructors, interfaces cannot have a function body, so no need to scan either.
        return [
            \T_CLASS,
            \T_ANON_CLASS,
            \T_TRAIT,
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
        if (ScannedCode::shouldRunOnOrAbove('8.6') === false) {
            return;
        }

        $ooMethods = ObjectDeclarations::getDeclaredMethods($phpcsFile, $stackPtr);
        if (empty($ooMethods)) {
            // OO declaration without methods or parse error.
            return;
        }

        $ooMethods = \array_change_key_case($ooMethods, \CASE_LOWER);

        $tokens       = $phpcsFile->getTokens();
        $closedScopes = Collections::closedScopes();
        foreach (self::TARGET_FUNCTIONS as $functionName => $functionType) {
            if (isset($ooMethods[$functionName]) === false) {
                continue;
            }

            $functionPtr = $ooMethods[$functionName];
            if (isset($tokens[$functionPtr]['scope_opener'], $tokens[$functionPtr]['scope_closer']) === false) {
                continue;
            }

            for ($i = ($tokens[$functionPtr]['scope_opener'] + 1); $i < $tokens[$functionPtr]['scope_closer']; $i++) {
                if (isset($closedScopes[$tokens[$i]['code']]) === true
                    && isset($tokens[$i]['scope_closer']) === true
                ) {
                    // Skip over nested closed scopes which may contain return or yield statements.
                    $i = $tokens[$i]['scope_closer'];
                    continue;
                }

                /*
                 * Arrow functions cannot contain a return statement, but a `yield` in an arrow function
                 * could confuse the sniff, so better to skip over them completely.
                 */
                if ($tokens[$i]['code'] === \T_FN && isset($tokens[$i]['scope_closer'])) {
                    $i = $tokens[$i]['scope_closer'];
                    continue;
                }

                if ($tokens[$i]['code'] === \T_RETURN) {
                    $nextNonEmpty = $phpcsFile->findNext(Tokens::EMPTY_TOKENS, ($i + 1), null, true);
                    if ($nextNonEmpty === false
                        || $tokens[$nextNonEmpty]['code'] === \T_SEMICOLON
                        || $tokens[$nextNonEmpty]['code'] === \T_CLOSE_TAG
                    ) {
                        // Returning without passing a value. This is okay.
                        continue;
                    }

                    $error = 'Returning a value from a %s is deprecated since PHP 8.6.';
                    $data  = [$functionType];
                    $phpcsFile->addWarning($error, $i, 'DeprecatedReturnWithValue', $data);

                    continue;
                }

                if ($tokens[$i]['code'] === \T_YIELD || $tokens[$i]['code'] === \T_YIELD_FROM) {

                    $error = 'Using a %s as a generator is deprecated since PHP 8.6.';
                    $data  = [$functionType];
                    $phpcsFile->addWarning($error, $i, 'DeprecatedGenerator', $data);

                    // Prevent duplicate messages for multi-token "yield from".
                    if ($tokens[$i]['code'] === \T_YIELD_FROM && \strtolower($tokens[$i]['content']) === 'yield') {
                        for ($j = ($i + 1); $j < $tokens[$functionPtr]['scope_closer']; $j++) {
                            if (isset(Tokens::EMPTY_TOKENS[$tokens[$j]['code']]) === true) {
                                continue;
                            }

                            // The next token should be the "from" keyword.
                            if ($tokens[$j]['code'] === \T_YIELD_FROM && \strtolower($tokens[$j]['content']) === 'from') {
                                $i = $j;
                            }

                            break;
                        }
                    }
                }
            }
        }
    }
}
