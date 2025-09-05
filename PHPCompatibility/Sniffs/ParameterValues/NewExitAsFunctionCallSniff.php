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
use PHPCompatibility\Helpers\MiscHelper;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detects the use of exit as a function call, as allowed since PHP 8.4.
 *
 * Prior to PHP 8.4, exit/die was a language construct.
 * Since PHP 8.4, it is a proper function, with internal handling of its use as a constant
 * (by changing this to a function call at compile time).
 *
 * As a consequence of this, exit/die can now:
 * - be called with a named argument (handled in the `NewNamedParameters` sniff);
 * - passed to functions as a callable (can't be reliably detected);
 * - passed to functions as a first class callable (handled in the `NewFirstClassCallables` sniff);
 * - have a trailing comma after its parameter (handled in the `NewFunctionCallTrailingComma` sniff);
 * - respect strict_types and follow type juggling semantics (handled in this sniff).
 *
 * Passing as a callable can only reliable be detected when passed as a first class callable as
 * when `exit`/`die` is passed as a text string, the chance of flagging a false positive is too high.
 *
 * As for the type juggling part: this can only be detected when the parameter is passed hard-coded, but
 * in that case, this sniff can detect it with high precision.
 *
 * PHP version 8.4
 *
 * @link https://wiki.php.net/rfc/exit-as-function
 *
 * @since 10.0.0
 */
final class NewExitAsFunctionCallSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'exit' => true,
        'die'  => true,
    ];

    /**
     * All constants natively declared by PHP.
     *
     * @since 10.0.0
     *
     * @var array<string, mixed>
     */
    private $phpNativeConstants = [];

    /**
     * Current file being scanned.
     *
     * @since 10.0.0
     *
     * @var string
     */
    private $currentFile = '';

    /**
     * Whether strict types are in effect in the current file.
     *
     * @since 10.0.0
     *
     * @var bool
     */
    private $strictTypes = false;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        // Get the PHP natively defined constants only once.
        $constants = \get_defined_constants(true);
        unset($constants['user']);

        $this->phpNativeConstants = [];
        foreach ($constants as $group) {
            $this->phpNativeConstants += $group;
        }

        // Call the parent method to set up some properties for the abstract.
        parent::register();

        // ... but register our own target tokens.
        return [
            \T_DECLARE,
            \T_EXIT,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if ($this->bowOutEarly() === true) {
            return;
        }

        $fileName = $phpcsFile->getFilename();
        if ($this->currentFile !== $fileName) {
            // Reset the declare statement related properties for each new file.
            $this->currentFile = $fileName;
            $this->strictTypes = false;
        }

        /*
         * Check for strict types declarations.
         *
         * Ignore any invalid/incomplete declare statements.
         */
        $tokens = $phpcsFile->getTokens();
        if ($tokens[$stackPtr]['code'] === \T_DECLARE) {
            if (isset($tokens[$stackPtr]['parenthesis_opener'], $tokens[$stackPtr]['parenthesis_closer']) === false) {
                // Live coding or parse error.
                return;
            }

            $declarations = GetTokensAsString::noEmpties(
                $phpcsFile,
                ($tokens[$stackPtr]['parenthesis_opener'] + 1),
                ($tokens[$stackPtr]['parenthesis_closer'] - 1)
            );

            if (\preg_match('`\bstrict_types=([01])`i', $declarations, $matches) === 1) {
                if ($matches[1] === '1') {
                    $this->strictTypes = true;
                } else {
                    $this->strictTypes = false;
                }
            }

            return;
        }

        // Check if this is exit/die used as a fully qualified function call.
        $isFullyQualified = false;
        if ($tokens[$stackPtr]['content'][0] === '\\') {
            // PHPCS 4.x.
            $isFullyQualified = true;
        } else {
            // PHPCS 3.x.
            $prev = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
            if ($tokens[$prev]['code'] === \T_NS_SEPARATOR) {
                $isFullyQualified = true;
            }
        }

        if ($isFullyQualified === true) {
            $phpcsFile->addError(
                'Using "%s" as a fully qualified function call is not allowed in PHP 8.3 or earlier.',
                $stackPtr,
                'FullyQualified',
                [\ltrim($tokens[$stackPtr]['content'], '\\')]
            );
        }

        return parent::process($phpcsFile, $stackPtr);
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
        return (ScannedCode::shouldRunOnOrAbove('8.4') === false);
    }

    /**
     * Process the parameters of a matched function.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File                  $phpcsFile    The file being scanned.
     * @param int                                          $stackPtr     The position of the current token in the stack.
     * @param string                                       $functionName The token content (function name) which was matched.
     * @param array<int|string, array<string, int|string>> $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $targetParam = PassedParameters::getParameterFromStack($parameters, 1, 'status');
        if ($targetParam === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $data   = [$functionName, $targetParam['clean']];

        $integer = 0;
        $string  = 0;
        $boolean = 0;
        $float   = 0;
        $null    = 0;
        $concat  = 0;
        $arithm  = 0;
        $total   = 0;

        for ($i = $targetParam['start']; $i <= $targetParam['end']; $i++) {
            if (isset(Tokens::$emptyTokens[$tokens[$i]['code']]) || $tokens[$i]['code'] === \T_NS_SEPARATOR) {
                continue;
            }

            if (($tokens[$i]['code'] === \T_INT_CAST || $tokens[$i]['code'] === \T_STRING_CAST)
                && $total === 0
            ) {
                // Assume the cast is for the whole parameter, in which case, we're good.
                return;
            }

            if ($tokens[$i]['code'] === \T_NEW) {
                // For objects, there is no change in behaviour. This was already a type error,
                // or, in case of a stingable object, was okay and is still okay.
                return;
            }

            // Check for use of PHP native global constants for which we know the type.
            $trimmedContent = \ltrim($tokens[$i]['content'], '\\');
            if (($tokens[$i]['code'] === \T_STRING
                || $tokens[$i]['code'] === \T_NAME_FULLY_QUALIFIED)
                && isset($this->phpNativeConstants[$trimmedContent]) === true
                && MiscHelper::isUseOfGlobalConstant($phpcsFile, $i) === true
            ) {
                $type = \gettype($this->phpNativeConstants[$trimmedContent]);
                switch ($type) {
                    case 'integer':
                        ++$integer;
                        break;

                    case 'string':
                        ++$string;
                        break;

                    case 'double':
                        ++$float;
                        break;

                    case 'boolean':
                        ++$boolean;
                        break;

                    // At this time, PHP doesn't have any native constants of type null.
                    // @codeCoverageIgnoreStart
                    case 'null':
                        ++$null;
                        break;
                        // @codeCoverageIgnoreEnd

                    default:
                        $this->flagTypeError($phpcsFile, $i, $data, $type);
                        return;
                }

                ++$total;
                continue;
            }

            if (isset(Collections::nameTokens()[$tokens[$i]['code']]) === true
                || $tokens[$i]['code'] === \T_VARIABLE
            ) {
                // Variable, non-PHP-native constant, function call. Ignore as undetermined.
                return;
            }

            if (($tokens[$i]['code'] === \T_ARRAY
                || $tokens[$i]['code'] === \T_LIST
                || $tokens[$i]['code'] === \T_OPEN_SHORT_ARRAY)
                && $total === 0 // Only flag when the parameter starts with one of these tokens.
            ) {
                $this->flagTypeError($phpcsFile, $i, $data, 'array');
                return;
            }

            ++$total;

            if ($tokens[$i]['code'] === \T_LNUMBER) {
                ++$integer;
                continue;
            }

            if (isset(Tokens::$arithmeticTokens[$tokens[$i]['code']])) {
                ++$arithm;
                continue;
            }

            if (isset(Tokens::$textStringTokens[$tokens[$i]['code']])
                || isset(Tokens::$heredocTokens[$tokens[$i]['code']])
            ) {
                ++$string;
                continue;
            }

            if ($tokens[$i]['code'] === \T_STRING_CONCAT) {
                ++$concat;
                continue;
            }

            if ($tokens[$i]['code'] === \T_DNUMBER) {
                ++$float;
                continue;
            }

            if ($tokens[$i]['code'] === \T_TRUE || $tokens[$i]['code'] === \T_FALSE) {
                ++$boolean;
                continue;
            }

            if ($tokens[$i]['code'] === \T_NULL) {
                ++$null;
                continue;
            }
        }

        $unrecognized = ($total - $integer - $string - $boolean - $float - $null - $concat - $arithm);

        if ($unrecognized > 0) {
            // Ignore as undetermined.
            return;
        }

        if (($integer > 0 && ($total - $integer - $arithm) === 0)
            || ($string > 0 && ($total - $string - $concat - $integer) === 0)
        ) {
            // This is fine, either a purely integer value, a purely string value or a simple operation involving only strings/integers.
            // No change in behaviour.
            return;
        }

        if ($boolean > 0 && ($total - $boolean) === 0) {
            if ($this->strictTypes === true) {
                $this->flagTypeError($phpcsFile, $i, $data, 'boolean');
                return;
            }

            $phpcsFile->addWarning(
                'Passing a boolean value to %s() will be interpreted as an exit code instead of as a status message since PHP 8.4. Found: "%s"',
                $i,
                'BooleanParamFound',
                $data
            );
            return;
        }

        if ($float > 0 && ($total - $float - $arithm - $integer) === 0) {
            if ($this->strictTypes === true) {
                $this->flagTypeError($phpcsFile, $i, $data, 'float');
                return;
            }

            $phpcsFile->addWarning(
                'Passing a floating point value to %s() will be interpreted as an exit code instead of as a status message since PHP 8.4. Found: "%s"',
                $i,
                'FloatParamFound',
                $data
            );
            return;
        }

        if ($null > 0 && ($total - $null) === 0) {
            if ($this->strictTypes === true) {
                $this->flagTypeError($phpcsFile, $i, $data, 'null');
                return;
            }

            $phpcsFile->addWarning(
                'Passing null to %s() will be interpreted as an exit code instead of as a status message since PHP 8.4. Found: "%s"',
                $i,
                'NullParamFound',
                $data
            );
            return;
        }

        // Ignore everything else as undetermined.
    }

    /**
     * Throw an error about a received parameter type which will be a type error as of PHP 8.4.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The token position to throw the error on.
     * @param array<string>               $data      The data for the error message.
     * @param string                      $type      The inferred parameter type.
     *
     * @return void
     */
    private function flagTypeError(File $phpcsFile, $stackPtr, $data, $type)
    {
        $aOrAn = 'a ';
        if ($type === 'null') {
            $aOrAn = '';
        } elseif ($type === 'array') {
            $aOrAn = 'an ';
        }

        $data[] = $aOrAn;
        $data[] = $type;

        $phpcsFile->addError(
            'Passing %3$s%4$s to %1$s() will result in a TypeError since PHP 8.4. Found: "%2$s"',
            $stackPtr,
            'TypeError',
            $data
        );
    }
}
