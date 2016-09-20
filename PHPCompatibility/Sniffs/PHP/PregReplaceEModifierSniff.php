<?php
/**
 * PHPCompatibility_Sniffs_PHP_PregReplaceEModifierSniff.
 *
 * PHP version 5.6
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2014 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_PregReplaceEModifierSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @version   1.1.0
 * @copyright 2014 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_PregReplaceEModifierSniff extends PHPCompatibility_Sniff
{

    /**
     * Functions to check for.
     *
     * @var array
     */
    protected $functions = array(
        'preg_replace' => true,
        'preg_filter'  => true,
    );

    /**
     * Regex bracket delimiters.
     *
     * @var array
     */
    protected $doublesSeparators = array(
        '{' => '}',
        '[' => ']',
        '(' => ')',
        '<' => '>',
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
        if ($this->supportsAbove('5.5') === false) {
            return;
        }

        $tokens         = $phpcsFile->getTokens();
        $functionName   = $tokens[$stackPtr]['content'];
        $functionNameLc = strtolower($functionName);

        // Bow out if not one of the functions we're targetting.
        if ( isset($this->functions[$functionNameLc]) === false ) {
            return;
        }

        // Get the first parameter in the function call as that should contain the regex.
        $firstParam = $this->getFunctionCallParameter($phpcsFile, $stackPtr, 1);
        if ($firstParam === false) {
            return;
        }

        $stringToken = $phpcsFile->findNext(T_CONSTANT_ENCAPSED_STRING, $firstParam['start'], $firstParam['end'] + 1);
        if ($stringToken === false) {
            // No string token found in the first parameter, so skip it (e.g. if variable passed in).
            return;
        }

        /*
         * The first parameter might be build up of a combination of strings,
         * variables and function calls, but in that case, generally the start
         * and end will still be strings. And as that's all we're concerned with,
         * just use the raw content of the first parameter for further processing.
         */
        $regex = $this->stripQuotes($firstParam['raw']);

        $regexFirstChar = substr($regex, 0, 1);
        if (isset($this->doublesSeparators[$regexFirstChar])) {
            $regexEndPos = strrpos($regex, $this->doublesSeparators[$regexFirstChar]);
        }
        else {
            $regexEndPos = strrpos($regex, $regexFirstChar);
        }

        if($regexEndPos) {
            $modifiers = substr($regex, $regexEndPos + 1);

            if (strpos($modifiers, 'e') !== false) {
                if ($this->supportsAbove('7.0')) {
                    $error = '%s() - /e modifier is forbidden since PHP 7.0';
                } else {
                    $error = '%s() - /e modifier is deprecated since PHP 5.5';
                }
                $data = array($functionName);
                $phpcsFile->addError($error, $stackPtr, 'Found', $data);
            }
        }

    }//end process()

}//end class
