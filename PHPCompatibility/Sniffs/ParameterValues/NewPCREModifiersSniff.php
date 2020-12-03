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
use PHP_CodeSniffer\Files\File;

/**
 * Check for the use of newly added regex modifiers for PCRE functions.
 *
 * Initially just checks for the PHP 7.2 new `J` modifier.
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
     *
     * @var array
     */
    protected $targetFunctions = [
        'preg_filter'                 => true,
        'preg_grep'                   => true,
        'preg_match_all'              => true,
        'preg_match'                  => true,
        'preg_replace_callback_array' => true,
        'preg_replace_callback'       => true,
        'preg_replace'                => true,
        'preg_split'                  => true,
    ];

    /**
     * Array listing newly introduced regex modifiers.
     *
     * The key should be the modifier (case-sensitive!).
     * The value should be the PHP version in which the modifier was introduced.
     *
     * @since 8.2.0
     *
     * @var array
     */
    protected $newModifiers = [
        'J' => [
            '7.1' => false,
            '7.2' => true,
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
        return ($this->supportsBelow('7.2') === false);
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
        // Check the first parameter in the function call as that should contain the regex(es).
        if (isset($parameters[1]) === false) {
            return;
        }

        $patterns = $this->getRegexPatternsFromParameter($phpcsFile, $functionName, $parameters[1]);
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
                    && $this->supportsBelow($version) === true
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
