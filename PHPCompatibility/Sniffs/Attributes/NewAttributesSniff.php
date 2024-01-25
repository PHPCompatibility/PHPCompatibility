<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Attributes;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\GetTokensAsString;

/**
 * Attributes as a form of structured, syntactic metadata to declarations of classes, properties,
 * functions, methods, parameters and constants is supported as of PHP 8.0.
 *
 * {@internal This sniff does not check whether attributes are used correctly and in
 * combination with syntaxes for which attributes are valid.
 * If that's not the case, PHP 8.0 would throw a parse error anyway.}
 *
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/attributes_v2
 * @link https://wiki.php.net/rfc/attribute_amendments
 * @link https://wiki.php.net/rfc/shorter_attribute_syntax
 * @link https://wiki.php.net/rfc/shorter_attribute_syntax_change
 * @link https://www.php.net/manual/en/language.attributes.php
 *
 * @since 10.0.0
 */
class NewAttributesSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_ATTRIBUTE];
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
        if (ScannedCode::shouldRunOnOrBelow('7.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['attribute_closer']) === false) {
	        // Live coding or parse error. Shouldn't be possible as shouldn't have retokenized in that case.
            return; // @codeCoverageIgnore
        }

        $content = GetTokensAsString::compact($phpcsFile, $stackPtr, $tokens[$stackPtr]['attribute_closer'], true);

        if ($this->isBackwardsCompatibleAttribute($phpcsFile, $stackPtr, $tokens)) {
            $phpcsFile->addWarning(
                'Backwards compatible attribute detected. This may not cause parse errors in PHP < 8.0: Found: %s',
                $stackPtr,
                'Found',
                [$content]
            );
        } else {
            $phpcsFile->addError(
                'Attributes are not supported in PHP 7.4 or earlier. Found: %s',
                $stackPtr,
                'Found',
                [$content]
            );
        }
    }

    /**
     * Determines if an attribute is likely to be backwards compatible.
     *
     * @param  \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param  int                         $stackPtr  The position of the current token in the stack.
     * @param  array                       $tokens    The array of tokens.
     * @return bool
     */
    protected function isBackwardsCompatibleAttribute($phpcsFile, $stackPtr, $tokens)
    {
        $currentLine = $tokens[$stackPtr]['line'];

        // Check if the attribute starts the line (ignoring whitespace)
        $startOfLineToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if ($tokens[$startOfLineToken]['line'] < $currentLine) {
            // The attribute starts a new line
            $attributeCloser = $tokens[$stackPtr]['attribute_closer'];

            if ($tokens[$attributeCloser]['line'] == $currentLine) {
                // Check for any token after the attribute on the same line
                $nextToken = $phpcsFile->findNext(T_WHITESPACE, ($attributeCloser + 1), null, true);
                if ($nextToken === false || $tokens[$nextToken]['line'] > $currentLine) {
                    // No non-whitespace token after the attribute on the same line
                    for ($i = $stackPtr; $i <= $attributeCloser; $i++) {
                        // Check for a closing tag within a string
                        if ($tokens[$i]['type'] === 'T_CONSTANT_ENCAPSED_STRING' && strpos($tokens[$i]['content'], '?>') !== false) {
                            return false;
                        }
                    }

                    // No problematic closing tag found in the attribute
                    return true;
                }
            }
        }

        // Attribute does not start its own line, is multi-line,
        // or has problematic tokens.
        return false;
    }
}
