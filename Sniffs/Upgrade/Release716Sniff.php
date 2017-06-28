<?php
/**
 * PHPCompatibility_Sniffs_Upgrade_Release716Sniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_Upgrade_Release716Sniff.
 *
 * Adds a temporary warning about the breaking changes in the upcoming 7.1.6
 * release of the PHPCompatibility standard.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_Upgrade_Release716Sniff extends PHPCompatibility_Sniff
{
    /**
     * Keep track of whether the warning has been thrown yet.
     *
     * This warning should only be thrown once per run.
     *
     * @var bool
     */
    protected $thrown = false;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_OPEN_TAG,
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        // Don't do anything if the warning has already been thrown once.
        if ($this->thrown === true) {
            return ($phpcsFile->numTokens + 1);
        }

        $phpcsFile->addWarning(
            "IMPORTANT NOTICE:\nPlease be advised that the upcoming 7.1.6 version of the PHPCompatibility standard will contain a breaking change.\n\nPlease read the changelog carefully when you upgrade and follow the instructions contained therein to retain uninterupted service.\n\nThank you for using PHPCompatibility!",
            0,
            'BreakingChange'
        );

        $this->thrown = true;
    }
}
