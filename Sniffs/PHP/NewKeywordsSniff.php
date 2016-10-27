<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewKeywordsSniff.
 *
 * PHP version 5.5
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2013 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewClassesSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @version   1.0.0
 * @copyright 2013 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_NewKeywordsSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of new keywords, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the last version which did not contain the keyword.
     *
     * Description will be used as part of the error message.
     * Condition is an array of valid scope conditions to check for.
     * If you need a condition of a different type, make sure to add the appropriate
     * logic for it as well as this will not resolve itself automatically.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newKeywords = array(
                                        'T_HALT_COMPILER' => array(
                                            '5.0' => false,
                                            '5.1' => true,
                                            'description' => '"__halt_compiler" keyword'
                                        ),
                                        'T_CONST' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '"const" keyword',
                                            'condition' => array(T_CLASS), // Keyword is only new when not in class context.
                                        ),
                                        'T_CALLABLE' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            'description' => '"callable" keyword',
                                            'content' => 'callable',
                                        ),
                                        'T_DIR' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '__DIR__ magic constant',
                                            'content' => '__DIR__',
                                        ),
                                        'T_GOTO' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '"goto" keyword',
                                            'content' => 'goto',
                                        ),
                                        'T_INSTEADOF' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            'description' => '"insteadof" keyword (for traits)',
                                            'content' => 'insteadof',
                                        ),
                                        'T_NAMESPACE' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '"namespace" keyword',
                                            'content' => 'namespace',
                                        ),
                                        'T_NS_C' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '__NAMESPACE__ magic constant',
                                            'content' => '__NAMESPACE__',
                                        ),
                                        'T_USE' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => '"use" keyword (for traits/namespaces/anonymous functions)'
                                        ),
                                        'T_TRAIT' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            'description' => '"trait" keyword',
                                            'content' => 'trait',
                                        ),
                                        'T_TRAIT_C' => array(
                                            '5.3' => false,
                                            '5.4' => true,
                                            'description' => '__TRAIT__ magic constant',
                                            'content' => '__TRAIT__',
                                        ),
                                        'T_YIELD' => array(
                                            '5.4' => false,
                                            '5.5' => true,
                                            'description' => '"yield" keyword (for generators)',
                                            'content' => 'yield',
                                        ),
                                        'T_FINALLY' => array(
                                            '5.4' => false,
                                            '5.5' => true,
                                            'description' => '"finally" keyword (in exception handling)',
                                            'content' => 'finally',
                                        ),
                                        'T_START_NOWDOC' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => 'nowdoc functionality',
                                        ),
                                        'T_END_NOWDOC' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => 'nowdoc functionality',
                                        ),
                                    );

    /**
     * Translation table for T_STRING tokens.
     *
     * Will be set up from the register() method.
     *
     * @var array(string => string)
     */
    protected $translateContentToToken = array();


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $tokens    = array();
        $translate = array();
        foreach ($this->newKeywords as $token => $versions) {
            if (defined($token)) {
                $tokens[] = constant($token);
            }
            if (isset($versions['content'])) {
                $translate[$versions['content']] = $token;
            }
        }

        /*
         * Deal with tokens not recognized by the PHP version the sniffer is run
         * under and (not correctly) compensated for by PHPCS.
         */
        if (empty($translate) === false) {
            $this->translateContentToToken = $translate;
            $tokens[] = T_STRING;
        }

        return $tokens;

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $tokenType = $tokens[$stackPtr]['type'];

        // Translate T_STRING token if necessary.
        if ($tokens[$stackPtr]['type'] === 'T_STRING') {
            $content = $tokens[$stackPtr]['content'];
            if (isset($this->translateContentToToken[$content]) === false) {
                // Not one of the tokens we're looking for.
                return;
            }

            $tokenType = $this->translateContentToToken[$content];
        }

        if (isset($this->newKeywords[$tokenType]) === false) {
            return;
        }

        $nextToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        $prevToken = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);


        // Skip attempts to use keywords as functions or class names - the former
        // will be reported by ForbiddenNamesAsInvokedFunctionsSniff, whilst the
        // latter will be (partially) reported by the ForbiddenNames sniff.
        // Either type will result in false-positives when targetting lower versions
        // of PHP where the name was not reserved, unless we explicitly check for
        // them.
        if (
            ($nextToken === false || $tokens[$nextToken]['type'] !== 'T_OPEN_PARENTHESIS')
            &&
            ($prevToken === false || $tokens[$prevToken]['type'] !== 'T_CLASS' || $tokens[$prevToken]['type'] !== 'T_INTERFACE')
        ) {
            // Skip based on token scope condition.
            if (isset($this->newKeywords[$tokenType]['condition'])) {
                $condition = $this->newKeywords[$tokenType]['condition'];
                if ($this->tokenHasScope($phpcsFile, $stackPtr, $condition) === true) {
                    return;
                }
            }

            $this->addError($phpcsFile, $stackPtr, $tokenType);
        }
    }//end process()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                  $stackPtr    The position of the function
     *                                          in the token array.
     * @param string               $keywordName The name of the keyword.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $keywordName)
    {
        $notInVersion = '';
        foreach ($this->newKeywords[$keywordName] as $version => $present) {
            if (in_array($version, array('condition', 'description', 'content'), true)) {
                continue;
            }

            if ($present === false && $this->supportsBelow($version)) {
                $notInVersion = $version;
            }
        }

        if ($notInVersion !== '') {
            $error = '%s is not present in PHP version %s or earlier';
            $data  = array(
                $this->newKeywords[$keywordName]['description'],
                $notInVersion,
            );
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
        }

    }//end addError()

}//end class
