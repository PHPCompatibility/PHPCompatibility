<?php
/**
 * \PHPCompatibility\Sniffs\ControlStructures\DiscouragedSwitchContinue.
 *
 * PHP version 7.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\ControlStructures;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * \PHPCompatibility\Sniffs\ControlStructures\DiscouragedSwitchContinue.
 *
 * PHP 7.3 will throw a warning when continue is used to target a switch control structure.
 *
 * PHP version 7.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class DiscouragedSwitchContinueSniff extends Sniff
{

    /**
     * Token codes of control structures which can be targeted using continue.
     *
     * @var array
     */
    protected $loopStructures = array(
        \T_FOR     => \T_FOR,
        \T_FOREACH => \T_FOREACH,
        \T_WHILE   => \T_WHILE,
        \T_DO      => \T_DO,
        \T_SWITCH  => \T_SWITCH,
    );

    /**
     * Tokens which start a new case within a switch.
     *
     * @var array
     */
    protected $caseTokens = array(
        \T_CASE    => \T_CASE,
        \T_DEFAULT => \T_DEFAULT,
    );

    /**
     * Token codes which are accepted to determine the level for the continue.
     *
     * This array is enriched with the arithmetic operators in the register() method.
     *
     * @var array
     */
    protected $acceptedLevelTokens = array(
        \T_LNUMBER           => \T_LNUMBER,
        \T_OPEN_PARENTHESIS  => \T_OPEN_PARENTHESIS,
        \T_CLOSE_PARENTHESIS => \T_CLOSE_PARENTHESIS,
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $this->acceptedLevelTokens += Tokens::$arithmeticTokens;
        $this->acceptedLevelTokens += Tokens::$emptyTokens;

        return array(\T_SWITCH);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.3') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_opener'], $tokens[$stackPtr]['scope_closer']) === false) {
            return;
        }

        $switchOpener = $tokens[$stackPtr]['scope_opener'];
        $switchCloser = $tokens[$stackPtr]['scope_closer'];

        // Quick check whether we need to bother with the more complex logic.
        $hasContinue = $phpcsFile->findNext(\T_CONTINUE, ($switchOpener + 1), $switchCloser);
        if ($hasContinue === false) {
            return;
        }

        $caseDefault = $switchOpener;

        do {
            $caseDefault = $phpcsFile->findNext($this->caseTokens, ($caseDefault + 1), $switchCloser);
            if ($caseDefault === false) {
                break;
            }

            if (isset($tokens[$caseDefault]['scope_opener']) === false) {
                // Unknown start of the case, skip.
                continue;
            }

            $caseOpener      = $tokens[$caseDefault]['scope_opener'];
            $nextCaseDefault = $phpcsFile->findNext($this->caseTokens, ($caseDefault + 1), $switchCloser);
            if ($nextCaseDefault === false) {
                $caseCloser = $switchCloser;
            } else {
                $caseCloser = $nextCaseDefault;
            }

            // Check for unscoped control structures within the case.
            $controlStructure = $caseOpener;
            $doCount          = 0;
            while (($controlStructure = $phpcsFile->findNext($this->loopStructures, ($controlStructure + 1), $caseCloser)) !== false) {
                if ($tokens[$controlStructure]['code'] === \T_DO) {
                    $doCount++;
                }

                if (isset($tokens[$controlStructure]['scope_opener'], $tokens[$controlStructure]['scope_closer']) === false) {
                    if ($tokens[$controlStructure]['code'] === \T_WHILE && $doCount > 0) {
                        // While in a do-while construct.
                        $doCount--;
                        continue;
                    }

                    // Control structure without braces found within the case, ignore this case.
                    continue 2;
                }
            }

            // Examine the contents of the case.
            $continue = $caseOpener;

            do {
                $continue = $phpcsFile->findNext(\T_CONTINUE, ($continue + 1), $caseCloser);
                if ($continue === false) {
                    break;
                }

                $nextSemicolon = $phpcsFile->findNext(array(\T_SEMICOLON, \T_CLOSE_TAG), ($continue + 1), $caseCloser);
                $codeString    = '';
                for ($i = ($continue + 1); $i < $nextSemicolon; $i++) {
                    if (isset($this->acceptedLevelTokens[$tokens[$i]['code']]) === false) {
                        // Function call/variable or other token which make numeric level impossible to determine.
                        continue 2;
                    }

                    if (isset(Tokens::$emptyTokens[$tokens[$i]['code']]) === true) {
                        continue;
                    }

                    $codeString .= $tokens[$i]['content'];
                }

                $level = null;
                if ($codeString !== '') {
                    if (is_numeric($codeString)) {
                        $level = (int) $codeString;
                    } else {
                        // With the above logic, the string can only contain digits and operators, eval!
                        $level = eval("return ( $codeString );");
                    }
                }

                if (isset($level) === false || $level === 0) {
                    $level = 1;
                }

                // Examine which control structure is being targeted by the continue statement.
                if (isset($tokens[$continue]['conditions']) === false) {
                    continue;
                }

                $conditions = array_reverse($tokens[$continue]['conditions'], true);
                // PHPCS adds more structures to the conditions array than we want to take into
                // consideration, so clean up the array.
                foreach ($conditions as $tokenPtr => $tokenCode) {
                    if (isset($this->loopStructures[$tokenCode]) === false) {
                        unset($conditions[$tokenPtr]);
                    }
                }

                $targetCondition = \array_slice($conditions, ($level - 1), 1, true);
                if (empty($targetCondition)) {
                    continue;
                }

                $conditionToken = key($targetCondition);
                if ($conditionToken === $stackPtr) {
                    $phpcsFile->addWarning(
                        "Targeting a 'switch' control structure with a 'continue' statement is strongly discouraged and will throw a warning as of PHP 7.3.",
                        $continue,
                        'Found'
                    );
                }

            } while ($continue < $caseCloser);

        } while ($caseDefault < $switchCloser);
    }
}
