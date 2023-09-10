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
use PHPCSUtils\BackCompat\BCTokens;
use PHPCSUtils\Utils\PassedParameters;

/**
 * As of PHP 7.4, `strip_tags()` now also accepts an array of `$allowed_tags`.
 *
 * PHP version 7.4
 *
 * @link https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.standard.strip-tags
 * @link https://www.php.net/manual/en/function.strip-tags.php
 *
 * @since 9.3.0
 */
class NewStripTagsAllowableTagsArraySniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 9.3.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'strip_tags' => true,
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
        return false;
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
        $targetParam = PassedParameters::getParameterFromStack($parameters, 2, 'allowed_tags');
        if ($targetParam === false) {
            return;
        }

        $tokens       = $phpcsFile->getTokens();
        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $targetParam['start'], $targetParam['end'], true);

        if ($nextNonEmpty === false) {
            // Shouldn't be possible.
            return;
        }

        if ($tokens[$nextNonEmpty]['code'] !== \T_ARRAY
            && $tokens[$nextNonEmpty]['code'] !== \T_OPEN_SHORT_ARRAY
        ) {
            // Not passed as a hard-coded array.
            return;
        }

        if (ScannedCode::shouldRunOnOrBelow('7.3') === true) {
            $phpcsFile->addError(
                'The strip_tags() function did not accept $allowed_tags to be passed in array format in PHP 7.3 and earlier.',
                $nextNonEmpty,
                'Found'
            );
        }

        if (ScannedCode::shouldRunOnOrAbove('7.4') === true) {
            if (\strpos($targetParam['clean'], '>') === false) {
                // Efficiency: prevent needlessly walking the array.
                return;
            }

            $items = PassedParameters::getParameters($phpcsFile, $nextNonEmpty);

            if (empty($items)) {
                return;
            }

            foreach ($items as $item) {
                for ($i = $item['start']; $i <= $item['end']; $i++) {
                    if ($tokens[$i]['code'] === \T_STRING
                        || $tokens[$i]['code'] === \T_VARIABLE
                    ) {
                        // Variable, constant, function call. Ignore complete item as undetermined.
                        break;
                    }

                    if (isset(BCTokens::textStringTokens()[$tokens[$i]['code']]) === true
                        && \strpos($tokens[$i]['content'], '>') !== false
                    ) {
                        $phpcsFile->addWarning(
                            'When passing strip_tags() the $allowed_tags parameter as an array, the tags should not be enclosed in <> brackets. Found: %s',
                            $i,
                            'Invalid',
                            [$item['clean']]
                        );

                        // Only throw one error per array item.
                        break;
                    }
                }
            }
        }
    }
}
