<?php
/**
 * \PHPCompatibility\Sniffs\ParameterValues\RemovedIconvEncodingSniff.
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\ParameterValues\RemovedIconvEncodingSniff.
 *
 * Detect: "The iconv and mbstring configuration options related to encoding
 * have been deprecated in favour of default_charset."
 *
 * {@internal It is unclear which mbstring functions should be targetted, so for now,
 * only the iconv function is handled.}}
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class RemovedIconvEncodingSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @var array
     */
    protected $targetFunctions = array(
        'iconv_set_encoding' => true,
    );


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return ($this->supportsAbove('5.6') === false);
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

        $phpcsFile->addWarning(
            'All previously accepted values for the $type parameter of iconv_set_encoding() have been deprecated since PHP 5.6. Found %s',
            $parameters[1]['start'],
            'DeprecatedValueFound',
            $parameters[1]['raw']
        );
    }
}
