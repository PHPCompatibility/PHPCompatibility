<?php
/**
 * \PHPCompatibility\Sniffs\Constants\RemovedConstantsSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\Constants;

use PHPCompatibility\AbstractRemovedFeatureSniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\Constants\RemovedConstantsSniff.
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
        'MCRYPT_3DES' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_ARCFOUR_IV' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_ARCFOUR' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_BLOWFISH' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_CAST_128' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_CAST_256' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_CRYPT' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_DES' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_DES_COMPAT' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_ENIGMA' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_GOST' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_IDEA' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_LOKI97' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_MARS' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_PANAMA' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_RIJNDAEL_128' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_RIJNDAEL_192' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_RIJNDAEL_256' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_RC2' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_RC4' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_RC6' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_RC6_128' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_RC6_192' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_RC6_256' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_SAFER64' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_SAFER128' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_SAFERPLUS' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_SERPENT' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_SERPENT_128' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_SERPENT_192' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_SERPENT_256' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_SKIPJACK' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_TEAN' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_THREEWAY' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_TRIPLEDES' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_TWOFISH' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_TWOFISH128' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_TWOFISH192' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_TWOFISH256' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_WAKE' => array(
            '7.1' => false,
            '7.2' => true,
        ),
        'MCRYPT_XTEA' => array(
            '7.1' => false,
            '7.2' => true,
        ),

        'PHPDBG_FILE' => array(
            '7.3' => true,
        ),
        'PHPDBG_METHOD' => array(
            '7.3' => true,
        ),
        'PHPDBG_LINENO' => array(
            '7.3' => true,
        ),
        'PHPDBG_FUNC' => array(
            '7.3' => true,
        ),
        'FILTER_FLAG_SCHEME_REQUIRED' => array(
            '7.3' => false,
        ),
        'FILTER_FLAG_HOST_REQUIRED' => array(
            '7.3' => false,
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
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
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
    }


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
}
