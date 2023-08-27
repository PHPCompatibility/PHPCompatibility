<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Numbers;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\Numbers;

/**
 * Check for valid integer types and values.
 *
 * Checks:
 * - PHP 5.4 introduced binary integers.
 * - PHP 7.0 removed tolerance for invalid octals. These were truncated prior to PHP 7
 *   and give a parse error since PHP 7.
 *
 * PHP version 5.4+
 *
 * @link https://wiki.php.net/rfc/binnotation4ints
 * @link https://www.php.net/manual/en/language.types.integer.php
 *
 * @since 7.0.3
 * @since 7.0.8  This sniff now throws a warning instead of an error for invalid binary integers.
 * @since 10.0.0 - The sniff has been moved from the `Miscellaneous` category to `Numbers`.
 *               - The check for hexadecimal numeric strings has been split off to its own sniff.
 */
class ValidIntegersSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.3
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            \T_LNUMBER,
        ];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens     = $phpcsFile->getTokens();
        $numberInfo = Numbers::getCompleteNumber($phpcsFile, $stackPtr);

        if (Numbers::isBinaryInt($numberInfo['content']) === true) {
            if (ScannedCode::shouldRunOnOrBelow('5.3') === true) {
                $error = 'Binary integer literals were not present in PHP version 5.3 or earlier. Found: %s';
                $data  = [$numberInfo['orig_content']];
                $phpcsFile->addError($error, $stackPtr, 'BinaryIntegerFound', $data);
            }

            if ($this->isInvalidBinaryInteger($tokens, $numberInfo['last_token']) === true) {
                $error = 'Invalid binary integer detected. Found: %s';
                $data  = [$this->getBinaryInteger($phpcsFile, $tokens, $stackPtr)];
                $phpcsFile->addWarning($error, $stackPtr, 'InvalidBinaryIntegerFound', $data);
            }

            // If this was a PHP 7.4 numeric literal, no need to scan subsequent parts of the number again.
            return $numberInfo['last_token'];
        }

        $isError = ScannedCode::shouldRunOnOrAbove('7.0');

        $isInvalidOctal = $this->isInvalidOctalInteger($tokens, $stackPtr, $numberInfo);
        if ($isInvalidOctal !== false) {
            MessageHelper::addMessage(
                $phpcsFile,
                'Invalid octal integer detected. Prior to PHP 7 this would lead to a truncated number. From PHP 7 onwards this causes a parse error. Found: %s',
                $stackPtr,
                $isError,
                'InvalidOctalIntegerFound',
                [$isInvalidOctal]
            );
        }

        // If this was a PHP 7.4 numeric literal, no need to scan subsequent parts of the number again.
        return $numberInfo['last_token'];
    }

    /**
     * Is the current token an invalid binary integer ?
     *
     * @since 7.0.3
     *
     * @param array $tokens   Token stack.
     * @param int   $stackPtr The current position in the token stack.
     *
     * @return bool
     */
    private function isInvalidBinaryInteger($tokens, $stackPtr)
    {
        $next = $tokens[$stackPtr + 1];

        // If it's an invalid binary int, the token will be split into two T_LNUMBER tokens.
        if ($next['code'] === \T_LNUMBER) {
            return true;
        }

        if ($next['code'] === \T_STRING
            && \preg_match('`^((?<![\.e])_[0-9][0-9e\.]*)+$`iD', $next['content'])
        ) {
            return true;
        }

        return false;
    }

    /**
     * Retrieve the content of the tokens which together look like a binary integer.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param array                       $tokens    Token stack.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack.
     *
     * @return string
     */
    private function getBinaryInteger(File $phpcsFile, $tokens, $stackPtr)
    {
        $i = $stackPtr;
        while ($tokens[($i + 1)]['code'] === \T_LNUMBER) {
            ++$i;
        }

        return GetTokensAsString::normal($phpcsFile, $stackPtr, $i);
    }

    /**
     * Is the current token an invalid octal integer ?
     *
     * @since 7.0.3
     *
     * @param array                     $tokens     Token stack.
     * @param int                       $stackPtr   The current position in the token stack.
     * @param array<string, string|int> $numberInfo The information on the number to examine
     *
     * @return string|bool The invalid octal as a string or false when this is not an invalid octal.
     */
    private function isInvalidOctalInteger($tokens, $stackPtr, $numberInfo)
    {
        // For invalid explicit octal, we need to also check the next token.
        if (($numberInfo['content'] === '0'
             && \strtolower($tokens[($stackPtr + 1)]['content'][0]) === 'o')
             || \stripos($numberInfo['content'], '0o') === 0
        ) {
            if (\preg_match('`^(?:[o_][0-7_]*)?[8-9]+[_0-9]*$`iD', $tokens[($stackPtr + 1)]['content']) === 1) {
                return $tokens[$stackPtr]['content'] . $tokens[($stackPtr + 1)]['content'];
            }
        }

        if (\preg_match('`^0[0-7]*[8-9]+[0-9]*$`iD', $numberInfo['content']) === 1) {
            return $numberInfo['orig_content'];
        }

        return false;
    }
}
