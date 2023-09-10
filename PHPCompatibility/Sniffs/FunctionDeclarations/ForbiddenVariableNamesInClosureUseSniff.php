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

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\UseStatements;
use PHPCSUtils\Utils\Variables;

/**
 * Detect variable names forbidden to be used in closure `use` statements.
 *
 * Variables bound to a closure via the `use` construct cannot use the same name
 * as any superglobals, `$this`, or any parameter since PHP 7.1.
 *
 * PHP version 7.1
 *
 * @link https://www.php.net/manual/en/migration71.incompatible.php#migration71.incompatible.lexical-names
 * @link https://www.php.net/manual/en/functions.anonymous.php
 *
 * @since 7.1.4
 */
class ForbiddenVariableNamesInClosureUseSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.1.4
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_USE];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.1.4
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('7.1') === false) {
            return;
        }

        if (UseStatements::isClosureUse($phpcsFile, $stackPtr) === false) {
            // Import or trait use statement.
            return;
        }

        $useParams = FunctionDeclarations::getParameters($phpcsFile, $stackPtr);
        if (empty($useParams)) {
            // No parameters imported. Parse error.
            return;
        }

        /*
         * Get the parameters declared by the closure.
         *
         * No defensive coding needed as if this wasn't a closure, we'd have bowed out at the
         * UseStatements::isClosureUse() check.
         */
        $tokens        = $phpcsFile->getTokens();
        $prevNonEmpty  = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        $closureParams = FunctionDeclarations::getParameters($phpcsFile, $tokens[$prevNonEmpty]['parenthesis_owner']);

        /*
         * Examine the imported closure use variables.
         */
        $errorMsg = 'Variables bound to a closure via the use construct cannot use the same name as superglobals, $this, or a declared parameter since PHP 7.1. Found: %s';

        foreach ($useParams as $useVar) {
            $variableName = $useVar['name'];

            if ($variableName === '$this') {
                $phpcsFile->addError($errorMsg, $useVar['token'], 'FoundThis', [$variableName]);
                continue;
            }

            if (Variables::isSuperglobalName($variableName) === true) {
                $phpcsFile->addError($errorMsg, $useVar['token'], 'FoundSuperglobal', [$variableName]);
                continue;
            }

            // Check whether it is one of the parameters declared by the closure.
            if (empty($closureParams) === false) {
                foreach ($closureParams as $param) {
                    if ($param['name'] === $variableName) {
                        $phpcsFile->addError($errorMsg, $useVar['token'], 'FoundShadowParam', [$variableName]);
                        continue 2;
                    }
                }
            }
        }
    }
}
