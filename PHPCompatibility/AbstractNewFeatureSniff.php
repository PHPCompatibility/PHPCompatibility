<?php
/**
 * \PHPCompatibility\AbstractNewFeatureSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility;

/**
 * \PHPCompatibility\AbstractNewFeatureSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
abstract class AbstractNewFeatureSniff extends AbstractComplexVersionSniff
{


    /**
     * Determine whether an error/warning should be thrown for an item based on collected information.
     *
     * @param array $errorInfo Detail information about an item.
     *
     * @return bool
     */
    protected function shouldThrowError(array $errorInfo)
    {
        return ($errorInfo['not_in_version'] !== '');
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
        $errorInfo = array(
            'not_in_version' => '',
            'error'          => true,
        );

        $versionArray = $this->getVersionArray($itemArray);

        if (empty($versionArray) === false) {
            foreach ($versionArray as $version => $present) {
                if ($errorInfo['not_in_version'] === '' && $present === false
                    && $this->supportsBelow($version) === true
                ) {
                    $errorInfo['not_in_version'] = $version;
                }
            }
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
        return '%s is not present in PHP version %s or earlier';
    }


    /**
     * Generates the error or warning for this item.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the relevant token in
     *                                         the stack.
     * @param array                 $itemInfo  Base information about the item.
     * @param array                 $errorInfo Array with detail (version) information
     *                                         relevant to the item.
     *
     * @return void
     */
    public function addError(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, array $itemInfo, array $errorInfo)
    {
        $itemName = $this->getItemName($itemInfo, $errorInfo);
        $error    = $this->getErrorMsgTemplate();

        $errorCode = $this->stringToErrorCode($itemName) . 'Found';
        $data      = array(
            $itemName,
            $errorInfo['not_in_version'],
        );

        $error = $this->filterErrorMsg($error, $itemInfo, $errorInfo);
        $data  = $this->filterErrorData($data, $itemInfo, $errorInfo);

        $this->addMessage($phpcsFile, $error, $stackPtr, $errorInfo['error'], $errorCode, $data);
    }


}//end class
