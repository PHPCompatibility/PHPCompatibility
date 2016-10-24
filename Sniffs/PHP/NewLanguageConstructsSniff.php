<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewLanguageConstructsSniff.
 *
 * PHP version 5.5
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2013 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewLanguageConstructsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @version   1.0.0
 * @copyright 2013 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_NewLanguageConstructsSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of new language constructs, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newConstructs = array(
                                        'T_NS_SEPARATOR' => array(
                                            '5.2' => false,
                                            '5.3' => true,
                                            'description' => 'the \ operator (for namespaces)'
                                        ),
                                        'T_POW' => array(
                                            '5.5' => false,
                                            '5.6' => true,
                                            'description' => 'power operator (**)'
                                        ), // identified in PHPCS 1.5 as T_MULTIPLY + T_MULTIPLY
                                        'T_POW_EQUAL' => array(
                                            '5.5' => false,
                                            '5.6' => true,
                                            'description' => 'power assignment operator (**=)'
                                        ), // identified in PHPCS 1.5 as T_MULTIPLY + T_MUL_EQUAL
                                        'T_ELLIPSIS' => array(
                                            '5.5' => false,
                                            '5.6' => true,
                                            'description' => 'variadic functions using ...'
                                        ),
                                        'T_SPACESHIP' => array(
                                            '5.6' => false,
                                            '7.0' => true,
                                            'description' => 'spaceship operator (<=>)'
                                        ), // identified in PHPCS 1.5 as T_IS_SMALLER_OR_EQUAL + T_GREATER_THAN
                                        'T_COALESCE' => array(
                                            '5.6' => false,
                                            '7.0' => true,
                                            'description' => 'null coalescing operator (??)'
                                        ), // identified in PHPCS 1.5 as T_INLINE_THEN + T_INLINE_THEN
                                    );


    /**
     * A list of new language constructs which are not recognized in PHPCS 1.x.
     *
     * The array lists an alternative token to listen for.
     *
     * @var array(string => int)
     */
    protected $newConstructsPHPCSCompat = array(
                                        'T_POW'       => T_MULTIPLY,
                                        'T_POW_EQUAL' => T_MUL_EQUAL,
                                        'T_SPACESHIP' => T_GREATER_THAN,
                                        'T_COALESCE'  => T_INLINE_THEN,
                                    );

    /**
     * Translation table for PHPCS 1.x tokens.
     *
     * The 'before' index lists the token which would have to be directly before the
     * token found for it to be one of the new language constructs.
     * The 'real_token' index indicates which language construct was found in that case.
     *
     * {@internal 'before' was choosen rather than 'after' as that allowed for a 1-on-1
     * translation list with the current tokens.}}
     *
     * @var array(string => array(string => string))
     */
    protected $PHPCSCompatTranslate = array(
                                        'T_MULTIPLY' => array(
                                            'before' => 'T_MULTIPLY',
                                            'real_token' => 'T_POW',
                                        ),
                                        'T_MUL_EQUAL' => array(
                                            'before' => 'T_MULTIPLY',
                                            'real_token' => 'T_POW_EQUAL',
                                        ),
                                        'T_GREATER_THAN' => array(
                                            'before' => 'T_IS_SMALLER_OR_EQUAL',
                                            'real_token' => 'T_SPACESHIP',
                                        ),
                                        'T_INLINE_THEN' => array(
                                            'before' => 'T_INLINE_THEN',
                                            'real_token' => 'T_COALESCE',
                                        ),
                                    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $tokens = array();
        foreach ($this->newConstructs as $token => $versions) {
            if (defined($token)) {
                $tokens[] = constant($token);
            }
            else if(isset($this->newConstructsPHPCSCompat[$token])) {
                $tokens[] = $this->newConstructsPHPCSCompat[$token];
            }
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

        // Translate pre-PHPCS 2.0 tokens for new constructs to the actual construct.
        if (isset($this->newConstructs[$tokenType]) === false) {
            if (
                isset($this->PHPCSCompatTranslate[$tokenType])
                &&
                $tokens[$stackPtr - 1]['type'] === $this->PHPCSCompatTranslate[$tokenType]['before']
            ) {
                $tokenType = $this->PHPCSCompatTranslate[$tokenType]['real_token'];
            }
        }

        // If the translation did not yield one of the tokens we are looking for, bow out.
        if (isset($this->newConstructs[$tokenType]) === false) {
            return;
        }

        $errorInfo = $this->getErrorInfo($tokenType);

        if ($errorInfo['not_in_version'] !== '') {
            $this->addError($phpcsFile, $stackPtr, $tokenType, $errorInfo);
        }

    }//end process()


    /**
     * Retrieve the relevant (version) information for the error message.
     *
     * @param string $tokenType The token type representing the language construct.
     *
     * @return array
     */
    protected function getErrorInfo($tokenType)
    {
        $errorInfo  = array(
            'description'    => '',
            'not_in_version' => '',
        );

        $errorInfo['description'] = $this->newConstructs[$tokenType]['description'];

        foreach ($this->newConstructs[$tokenType] as $version => $present) {
            if ($present === false && $this->supportsBelow($version)) {
                $errorInfo['not_in_version'] = $version;
            }
        }

        return $errorInfo;

    }//end getErrorInfo()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the keyword token
     *                                        in the token array.
     * @param string               $tokenType The token type representing the language construct.
     * @param array                $errorInfo Array with details about when the
     *                                        language construct was not (yet) available.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $tokenType, $errorInfo)
    {
        $error     = '%s is not present in PHP version %s or earlier';
        $errorCode = $this->stringToErrorCode($tokenType) . 'Found';
        $data      = array(
            $errorInfo['description'],
            $errorInfo['not_in_version'],
        );

        $phpcsFile->addError($error, $stackPtr, $errorCode, $data);

    }//end addError()

}//end class
