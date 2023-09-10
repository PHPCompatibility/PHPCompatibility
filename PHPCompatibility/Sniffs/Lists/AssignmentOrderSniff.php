<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Lists;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\Lists;

/**
 * Detect code affected by the changed list assignment order in PHP 7.0+.
 *
 * The `list()` construct no longer assigns variables in reverse order.
 * This affects all list constructs where non-unique variables are used.
 *
 * PHP version 7.0
 *
 * @link https://www.php.net/manual/en/migration70.incompatible.php#migration70.incompatible.variable-handling.list.order
 * @link https://wiki.php.net/rfc/abstract_syntax_tree#changes_to_list
 * @link https://www.php.net/manual/en/function.list.php
 *
 * @since 9.0.0
 */
class AssignmentOrderSniff extends Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 9.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return Collections::listOpenTokensBC();
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 9.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void|int Void if not a valid list. If a list construct has been
     *                  examined, the stack pointer to the list closer to skip
     *                  passed any nested lists which don't need to be examined again.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrAbove('7.0') === false) {
            return;
        }

        $openClose = Lists::getOpenClose($phpcsFile, $stackPtr);
        if ($openClose === false) {
            // Parse error, live coding, real square brackets or short array, not short list.
            return;
        }

        $closer   = $openClose['closer'];
        $listVars = $this->getAssignedVars($phpcsFile, $stackPtr);
        if (empty($listVars)) {
            // Empty list, not our concern.
            return ($closer + 1);
        }

        // Verify that all variables used in the list() construct are unique.
        if (\count($listVars) !== \count(\array_unique($listVars))) {
            $phpcsFile->addError(
                'list() will assign variable from left-to-right since PHP 7.0. Ensure all variables in list() are unique to prevent unexpected results.',
                $stackPtr,
                'Affected'
            );
        }

        // Only examine each list once. Nested lists are examined with the encompassing list.
        return ($closer + 1);
    }


    /**
     * Retrieve the variables being assigned to in a list construct.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of a list-like token.
     *
     * @return array<int, string> Array with the variables being assigned to as values and the corresponding
     *                            stack pointer to the start of each variable as keys.
     *
     * @throws \PHP_CodeSniffer\Exceptions\RuntimeException If the specified $stackPtr is not of
     *                                                      type T_LIST, T_OPEN_SHORT_ARRAY or
     *                                                      T_OPEN_SQUARE_BRACKET.
     */
    protected function getAssignedVars($phpcsFile, $stackPtr)
    {
        $assignments = Lists::getAssignments($phpcsFile, $stackPtr);

        $listVars = [];
        foreach ($assignments as $assign) {
            if ($assign['is_empty'] === true) {
                continue;
            }

            if ($assign['is_nested_list'] === true) {
                /*
                 * Recurse into the nested list and get the variables.
                 * No need to `catch` any errors as only lists can be nested in lists.
                 */
                $listVars += $this->getAssignedVars($phpcsFile, $assign['assignment_token']);
                continue;
            }

            /*
             * Ok, so this must be a "normal" assignment in the list.
             * Make sure that differences in whitespace will not confuse the variable comparison
             * we need to do later.
             */
            $varNoEmpties = GetTokensAsString::noEmpties(
                $phpcsFile,
                $assign['assignment_token'],
                $assign['assignment_end_token']
            );
            $listVars[$assign['assignment_token']] = $varNoEmpties;
        }

        return $listVars;
    }
}
