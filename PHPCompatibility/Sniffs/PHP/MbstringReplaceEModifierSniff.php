<?php
/**
 * PHPCompatibility_Sniffs_PHP_MbstringReplaceEModifierSniff.
 *
 * PHP version 7.1
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_MbstringReplaceEModifierSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_MbstringReplaceEModifierSniff extends PHPCompatibility_Sniff
{

    /**
     * Functions to check for.
     *
     * Key is the function name, value the parameter position of the options parameter.
     *
     * @var array
     */
    protected $functions = array(
        'mb_ereg_replace'      => 4,
        'mb_eregi_replace'     => 4,
        'mb_regex_set_options' => 1,
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING);
    }//end register()


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
        if ($this->supportsAbove('7.1') === false) {
            return;
        }

        $tokens         = $phpcsFile->getTokens();
        $functionNameLc = strtolower($tokens[$stackPtr]['content']);

        // Bow out if not one of the functions we're targetting.
        if ( isset($this->functions[$functionNameLc]) === false ) {
            return;
        }

        // Get the options parameter in the function call.
        $optionsParam = $this->getFunctionCallParameter($phpcsFile, $stackPtr, $this->functions[$functionNameLc]);
        if ($optionsParam === false) {
            return;
        }

        $stringToken = $phpcsFile->findNext(T_CONSTANT_ENCAPSED_STRING, $optionsParam['start'], $optionsParam['end'] + 1);
        if ($stringToken === false) {
            // No string token found in the options parameter, so skip it (e.g. variable passed in).
            return;
        }

        /**
         * Get the content of any string tokens in the options parameter and remove the quotes.
         */
        $options = $this->stripQuotes($tokens[$stringToken]['content']);
        if ($stringToken !== $optionsParam['end']) {
            while ($stringToken = $phpcsFile->findNext(T_CONSTANT_ENCAPSED_STRING, $stringToken + 1, $optionsParam['end'] + 1)) {
                if ($tokens[$stringToken]['code'] === T_CONSTANT_ENCAPSED_STRING) {
                    $options .= $this->stripQuotes($tokens[$stringToken]['content']);
                }
            }
        }

        if (strpos($options, 'e') !== false) {
            $error = 'The Mbstring regex "e" modifier is deprecated since PHP 7.1.';

            // The alternative mb_ereg_replace_callback() function is only available since 5.4.1
            if ($this->supportsBelow('5.4.1') === false) {
                $error .= ' Use mb_ereg_replace_callback() instead (PHP 5.4.1+).';
            }

            $phpcsFile->addError($error, $stackPtr, 'Found');
        }

    }//end process()

}//end class
