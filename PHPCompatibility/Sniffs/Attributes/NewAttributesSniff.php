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
     * @return array
     */
    public function register()
    {
        $targets = [\T_COMMENT];

        if (\defined('T_ATTRIBUTE') === true) {
            // PHP 8.0 or PHPCS >= 3.6.0.
            $targets[] = \T_ATTRIBUTE;

        }

        return $targets;
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
        if ($this->supportsBelow('7.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['type'] === 'T_ATTRIBUTE') {
            /*
             * Either PHP 8.0 or PHPCS 3.6.0+.
             * PHP 8.0 tokenizes the start marker of the attribute as `T_ATTRIBUTE` and the
             * end marker as `T_CLOSE_SQUARE_BRACKET.
             * In PHPCS 3.6.0+ the end marker will be tokenized as `T_ATTRIBUTE_END` and
             * the start marker will have an 'attribute_closer' token array index.
             */
            if (isset($tokens[$stackPtr]['attribute_closer']) === true) {
                // This is PHPCS >= 3.6.0.
                $this->throwError(
                    $phpcsFile,
                    $stackPtr,
                    GetTokensAsString::compact($phpcsFile, $stackPtr, $tokens[$stackPtr]['attribute_closer'], true)
                );
                return;
            }

            // This must be PHP 8.0 in combination with PHPCS < 3.6.0.
            $nestedBrackets = 1;
            for ($i = ($stackPtr + 1); $i < $phpcsFile->numTokens; $i++) {
                if ($tokens[$i]['code'] === \T_OPEN_SQUARE_BRACKET) {
                    ++$nestedBrackets;
                    continue;
                }

                if ($tokens[$i]['code'] === \T_CLOSE_SQUARE_BRACKET) {
                    --$nestedBrackets;

                    if ($nestedBrackets === 0) {
                        // Found the end of the attribute.
                        break;
                    }
                }

                if ($tokens[$i]['code'] === \T_CLOSE_TAG) {
                    /*
                     * Prevent looping to the end of the file for a particular edge case which
                     * breaks the tokenizer 8.0.
                     */
                    break;
                }
            }

            $this->throwError($phpcsFile, $stackPtr, GetTokensAsString::compact($phpcsFile, $stackPtr, $i, true));
            return;
        }

        /*
         * PHP < 8.0 in combination with PHPCS < 3.6.0 will tokenize attributes as comments.
         * This comment can contain multiple attributes on the same line or can be followed
         * by actual code.
         * The attribute closer can also be on a later line.
         */
        $contents = $tokens[$stackPtr]['content'];

        if (\substr($contents, 0, 2) !== '#[') {
            // Bow out early if we already know this is not an attribute.
            return;
        }

        $expectedErrors = \substr_count($contents, '#[');
        $actualErrors   = 0;

        while (empty($contents) === false && \substr($contents, 0, 2) === '#[') {
            $length         = \strlen($contents);
            $nestedBrackets = 1;

            for ($i = 2; $i < $length; $i++) {
                if ($contents[$i] === '[') {
                    ++$nestedBrackets;
                    continue;
                }

                if ($contents[$i] === ']') {
                    --$nestedBrackets;

                    if ($nestedBrackets === 0) {
                        // Found the end of the attribute.
                        $this->throwError($phpcsFile, $stackPtr, \substr($contents, 0, ($i + 1)));
                        ++$actualErrors;

                        if (($i + 1) === $length) {
                            $contents = '';
                        } else {
                            $contents = \trim(\substr($contents, ($i + 1)));
                        }
                        break;
                    }
                }
            }

            if ($i === $length) {
                // Reached the end of the content without finding a closing bracket.
                break;
            }
        }

        if ($expectedErrors === $actualErrors) {
            // This was a single-line attribute, closer found.
            return;
        }

        // This must be a multi-line attribute.
        $nestedBrackets = 1;
        for ($i = ($stackPtr + 1); $i < $phpcsFile->numTokens; $i++) {
            if ($tokens[$i]['code'] === \T_OPEN_SQUARE_BRACKET) {
                ++$nestedBrackets;
                continue;
            }

            if ($tokens[$i]['code'] === \T_CLOSE_SQUARE_BRACKET) {
                --$nestedBrackets;

                if ($nestedBrackets === 0) {
                    // Found the end of the attribute.
                    break;
                }
            }

            if ($tokens[$i]['code'] === \T_CLOSE_TAG) {
                /*
                 * Prevent looping to the end of the file for a particular edge case which
                 * breaks the tokenizer on PHP < 8.0.
                 */
                break;
            }
        }

        $this->throwError(
            $phpcsFile,
            $stackPtr,
            \rtrim($contents, "\r\n") . GetTokensAsString::compact($phpcsFile, $stackPtr, $i, true)
        );
    }

    /**
     * Throw an error when an attribute is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     * @param string                      $content   Attribute content for use in the error message.
     *
     * @return void
     */
    protected function throwError($phpcsFile, $stackPtr, $content)
    {
        $phpcsFile->addError(
            'Attributes are not supported in PHP 7.4 or earlier. Found: %s',
            $stackPtr,
            'Found',
            [$content]
        );
    }
}
