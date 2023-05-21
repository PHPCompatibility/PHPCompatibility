<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Helpers;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCSUtils\Utils\TextStrings;

/**
 * Helper functions to determine if a set of consecutive tokens complies with certain criteria.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is only intended for internal use by PHPCompatibility and is not part of the public API.
 * This also means that it has no promise of backward compatibility. Use at your own risk.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 10.0.0 These functions were moved from the generic `Sniff` class to this class.
 */
final class TokenGroup
{

    /**
     * Determine whether the tokens between $start and $end together form a positive number
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for `true`, `false` should be regarded as
     * "undetermined".
     *
     * Note: Zero is *not* regarded as a positive number.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $start       Start of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param int                         $end         End of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param bool                        $allowFloats Whether to only consider integers, or also floats.
     *
     * @return bool True if PHP would evaluate the snippet as a positive number.
     *              False if not or if it could not be reliably determined
     *              (variable or calculations and such).
     */
    public static function isPositiveNumber(File $phpcsFile, $start, $end, $allowFloats = false)
    {
        $number = self::isNumber($phpcsFile, $start, $end, $allowFloats);

        if ($number === false) {
            return false;
        }

        return ($number > 0);
    }

    /**
     * Determine whether the tokens between $start and $end together form a negative number
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for `true`, `false` should be regarded as
     * "undetermined".
     *
     * Note: Zero is *not* regarded as a negative number.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $start       Start of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param int                         $end         End of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param bool                        $allowFloats Whether to only consider integers, or also floats.
     *
     * @return bool True if PHP would evaluate the snippet as a negative number.
     *              False if not or if it could not be reliably determined
     *              (variable or calculations and such).
     */
    public static function isNegativeNumber(File $phpcsFile, $start, $end, $allowFloats = false)
    {
        $number = self::isNumber($phpcsFile, $start, $end, $allowFloats);

        if ($number === false) {
            return false;
        }

        return ($number < 0);
    }

    /**
     * Determine whether the tokens between $start and $end together form a number
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for "true-ish" values, `false` should
     * be regarded as "undetermined".
     *
     * @link https://3v4l.org/npTeM
     *
     * Mainly intended for examining variable assignments, function call parameters, array values
     * where the start and end of the snippet to examine is very clear.
     *
     * @since 8.2.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $start       Start of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param int                         $end         End of the snippet (inclusive), i.e. this
     *                                                 token will be examined as part of the
     *                                                 snippet.
     * @param bool                        $allowFloats Whether to only consider integers, or also floats.
     *
     * @return int|float|bool The number found if PHP would evaluate the snippet as a number.
     *                        The return type will be int if $allowFloats is false, if
     *                        $allowFloats is true, the return type will be float.
     *                        False will be returned when the snippet does not evaluate to a
     *                        number or if it could not be reliably determined
     *                        (variable or calculations and such).
     */
    public static function isNumber(File $phpcsFile, $start, $end, $allowFloats = false)
    {
        $stringTokens = Tokens::$heredocTokens + Tokens::$stringTokens;

        $validTokens             = [];
        $validTokens[\T_LNUMBER] = true;
        $validTokens[\T_TRUE]    = true; // Evaluates to int 1.
        $validTokens[\T_FALSE]   = true; // Evaluates to int 0.
        $validTokens[\T_NULL]    = true; // Evaluates to int 0.

        if ($allowFloats === true) {
            $validTokens[\T_DNUMBER] = true;
        }

        $maybeValidTokens = $stringTokens + $validTokens;

        $tokens         = $phpcsFile->getTokens();
        $searchEnd      = ($end + 1);
        $negativeNumber = false;

        if (isset($tokens[$start], $tokens[$searchEnd]) === false) {
            return false;
        }

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, $start, $searchEnd, true);
        while ($nextNonEmpty !== false
            && ($tokens[$nextNonEmpty]['code'] === \T_PLUS
            || $tokens[$nextNonEmpty]['code'] === \T_MINUS)
        ) {
            if ($tokens[$nextNonEmpty]['code'] === \T_MINUS) {
                $negativeNumber = ($negativeNumber === false) ? true : false;
            }

            $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonEmpty + 1), $searchEnd, true);
        }

        if ($nextNonEmpty === false || isset($maybeValidTokens[$tokens[$nextNonEmpty]['code']]) === false) {
            return false;
        }

        $content = false;
        if ($tokens[$nextNonEmpty]['code'] === \T_LNUMBER
            || $tokens[$nextNonEmpty]['code'] === \T_DNUMBER
        ) {
            $content = (float) $tokens[$nextNonEmpty]['content'];
        } elseif ($tokens[$nextNonEmpty]['code'] === \T_TRUE) {
            $content = 1.0;
        } elseif ($tokens[$nextNonEmpty]['code'] === \T_FALSE
            || $tokens[$nextNonEmpty]['code'] === \T_NULL
        ) {
            $content = 0.0;
        } elseif (isset($stringTokens[$tokens[$nextNonEmpty]['code']]) === true) {

            if ($tokens[$nextNonEmpty]['code'] === \T_START_HEREDOC
                || $tokens[$nextNonEmpty]['code'] === \T_START_NOWDOC
            ) {
                // Skip past heredoc/nowdoc opener to the first content.
                $firstDocToken = $phpcsFile->findNext([\T_HEREDOC, \T_NOWDOC], ($nextNonEmpty + 1), $searchEnd);
                if ($firstDocToken === false) {
                    // Live coding or parse error.
                    return false;
                }

                $stringContent = $content = $tokens[$firstDocToken]['content'];

                // Skip forward to the end in preparation for the next part of the examination.
                $nextNonEmpty = $phpcsFile->findNext([\T_END_HEREDOC, \T_END_NOWDOC], ($nextNonEmpty + 1), $searchEnd);
                if ($nextNonEmpty === false) {
                    // Live coding or parse error.
                    return false;
                }
            } else {
                // Gather subsequent lines for a multi-line string.
                for ($i = $nextNonEmpty; $i < $searchEnd; $i++) {
                    if ($tokens[$i]['code'] !== $tokens[$nextNonEmpty]['code']) {
                        break;
                    }
                    $content .= $tokens[$i]['content'];
                }

                $nextNonEmpty  = --$i;
                $content       = TextStrings::stripQuotes($content);
                $stringContent = $content;
            }

            /*
             * Regexes based on the formats outlined in the manual, created by JRF.
             * @link https://www.php.net/manual/en/language.types.float.php
             */
            $regexInt   = '`^\s*[0-9]+`';
            $regexFloat = '`^\s*(?:[+-]?(?:(?:(?P<LNUM>[0-9]+)|(?P<DNUM>([0-9]*\.(?P>LNUM)|(?P>LNUM)\.[0-9]*)))[eE][+-]?(?P>LNUM))|(?P>DNUM))`';

            $intString   = \preg_match($regexInt, $content, $intMatch);
            $floatString = \preg_match($regexFloat, $content, $floatMatch);

            // Does the text string start with a number ? If so, PHP would juggle it and use it as a number.
            if ($allowFloats === false) {
                if ($intString !== 1 || $floatString === 1) {
                    if ($floatString === 1) {
                        // Found float. Only integers targetted.
                        return false;
                    }

                    $content = 0.0;
                } else {
                    $content = (float) \trim($intMatch[0]);
                }
            } else {
                if ($intString !== 1 && $floatString !== 1) {
                    $content = 0.0;
                } else {
                    $content = ($floatString === 1) ? (float) \trim($floatMatch[0]) : (float) \trim($intMatch[0]);
                }
            }

            // Allow for different behaviour for hex numeric strings between PHP 5 vs PHP 7.
            if ($intString === 1 && \trim($intMatch[0]) === '0'
                && \preg_match('`^\s*(0x[A-Fa-f0-9]+)`', $stringContent, $hexNumberString) === 1
                && ScannedCode::shouldRunOnOrBelow('5.6') === true
            ) {
                // The filter extension still allows for hex numeric strings in PHP 7, so
                // use that to get the numeric value if possible.
                // If the filter extension is not available, the value will be zero, but so be it.
                if (\function_exists('filter_var')) {
                    $filtered = \filter_var($hexNumberString[1], \FILTER_VALIDATE_INT, \FILTER_FLAG_ALLOW_HEX);
                    if ($filtered !== false) {
                        $content = $filtered;
                    }
                }
            }
        }

        // OK, so we have a number, now is there still more code after it ?
        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonEmpty + 1), $searchEnd, true);
        if ($nextNonEmpty !== false) {
            return false;
        }

        if ($negativeNumber === true) {
            $content = -$content;
        }

        if ($allowFloats === false) {
            return (int) $content;
        }

        return $content;
    }

    /**
     * Determine whether the tokens between $start and $end together form a numberic calculation
     * as recognized by PHP.
     *
     * The outcome of this function is reliable for `true`, `false` should be regarded as "undetermined".
     *
     * Mainly intended for examining variable assignments, function call parameters, array values
     * where the start and end of the snippet to examine is very clear.
     *
     * @since 9.0.0
     * @since 10.0.0 This method is now static.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $start     Start of the snippet (inclusive), i.e. this
     *                                               token will be examined as part of the
     *                                               snippet.
     * @param int                         $end       End of the snippet (inclusive), i.e. this
     *                                               token will be examined as part of the
     *                                               snippet.
     *
     * @return bool
     */
    public static function isNumericCalculation(File $phpcsFile, $start, $end)
    {
        $arithmeticTokens = Tokens::$arithmeticTokens;

        $skipTokens   = Tokens::$emptyTokens;
        $skipTokens[] = \T_MINUS;
        $skipTokens[] = \T_PLUS;

        // Find the first arithmetic operator, but skip past +/- signs before numbers.
        $nextNonEmpty = ($start - 1);
        do {
            $nextNonEmpty       = $phpcsFile->findNext($skipTokens, ($nextNonEmpty + 1), ($end + 1), true);
            $arithmeticOperator = $phpcsFile->findNext($arithmeticTokens, ($nextNonEmpty + 1), ($end + 1));
        } while ($nextNonEmpty !== false && $arithmeticOperator !== false && $nextNonEmpty === $arithmeticOperator);

        if ($arithmeticOperator === false) {
            return false;
        }

        $tokens      = $phpcsFile->getTokens();
        $subsetStart = $start;
        $subsetEnd   = ($arithmeticOperator - 1);

        while (self::isNumber($phpcsFile, $subsetStart, $subsetEnd, true) !== false
            && isset($tokens[($arithmeticOperator + 1)]) === true
        ) {
            $subsetStart  = ($arithmeticOperator + 1);
            $nextNonEmpty = $arithmeticOperator;
            do {
                $nextNonEmpty       = $phpcsFile->findNext($skipTokens, ($nextNonEmpty + 1), ($end + 1), true);
                $arithmeticOperator = $phpcsFile->findNext($arithmeticTokens, ($nextNonEmpty + 1), ($end + 1));
            } while ($nextNonEmpty !== false && $arithmeticOperator !== false && $nextNonEmpty === $arithmeticOperator);

            if ($arithmeticOperator === false) {
                // Last calculation operator already reached.
                if (self::isNumber($phpcsFile, $subsetStart, $end, true) !== false) {
                    return true;
                }

                return false;
            }

            $subsetEnd = ($arithmeticOperator - 1);
        }

        return false;
    }

    /**
     * Determine whether the tokens between $start and $end could together represent a variable.
     *
     * @since 9.0.0
     * @since 10.0.0 This method is now static.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile          The file being scanned.
     * @param int                         $start              Starting point stack pointer. Inclusive.
     *                                                        I.e. this token should be taken into
     *                                                        account.
     * @param int                         $end                End point stack pointer. Exclusive.
     *                                                        I.e. this token should not be taken
     *                                                        into account.
     * @param int                         $targetNestingLevel The nesting level the variable should be at.
     *
     * @return bool
     */
    public static function isVariable(File $phpcsFile, $start, $end, $targetNestingLevel)
    {
        static $disallowedTokens, $bracketTokens;

        // Create the token arrays only once.
        if (isset($disallowedTokens, $bracketTokens) === false) {
            $disallowedTokens  = [
                \T_OPEN_PARENTHESIS => \T_OPEN_PARENTHESIS,
                \T_STRING_CONCAT    => \T_STRING_CONCAT,
            ];
            $disallowedTokens += Tokens::$assignmentTokens;
            $disallowedTokens += Tokens::$equalityTokens;
            $disallowedTokens += Tokens::$comparisonTokens;
            $disallowedTokens += Tokens::$operators;
            $disallowedTokens += Tokens::$booleanOperators;
            $disallowedTokens += Tokens::$castTokens;

            /*
             * List of brackets which can be part of a variable variable.
             *
             * Key is the open bracket token, value the close bracket token.
             */
            $bracketTokens = [
                \T_OPEN_CURLY_BRACKET  => \T_CLOSE_CURLY_BRACKET,
                \T_OPEN_SQUARE_BRACKET => \T_CLOSE_SQUARE_BRACKET,
            ];
        }

        $tokens = $phpcsFile->getTokens();

        // If no variable at all was found, then it's definitely a no-no.
        $hasVariable = $phpcsFile->findNext(\T_VARIABLE, $start, $end);
        if ($hasVariable === false) {
            return false;
        }

        // Check if the variable found is at the right level. Deeper levels are always an error.
        if (isset($tokens[$hasVariable]['nested_parenthesis'])
            && \count($tokens[$hasVariable]['nested_parenthesis']) !== $targetNestingLevel
        ) {
                return false;
        }

        // Ok, so the first variable is at the right level, now are there any
        // disallowed tokens within the empty() ?
        $hasBadToken = $phpcsFile->findNext($disallowedTokens, $start, $end);
        if ($hasBadToken === false) {
            return true;
        }

        // If there are also bracket tokens, the disallowed token might be part of a variable
        // variable, but if there are no bracket tokens, we know we have an error.
        $hasBrackets = $phpcsFile->findNext($bracketTokens, $start, $end);
        if ($hasBrackets === false) {
            return false;
        }

        // Ok, we have both a disallowed token as well as brackets, so we need to walk
        // the tokens of the variable variable.
        for ($i = $start; $i < $end; $i++) {
            // If this is a bracket token, skip to the end of the bracketed expression.
            if (isset($bracketTokens[$tokens[$i]['code']], $tokens[$i]['bracket_closer'])) {
                $i = $tokens[$i]['bracket_closer'];
                continue;
            }

            // If it's a disallowed token, not within brackets, we have an error.
            if (isset($disallowedTokens[$tokens[$i]['code']])) {
                return false;
            }
        }

        return true;
    }
}
