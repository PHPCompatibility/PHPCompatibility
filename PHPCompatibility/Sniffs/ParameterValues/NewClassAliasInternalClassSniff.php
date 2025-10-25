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
use ReflectionClass;

/**
 * PHP native classes/interfaces/traits/enums can be aliased since PHP 8.3.
 *
 * Prior to PHP 8.3, only userland classes could be aliased.
 *
 * Mind: this sniff may yield false negatives for:
 * - Classes declared in extensions, when the extension is not loaded during the PHPCS run.
 * - PHP native classes which are not available in the PHP version on which PHPCS is being run.
 *
 * PHP version 8.3
 *
 * @link https://www.php.net/manual/en/function.class-alias.php
 *
 * @since 10.0.0
 */
final class NewClassAliasInternalClassSniff extends AbstractFunctionCallParameterSniff
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
     * List of all OO names currently registered with PHP.
     *
     * This property is set in the register() method.
     *
     * This list will include classes from PHPCS + PHPCompatibility and we will filter those out
     * at a later point in the logic.
     *
     * @since 10.0.0
     *
     * @var array<string, int>
     */
    private $declaredOONamesLC = [];

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
        /*
         * Gather the names of all currently declared classes/interfaces/traits/enums.
         * Mind: there is no `get_declared_enums()` function, but enums are included
         * in the return value from `get_declared_classes()`.
         */
        $classes    = \get_declared_classes();
        $interfaces = \get_declared_interfaces();
        $traits     = \get_declared_traits();

        $allOONames = \array_merge($classes, $interfaces, $traits);
        $allOONames = \array_flip($allOONames);

        $this->declaredOONamesLC = \array_change_key_case($allOONames, \CASE_LOWER);

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
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $param = PassedParameters::getParameterFromStack($parameters, 1, 'class');
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

        if (isset($this->declaredOONamesLC[$contentLC]) === false) {
            return;
        }

        // Make sure this is a PHP native class and not a userland (PHPCS or PHPCompatibility) class.
        $reflectionClass = new ReflectionClass($contentLC);
        if ($reflectionClass->isInternal() === false) {
            return;
        }

        $phpcsFile->addError(
            'PHP internal classes can not be aliased prior to PHP 8.3. Found aliasing of class: %s',
            $firstNonEmpty,
            'Found',
            [$content]
        );
    }
}
