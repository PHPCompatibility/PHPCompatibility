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

use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;

/**
 * Detect use of new PHP keywords.
 *
 * PHP version All
 *
 * @link https://wiki.php.net/rfc/heredoc-with-double-quotes
 * @link https://wiki.php.net/rfc/horizontalreuse (traits)
 * @link https://wiki.php.net/rfc/generators
 * @link https://wiki.php.net/rfc/finally
 * @link https://wiki.php.net/rfc/generator-delegation
 *
 * @since 5.5
 * @since 7.1.0  Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class..
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionNewFeatureTrait`.
 */
class NewKeywordsSniff extends Sniff
{
    use ComplexVersionNewFeatureTrait;

    /**
     * A list of new keywords, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the last version which did not contain the keyword.
     *
     * Description will be used as part of the error message.
     * Callback should be a callback which checks whether the token complies
     * with a certain condition.
     * The callback function will be passed the $phpcsFile and the $stackPtr.
     * The callback function should return `true` if the condition is met and the
     * error should *not* be thrown.
     *
     * {@internal This list does not contain `T_FN`.
     * The separate `Syntax.NewArrowFunction` sniff reports on that token.}
     *
     * @since 5.5
     * @since 7.0.3  Support for `condition` has been added.
     * @since 10.0.0 The `condition` index used to allow only for callback methods in this or the
     *               parent class. This index has been renamed to `callback` and now expect
     *               one of the PHP accepted callback formats.
     *
     * @var array<string, array<string, bool|string|callable>>
     */
    protected $newKeywords = [
        'T_HALT_COMPILER' => [
            '5.0'         => false,
            '5.1'         => true,
            'description' => '"__halt_compiler" keyword',
        ],
        'T_CONST' => [
            '5.2'         => false,
            '5.3'         => true,
            'description' => '"const" keyword',
            'callback'    => '\PHPCSUtils\Utils\Scopes::isOOConstant', // Keyword is only new when not in class context.
        ],
        'T_CALLABLE' => [
            '5.3'         => false,
            '5.4'         => true,
            'description' => '"callable" keyword',
        ],
        'T_DIR' => [
            '5.2'         => false,
            '5.3'         => true,
            'description' => '__DIR__ magic constant',
        ],
        'T_GOTO' => [
            '5.2'         => false,
            '5.3'         => true,
            'description' => '"goto" keyword',
        ],
        'T_INSTEADOF' => [
            '5.3'         => false,
            '5.4'         => true,
            'description' => '"insteadof" keyword (for traits)',
        ],
        'T_NAMESPACE' => [
            '5.2'         => false,
            '5.3'         => true,
            'description' => '"namespace" keyword',
        ],
        'T_NS_C' => [
            '5.2'         => false,
            '5.3'         => true,
            'description' => '__NAMESPACE__ magic constant',
        ],
        'T_USE' => [
            '5.2'         => false,
            '5.3'         => true,
            'description' => '"use" keyword (for traits/namespaces/anonymous functions)',
        ],
        'T_START_NOWDOC' => [
            '5.2'         => false,
            '5.3'         => true,
            'description' => 'nowdoc functionality',
        ],
        'T_END_NOWDOC' => [
            '5.2'         => false,
            '5.3'         => true,
            'description' => 'nowdoc functionality',
        ],
        'T_START_HEREDOC' => [
            '5.2'         => false,
            '5.3'         => true,
            'description' => '(Double) quoted Heredoc identifier',
            'callback'    => [__CLASS__, 'isNotQuoted'], // Heredoc is only new with quoted identifier.
        ],
        'T_TRAIT' => [
            '5.3'         => false,
            '5.4'         => true,
            'description' => '"trait" keyword',
        ],
        'T_TRAIT_C' => [
            '5.3'         => false,
            '5.4'         => true,
            'description' => '__TRAIT__ magic constant',
        ],
        'T_YIELD_FROM' => [
            '5.6'         => false,
            '7.0'         => true,
            'description' => '"yield from" keyword (for generators)',
        ],
        'T_YIELD' => [
            '5.4'         => false,
            '5.5'         => true,
            'description' => '"yield" keyword (for generators)',
        ],
        'T_FINALLY' => [
            '5.4'         => false,
            '5.5'         => true,
            'description' => '"finally" keyword (in exception handling)',
        ],
        'T_FN' => [
            '7.3'         => false,
            '7.4'         => true,
            'description' => 'The "fn" keyword for arrow functions',
        ],
        'T_MATCH' => [
            '7.4'         => false,
            '8.0'         => true,
            'description' => 'The "match" keyword',
        ],
        'T_ENUM' => [
            '8.0'         => false,
            '8.1'         => true,
            'description' => 'The "enum" keyword',
        ],
    ];

    /**
     * Translation table for T_STRING tokens.
     *
     * Will be set up from the register() method.
     *
     * @since 7.0.5
     *
     * @var array<string, string>
     */
    protected $translateContentToToken = [];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.5
     *
     * @return array<int|string>
     */
    public function register()
    {
        $tokens    = [];
        $translate = [];
        foreach ($this->newKeywords as $token => $versions) {
            if (\defined($token)) {
                $tokens[] = \constant($token);
            }
            if (isset($versions['content'])) {
                $translate[\strtolower($versions['content'])] = $token;
            }
        }

        /*
         * Deal with tokens not recognized by the PHP version the sniffer is run
         * under and (not correctly) compensated for by PHPCS.
         */
        if (empty($translate) === false) {
            $this->translateContentToToken = $translate;
            $tokens[]                      = \T_STRING;
        }

        return $tokens;
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $tokenType = $tokens[$stackPtr]['type'];

        // Allow for dealing with multi-token keywords, like "yield from".
        $end = $stackPtr;

        // Translate T_STRING token if necessary.
        if ($tokens[$stackPtr]['type'] === 'T_STRING') {
            $content = \strtolower($tokens[$stackPtr]['content']);

            if (isset($this->translateContentToToken[$content]) === false) {
                // Not one of the tokens we're looking for.
                return;
            }

            $tokenType = $this->translateContentToToken[$content];
        }

        /*
         * Special case: distinguish between `yield` and `yield from`.
         *
         * Prior to PHP 8.3, `yield from` with a comment between the keywords would be tokenized
         * as `T_YIELD`, `T_COMMENT`, T_STRING`.
         * As of PHP 8.3, this is correctly tokenized as `T_YIELD_FROM`.
         */
        if ($tokenType === 'T_YIELD') {
            $nextToken = $phpcsFile->findNext(Tokens::$emptyTokens, ($end + 1), null, true);
            if ($tokens[$nextToken]['code'] === \T_STRING
                && \strtolower($tokens[$nextToken]['content']) === 'from'
            ) {
                $tokenType = 'T_YIELD_FROM';
                $end       = $nextToken;
            }
            unset($nextToken);
        }

        if ($tokenType === 'T_YIELD_FROM' && $tokens[($stackPtr - 1)]['type'] === 'T_YIELD_FROM') {
            // Multi-line "yield from", no need to report it twice.
            return;
        }

        if (isset($this->newKeywords[$tokenType]) === false) {
            return;
        }

        $nextToken = $phpcsFile->findNext(Tokens::$emptyTokens, ($end + 1), null, true);
        $prevToken = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);

        if ($prevToken !== false
            && isset(Collections::objectOperators()[$tokens[$prevToken]['code']]) === true
        ) {
            // Class property of the same name as one of the keywords. Ignore.
            return;
        }

        // Skip attempts to use keywords as functions or class names - the former
        // will be reported by ForbiddenNamesAsInvokedFunctionsSniff, whilst the
        // latter will be (partially) reported by the ForbiddenNames sniff.
        // Either type will result in false-positives when targetting lower versions
        // of PHP where the name was not reserved, unless we explicitly check for
        // them.
        if (($nextToken === false
                || $tokenType === 'T_FN' // Open parenthesis is expected after "fn" keyword.
                || $tokenType === 'T_MATCH' // ... and after the "match" keyword.
                || $tokens[$nextToken]['type'] !== 'T_OPEN_PARENTHESIS')
            && ($prevToken === false
                || $tokens[$prevToken]['type'] !== 'T_CLASS'
                || $tokens[$prevToken]['type'] !== 'T_INTERFACE')
        ) {
            // Skip based on the output of a specific callback.
            if (isset($this->newKeywords[$tokenType]['callback'])
                && \call_user_func($this->newKeywords[$tokenType]['callback'], $phpcsFile, $stackPtr) === true
            ) {
                return;
            }

            $itemInfo = [
                'name' => $tokenType,
            ];
            $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
        }
    }


    /**
     * Callback for the quoted heredoc identifier condition.
     *
     * A double quoted identifier will have the opening quote at offset 3
     * in the string: `<<<"ID"`.
     *
     * @since 8.0.0
     * @since 10.0.0 This function is now a static function (to allow it to be set
     *               as a callback from a class property).
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return bool
     */
    public static function isNotQuoted(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        return ($tokens[$stackPtr]['content'][3] !== '"');
    }

    /**
     * Handle the retrieval of relevant information and - if necessary - throwing of an
     * error for a matched item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the relevant token in
     *                                               the stack.
     * @param array                       $itemInfo  Base information about the item.
     *
     * @return void
     */
    protected function handleFeature(File $phpcsFile, $stackPtr, array $itemInfo)
    {
        $itemArray   = $this->newKeywords[$itemInfo['name']];
        $versionInfo = $this->getVersionInfo($itemArray);

        if (empty($versionInfo['not_in_version'])
            || ScannedCode::shouldRunOnOrBelow($versionInfo['not_in_version']) === false
        ) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $itemInfo, $itemArray, $versionInfo);
    }


    /**
     * Generates the error for this item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param array                       $itemInfo    Base information about the item.
     * @param array                       $itemArray   The sub-array with all the details about
     *                                                 this item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, array $itemInfo, array $itemArray, array $versionInfo)
    {
        $msgInfo = $this->getMessageInfo($itemArray['description'], $itemInfo['name'], $versionInfo);

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
