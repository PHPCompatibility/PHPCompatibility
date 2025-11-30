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

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;

/**
 * Detect: Passing "user" to `session_module_name()` is no longer allowed as of PHP 7.2.
 * This will now result in a (recoverable) fatal error being thrown.
 *
 * Previously, passing "user" would result in it being silently ignored.
 *
 * PHP version 7.2
 *
 * @link https://www.php.net/manual/en/function.session-module-name.php#refsect1-function.session-module-name-changelog
 *
 * @since 10.0.0
 */
final class ForbiddenSessionModuleNameUserSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'session_module_name' => true,
    ];

    /**
     * Tokens which we are looking for in the parameter.
     *
     * @since 10.0.0
     *
     * @var array<int|string, int|string>
     */
    private $targetTokens = Tokens::EMPTY_TOKENS + Tokens::HEREDOC_TOKENS + Tokens::STRING_TOKENS;

    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrAbove('7.2') === false);
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
        $param = PassedParameters::getParameterFromStack($parameters, 1, 'module');
        if ($param === false) {
            return;
        }

        $firstNonEmpty   = $phpcsFile->findNext(Tokens::EMPTY_TOKENS, $param['start'], ($param['end'] + 1), true);
        $hasNonTextToken = $phpcsFile->findNext($this->targetTokens, $firstNonEmpty, ($param['end'] + 1), true);
        if ($hasNonTextToken !== false) {
            // Non text string token found.
            return;
        }

        $content   = TextStrings::getCompleteTextString($phpcsFile, $firstNonEmpty);
        $contentLC = \strtolower($content);

        if (\trim($contentLC) !== 'user') {
            return;
        }

        $phpcsFile->addError(
            'Passing "user" as the $module to session_module_name() is not allowed since PHP 7.2.',
            $firstNonEmpty,
            'Found'
        );
    }
}
