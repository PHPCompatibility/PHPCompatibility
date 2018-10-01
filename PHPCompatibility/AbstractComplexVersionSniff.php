<?php
/**
 * \PHPCompatibility\AbstractComplexVersionSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility;

use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\AbstractComplexVersionSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
abstract class AbstractComplexVersionSniff extends Sniff implements ComplexVersionInterface
{


    /**
     * Handle the retrieval of relevant information and - if necessary - throwing of an
     * error/warning for an item.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the relevant token in
     *                                         the stack.
     * @param array                 $itemInfo  Base information about the item.
     *
     * @return void
     */
    public function handleFeature(File $phpcsFile, $stackPtr, array $itemInfo)
    {
        $itemArray = $this->getItemArray($itemInfo);
        $errorInfo = $this->getErrorInfo($itemArray, $itemInfo);

        if ($this->shouldThrowError($errorInfo) === true) {
            $this->addError($phpcsFile, $stackPtr, $itemInfo, $errorInfo);
        }
    }


    /**
     * Determine whether an error/warning should be thrown for an item based on collected information.
     *
     * @param array $errorInfo Detail information about an item.
     *
     * @return bool
     */
    abstract protected function shouldThrowError(array $errorInfo);


    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * @return array
     */
    protected function getNonVersionArrayKeys()
    {
        return array();
    }


    /**
     * Retrieve a subset of an item array containing only the array keys which
     * contain PHP version information.
     *
     * @param array $itemArray Version and other information about an item.
     *
     * @return array Array with only the version information.
     */
    protected function getVersionArray(array $itemArray)
    {
        return array_diff_key($itemArray, array_flip($this->getNonVersionArrayKeys()));
    }


    /**
     * Get the item name to be used for the creation of the error code and in the error message.
     *
     * @param array $itemInfo  Base information about the item.
     * @param array $errorInfo Detail information about an item.
     *
     * @return string
     */
    protected function getItemName(array $itemInfo, array $errorInfo)
    {
        return $itemInfo['name'];
    }


    /**
     * Get the error message template for a specific sniff.
     *
     * @return string
     */
    abstract protected function getErrorMsgTemplate();


    /**
     * Allow for concrete child classes to filter the error message before it's passed to PHPCS.
     *
     * @param string $error     The error message which was created.
     * @param array  $itemInfo  Base information about the item this error message applies to.
     * @param array  $errorInfo Detail information about an item this error message applies to.
     *
     * @return string
     */
    protected function filterErrorMsg($error, array $itemInfo, array $errorInfo)
    {
        return $error;
    }


    /**
     * Allow for concrete child classes to filter the error data before it's passed to PHPCS.
     *
     * @param array $data      The error data array which was created.
     * @param array $itemInfo  Base information about the item this error message applies to.
     * @param array $errorInfo Detail information about an item this error message applies to.
     *
     * @return array
     */
    protected function filterErrorData(array $data, array $itemInfo, array $errorInfo)
    {
        return $data;
    }
}
