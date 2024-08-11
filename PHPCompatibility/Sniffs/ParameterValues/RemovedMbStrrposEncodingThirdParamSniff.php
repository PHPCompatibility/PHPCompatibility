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
use PHPCompatibility\Helpers\TokenGroup;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect passing `$encoding` to `mb_strrpos()` as 3rd argument.
 *
 * The `$encoding` parameter was moved from the third position to the fourth in PHP 5.2.0.
 * For backward compatibility, `$encoding` could be specified as the third parameter, but doing
 * so is deprecated and will be removed in the future.
 *
 * Between PHP 5.2 and PHP 7.3, this was a deprecation in documentation only.
 * As of PHP 7.4, a deprecation warning will be thrown if an encoding is passed as the 3rd
 * argument.
 * As of PHP 8, an explicit 0 offset should be passed with the encoding as the fourth argument.
 *
 * PHP version 5.2
 * PHP version 7.4
 * PHP version 8.0
 *
 * @link https://www.php.net/manual/en/migration74.deprecated.php#migration74.deprecated.mbstring
 * @link https://wiki.php.net/rfc/deprecations_php_7_4#mb_strrpos_with_encoding_as_3rd_argument
 * @link https://www.php.net/manual/en/function.mb-strrpos.php
 *
 * @since 9.3.0
 */
class RemovedMbStrrposEncodingThirdParamSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 9.3.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'mb_strrpos' => true,
    ];

    /**
     * Tokens which should be recognized as numbers.
     *
     * @since 9.3.0
     *
     * @var array<int|string, int|string>
     */
    private $numberTokens = [
        \T_LNUMBER => \T_LNUMBER,
        \T_DNUMBER => \T_DNUMBER,
        \T_MINUS   => \T_MINUS,
        \T_PLUS    => \T_PLUS,
    ];


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 9.3.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return ScannedCode::shouldRunOnOrAbove('5.2') === false;
    }


    /**
     * Process the parameters of a matched function.
     *
     * @since 9.3.0
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
        $encodingParam = PassedParameters::getParameterFromStack($parameters, 4, 'encoding');
        if ($encodingParam !== false) {
            // Encoding set as fourth parameter.
            return;
        }

        $targetParam = PassedParameters::getParameterFromStack($parameters, 3, 'offset');
        if ($targetParam === false) {
            // Optional third parameter not set.
            return;
        }

        $targets   = $this->numberTokens + Tokens::$emptyTokens;
        $nonNumber = $phpcsFile->findNext($targets, $targetParam['start'], ($targetParam['end'] + 1), true);
        if ($nonNumber === false) {
            return;
        }

        if (TokenGroup::isNumericCalculation($phpcsFile, $targetParam['start'], $targetParam['end']) === true) {
            return;
        }

        $tokens         = $phpcsFile->getTokens();
        $probablyString = false;
        for ($i = $targetParam['start']; $i <= $targetParam['end']; $i++) {
            if (isset(Tokens::$emptyTokens[$tokens[$i]['code']])) {
                continue;
            }

            // Skip past anything nested in brackets.
            if (isset($tokens[$i]['scope_closer'])) {
                if ($tokens[$i]['code'] === \T_START_HEREDOC || $tokens[$i]['code'] === \T_START_NOWDOC) {
                    $probablyString = true;
                }

                if ($tokens[$i]['code'] === \T_FN) {
                    $i = ($tokens[$i]['scope_closer'] - 1);
                } else {
                    $i = $tokens[$i]['scope_closer'];
                }
                continue;
            }

            if (isset($tokens[$i]['bracket_closer'])) {
                $i = $tokens[$i]['bracket_closer'];
                continue;
            }

            if (isset($tokens[$i]['parenthesis_closer'])) {
                $i = $tokens[$i]['parenthesis_closer'];
                continue;
            }

            if (isset(Tokens::$textStringTokens[$tokens[$i]['code']])
                || $tokens[$i]['code'] === \T_STRING_CAST
                || $tokens[$i]['code'] === \T_STRING_CONCAT
            ) {
                $probablyString = true;
            }
        }

        if ($probablyString === false) {
            // No relevant text strings found. Undetermined.
            return;
        }

        $error     = 'Passing the encoding to mb_strrpos() as third parameter is soft deprecated since PHP 5.2';
        $isError   = false;
        $code      = 'Deprecated';
        $realStart = $phpcsFile->findNext(Tokens::$emptyTokens, $targetParam['start'], ($targetParam['end'] + 1), true);

        if (ScannedCode::shouldRunOnOrAbove('8.0') === true) {
            $error  .= ', hard deprecated since PHP 7.4 and removed since PHP 8.0';
            $isError = true;
            $code    = 'Removed';
        } elseif (ScannedCode::shouldRunOnOrAbove('7.4') === true) {
            $error .= ' and hard deprecated since PHP 7.4';
        }

        $error .= '. Use an explicit 0 as the offset in the third parameter.';

        MessageHelper::addMessage($phpcsFile, $error, $realStart, $isError, $code);
    }
}
