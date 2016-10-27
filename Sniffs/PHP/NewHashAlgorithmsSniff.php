<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewHashAlgorithmsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewHashAlgorithmsSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_NewHashAlgorithmsSniff extends PHPCompatibility_Sniff
{
    /**
     * A list of new hash algorithms, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the hash algorithm appears.
     *
     * @var array(string => array(string => bool))
     */
    protected $newAlgorithms = array(
        'md2' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'ripemd256' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'ripemd320' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'salsa10' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'salsa20' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'snefru256' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'sha224' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        'joaat' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'fnv132' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'fnv164' => array(
            '5.3' => false,
            '5.4' => true,
        ),
        'gost-crypto' => array(
            '5.5' => false,
            '5.6' => true,
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
        if (isset($this->newAlgorithms[$algo]) === false) {
            return;
        }

        // Check if the algorithm used is new.
        $errorInfo = $this->getErrorInfo($algo);

        if ($errorInfo['not_in_version'] !== '') {
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
            'not_in_version' => '',
        );

        foreach ($this->newAlgorithms[$algorithm] as $version => $present) {
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
        $error     = 'The %s hash algorithm is not present in PHP version %s or earlier ';
        $errorCode = $algorithm . 'Found';
        $data      = array(
            $algorithm,
            $errorInfo['not_in_version'],
        );

        $phpcsFile->addError($error, $stackPtr, $errorCode, $data);

    }//end addError()

}//end class
