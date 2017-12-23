<?php
/**
 * \PHPCompatibility\Sniffs\PHP\RemovedConstantsSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\AbstractRemovedFeatureSniff;

/**
 * \PHPCompatibility\Sniffs\PHP\RemovedConstantsSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class RemovedConstantsSniff extends AbstractRemovedFeatureSniff
{

    /**
     * A list of removed PHP Constants.
     *
     * The array lists : version number with false (deprecated) or true (removed).
     * If's sufficient to list the first version where the constant was deprecated/removed.
     *
     * Note: PHP Constants are case-sensitive!
     *
     * @var array(string => array(string => bool|string|null))
     */
    protected $removedConstants = array(
        // Disabled since PHP 5.3.0 due to thread safety issues.
        'FILEINFO_COMPRESS' => array(
            '5.3' => true,
        ),

        'CURLOPT_CLOSEPOLICY' => array(
            '5.6' => true,
        ),
        'CURLCLOSEPOLICY_LEAST_RECENTLY_USED' => array(
            '5.6' => true,
        ),
        'CURLCLOSEPOLICY_LEAST_TRAFFIC' => array(
            '5.6' => true,
        ),
        'CURLCLOSEPOLICY_SLOWEST' => array(
            '5.6' => true,
        ),
        'CURLCLOSEPOLICY_CALLBACK' => array(
            '5.6' => true,
        ),
        'CURLCLOSEPOLICY_OLDEST' => array(
            '5.6' => true,
        ),

        'PGSQL_ATTR_DISABLE_NATIVE_PREPARED_STATEMENT' => array(
            '7.0' => true,
        ),

        'INTL_IDNA_VARIANT_2003' => array(
            '7.2' => false,
        ),

        'MCRYPT_MODE_ECB' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_MODE_CBC' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_MODE_CFB' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_MODE_OFB' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_MODE_NOFB' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_MODE_STREAM' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_ENCRYPT' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_DECRYPT' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_DEV_RANDOM' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_DEV_URANDOM' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_RAND' => array(
            '7.1' => false,
            '7.2' => true,
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
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens       = $phpcsFile->getTokens();
        $constantName = $tokens[$stackPtr]['content'];

        if (isset($this->removedConstants[$constantName]) === false) {
            return;
        }

        if ($this->isUseOfGlobalConstant($phpcsFile, $stackPtr) === false) {
            return;
        }

        $itemInfo = array(
            'name' => $constantName,
        );
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);

    }//end process()


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->removedConstants[$itemInfo['name']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The constant "%s" is ';
    }

}//end class
