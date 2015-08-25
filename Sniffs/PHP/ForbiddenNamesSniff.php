<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenNamesSniff.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenNamesSniff.
 *
 * Prohibits the use of reserved keywords as class, function, namespace or constant names
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenNamesSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of keywords that can not be used as function, class and namespace name or constant name
     * Mentions since which version it's not allowed
     *
     * @var array(string => string)
     */
    protected $invalidNames = array(
        'abstract' => '5.0',
        'and' => 'all',
        'array' => 'all',
        'as' => 'all',
        'break' => 'all',
        'callable' => '5.4',
        'case' => 'all',
        'catch' => '5.0',
        'class' => 'all',
        'clone' => '5.0',
        'const' => 'all',
        'continue' => 'all',
        'declare' => 'all',
        'default' => 'all',
        'do' => 'all',
        'else' => 'all',
        'elseif' => 'all',
        'enddeclare' => 'all',
        'endfor' => 'all',
        'endforeach' => 'all',
        'endif' => 'all',
        'endswitch' => 'all',
        'endwhile' => 'all',
        'extends' => 'all',
        'final' => '5.0',
        'finally' => '5.5',
        'for' => 'all',
        'foreach' => 'all',
        'function' => 'all',
        'global' => 'all',
        'goto' => '5.3',
        'if' => 'all',
        'implements' => '5.0',
        'interface' => '5.0',
        'instanceof' => '5.0',
        'insteadof' => '5.4',
        'namespace' => '5.3',
        'new' => 'all',
        'or' => 'all',
        'private' => '5.0',
        'protected' => '5.0',
        'public' => '5.0',
        'static' => 'all',
        'switch' => 'all',
        'throw' => '5.0',
        'trait' => '5.4',
        'try' => '5.0',
        'use' => 'all',
        'var' => 'all',
        'while' => 'all',
        'xor' => 'all',
        '__class__' => 'all',
        '__dir__' => '5.3',
        '__file__' => 'all',
        '__function__' => 'all',
        '__method__' => 'all',
        '__namespace__' => '5.3',
    );

    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    protected $error = true;

    /**
     * targetedTokens
     *
     * @var array
     */
    protected $targetedTokens = array(T_CLASS, T_FUNCTION, T_NAMESPACE, T_STRING, T_CONST, T_USE, T_AS, T_EXTENDS, T_TRAIT);

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return $this->targetedTokens;
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
        $tokens = $phpcsFile->getTokens();

        /**
         * We distinguish between the class, function and namespace names or the define statements
         */
        if ($tokens[$stackPtr]['type'] == 'T_STRING') {
            $this->processString($phpcsFile, $stackPtr, $tokens);
        } else {
            $this->processNonString($phpcsFile, $stackPtr, $tokens);
        }
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     * @param array                $tokens    The stack of tokens that make up
     *                                        the file.
     *
     * @return void
     */
    public function processNonString(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
    {
        if (in_array(strtolower($tokens[$stackPtr + 2]['content']), array_keys($this->invalidNames)) === false) {
            return;
        }

        if ($this->supportsAbove($this->invalidNames[strtolower($tokens[$stackPtr + 2]['content'])])) {
            $error = "Function name, class name, namespace name or constant name can not be reserved keyword '" . $tokens[$stackPtr + 2]['content'] . "' (since version " . $this->invalidNames[strtolower($tokens[$stackPtr + 2]['content'])] . ")";
            $phpcsFile->addError($error, $stackPtr);
        }

    }//end process()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     * @param array                $tokens    The stack of tokens that make up
     *                                        the file.
     *
     * @return void
     */
    public function processString(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
    {
        // Special case for 5.3 where we want to find usage of traits, but
        // trait is not a token.
        if ($tokens[$stackPtr]['content'] == 'trait') {
            return $this->processNonString($phpcsFile, $stackPtr, $tokens);
        }

        // Look for any define/defined token (both T_STRING ones, blame Tokenizer)
        if ($tokens[$stackPtr]['content'] != 'define' && $tokens[$stackPtr]['content'] != 'defined') {
            return;
        }

        // Look for the end of the define/defined
        $closingParenthesis = $phpcsFile->findNext(T_CLOSE_PARENTHESIS, $stackPtr);
        if ($closingParenthesis === false) {
            return;
        }

        // Look for define name between current position and end of define/defined
        $defineContent = $phpcsFile->findNext(T_CONSTANT_ENCAPSED_STRING, $stackPtr, $closingParenthesis);
        if ($defineContent === false) {
            return;
        }

        foreach ($this->invalidNames as $key => $value) {
            if (substr(strtolower($tokens[$defineContent]['content']), 1, -1) == $key) {
                $error = "Function name, class name, namespace name or constant name can not be reserved keyword '" . $key . "' (since version " . $value . ")";
                $phpcsFile->addError($error, $stackPtr);
            }
        }
    }//end process()

}//end class

?>
