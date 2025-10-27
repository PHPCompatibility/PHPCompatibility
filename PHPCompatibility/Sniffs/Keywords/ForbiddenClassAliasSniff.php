<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Keywords;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;

/**
 * Type keywords cannot be used as class alias names.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/function.class-alias.php
 *
 * @since 10.0.0
 */
final class ForbiddenClassAliasSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, true>
     */
    protected $targetFunctions = [
        'class_alias' => true,
    ];

    /**
     * Keywords to recognize as forbidden alias names.
     *
     * List source: {@link https://github.com/php/php-src/blob/master/Zend/zend_compile.c}
     * See `reserved_class_name` struct.
     *
     * @since 10.0.0
     *
     * @var array<string, string>
     */
    private $forbiddenNames = [
        'bool'     => '7.0',
        'false'    => '7.0',
        'float'    => '7.0',
        'int'      => '7.0',
        'null'     => '7.0',
        'parent'   => '7.0',
        'self'     => '7.0',
        'static'   => '7.0',
        'string'   => '7.0',
        'true'     => '7.0',
        'iterable' => '7.1',
        'void'     => '7.1',
        'object'   => '7.2',
        'mixed'    => '8.0',
        'never'    => '8.1',
        'array'    => '8.5',
        'callable' => '8.5',
    ];

    /**
     * Tokens which we are looking for in the parameter.
     *
     * This property is set in the register() method.
     *
     * @since 10.0.0
     *
     * @var array<int|string, int|string>
     */
    private $targetTokens = [];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        // Only set the $targetTokens property once.
        $this->targetTokens  = Tokens::$emptyTokens;
        $this->targetTokens += Tokens::$heredocTokens;
        $this->targetTokens += Tokens::$stringTokens;
        unset($this->targetTokens[\T_DOUBLE_QUOTED_STRING]);

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
        return (ScannedCode::shouldRunOnOrAbove('7.0') === false);
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
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $param = PassedParameters::getParameterFromStack($parameters, 2, 'alias');
        if ($param === false) {
            return;
        }

        $firstNonEmpty   = $phpcsFile->findNext(Tokens::$emptyTokens, $param['start'], ($param['end'] + 1), true);
        $hasNonTextToken = $phpcsFile->findNext($this->targetTokens, $firstNonEmpty, ($param['end'] + 1), true);
        if ($hasNonTextToken !== false) {
            // Non text string token found.
            return;
        }

        $content   = TextStrings::getCompleteTextString($phpcsFile, $firstNonEmpty);
        $contentLC = \strtolower(\ltrim($content, '\\'));

        if (isset($this->forbiddenNames[$contentLC]) === false) {
            return;
        }

        if (ScannedCode::shouldRunOnOrAbove($this->forbiddenNames[$contentLC]) === false) {
            return;
        }

        $phpcsFile->addError(
            'Type keyword "%s" is not allowed as a class alias name since PHP %s.',
            $firstNonEmpty,
            'Found',
            [$contentLC, $this->forbiddenNames[$contentLC]]
        );
    }
}
