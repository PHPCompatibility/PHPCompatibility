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
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;

/**
 * Check for valid values for the `$format` passed to `pack()`.
 *
 * PHP version 5.4+
 *
 * @link https://www.php.net/manual/en/function.pack.php#refsect1-function.pack-changelog
 *
 * @since 9.0.0
 */
class NewPackFormatSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 9.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'pack' => true,
    ];

    /**
     * List of new format character codes added to pack().
     *
     * @since 9.0.0
     *
     * @var array<string, array<string, bool>> Regex pattern => Version array.
     */
    protected $newFormats = [
        '`([Z])`'    => [
            '5.4' => false,
            '5.5' => true,
        ],
        '`([qQJP])`' => [
            '5.6.2' => false,
            '5.6.3' => true,
        ],
        '`([eEgG])`' => [
            '7.0.14' => false,
            '7.0.15' => true, // And 7.1.1.
        ],
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
        return (ScannedCode::shouldRunOnOrBelow('7.1') === false);
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
        $targetParam = PassedParameters::getParameterFromStack($parameters, 1, 'format');
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

            $content = $tokens[$i]['content'];
            if ($tokens[$i]['code'] === \T_DOUBLE_QUOTED_STRING) {
                $content = TextStrings::stripEmbeds($content);
            }

            foreach ($this->newFormats as $pattern => $versionArray) {
                if (\preg_match($pattern, $content, $matches) !== 1) {
                    continue;
                }

                foreach ($versionArray as $version => $present) {
                    if ($present === false && ScannedCode::shouldRunOnOrBelow($version) === true) {
                        $phpcsFile->addError(
                            'Passing the $format(s) "%s" to pack() is not supported in PHP %s or lower. Found: %s',
                            $targetParam['start'],
                            'NewFormatFound',
                            [
                                $matches[1],
                                $version,
                                $targetParam['clean'],
                            ]
                        );
                        continue 2;
                    }
                }
            }
        }
    }
}
