<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Miscellaneous;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * PHP 7.4 now supports stand-alone PHP tags at the end of a file (without new line).
 *
 * > `<?php` at the end of the file (without trailing newline) will now be
 * > interpreted as an opening PHP tag. Previously it was interpreted either as
 * > `<? php` and resulted in a syntax error (with short_open_tag=1) or was
 * > interpreted as a literal `<?php` string (with short_open_tag=0).
 *
 * PHP version 7.4
 *
 * @link https://www.php.net/manual/en/migration74.incompatible.php#migration74.incompatible.core.php-tag
 * @link https://github.com/php/php-src/blob/30de357fa14480468132bbc22a272aeb91789ba8/UPGRADING#L37-L40
 *
 * @since 9.3.0
 */
class NewPHPOpenTagEOFSniff extends Sniff
{

    /**
     * Whether or not short open tags is enabled on the install running the sniffs.
     *
     * @since 9.3.0
     *
     * @var bool
     */
    protected $shortOpenTags;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.3.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        $targets = [
            \T_OPEN_TAG_WITH_ECHO,
        ];

        $this->shortOpenTags = (bool) \ini_get('short_open_tag');
        if ($this->shortOpenTags === false) {
            $targets[] = \T_INLINE_HTML;
        } else {
            $targets[] = \T_STRING;
        }

        if (\PHP_VERSION_ID >= 70400) {
            $targets[] = \T_OPEN_TAG;
        }

        return $targets;
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.3.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($stackPtr !== ($phpcsFile->numTokens - 1)) {
            // We're only interested in the last token in the file.
            return;
        }

        if (ScannedCode::shouldRunOnOrBelow('7.3') === false) {
            return;
        }

        $tokens   = $phpcsFile->getTokens();
        $contents = $tokens[$stackPtr]['content'];
        $error    = false;

        switch ($tokens[$stackPtr]['code']) {
            case \T_INLINE_HTML:
                // PHP < 7.4 with short open tags off.
                if ($contents === '<?php') {
                    $error = true;
                }
                break;

            case \T_STRING:
                // PHP < 7.4 with short open tags on.
                if ($contents === 'php'
                    && $tokens[($stackPtr - 1)]['code'] === \T_OPEN_TAG
                    && $tokens[($stackPtr - 1)]['content'] === '<?'
                ) {
                    $error = true;
                }
                break;

            case \T_OPEN_TAG_WITH_ECHO:
                // PHP 5.4+.
                if (\rtrim($contents) === '<?=') {
                    $error = true;
                }
                break;

            case \T_OPEN_TAG:
                // PHP 7.4+.
                if ($contents === '<?php') {
                    $error = true;
                }
                break;
        }

        if ($error === true) {
            $phpcsFile->addError(
                'A PHP open tag at the end of a file, without trailing newline, was not supported in PHP 7.3 or earlier and would result in a syntax error or be interpreted as a literal string',
                $stackPtr,
                'Found'
            );
        }
    }
}
