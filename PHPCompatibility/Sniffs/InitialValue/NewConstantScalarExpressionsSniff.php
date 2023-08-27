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
use PHPCompatibility\Helpers\TokenGroup;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\Arrays;
use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\PassedParameters;

/**
 * Detect constant scalar expressions being used to set an initial value.
 *
 * Since PHP 5.6, it is now possible to provide a scalar expression involving
 * numeric and string literals and/or constants in contexts where PHP previously
 * expected a static value, such as constant and property declarations and
 * default values for function parameters.
 *
 * PHP version 5.6
 *
 * @link https://www.php.net/manual/en/migration56.new-features.php#migration56.new-features.const-scalar-exprs
 * @link https://wiki.php.net/rfc/const_scalar_exprs
 *
 * @since 8.2.0
 * @since 10.0.0 This sniff now extends the `AbstractInitialValueSniff` class instead of the
 *               base `Sniff` class.
 */
class NewConstantScalarExpressionsSniff extends AbstractInitialValueSniff
{

    /**
     * Error message.
     *
     * @since 8.2.0
     *
     * @var string
     */
    const ERROR_PHRASE = 'Constant scalar expressions are not allowed %s in PHP 5.5 or earlier.';

    /**
     * Tokens which were allowed to be used in these declarations prior to PHP 5.6.
     *
     * This list will be enriched in the setProperties() method.
     *
     * @since 8.2.0
     *
     * @var array<int|string, int|string>
     */
    protected $safeOperands = [
        \T_LNUMBER                  => \T_LNUMBER,
        \T_DNUMBER                  => \T_DNUMBER,
        \T_CONSTANT_ENCAPSED_STRING => \T_CONSTANT_ENCAPSED_STRING,
        \T_TRUE                     => \T_TRUE,
        \T_FALSE                    => \T_FALSE,
        \T_NULL                     => \T_NULL,

        // Special cases:
        \T_NS_SEPARATOR             => \T_NS_SEPARATOR,
        /*
         * This can be neigh anything, but for any usage except constants,
         * the T_STRING will be combined with non-allowed tokens, so we should be good.
         */
        \T_STRING                   => \T_STRING,
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.2.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        // Set the properties up only once.
        $this->setProperties();

        return parent::register();
    }

    /**
     * Make some adjustments to the $safeOperands property.
     *
     * @since 8.2.0
     *
     * @return void
     */
    public function setProperties()
    {
        $this->safeOperands += Tokens::$heredocTokens;
        $this->safeOperands += Tokens::$magicConstants;
        $this->safeOperands += Tokens::$emptyTokens;
    }

    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 8.2.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrBelow('5.5') === false);
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
        $isStaticValue = $this->isStaticValue($phpcsFile, $start, ($end - 1));
        if ($isStaticValue === true) {
            // Not a constant scalar expression, nothing to do.
            return;
        }

        $this->throwError($phpcsFile, $stackPtr, $end, $type);
    }

    /**
     * Is a value declared and is the value declared constant as accepted in PHP 5.5 and lower ?
     *
     * @since 8.2.0
     * @since 10.0.0 The `$tokens` parameter which was previously at the second position,
     *               has been removed.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $start        The stackPtr from which to start examining.
     * @param int                         $end          The end of the value definition (inclusive),
     *                                                  i.e. this token will be examined as part of
     *                                                  the snippet.
     * @param int                         $nestedArrays Optional. Array nesting level when examining
     *                                                  the content of an array.
     *
     * @return bool
     */
    protected function isStaticValue(File $phpcsFile, $start, $end, $nestedArrays = 0)
    {
        $tokens        = $phpcsFile->getTokens();
        $nextNonSimple = $phpcsFile->findNext($this->safeOperands, $start, ($end + 1), true);
        if ($nextNonSimple === false) {
            return true;
        }

        /*
         * OK, so we have at least one token which needs extra examination.
         */
        switch ($tokens[$nextNonSimple]['code']) {
            case \T_MINUS:
            case \T_PLUS:
                if (TokenGroup::isNumber($phpcsFile, $start, $end, true) !== false) {
                    // Int or float with sign.
                    return true;
                }

                return false;

            case \T_NAMESPACE:
            case \T_PARENT:
            case \T_SELF:
            case \T_DOUBLE_COLON:
                $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonSimple + 1), ($end + 1), true);

                if ($tokens[$nextNonSimple]['code'] === \T_NAMESPACE) {
                    // Allow only `namespace\...`.
                    if ($nextNonEmpty === false || $tokens[$nextNonEmpty]['code'] !== \T_NS_SEPARATOR) {
                        return false;
                    }
                } elseif ($tokens[$nextNonSimple]['code'] === \T_PARENT
                    || $tokens[$nextNonSimple]['code'] === \T_SELF
                ) {
                    // Allow only `parent::` and `self::`.
                    if ($nextNonEmpty === false || $tokens[$nextNonEmpty]['code'] !== \T_DOUBLE_COLON) {
                        return false;
                    }
                } elseif ($tokens[$nextNonSimple]['code'] === \T_DOUBLE_COLON) {
                    // Allow only `T_STRING::T_STRING`.
                    if ($nextNonEmpty === false || $tokens[$nextNonEmpty]['code'] !== \T_STRING) {
                        return false;
                    }

                    $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($nextNonSimple - 1), null, true);
                    // No need to worry about parent/self, that's handled above and
                    // the double colon is skipped over in that case.
                    if ($prevNonEmpty === false || $tokens[$prevNonEmpty]['code'] !== \T_STRING) {
                        return false;
                    }
                }

                // Examine what comes after the namespace/parent/self/double colon, if anything.
                return $this->isStaticValue($phpcsFile, ($nextNonEmpty + 1), $end, $nestedArrays);

            case \T_ARRAY:
            case \T_OPEN_SHORT_ARRAY:
                ++$nestedArrays;

                $arrayItems = PassedParameters::getParameters($phpcsFile, $nextNonSimple);
                if (empty($arrayItems) === false) {
                    foreach ($arrayItems as $item) {
                        // Check for a double arrow, but only if it's for this array item, not for a nested array.
                        $doubleArrow = Arrays::getDoubleArrowPtr($phpcsFile, $item['start'], $item['end']);

                        if ($doubleArrow === false) {
                            if ($this->isStaticValue($phpcsFile, $item['start'], $item['end'], $nestedArrays) === false) {
                                return false;
                            }
                        } else {
                            // Examine array key.
                            if ($this->isStaticValue($phpcsFile, $item['start'], ($doubleArrow - 1), $nestedArrays) === false) {
                                return false;
                            }

                            // Examine array value.
                            if ($this->isStaticValue($phpcsFile, ($doubleArrow + 1), $item['end'], $nestedArrays) === false) {
                                return false;
                            }
                        }
                    }
                }

                --$nestedArrays;

                /*
                 * Find the end of the array.
                 * We already know we will have a valid closer as otherwise we wouldn't have been
                 * able to get the array items.
                 */
                $closer = ($nextNonSimple + 1);
                if ($tokens[$nextNonSimple]['code'] === \T_OPEN_SHORT_ARRAY
                    && isset($tokens[$nextNonSimple]['bracket_closer']) === true
                ) {
                    $closer = $tokens[$nextNonSimple]['bracket_closer'];
                } else {
                    $maybeOpener = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonSimple + 1), ($end + 1), true);
                    if ($tokens[$maybeOpener]['code'] === \T_OPEN_PARENTHESIS) {
                        $opener = $maybeOpener;
                        if (isset($tokens[$opener]['parenthesis_closer']) === true) {
                            $closer = $tokens[$opener]['parenthesis_closer'];
                        }
                    }
                }

                if ($closer === $end) {
                    return true;
                }

                // Examine what comes after the array, if anything.
                return $this->isStaticValue($phpcsFile, ($closer + 1), $end, $nestedArrays);
        }

        // Ok, so this unsafe token was not one of the exceptions, i.e. this is a PHP 5.6+ syntax.
        return false;
    }

    /**
     * Throw an error if a scalar expression is found.
     *
     * @since 8.2.0
     * @since 10.0.0 The `$end` parameter has been added, moving the `$type` parameter
     *               from the third to the fourth position.
     *               The previously optional fourth `$content` parameter has been removed.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the token to link the error to.
     * @param int                         $end       The end of the value definition (inclusive).
     * @param string                      $type      Type of usage found.
     *
     * @return void
     */
    protected function throwError(File $phpcsFile, $stackPtr, $end, $type)
    {
        $error     = static::ERROR_PHRASE;
        $errorCode = 'Found';
        $phrase    = '';

        if (isset($this->initialValueTypes[$type]) === true) {
            $errorCode = MessageHelper::stringToErrorCode($type) . 'Found';
            $phrase    = $this->initialValueTypes[$type];
        }

        $data = [$phrase];

        // Create the "found" snippet.
        $content    = '';
        $tokenCount = ($end - $stackPtr);
        if ($tokenCount < 20) {
            // Prevent large arrays from being added to the error message.
            $content = \trim(GetTokensAsString::noComments($phpcsFile, $stackPtr, $end));
        }

        if (empty($content) === false) {
            $error .= ' Found: %s';
            $data[] = $content;
        }

        $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
    }
}
