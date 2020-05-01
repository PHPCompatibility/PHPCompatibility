<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;
use PHPCompatibility\AbstractFunctionCallParameterSniff;

/**
 * Detect the use of assertions passed as a string.
 *
 * > Usage of a string as the assertion became deprecated. It now emits an E_DEPRECATED
 * > notice when both assert.active and zend.assertions are set to 1.
 *
 * PHP version 7.2
 *
 * @link https://wiki.php.net/rfc/deprecations_php_7_2#assert_with_string_argument
 * @link https://www.php.net/manual/en/function.assert.php#refsect1-function.assert-changelog
 *
 * @since 10.0.0
 */
class RemovedAssertStringAssertionSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array
     */
    protected $targetFunctions = array(
        'assert' => true,
    );

    /**
     * Target tokens.
     *
     * If there is anything but any of these tokens in the parameter, we bow out as undetermined.
     *
     * @since 10.0.0
     *
     * @var array
     */
    private $targetTokens = array();

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array
     */
    public function register()
    {
        // Set $targetTokens only once.
        $this->targetTokens                   = Tokens::$emptyTokens;
        $this->targetTokens                  += Tokens::$stringTokens + Tokens::$heredocTokens;
        $this->targetTokens[\T_STRING_CONCAT] = \T_STRING_CONCAT;

        return parent::register();
    }

    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 10.0.0
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
     * @since 10.0.0
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

        $targetParam = $parameters[1];
        $hasOther    = $phpcsFile->findNext($this->targetTokens, $targetParam['start'], ($targetParam['end'] + 1), true);
        if ($hasOther !== false) {
            // Some other token was found, unclear whether this is really a text string. Bow out.
            return;
        }

        $error = 'Using a string as the assertion passed to assert() is deprecated since PHP 7.2. Found: %s';
        $data  = array($targetParam['clean']);

        $phpcsFile->addWarning($error, $targetParam['start'], 'Found', $data);
    }
}
