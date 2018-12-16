<?php
/**
 * \PHPCompatibility\Sniffs\ParameterValues\ForbiddenGetClassNullSniff.
 *
 * PHP version 7.2
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\ParameterValues\ForbiddenGetClassNullSniff.
 *
 * Detect: Passing `null` to get_class() is no longer allowed as of PHP 7.2.
 * This will now result in an E_WARNING being thrown.
 *
 * PHP version 7.2
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class ForbiddenGetClassNullSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @var array
     */
    protected $targetFunctions = array(
        'get_class' => true,
    );


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return ($this->supportsAbove('7.2') === false);
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

        if ($parameters[1]['raw'] !== 'null') {
            return;
        }

        $phpcsFile->addError(
            'Passing "null" as the $object to get_class() is not allowed since PHP 7.2.',
            $parameters[1]['start'],
            'Found'
        );
    }
}
