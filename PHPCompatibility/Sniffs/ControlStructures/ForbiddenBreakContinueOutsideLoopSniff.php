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
use PHPCSUtils\Utils\Conditions;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Detect using `break` and/or `continue` statements outside of a looping structure.
 *
 * PHP version 7.0
 *
 * @link https://www.php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.break-continue
 * @link https://www.php.net/manual/en/control-structures.break.php
 * @link https://www.php.net/manual/en/control-structures.continue.php
 *
 * @since 7.0.7
 */
class ForbiddenBreakContinueOutsideLoopSniff extends Sniff
{

    /**
     * Token codes of control structure in which usage of break/continue is valid.
     *
     * @since 7.0.7
     *
     * @var array<int|string, int|string>
     */
    protected $validLoopStructures = [
        \T_FOR     => \T_FOR,
        \T_FOREACH => \T_FOREACH,
        \T_WHILE   => \T_WHILE,
        \T_DO      => \T_DO,
        \T_SWITCH  => \T_SWITCH,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.7
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            \T_BREAK,
            \T_CONTINUE,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.7
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // Check if the break/continue is within a valid loop structure.
        if (Conditions::getCondition($phpcsFile, $stackPtr, $this->validLoopStructures) !== false) {
            return;
        }

        // If we're still here, no valid loop structure container has been found, so throw an error.
        $tokens = $phpcsFile->getTokens();

        $error     = "Using '%s' outside of a loop or switch structure is invalid";
        $isError   = false;
        $errorCode = 'Found';
        $data      = [$tokens[$stackPtr]['content']];

        if (ScannedCode::shouldRunOnOrAbove('7.0') === true) {
            $error    .= ' and will throw a fatal error since PHP 7.0';
            $isError   = true;
            $errorCode = 'FatalError';
        }

        MessageHelper::addMessage($phpcsFile, $error, $stackPtr, $isError, $errorCode, $data);
    }
}
