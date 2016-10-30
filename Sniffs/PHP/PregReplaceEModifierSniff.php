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

        /*
         * The first parameter might be build up of a combination of strings,
         * variables and function calls. We are only concerned with the strings.
         */
        $regex = '';
        for ($i = $firstParam['start']; $i <= $firstParam['end']; $i++ ) {
            if (in_array($tokens[$i]['code'], PHP_CodeSniffer_Tokens::$stringTokens, true) === true) {
                $regex .= $this->stripQuotes($tokens[$i]['content']);
            }
        }
        // Deal with multi-line regexes which were broken up in several string tokens.
        $regex = $this->stripQuotes($regex);

        if ($regex === '') {
            // No string token found in the first parameter, so skip it (e.g. if variable passed in).
            return;
        }

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
                $error     = '%s() - /e modifier is deprecated since PHP 5.5';
                $isError   = false;
                $errorCode = 'Deprecated';
                $data      = array($functionName);

                if ($this->supportsAbove('7.0')) {
                    $error    .= ' and removed since PHP 7.0';
                    $isError   = true;
                    $errorCode = 'Removed';
                }

                if ($isError === true) {
                    $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
                } else {
                    $phpcsFile->addWarning($error, $stackPtr, $errorCode, $data);
                }
            }
        }

    }//end process()

}//end class
