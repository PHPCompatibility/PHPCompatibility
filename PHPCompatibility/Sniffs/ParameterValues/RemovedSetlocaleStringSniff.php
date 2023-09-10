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

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect passing a string literal as `$category` to `setlocale()`.
 *
 * Support for the category parameter passed as a string has been removed.
 * Only `LC_*` constants can be used as of PHP 7.0.0.
 *
 * PHP version 4.2
 * PHP version 7.0
 *
 * @link https://wiki.php.net/rfc/remove_deprecated_functionality_in_php7
 * @link https://www.php.net/manual/en/function.setlocale.php#refsect1-function.setlocale-changelog
 *
 * @since 9.0.0
 */
class RemovedSetlocaleStringSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 9.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'setlocale' => true,
    ];


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 9.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrAbove('4.2') === false);
    }


    /**
     * Process the parameters of a matched function.
     *
     * @since 9.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     * @param array                       $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $targetParam = PassedParameters::getParameterFromStack($parameters, 1, 'category');
        if ($targetParam === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        for ($i = $targetParam['start']; $i <= $targetParam['end']; $i++) {
            if ($tokens[$i]['code'] === \T_STRING
                || $tokens[$i]['code'] === \T_VARIABLE
            ) {
                // Variable, constant, function call. Ignore as undetermined.
                return;
            }

            if (isset(Tokens::$stringTokens[$tokens[$i]['code']]) === false) {
                continue;
            }

            $message   = 'Passing the $category as a string to setlocale() has been deprecated since PHP 4.2';
            $isError   = false;
            $errorCode = 'Deprecated';
            $data      = [$targetParam['clean']];

            if (ScannedCode::shouldRunOnOrAbove('7.0') === true) {
                $message  .= ' and is removed since PHP 7.0';
                $isError   = true;
                $errorCode = 'Removed';
            }

            $message .= '; Pass one of the LC_* constants instead. Found: %s';

            MessageHelper::addMessage($phpcsFile, $message, $i, $isError, $errorCode, $data);
            break;
        }
    }
}
