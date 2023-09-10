<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ControlStructures;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\Numbers;

/**
 * Detects using 0 and variable numeric arguments on `break` and `continue` statements.
 *
 * This sniff checks for:
 * - Using `break` and/or `continue` with a variable as the numeric argument.
 * - Using `break` and/or `continue` with a zero - 0 - as the numeric argument.
 *
 * PHP version 5.4
 *
 * @link https://php-legacy-docs.zend.com/manual/php5/en/migration54.incompatible
 * @link https://www.php.net/manual/en/control-structures.break.php
 * @link https://www.php.net/manual/en/control-structures.continue.php
 *
 * @since 5.5
 * @since 5.6 Now extends the base `Sniff` class.
 */
class ForbiddenBreakContinueVariableArgumentsSniff extends Sniff
{

    /**
     * Error types this sniff handles for forbidden break/continue arguments.
     *
     * Array key is the error code. Array value will be used as part of the error message.
     *
     * @since 7.0.5
     * @since 7.1.0 Changed from class constants to property.
     *
     * @var array<string, string>
     */
    private $errorTypes = [
        'variableArgument' => 'a variable argument',
        'zeroArgument'     => '0 as an argument',
    ];

    /**
     * Tokens indicating this is definitely a variable argument.
     *
     * @since 10.0.0
     *
     * @var array<int|string, int|string>
     */
    private $varArgTokens = [
        \T_VARIABLE => \T_VARIABLE,
        \T_CLOSURE  => \T_CLOSURE,
        \T_FN       => \T_FN,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.5
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_BREAK, \T_CONTINUE];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('5.4') === false) {
            return;
        }

        $tokens             = $phpcsFile->getTokens();
        $nextSemicolonToken = $phpcsFile->findNext([\T_SEMICOLON, \T_CLOSE_TAG], ($stackPtr), null, false);
        $errorType          = '';
        for ($curToken = $stackPtr + 1; $curToken < $nextSemicolonToken; $curToken++) {
            if ($tokens[$curToken]['code'] === \T_STRING) {
                // If the next non-whitespace token after the string
                // is an opening parenthesis then it's a function call.
                $openBracket = $phpcsFile->findNext(Tokens::$emptyTokens, $curToken + 1, null, true);
                if ($tokens[$openBracket]['code'] === \T_OPEN_PARENTHESIS) {
                    $errorType = 'variableArgument';
                    break;
                }
            }

            if (isset($this->varArgTokens[$tokens[$curToken]['code']]) === true) {
                $errorType = 'variableArgument';
                break;
            }

            if ($tokens[$curToken]['code'] === \T_LNUMBER) {
                $numberInfo = Numbers::getCompleteNumber($phpcsFile, $curToken);
                if ($numberInfo['decimal'] === '0') {
                    $errorType = 'zeroArgument';
                    break;
                }
            }
        }

        if ($errorType !== '') {
            $error     = 'Using %s on break or continue is forbidden since PHP 5.4';
            $errorCode = $errorType . 'Found';
            $data      = [$this->errorTypes[$errorType]];

            $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
        }
    }
}
