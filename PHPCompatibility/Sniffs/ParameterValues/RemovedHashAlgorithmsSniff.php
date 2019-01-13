<?php
/**
 * \PHPCompatibility\Sniffs\ParameterValues\RemovedHashAlgorithmsSniff.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractRemovedFeatureSniff;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\ParameterValues\RemovedHashAlgorithmsSniff.
 *
 * Discourages the use of deprecated and removed hash algorithms.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class RemovedHashAlgorithmsSniff extends AbstractRemovedFeatureSniff
{

    /**
     * A list of removed hash algorithms, which were present in older versions.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the hash algorithm was deprecated/removed.
     *
     * @var array(string => array(string => bool))
     */
    protected $removedAlgorithms = array(
        'salsa10' => array(
            '5.4' => true,
        ),
        'salsa20' => array(
            '5.4' => true,
        ),
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(\T_STRING);
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $algo = $this->getHashAlgorithmParameter($phpcsFile, $stackPtr);
        if (empty($algo) || \is_string($algo) === false) {
            return;
        }

        // Bow out if not one of the algorithms we're targetting.
        if (isset($this->removedAlgorithms[$algo]) === false) {
            return;
        }

        $itemInfo = array(
            'name' => $algo,
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
        return $this->removedAlgorithms[$itemInfo['name']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The %s hash algorithm is ';
    }
}
