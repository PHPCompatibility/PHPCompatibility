<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Classes;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHPCSUtils\Utils\Conditions;

/**
 * Using `parent` inside a class without parent is deprecated since PHP 7.4.
 *
 * This will throw a compile-time error in the future. Currently an error will only
 * be generated if/when the parent is accessed at run-time.
 *
 * PHP version 7.4
 *
 * @link https://www.php.net/manual/en/migration74.deprecated.php#migration74.deprecated.core.parent
 *
 * @since 9.2.0
 */
class RemovedOrphanedParentSniff extends Sniff
{

    /**
     * Class scopes to check the class declaration.
     *
     * @since 9.2.0
     * @since 10.0.0 - Changed to `private`, should never have been public in the first place.
     *               - Now uses token constants instead of token type strings.
     *
     * @var array
     */
    private $classScopeTokens = array(
        \T_CLASS      => \T_CLASS,
        \T_ANON_CLASS => \T_ANON_CLASS,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.2.0
     *
     * @return array
     */
    public function register()
    {
        return array(\T_PARENT);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.2.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.4') === false) {
            return;
        }

        $classPtr = Conditions::getLastCondition($phpcsFile, $stackPtr, $this->classScopeTokens);
        if ($classPtr === false) {
            // Use outside of a class scope. Not our concern.
            return;
        }

        $tokens = $phpcsFile->getTokens();
        if (isset($tokens[$classPtr]['scope_opener']) === false) {
            // No scope opener known. Probably a parse error.
            return;
        }

        $extends = $phpcsFile->findNext(\T_EXTENDS, ($classPtr + 1), $tokens[$classPtr]['scope_opener']);
        if ($extends !== false) {
            // Class has a parent.
            return;
        }

        $phpcsFile->addError(
            'Using "parent" inside a class without parent is deprecated since PHP 7.4',
            $stackPtr,
            'Deprecated'
        );
    }
}
