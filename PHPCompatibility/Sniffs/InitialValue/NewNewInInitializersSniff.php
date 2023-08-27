<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\InitialValue;

use PHPCompatibility\AbstractInitialValueSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\Parentheses;
use PHPCSUtils\Utils\Scopes;

/**
 * Detect object instantiation in initial values as allowed per PHP 8.1.
 *
 * As of PHP 8.1, `new` expressions are allowed in parameter default values,
 * attribute arguments, static variable initializers and global class constant initializers.
 * Parameter default values also include defaults for promoted properties.
 *
 * The use of a dynamic or non-string class name or an anonymous class is not allowed.
 * The use of argument unpacking is not allowed. The use of unsupported expressions as arguments is not allowed.
 *
 * PHP version 8.1
 *
 * @link https://wiki.php.net/rfc/new_in_initializers
 * @link https://www.php.net/manual/en/migration81.new-features.php#migration81.new-features.core.new-in-initializer
 *
 * @since 10.0.0
 */
final class NewNewInInitializersSniff extends AbstractInitialValueSniff
{

    /**
     * Error message.
     *
     * @since 10.0.0
     *
     * @var string
     */
    const ERROR_PHRASE = 'New in initializers is not supported in PHP 8.0 or earlier for %s.';

    /**
     * Partial error phrases to be used in combination with the error message constant.
     *
     * @since 10.0.0.
     *
     * @var array<string, string> Type indicator => suggested partial error phrase.
     */
    protected $initialValueTypes = [
        'const'     => 'global/namespaced constants declared using the const keyword',
        'property'  => '', // Not supported.
        'staticvar' => 'static variables',
        'default'   => 'default parameter values',
    ];

    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrBelow('8.0') === false);
    }

    /**
     * Process a token which has an initial value.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the variable/constant name token
     *                                               in the stack passed in $tokens.
     * @param int                         $start     The stackPtr to the start of the initial value.
     * @param int                         $end       The stackPtr to the end of the initial value.
     *                                               This will normally be a comma or semi-colon.
     * @param string                      $type      The "type" of initial value declaration being examined.
     *                                               The type will match one of the keys in the
     *                                               `AbstractInitialValueSniff::$initialValueTypes` property.
     *
     * @return void
     */
    protected function processInitialValue(File $phpcsFile, $stackPtr, $start, $end, $type)
    {
        if ($type === 'property'
            || ($type === 'const'
            && Scopes::validDirectScope($phpcsFile, $stackPtr, Collections::ooConstantScopes()) !== false)
        ) {
            // New is (still) not allowed in OO constants or properties.
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $targetNestingLevel = 0;
        if (isset($tokens[$start]['nested_parenthesis'])) {
            $targetNestingLevel = \count($tokens[$start]['nested_parenthesis']);
        }

        $error     = self::ERROR_PHRASE;
        $errorCode = 'Found';
        $phrase    = '';

        if (isset($this->initialValueTypes[$type]) === true) {
            $errorCode = MessageHelper::stringToErrorCode($type) . 'Found';
            $phrase    = $this->initialValueTypes[$type];
        }

        $data = [$phrase];

        $allowedNameTokens            = Collections::namespacedNameTokens();
        $allowedNameTokens[\T_SELF]   = \T_SELF;
        $allowedNameTokens[\T_PARENT] = \T_PARENT;

        $current = $start;
        while (($hasNew = $phpcsFile->findNext(\T_NEW, $current, $end)) !== false) {
            // Handle nesting within arrays.
            $currentNestingLevel = 0;
            if (isset($tokens[$hasNew]['nested_parenthesis'])) {
                foreach ($tokens[$hasNew]['nested_parenthesis'] as $opener => $closer) {
                    // Always count outer parentheses.
                    if ($opener < $start) {
                        ++$currentNestingLevel;
                        continue;
                    }

                    // Only count inner parentheses when they are not for an array.
                    $owner = Parentheses::getOwner($phpcsFile, $opener);
                    if ($owner === false || $tokens[$owner]['code'] !== \T_ARRAY) {
                        ++$currentNestingLevel;
                    }
                }
            }

            $current = ($hasNew + 1);

            if ($currentNestingLevel !== $targetNestingLevel) {
                continue;
            }

            // Only throw an error if this is a non-dynamic object instantiation. Dynamic is still not supported.
            $isNameInvalid     = $phpcsFile->findNext($allowedNameTokens + Tokens::$emptyTokens, ($hasNew + 1), $end, true);
            $hasValidNameToken = $phpcsFile->findNext($allowedNameTokens, ($hasNew + 1), $end);

            if ($hasValidNameToken !== false
                && ($isNameInvalid === false
                || ($tokens[$isNameInvalid]['code'] === \T_OPEN_PARENTHESIS && $hasValidNameToken < $isNameInvalid))
            ) {
                $phpcsFile->addError($error, $hasNew, $errorCode, $data);
                return;
            }
        }
    }
}
