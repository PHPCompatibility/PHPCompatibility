<?php

class PHPCompatibility_Sniffs_PHP_ShortArraySniff extends PHPCompatibility_Sniff
{
    /** @var array */
    protected $supportByVersion = array(
        '5.3' => false,
        '5.4' => true
    );

    /** @var array */
    protected $errorByForbiddenTokens = array(
        'T_OPEN_SHORT_ARRAY' => 'Short array syntax (open)',
        'T_CLOSE_SHORT_ARRAY' => 'Short array syntax (close)'
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG);
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
        $tokens = $phpcsFile->getTokens();

        foreach ($tokens as $currentToken) {
            if (!isset($this->errorByForbiddenTokens[$currentToken['type']])) {
                continue;
            }

            foreach ($this->supportByVersion as $version => $support) {
                if ($this->supportsBelow($version)) {
                    if ($support) {
                        continue;
                    }

                    $error = $this->errorByForbiddenTokens[$currentToken['type']] . ' is available since 5.4';
                    $phpcsFile->addError($error, $stackPtr);
                }
            }
        }
    }//end process()

}//end class
