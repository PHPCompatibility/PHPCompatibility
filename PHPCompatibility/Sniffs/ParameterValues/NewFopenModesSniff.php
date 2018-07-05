<?php
/**
 * \PHPCompatibility\Sniffs\ParameterValues\NewFopenModesSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\ParameterValues\NewFopenModesSniff.
 *
 * Detect: Changes in allowed values for the fopen() $mode parameter.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewFopenModesSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @var array
     */
    protected $targetFunctions = array(
        'fopen' => true,
    );


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        // Version used here should be (above) the highest version from the `newModes` control,
        // structure below, i.e. the last PHP version in which a new mode was introduced.
        return ($this->supportsBelow('7.1') === false);
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
        if (isset($parameters[2]) === false) {
            return;
        }

        $tokens      = $phpcsFile->getTokens();
        $targetParam = $parameters[2];
        $errors      = array();

        for ($i = $targetParam['start']; $i <= $targetParam['end']; $i++) {
            if ($tokens[$i]['code'] !== \T_CONSTANT_ENCAPSED_STRING) {
                continue;
            }

            if (strpos($tokens[$i]['content'], 'c+') !== false && $this->supportsBelow('5.2.5')) {
                $errors['cplusFound'] = array(
                    'c+',
                    '5.2.5',
                    $targetParam['raw'],
                );
            } elseif (strpos($tokens[$i]['content'], 'c') !== false && $this->supportsBelow('5.2.5')) {
                $errors['cFound'] = array(
                    'c',
                    '5.2.5',
                    $targetParam['raw'],
                );
            }

            if (strpos($tokens[$i]['content'], 'e') !== false && $this->supportsBelow('7.0.15')) {
                $errors['eFound'] = array(
                    'e',
                    '7.0.15',
                    $targetParam['raw'],
                );
            }
        }

        if (empty($errors) === true) {
            return;
        }

        foreach ($errors as $errorCode => $errorData) {
            $phpcsFile->addError(
                'Passing "%s" as the $mode to fopen() is not supported in PHP %s or lower. Found %s',
                $targetParam['start'],
                $errorCode,
                $errorData
            );
        }
    }
}
