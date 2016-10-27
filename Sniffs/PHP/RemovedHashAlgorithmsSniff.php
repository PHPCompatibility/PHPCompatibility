<?php
/**
 * PHPCompatibility_Sniffs_PHP_RemovedHashAlgorithmsSniff.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_RemovedHashAlgorithmsSniff.
 *
 * Discourages the use of deprecated and removed hash algorithms.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_RemovedHashAlgorithmsSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of removed hash algorithms, which were present in older versions.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the hash algorithm was deprecated/removed.
     *
     * @var array(string => array(string => bool))
     */
    protected $removedAlgorithms = array(
        'salsa10' => array(
            '5.4' => true,
        ),
        'salsa20' => array(
            '5.4' => true,
        ),
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
        $algo = $this->getHashAlgorithmParameter($phpcsFile, $stackPtr);
        if (empty($algo) || is_string($algo) === false) {
            return;
        }

        // Bow out if not one of the algorithms we're targetting.
        if (isset($this->removedAlgorithms[$algo]) === false) {
            return;
        }

        // Check if the algorithm used is deprecated or removed.
        $errorInfo = $this->getErrorInfo($algo);

        if ($errorInfo['deprecated'] !== '' || $errorInfo['removed'] !== '') {
            $this->addError($phpcsFile, $stackPtr, $algo, $errorInfo);
        }

    }//end process()


    /**
     * Retrieve the relevant (version) information for the error message.
     *
     * @param string $algorithm The name of the algorithm.
     *
     * @return array
     */
    protected function getErrorInfo($algorithm)
    {
        $errorInfo  = array(
            'deprecated'  => '',
            'removed'     => '',
            'error'       => false,
        );

        foreach ($this->removedAlgorithms[$algorithm] as $version => $removed) {
            if ($this->supportsAbove($version)) {
                if ($removed === true && $errorInfo['removed'] === '') {
                    $errorInfo['removed'] = $version;
                    $errorInfo['error']   = true;
                } elseif ($errorInfo['deprecated'] === '') {
                    $errorInfo['deprecated'] = $version;
                }
            }
        }

        return $errorInfo;

    }//end getErrorInfo()


    /**
     * Generates the error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the function
     *                                        in the token array.
     * @param string               $algorithm The name of the algorithm.
     * @param array                $errorInfo Array with details about the versions
     *                                        in which the algorithm was deprecated
     *                                        and/or removed.
     *
     * @return void
     */
    protected function addError($phpcsFile, $stackPtr, $algorithm, $errorInfo)
    {
        $error     = 'The %s hash algorithm is ';
        $errorCode = $algorithm . 'Found';
        $data      = array($algorithm);

        if ($errorInfo['deprecated'] !== '') {
            $error .= 'deprecated since PHP version %s and ';
            $data[] = $errorInfo['deprecated'];
        }
        if ($errorInfo['removed'] !== '') {
            $error .= 'removed since PHP version %s and ';
            $data[] = $errorInfo['removed'];
        }

        // Remove the last 'and' from the message.
        $error = substr($error, 0, strlen($error) - 5);

        if ($errorInfo['error'] === true) {
            $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
        } else {
            $phpcsFile->addWarning($error, $stackPtr, $errorCode, $data);
        }

    }//end addError()

}//end class
