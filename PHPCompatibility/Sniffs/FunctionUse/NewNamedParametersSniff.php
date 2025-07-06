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
 * As of PHP 8.4, named parameters can also be used for calls to exit/die.
 *
 * PHP version 8.0
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/named_params
 * @link https://wiki.php.net/rfc/exit-as-function
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
        if (ScannedCode::shouldRunOnOrBelow('8.3') === false) {
            // Named params are supported in both function calls + exit on PHP 8.4 and higher.
            return;
        }

        $tokens = $phpcsFile->getTokens();
        if ($tokens[$stackPtr]['code'] !== \T_EXIT
            && ScannedCode::shouldRunOnOrBelow('7.4') === false
        ) {
            // Not an exit expression and PHP < 8.0 does not need to be supported, so we're good. Bow out.
            return;
        }

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

        $error    = 'Using named arguments %s is not supported in PHP 7.4 or earlier. Found: "%s"';
        $code     = 'Found';
        $inPhrase = 'in function calls';

        if ($tokens[$stackPtr]['code'] === \T_EXIT) {
            $error    = 'Using named arguments %s is not supported in PHP 8.3 or earlier. Found: "%s"';
            $code     = 'FoundInExitDie';
            $inPhrase = 'for calls to exit() or die()';
        }

        foreach ($params as $param) {
            if (isset($param['name']) === false) {
                continue;
            }

            $data = [$inPhrase, $param['name'] . ': ' . $param['raw']];
            $phpcsFile->addError($error, $param['name_token'], $code, $data);
        }
    }
}
