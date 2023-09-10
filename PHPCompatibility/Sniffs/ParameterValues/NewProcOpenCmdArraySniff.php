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

/**
 * As of PHP 7.4, `proc_open()` now also accepts an array instead of a string for the command.
 *
 * In that case, the process will be opened directly (without going through a shell) and
 * PHP will take care of any necessary argument escaping.
 *
 * PHP version 7.4
 *
 * @link https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.standard.proc-open
 * @link https://www.php.net/manual/en/function.proc-open.php
 *
 * @since 9.3.0
 */
class NewProcOpenCmdArraySniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 9.3.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'proc_open' => true,
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
        $targetParam = PassedParameters::getParameterFromStack($parameters, 1, 'command');
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
            // Not passed as an array.
            return;
        }

        if (ScannedCode::shouldRunOnOrBelow('7.3') === true) {
            $phpcsFile->addError(
                'The proc_open() function did not accept $command to be passed in array format in PHP 7.3 and earlier.',
                $nextNonEmpty,
                'Found'
            );
        }

        if (ScannedCode::shouldRunOnOrAbove('7.4') === true) {
            if (\strpos($targetParam['clean'], 'escapeshellarg(') === false) {
                // Efficiency: prevent needlessly walking the array.
                return;
            }

            $items = PassedParameters::getParameters($phpcsFile, $nextNonEmpty);

            if (empty($items)) {
                return;
            }

            foreach ($items as $item) {
                for ($i = $item['start']; $i <= $item['end']; $i++) {
                    if ($tokens[$i]['code'] !== \T_STRING
                        || $tokens[$i]['content'] !== 'escapeshellarg'
                    ) {
                        continue;
                    }

                    // @todo Potential future enhancement: check if it's a call to the PHP native function.

                    $phpcsFile->addWarning(
                        'When passing the $command parameter to proc_open() as an array, PHP will take care of any necessary argument escaping. Found: %s',
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
