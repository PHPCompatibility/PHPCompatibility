<?php
/**
 * \PHPCompatibility\ComplexVersionInterface.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility;

/**
 * \PHPCompatibility\ComplexVersionInterface.
 *
 * Interface to be implemented by sniffs using a multi-dimensional array of
 * PHP features (functions, classes etc) being sniffed for with version
 * information in sub-arrays.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
interface ComplexVersionInterface
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
    public function handleFeature(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, array $itemInfo);


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo);


    /**
     * Retrieve the relevant detail (version) information for use in an error message.
     *
     * @param array $itemArray Version and other information about the item.
     * @param array $itemInfo  Base information about the item.
     *
     * @return array
     */
    public function getErrorInfo(array $itemArray, array $itemInfo);


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
    public function addError(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, array $itemInfo, array $errorInfo);


}//end interface
