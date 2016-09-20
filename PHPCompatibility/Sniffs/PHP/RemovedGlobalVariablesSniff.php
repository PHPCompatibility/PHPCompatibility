<?php
/**
 * PHPCompatibility_Sniffs_PHP_RemovedGlobalVariablesSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */

/**
 * PHPCompatibility_Sniffs_PHP_RemovedGlobalVariablesSniff.
 *
 * Discourages the use of removed global variables. Suggests alternative extensions if available
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 */
class PHPCompatibility_Sniffs_PHP_RemovedGlobalVariablesSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of removed global variables with their alternative, if any
     * Array codes : 0 = removed/unavailable, -1 = deprecated, 1 = active
     *
     * @var array(string|null)
     */
    protected $removedGlobalVariables = array(
        'HTTP_RAW_POST_DATA' => array(
            '5.5' => 1,
            '5.6' => -1,
            '7.0' => 0,
            'alternative' => 'php://input'
        ),
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_VARIABLE);

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
        $tokens    = $phpcsFile->getTokens();
        $varNameUc = strtoupper(substr($tokens[$stackPtr]['content'], 1));

        if (isset($this->removedGlobalVariables[$varNameUc]) === false) {
            return;
        }

        $versionList = $this->removedGlobalVariables[$varNameUc];

        $error = '';
        $isError = false;
        foreach ($versionList as $version => $status) {
            if ($version !== 'alternative' && ($status === -1 || $status === 0)) {
                if ($this->supportsAbove($version)) {
                    switch ($status) {
                        case -1:
                            $error .= 'deprecated since PHP ' . $version . ' and ';
                            break;
                        case 0:
                            $isError = true;
                            $error .= 'removed since PHP ' . $version . ' and ';
                            break 2;
                    }
                }
            }
        }
        if (strlen($error) > 0) {
            $error = "Global variable '%s' is " . $error;
            $error = substr($error, 0, strlen($error) - 5);
            $data  = array(
                $tokens[$stackPtr]['content']
            );
            if (isset($versionList['alternative'])) {
                $error .= ' - use %s instead.';
                $data[] = $versionList['alternative'];
            }
            if ($isError === true) {
                $phpcsFile->addError($error, $stackPtr, 'Found', $data);
            } else {
                $phpcsFile->addWarning($error, $stackPtr, 'Found', $data);
            }
        }

    }//end process()

}//end class
