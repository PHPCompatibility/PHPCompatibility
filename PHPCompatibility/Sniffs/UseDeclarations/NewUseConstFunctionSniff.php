<?php
/**
 * \PHPCompatibility\Sniffs\UseDeclarations\NewUseConstFunctionSniff.
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\UseDeclarations;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * \PHPCompatibility\Sniffs\UseDeclarations\NewUseConstFunctionSniff.
 *
 * The use operator has been extended to support importing functions and
 * constants in addition to classes. This is achieved via the use function
 * and use const constructs, respectively.
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewUseConstFunctionSniff extends Sniff
{

    /**
     * A list of keywords that can follow use statements.
     *
     * @var array(string => string)
     */
    protected $validUseNames = array(
        'const'    => true,
        'function' => true,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(\T_USE);
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
        if ($this->supportsBelow('5.5') !== true) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextNonEmpty === false) {
            // Live coding.
            return;
        }

        if (isset($this->validUseNames[strtolower($tokens[$nextNonEmpty]['content'])]) === false) {
            // Not a `use const` or `use function` statement.
            return;
        }

        // `use const` and `use function` have to be followed by the function/constant name.
        $functionOrConstName = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonEmpty + 1), null, true);
        if ($functionOrConstName === false
            // Identifies as T_AS or T_STRING, this covers both.
            || ($tokens[$functionOrConstName]['content'] === 'as'
            || $tokens[$functionOrConstName]['code'] === \T_COMMA)
        ) {
            // Live coding or incorrect use of reserved keyword, but that is
            // covered by the ForbiddenNames sniff.
            return;
        }

        // Still here ? In that case we have encountered a `use const` or `use function` statement.
        $phpcsFile->addError(
            'Importing functions and constants through a "use" statement is not supported in PHP 5.5 or lower.',
            $nextNonEmpty,
            'Found'
        );
    }
}
