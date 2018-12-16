<?php
/**
 * \PHPCompatibility\Sniffs\ParameterValues\RemovedSetlocaleStringSniff.
 *
 * PHP version 4.2
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\ParameterValues\RemovedSetlocaleStringSniff.
 *
 * Detect: Support for the category parameter passed as a string has been removed.
 * Only LC_* constants can be used as of this version [7.0.0].
 *
 * PHP version 4.2
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class RemovedSetlocaleStringSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @var array
     */
    protected $targetFunctions = array(
        'setlocale' => true,
    );


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return ($this->supportsAbove('4.2') === false);
    }


    /**
     * Process the parameters of a matched function.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile    The file being scanned.
     * @param int                   $stackPtr     The position of the current token in the stack.
     * @param string                $functionName The token content (function name) which was matched.
     * @param array                 $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        if (isset($parameters[1]) === false) {
            return;
        }

        $tokens      = $phpcsFile->getTokens();
        $targetParam = $parameters[1];

        for ($i = $targetParam['start']; $i <= $targetParam['end']; $i++) {
            if ($tokens[$i]['code'] !== T_CONSTANT_ENCAPSED_STRING
                && $tokens[$i]['code'] !== T_DOUBLE_QUOTED_STRING
            ) {
                continue;
            }

            $message   = 'Passing the $category as a string to setlocale() has been deprecated since PHP 4.2';
            $isError   = false;
            $errorCode = 'Deprecated';
            $data      = array($targetParam['raw']);

            if ($this->supportsAbove('7.0') === true) {
                $message  .= ' and is removed since PHP 7.0';
                $isError   = true;
                $errorCode = 'Removed';
            }

            $message .= '; Pass one of the LC_* constants instead. Found: %s';

            $this->addMessage($phpcsFile, $message, $i, $isError, $errorCode, $data);
            break;
        }
    }
}
