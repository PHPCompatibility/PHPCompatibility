<?php
/**
 * \PHPCompatibility\Sniffs\PHP\NewMagicMethodsSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\AbstractNewFeatureSniff;

/**
 * \PHPCompatibility\Sniffs\PHP\NewMagicMethodsSniff.
 *
 * Warns for non-magic behaviour of magic methods prior to becoming magic.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewMagicMethodsSniff extends AbstractNewFeatureSniff
{

    /**
     * A list of new magic methods, not considered magic in older versions.
     *
     * Method names in the array should be all *lowercase*.
     * The array lists : version number with false (not magic) or true (magic).
     * If's sufficient to list the first version where the method became magic.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newMagicMethods = array(
        '__get' => array(
            '4.4' => false,
            '5.0' => true,
        ),

        '__isset' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        '__unset' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        '__set_state' => array(
            '5.0' => false,
            '5.1' => true,
        ),

        '__callstatic' => array(
            '5.2' => false,
            '5.3' => true,
        ),
        '__invoke' => array(
            '5.2' => false,
            '5.3' => true,
        ),

        '__debuginfo' => array(
            '5.5' => false,
            '5.6' => true,
        ),

        // Special case - only became properly magical in 5.2.0,
        // before that it was only called for echo and print.
        '__tostring' => array(
            '5.1' => false,
            '5.2' => true,
            'message' => 'The method %s() was not truly magical in PHP version %s and earlier. The associated magic functionality will only be called when directly combined with echo or print.',
        ),
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_FUNCTION);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $functionName   = $phpcsFile->getDeclarationName($stackPtr);
        $functionNameLc = strtolower($functionName);

        if (isset($this->newMagicMethods[$functionNameLc]) === false) {
            return;
        }

        if ($this->inClassScope($phpcsFile, $stackPtr, false) === false) {
            return;
        }

        $itemInfo = array(
            'name'   => $functionName,
            'nameLc' => $functionNameLc,
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
        return $this->newMagicMethods[$itemInfo['nameLc']];
    }


    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * @return array
     */
    protected function getNonVersionArrayKeys()
    {
        return array('message');
    }


    /**
     * Retrieve the relevant detail (version) information for use in an error message.
     *
     * @param array $itemArray Version and other information about the item.
     * @param array $itemInfo  Base information about the item.
     *
     * @return array
     */
    public function getErrorInfo(array $itemArray, array $itemInfo)
    {
        $errorInfo            = parent::getErrorInfo($itemArray, $itemInfo);
        $errorInfo['error']   = false; // Warning, not error.
        $errorInfo['message'] = '';

        if (empty($itemArray['message']) === false) {
            $errorInfo['message'] = $itemArray['message'];
        }

        return $errorInfo;
    }


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The method %s() was not magical in PHP version %s and earlier. The associated magic functionality will not be invoked.';
    }


    /**
     * Allow for concrete child classes to filter the error message before it's passed to PHPCS.
     *
     * @param string $error     The error message which was created.
     * @param array  $itemInfo  Base information about the item this error message applied to.
     * @param array  $errorInfo Detail information about an item this error message applied to.
     *
     * @return string
     */
    protected function filterErrorMsg($error, array $itemInfo, array $errorInfo)
    {
        if ($errorInfo['message'] !== '') {
            $error = $errorInfo['message'];
        }

        return $error;
    }


}//end class
