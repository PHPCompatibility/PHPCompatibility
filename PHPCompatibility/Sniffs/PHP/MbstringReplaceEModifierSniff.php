<?php
/**
 * \PHPCompatibility\Sniffs\PHP\MbstringReplaceEModifierSniff.
 *
 * PHP version 7.1
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\AbstractFunctionCallParameterSniff;

/**
 * \PHPCompatibility\Sniffs\PHP\MbstringReplaceEModifierSniff.
 *
 * PHP version 7.1
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class MbstringReplaceEModifierSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * Key is the function name, value the parameter position of the options parameter.
     *
     * @var array
     */
    protected $targetFunctions = array(
        'mb_ereg_replace'      => 4,
        'mb_eregi_replace'     => 4,
        'mb_regex_set_options' => 1,
    );


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        // Version used here should be the highest version from the `$newModifiers` array,
        // i.e. the last PHP version in which a new modifier was introduced.
        return ($this->supportsAbove('7.1') === false);
    }


    /**
     * Process the parameters of a matched function.
     *
     * This method has to be made concrete in child classes.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile    The file being scanned.
     * @param int                   $stackPtr     The position of the current token in the stack.
     * @param string                $functionName The token content (function name) which was matched.
     * @param array                 $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $tokens         = $phpcsFile->getTokens();
        $functionNameLc = strtolower($functionName);

        // Check whether the options parameter in the function call is passed.
        if (isset($parameters[$this->targetFunctions[$functionNameLc]]) === false) {
            return;
        }

        $optionsParam = $parameters[$this->targetFunctions[$functionNameLc]];

        $stringToken = $phpcsFile->findNext(\PHP_CodeSniffer_Tokens::$stringTokens, $optionsParam['start'], $optionsParam['end'] + 1);
        if ($stringToken === false) {
            // No string token found in the options parameter, so skip it (e.g. variable passed in).
            return;
        }

        $options = '';

        /*
         * Get the content of any string tokens in the options parameter and remove the quotes and variables.
         */
        for ($i = $stringToken; $i <= $optionsParam['end']; $i++) {
            if (in_array($tokens[$i]['code'], \PHP_CodeSniffer_Tokens::$stringTokens, true) === false) {
                continue;
            }

            $content = $this->stripQuotes($tokens[$i]['content']);
            if ($tokens[$i]['code'] === T_DOUBLE_QUOTED_STRING) {
                $content = $this->stripVariables($content);
            }
            $content = trim($content);

            if (empty($content) === false) {
                $options .= $content;
            }
        }

        if (strpos($options, 'e') !== false) {
            $error = 'The Mbstring regex "e" modifier is deprecated since PHP 7.1.';

            // The alternative mb_ereg_replace_callback() function is only available since 5.4.1.
            if ($this->supportsBelow('5.4.1') === false) {
                $error .= ' Use mb_ereg_replace_callback() instead (PHP 5.4.1+).';
            }

            $phpcsFile->addWarning($error, $stackPtr, 'Deprecated');
        }
    }
}//end class
