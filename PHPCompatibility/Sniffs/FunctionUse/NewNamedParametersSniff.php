<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionUse;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect the use of named function call parameters as supported since PHP 8.0.
 *
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/named_params
 *
 * @since 10.0.0
 */
class NewNamedParametersSniff extends Sniff
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
        return Collections::functionCallTokens();
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('7.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($tokens[$nextNonEmpty]['code'] !== \T_OPEN_PARENTHESIS
            || isset($tokens[$nextNonEmpty]['parenthesis_closer']) === false
        ) {
            return;
        }

        if ($tokens[$stackPtr]['code'] === \T_STRING) {
            $ignore = [
                \T_FUNCTION => true,
                \T_CONST    => true,
                \T_USE      => true,
            ];

            $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
            if (isset($ignore[$tokens[$prevNonEmpty]['code']]) === true) {
                // Not a function call.
                return;
            }
        }

        $params = PassedParameters::getParameters($phpcsFile, $stackPtr);
        if (empty($params) === true) {
            // No parameters found.
            return;
        }

        foreach ($params as $param) {
            if (isset($param['name']) === false) {
                continue;
            }

            $phpcsFile->addError(
                'Using named arguments in function calls is not supported in PHP 7.4 or earlier. Found: "%s"',
                $param['name_token'],
                'Found',
                [$param['name'] . ': ' . $param['raw']]
            );

        }
    }
}
