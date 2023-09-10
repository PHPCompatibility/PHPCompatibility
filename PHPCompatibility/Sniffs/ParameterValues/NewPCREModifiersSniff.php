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
use PHPCompatibility\Helpers\PCRERegexTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Check for the use of newly added regex modifiers for PCRE functions.
 *
 * Initially just checks for the PHP 7.2 new `J` modifier.
 * As of PHPCompatibility 10.0.0 also check for the PHP 8.2 `n` modifier.
 *
 * PHP version 7.2+
 *
 * @link https://www.php.net/manual/en/reference.pcre.pattern.modifiers.php
 * @link https://www.php.net/manual/en/migration72.new-features.php#migration72.new-features.pcre
 *
 * @since 8.2.0
 * @since 9.0.0  Renamed from `PCRENewModifiersSniff` to `NewPCREModifiersSniff`.
 * @since 10.0.0 Now uses the new `PCRERegexTrait` and extends the `AbstractFunctionCallParameterSniff`.
 */
class NewPCREModifiersSniff extends AbstractFunctionCallParameterSniff
{
    use PCRERegexTrait;

    /**
     * Functions to check for.
     *
     * @since 8.2.0
     * @since 10.0.0 Value changed from an irrelevant value to an array.
     *
     * @var array<string, array<string, int|string>> Key is the function name, value an array containing
     *                                               the 1-based parameter position and the official name of the parameter.
     */
    protected $targetFunctions = [
        'preg_filter' => [
            'position' => 1,
            'name'     => 'pattern',
        ],
        'preg_grep' => [
            'position' => 1,
            'name'     => 'pattern',
        ],
        'preg_match_all' => [
            'position' => 1,
            'name'     => 'pattern',
        ],
        'preg_match' => [
            'position' => 1,
            'name'     => 'pattern',
        ],
        'preg_replace_callback_array' => [
            'position' => 1,
            'name'     => 'pattern',
        ],
        'preg_replace_callback' => [
            'position' => 1,
            'name'     => 'pattern',
        ],
        'preg_replace' => [
            'position' => 1,
            'name'     => 'pattern',
        ],
        'preg_split' => [
            'position' => 1,
            'name'     => 'pattern',
        ],
    ];

    /**
     * Array listing newly introduced regex modifiers.
     *
     * The key should be the modifier (case-sensitive!).
     * The value should be the PHP version in which the modifier was introduced.
     *
     * @since 8.2.0
     *
     * @var array<string, array<string, bool>>
     */
    protected $newModifiers = [
        'J' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'n' => [
            '8.1' => false,
            '8.2' => true,
        ],
    ];


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 8.2.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        // Version used here should be the highest version from the `$newModifiers` array,
        // i.e. the last PHP version in which a new modifier was introduced.
        return (ScannedCode::shouldRunOnOrBelow('8.2') === false);
    }

    /**
     * Process the parameters of a matched function.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     * @param array                       $parameters   Array with information about the parameters.
     *
     * @return void
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $functionLC  = \strtolower($functionName);
        $paramInfo   = $this->targetFunctions[$functionLC];
        $targetParam = PassedParameters::getParameterFromStack($parameters, $paramInfo['position'], $paramInfo['name']);
        if ($targetParam === false) {
            return;
        }

        $patterns = $this->getRegexPatternsFromParameter($phpcsFile, $functionName, $targetParam);
        if (empty($patterns) === true) {
            return;
        }

        foreach ($patterns as $pattern) {
            $modifiers = $this->getRegexModifiers($phpcsFile, $pattern);
            if ($modifiers === '') {
                continue;
            }

            $this->examineModifiers($phpcsFile, $pattern['end'], $functionName, $modifiers);
        }
    }

    /**
     * Examine the regex modifier string.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the
     *                                                  stack passed in $tokens.
     * @param string                      $functionName The function which contained the pattern.
     * @param string                      $modifiers    The regex modifiers found.
     *
     * @return void
     */
    protected function examineModifiers(File $phpcsFile, $stackPtr, $functionName, $modifiers)
    {
        $error = 'The PCRE regex modifier "%s" is not present in PHP version %s or earlier';

        foreach ($this->newModifiers as $modifier => $versionArray) {
            if (\strpos($modifiers, $modifier) === false) {
                continue;
            }

            $notInVersion = '';
            foreach ($versionArray as $version => $present) {
                if ($notInVersion === '' && $present === false
                    && ScannedCode::shouldRunOnOrBelow($version) === true
                ) {
                    $notInVersion = $version;
                }
            }

            if ($notInVersion === '') {
                continue;
            }

            $errorCode = $modifier . 'ModifierFound';
            $data      = [
                $modifier,
                $notInVersion,
            ];

            $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
        }
    }
}
