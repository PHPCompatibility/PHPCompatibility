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
use PHPCSUtils\Utils\PassedParameters;

/**
 * As of PHP 8.1, the default value for the $flags parameters for `htmlspecialchars()`, `htmlentities()`
 * `htmlspecialchars_decode()`, `html_entity_decode()` and `get_html_translation_table()` is
 * now `ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401`, instead of `ENT_COMPAT`. The value of `ENT_HTML401` is 0,
 * therefore it's always present regardless of whether it's specified or not.
 *
 * PHP version 8.1
 *
 * @link https://www.php.net/manual/en/function.html-entity-decode#refsect1-function.html-entity-decode-changelog
 * @link https://github.com/php/php-src/commit/50eca61f68815005f3b0f808578cc1ce3b4297f0
 *
 * @since 10.0.0
 */
class NewHTMLEntitiesFlagsDefaultSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * Key is the function name, value an array containing the 1-based parameter position
     * and the official name of the parameter.
     *
     * @since 10.0.0
     *
     * @var array<string, array<string, int|string>>
     */
    protected $targetFunctions = [
        'get_html_translation_table' => [
            'position' => 2,
            'name'     => 'flags',
        ],
        'html_entity_decode'         => [
            'position' => 2,
            'name'     => 'flags',
        ],
        'htmlentities'               => [
            'position' => 2,
            'name'     => 'flags',
        ],
        'htmlspecialchars_decode'    => [
            'position' => 2,
            'name'     => 'flags',
        ],
        'htmlspecialchars'           => [
            'position' => 2,
            'name'     => 'flags',
        ],
    ];


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * Note: This sniff should only trigger errors when both PHP 8.0 or lower,
     * as well as PHP 8.1 or higher need to be supported within the application.
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrBelow('8.0') === false || ScannedCode::shouldRunOnOrAbove('8.1') === false);
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
        $functionLC  = \strtolower($functionName);
        $paramInfo   = $this->targetFunctions[$functionLC];
        $targetParam = PassedParameters::getParameterFromStack($parameters, $paramInfo['position'], $paramInfo['name']);
        if ($targetParam !== false) {
            // Parameter is set, not an issue.
            return;
        }

        $phpcsFile->addError(
            'The default value of the $%1$s parameter for %2$s() was changed from ENT_COMPAT to ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 in PHP 8.1. For cross-version compatibility, the $%1$s parameter should be explicitly set.',
            $stackPtr,
            'NotSet',
            [$paramInfo['name'], $functionName]
        );
    }
}
