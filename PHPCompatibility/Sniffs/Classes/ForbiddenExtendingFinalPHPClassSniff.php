<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Classes;

use PHPCompatibility\Helpers\ResolveHelper;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Detect extending of PHP native classes which became final at some point.
 *
 * PHP version 8.0+
 *
 * @since 10.0.0
 */
class ForbiddenExtendingFinalPHPClassSniff extends Sniff
{

    /**
     * A list of PHP native classes which became final at some point.
     *
     * @since 10.0.0
     *
     * @var array<string, string> Key is the fully qualified classname.
     *                            Value the PHP version in which the class became final.
     */
    protected $finalClasses = [
        '\__PHP_Incomplete_Class' => '8.0',
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        // Handle case-insensitivity of class names.
        $this->finalClasses = \array_change_key_case($this->finalClasses, \CASE_LOWER);

        return [
            \T_CLASS,
            \T_ANON_CLASS,
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
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $FQClassName = ResolveHelper::getFQExtendedClassName($phpcsFile, $stackPtr);
        if ($FQClassName === '') {
            return;
        }

        $classNameLc = \strtolower($FQClassName);

        if (isset($this->finalClasses[$classNameLc]) === false) {
            return;
        }

        if (ScannedCode::shouldRunOnOrAbove($this->finalClasses[$classNameLc]) === false) {
            return;
        }

        $data = [
            \substr($FQClassName, 1), // Remove global namespace indicator.
            $this->finalClasses[$classNameLc],
        ];

        $phpcsFile->addError(
            'The built-in class %s is final since PHP %s and cannot be extended',
            $stackPtr,
            'Found',
            $data
        );
    }
}
