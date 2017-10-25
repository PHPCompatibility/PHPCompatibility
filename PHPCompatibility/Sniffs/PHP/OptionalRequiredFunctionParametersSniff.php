<?php
/**
 * \PHPCompatibility\Sniffs\PHP\OptionalRequiredFunctionParametersSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniffs\PHP\RequiredOptionalFunctionParametersSniff;

/**
 * \PHPCompatibility\Sniffs\PHP\OptionalRequiredFunctionParametersSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class OptionalRequiredFunctionParametersSniff extends RequiredOptionalFunctionParametersSniff
{

    /**
     * A list of function parameters, which were optional in older versions and became required later on.
     *
     * The array lists : version number with true (required) and false (optional use deprecated).
     *
     * The index is the location of the parameter in the parameter list, starting at 0 !
     * If's sufficient to list the last version in which the parameter was not yet required.
     *
     * @var array
     */
    protected $functionParameters = array(
        'parse_str' => array(
            1 => array(
                'name' => 'result',
                '7.2'  => false,
            ),
        ),
    );


    /**
     * Determine whether an error/warning should be thrown for an item based on collected information.
     *
     * @param array $errorInfo Detail information about an item.
     *
     * @return bool
     */
    protected function shouldThrowError(array $errorInfo)
    {
        return ($errorInfo['optionalDeprecated'] !== '' || $errorInfo['optionalRemoved'] !== '');
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
            'paramName'          => '',
            'optionalDeprecated' => '',
            'optionalRemoved'    => '',
            'error'              => false,
        );

        $versionArray = $this->getVersionArray($itemArray);

        if (empty($versionArray) === false) {
            foreach ($versionArray as $version => $required) {
                if ($this->supportsAbove($version) === true) {
                    if ($required === true && $errorInfo['optionalRemoved'] === '') {
                        $errorInfo['optionalRemoved'] = $version;
                        $errorInfo['error']           = true;
                    } elseif ($errorInfo['optionalDeprecated'] === '') {
                        $errorInfo['optionalDeprecated'] = $version;
                    }
                }
            }
        }

        $errorInfo['paramName'] = $itemArray['name'];

        return $errorInfo;

    }//end getErrorInfo()


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The "%s" parameter for function %s() is missing. Passing this parameter is no longer optional. The optional nature of the parameter is ';
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
        $error     = $this->getErrorMsgTemplate();
        $errorCode = $this->stringToErrorCode($itemInfo['name'].'_'.$errorInfo['paramName']);
        $data      = array(
            $errorInfo['paramName'],
            $itemInfo['name'],
        );

        if ($errorInfo['optionalDeprecated'] !== '') {
            $error     .= 'deprecated since PHP %s and ';
            $errorCode .= 'Soft';
            $data[]     = $errorInfo['optionalDeprecated'];
        }

        if ($errorInfo['optionalRemoved'] !== '') {
            $error     .= 'removed since PHP %s and ';
            $errorCode .= 'Hard';
            $data[]     = $errorInfo['optionalRemoved'];
        }

        // Remove the last 'and' from the message.
        $error      = substr($error, 0, (strlen($error) - 5));
        $errorCode .= 'Required';

        $this->addMessage($phpcsFile, $error, $stackPtr, $errorInfo['error'], $errorCode, $data);

    }//end addError()


}//end class
