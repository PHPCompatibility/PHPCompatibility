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

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\BackCompat\BCTokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Conditions;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\Namespaces;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\Scopes;
use PHPCSUtils\Utils\TextStrings;
use PHPCSUtils\Utils\UseStatements;

/**
 * Detects the use of reserved keywords as class, function, namespace or constant names.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/reserved.keywords.php
 *
 * @since 5.5
 * @since 10.0.0 Strictly checks declarations and aliases only.
 */
class ForbiddenNamesSniff extends Sniff
{

    /**
     * A list of keywords that can not be used as function, class and namespace name or constant name.
     * Mentions since which version it's not allowed.
     *
     * @since 5.5
     *
     * @var array<string, string>
     */
    protected $invalidNames = [
        'abstract'      => '5.0',
        'and'           => 'all',
        'array'         => 'all',
        'as'            => 'all',
        'break'         => 'all',
        'callable'      => '5.4',
        'case'          => 'all',
        'catch'         => '5.0',
        'class'         => 'all',
        'clone'         => '5.0',
        'const'         => 'all',
        'continue'      => 'all',
        'declare'       => 'all',
        'default'       => 'all',
        'die'           => 'all',
        'do'            => 'all',
        'echo'          => 'all',
        'else'          => 'all',
        'elseif'        => 'all',
        'empty'         => 'all',
        'enddeclare'    => 'all',
        'endfor'        => 'all',
        'endforeach'    => 'all',
        'endif'         => 'all',
        'endswitch'     => 'all',
        'endwhile'      => 'all',
        'eval'          => 'all',
        'exit'          => 'all',
        'extends'       => 'all',
        'final'         => '5.0',
        'finally'       => '5.5',
        'fn'            => '7.4',
        'for'           => 'all',
        'foreach'       => 'all',
        'function'      => 'all',
        'global'        => 'all',
        'goto'          => '5.3',
        'if'            => 'all',
        'implements'    => '5.0',
        'include'       => 'all',
        'include_once'  => 'all',
        'instanceof'    => '5.0',
        'insteadof'     => '5.4',
        'interface'     => '5.0',
        'isset'         => 'all',
        'list'          => 'all',
        'match'         => '8.0',
        'namespace'     => '5.3',
        'new'           => 'all',
        'or'            => 'all',
        'print'         => 'all',
        'private'       => '5.0',
        'protected'     => '5.0',
        'public'        => '5.0',
        'readonly'      => '8.1',
        'require'       => 'all',
        'require_once'  => 'all',
        'return'        => 'all',
        'static'        => 'all',
        'switch'        => 'all',
        'throw'         => '5.0',
        'trait'         => '5.4',
        'try'           => '5.0',
        'unset'         => 'all',
        'use'           => 'all',
        'var'           => 'all',
        'while'         => 'all',
        'xor'           => 'all',
        'yield'         => '5.5',
        '__class__'     => 'all',
        '__dir__'       => '5.3',
        '__file__'      => 'all',
        '__function__'  => 'all',
        '__line__'      => 'all',
        '__method__'    => 'all',
        '__namespace__' => '5.3',
        '__trait__'     => '5.4',
    ];

    /**
     * T_STRING keywords to recognize as forbidden names.
     *
     * @since 7.0.8
     * @since 10.0.0 Moved from the ForbiddenNamesAsDeclared sniff to this sniff.
     *
     * @var array<string, string>
     */
    protected $otherForbiddenNames = [
        'null'     => '7.0',
        'true'     => '7.0',
        'false'    => '7.0',
        'bool'     => '7.0',
        'int'      => '7.0',
        'float'    => '7.0',
        'string'   => '7.0',
        'iterable' => '7.1',
        'void'     => '7.1',
        'object'   => '7.2',
        'mixed'    => '8.0',
        'never'    => '8.1',
    ];

    /**
     * T_STRING keywords to recognize as soft reserved names.
     *
     * Using any of these keywords to name a class, interface, trait or namespace
     * is highly discouraged since they may be used in future versions of PHP.
     *
     * @since 7.0.8
     * @since 10.0.0 Moved from the ForbiddenNamesAsDeclared sniff to this sniff.
     *
     * @var array<string, string>
     */
    protected $softReservedNames = [
        'resource' => '7.0',
        'object'   => '7.0',
        'mixed'    => '7.0',
        'numeric'  => '7.0',
        'enum'     => '8.1',
    ];

    /**
     * Combined list of the two lists above.
     *
     * Used for quick check whether or not something is a reserved
     * word.
     * Set from the `register()` method.
     *
     * @since 7.0.8
     * @since 10.0.0 Moved from the ForbiddenNamesAsDeclared sniff to this sniff.
     *
     * @var array<string, string>
     */
    private $allOtherForbiddenNames = [];

    /**
     * A list of keywords that can follow use statements.
     *
     * @since 7.0.1
     *
     * @var array<string, true>
     */
    protected $validUseNames = [
        'const'    => true,
        'function' => true,
    ];

    /**
     * Scope modifiers and other keywords allowed in trait use statements.
     *
     * @since 7.1.4
     *
     * @var array<int|string, int|string>
     */
    private $allowedModifiers = [];

    /**
     * Targeted tokens.
     *
     * @since 5.5
     *
     * @var array<int|string>
     */
    protected $targetedTokens = [
        \T_NAMESPACE,
        \T_CLASS,
        \T_INTERFACE,
        \T_TRAIT,
        \T_ENUM,
        \T_FUNCTION,
        \T_CONST,
        \T_STRING, // Function calls to `define()`.
        \T_USE,
        \T_ANON_CLASS, // Only for a specific tokenizer issue.
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.5
     *
     * @return array<int|string>
     */
    public function register()
    {
        $this->allowedModifiers           = Tokens::$scopeModifiers;
        $this->allowedModifiers[\T_FINAL] = \T_FINAL;

        // Do the "other reserved keywords" list merge only once.
        $this->allOtherForbiddenNames = \array_merge($this->otherForbiddenNames, $this->softReservedNames);

        return $this->targetedTokens;
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        switch ($tokens[$stackPtr]['code']) {
            case \T_NAMESPACE:
                $this->processNamespaceDeclaration($phpcsFile, $stackPtr);
                return;

            case \T_CLASS:
            case \T_INTERFACE:
            case \T_TRAIT:
            case \T_ENUM:
                $this->processOODeclaration($phpcsFile, $stackPtr);
                return;

            case \T_FUNCTION:
                $this->processFunctionDeclaration($phpcsFile, $stackPtr);
                return;

            case \T_CONST:
                $this->processConstDeclaration($phpcsFile, $stackPtr);
                return;

            case \T_STRING:
                /*
                 * Handle a very specific edge case `enum extends/implements`, where PHP itself does not
                 * correctly tokenize the keyword in PHP 8.1+.
                 * Additionally, handle that the PHPCS tokenizer does not backfill `enum` to `T_ENUM`
                 * when followed by a reserved keyword which can not be a valid name on PHP < 8.1.
                 * As these are edge cases/parse errors anyway, we cannot reasonably expect PHPCS to
                 * handle this.
                 */
                if (\strtolower($tokens[$stackPtr]['content']) === 'enum') {
                    $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
                    if ($tokens[$prevNonEmpty]['code'] === \T_DOUBLE_COLON
                        || $tokens[$prevNonEmpty]['code'] === \T_EXTENDS
                        || $tokens[$prevNonEmpty]['code'] === \T_IMPLEMENTS
                        || $tokens[$prevNonEmpty]['code'] === \T_NS_SEPARATOR
                    ) {
                        // Use of a construct named `enum`, not an enum declaration.
                        return;
                    }

                    $lastCondition = Conditions::getLastCondition($phpcsFile, $stackPtr);
                    if ($tokens[$lastCondition]['code'] === \T_USE) {
                        // Trait use conflict resolution. Ignore.
                        return;
                    }

                    $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
                    if ($nextNonEmpty === false) {
                        return;
                    }

                    $nextNonEmptyLC = \strtolower($tokens[$nextNonEmpty]['content']);
                    if (isset($this->invalidNames[$nextNonEmptyLC])) {
                        $this->checkName($phpcsFile, $stackPtr, $tokens[$nextNonEmpty]['content']);

                        $this->checkOtherName(
                            $phpcsFile,
                            $stackPtr,
                            $tokens[$nextNonEmpty]['content'],
                            $tokens[$stackPtr]['content'] . ' declaration'
                        );
                    }
                    return;
                }

                $this->processString($phpcsFile, $stackPtr);
                return;

            case \T_USE:
                $type = UseStatements::getType($phpcsFile, $stackPtr);

                if ($type === 'closure') {
                    // Not interested in closure use.
                    return;
                }

                if ($type === 'import') {
                    $this->processUseImportStatement($phpcsFile, $stackPtr);
                    return;
                }

                if ($type === 'trait') {
                    $this->processUseTraitStatement($phpcsFile, $stackPtr);
                    return;
                }

                /*
                 * When keywords are used in trait import statements, it sometimes confuses the PHPCS tokenizer
                 * and the 'conditions' aren't always correctly set, so we need to do an additional check for
                 * the last condition potentially being a previous trait T_USE.
                 */
                $traitScopes = BCTokens::ooScopeTokens();
                unset($traitScopes[\T_INTERFACE]);

                if (Conditions::hasCondition($phpcsFile, $stackPtr, $traitScopes) === false) {
                    return;
                }

                $current = $stackPtr;
                do {
                    $current = Conditions::getLastCondition($phpcsFile, $current);
                    if ($current === false) {
                        return;
                    }
                } while ($tokens[$current]['code'] === \T_USE);

                if (isset($traitScopes[$tokens[$current]['code']]) === true) {
                    $this->processUseTraitStatement($phpcsFile, $stackPtr);
                }

                return;

            case \T_ANON_CLASS:
                /*
                 * Deal with anonymous classes - `class` before a reserved keyword is sometimes
                 * misidentified as `T_ANON_CLASS`.
                 */
                $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
                if ($prevNonEmpty !== false && $tokens[$prevNonEmpty]['code'] === \T_NEW) {
                    return;
                }

                // Ok, so this isn't really an anonymous class.
                $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
                if ($nextNonEmpty === false) {
                    return;
                }

                $this->checkName($phpcsFile, $stackPtr, $tokens[$nextNonEmpty]['content']);
                return;
        }
    }

    /**
     * Processes namespace declarations.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    protected function processNamespaceDeclaration(File $phpcsFile, $stackPtr)
    {
        /*
         * Note: explicitly only excluding use of the keyword as an operator, not the "undetermined"
         * type, as the "undetermined" cases are often exactly the type of errors this sniff is trying to detect.
         *
         * Also note: that is also the reason to determine the namespace name within this method and
         * not to use the `Namespaces::getDeclaredName()` method.
         */
        $type = Namespaces::getType($phpcsFile, $stackPtr);
        if ($type === 'operator') {
            return;
        }

        $endOfStatement = $phpcsFile->findNext(Collections::namespaceDeclarationClosers(), ($stackPtr + 1));
        if ($endOfStatement === false) {
            // Live coding or parse error.
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $next   = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), ($endOfStatement + 1), true);
        if ($next === $endOfStatement || $tokens[$next]['code'] === \T_NS_SEPARATOR) {
            // Declaration of global namespace. I.e.: namespace {} or use as non-scoped operator.
            return;
        }

        /*
         * Deal with PHP 8 relaxing the rules.
         * "The namespace declaration will accept any name, including isolated reserved keywords.
         *  The only restriction is that the namespace name cannot start with a `namespace` segment"
         *
         * Note: keywords which didn't become reserved prior to PHP 8.0 should never be flagged
         * when used in namespace names, as they are not problematic in PHP < 8.0.
         */
        $nextContentLC = \strtolower($tokens[$next]['content']);
        if (ScannedCode::shouldRunOnOrBelow('7.4') === false
            && $nextContentLC !== 'namespace'
            && \strpos($nextContentLC, 'namespace\\') !== 0 // PHPCS 4.x.
        ) {
            return;
        }

        for ($i = $next; $i < $endOfStatement; $i++) {
            if (isset(Tokens::$emptyTokens[$tokens[$i]['code']]) === true
                || $tokens[$i]['code'] === \T_NS_SEPARATOR
            ) {
                continue;
            }

            if (isset(Collections::nameTokens()[$tokens[$i]['code']]) === true
                && $tokens[$i]['code'] !== \T_STRING
            ) {
                /*
                 * This is a PHP 8.0 "namespaced name as single token" token (PHPCS 4.x).
                 * This also means that there can be no whitespace or comments in the name.
                 */
                $parts = \explode('\\', $tokens[$i]['content']);
                $parts = \array_filter($parts); // Remove empties.

                if (empty($parts)) {
                    // Shouldn't be possible, but just in case.
                    continue;
                }

                foreach ($parts as $part) {
                    if ($this->isKeywordReservedPriorToPHP8($part) === true) {
                        $this->checkName($phpcsFile, $i, $part);
                        $this->checkOtherName($phpcsFile, $i, $part, 'namespace declaration');
                    }
                }
            } else {
                if ($this->isKeywordReservedPriorToPHP8($tokens[$i]['content']) === true) {
                    $this->checkName($phpcsFile, $i, $tokens[$i]['content']);
                    $this->checkOtherName($phpcsFile, $i, $tokens[$i]['content'], 'namespace declaration');
                }
            }
        }
    }

    /**
     * Check if a keyword was marked as reserved prior to PHP 8.0.
     *
     * Helper method for the `processNamespaceDeclaration()` method.
     *
     * Keywords which didn't become reserved prior to PHP 8.0 should never be flagged
     * when used in namespace names, as they are not problematic in PHP < 8.0.
     *
     * @param string $name The name to check.
     *
     * @return bool
     */
    private function isKeywordReservedPriorToPHP8($name)
    {
        $nameLC = \strtolower($name);

        if (isset($this->invalidNames[$nameLC]) === true
            && $this->invalidNames[$nameLC] !== 'all'
            && \version_compare($this->invalidNames[$nameLC], '8.0', '>=')
        ) {
            return false;
        }

        if (isset($this->softReservedNames[$nameLC]) === true
            && \version_compare($this->softReservedNames[$nameLC], '8.0', '>=')
        ) {
            return false;
        }

        if (isset($this->otherForbiddenNames[$nameLC]) === true
            && isset($this->softReservedNames[$nameLC]) === false
            && \version_compare($this->otherForbiddenNames[$nameLC], '8.0', '>=')
        ) {
            return false;
        }

        return true;
    }

    /**
     * Processes class/trait/interface declarations.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    protected function processOODeclaration(File $phpcsFile, $stackPtr)
    {
        $tokens        = $phpcsFile->getTokens();
        $lastCondition = Conditions::getLastCondition($phpcsFile, $stackPtr);
        if ($tokens[$lastCondition]['code'] === \T_USE) {
            // Trait use conflict resolution. Ignore.
            return;
        }

        $name = ObjectDeclarations::getName($phpcsFile, $stackPtr);
        if (isset($name) === false || $name === '') {
            return;
        }

        $this->checkName($phpcsFile, $stackPtr, $name);

        $tokens = $phpcsFile->getTokens();
        $this->checkOtherName($phpcsFile, $stackPtr, $name, $tokens[$stackPtr]['content'] . ' declaration');
    }

    /**
     * Processes function and method declarations.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    protected function processFunctionDeclaration(File $phpcsFile, $stackPtr)
    {
        $name = FunctionDeclarations::getName($phpcsFile, $stackPtr);
        if (empty($name)) {
            return;
        }

        $nameLC = \strtolower($name);

        /*
         * Deal with `readonly` being a reserved keyword, but still being allowed
         * as a function name.
         *
         * @link https://github.com/php/php-src/pull/7468 (PHP 8.1)
         * @link https://github.com/php/php-src/pull/9512 (PHP 8.2 follow-up)
         */
        if ($nameLC === 'readonly') {
            return;
        }

        if (isset($this->invalidNames[$nameLC]) === false) {
            return;
        }

        /*
         * Deal with PHP 7 relaxing the rules.
         * "As of PHP 7.0.0 these keywords are allowed as property, constant, and method names
         * of classes, interfaces and traits."
         *
         * Note: keywords which didn't become reserved prior to PHP 7.0 should never be flagged
         * when used as method names, as they are not problematic in PHP < 7.0.
         */
        if (Scopes::isOOMethod($phpcsFile, $stackPtr) === true
            && (ScannedCode::shouldRunOnOrBelow('5.6') === false
                || ($this->invalidNames[$nameLC] !== 'all'
                && \version_compare($this->invalidNames[$nameLC], '7.0', '>=')))
        ) {
            return;
        }

        $this->checkName($phpcsFile, $stackPtr, $name);
    }

    /**
     * Processes global/class constant declarations using the `const` keyword.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    protected function processConstDeclaration(File $phpcsFile, $stackPtr)
    {
        $namePtr = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($namePtr === false) {
            // Live coding or parse error.
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $name   = $tokens[$namePtr]['content'];
        $nameLc = \strtolower($name);
        if (isset($this->invalidNames[$nameLc]) === false) {
            return;
        }

        /*
         * Deal with PHP 7 relaxing the rules.
         * "As of PHP 7.0.0 these keywords are allowed as property, constant, and method names
         * of classes, interfaces and traits, except that class may not be used as constant name."
         *
         * Note: keywords which didn't become reserved prior to PHP 7.0 should never be flagged
         * when used as OO constant names, as they are not problematic in PHP < 7.0.
         */
        if ($nameLc !== 'class'
            && Scopes::isOOConstant($phpcsFile, $stackPtr) === true
            && (ScannedCode::shouldRunOnOrBelow('5.6') === false
                || ($this->invalidNames[$nameLc] !== 'all'
                && \version_compare($this->invalidNames[$nameLc], '7.0', '>=')))
        ) {
            return;
        }

        $this->checkName($phpcsFile, $namePtr, $name);
    }

    /**
     * Processes constant declarations via a function call to `define()`.
     *
     * @since 5.5
     * @since 10.0.0 - Removed the $tokens parameter.
     *               - Visibility changed from `public` to `protected`.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    protected function processString(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Look for function calls to `define()`.
        if (\strtolower($tokens[$stackPtr]['content']) !== 'define') {
            return;
        }

        // Retrieve the define(d) constant name.
        $constantName = PassedParameters::getParameter($phpcsFile, $stackPtr, 1, 'constant_name');
        if ($constantName === false) {
            return;
        }

        $defineName = TextStrings::stripQuotes($constantName['clean']);
        $this->checkName($phpcsFile, $stackPtr, $defineName);
    }

    /**
     * Processes alias declarations in import use statements.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    protected function processUseImportStatement(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $endOfStatement = $phpcsFile->findNext([\T_SEMICOLON, \T_CLOSE_TAG], ($stackPtr + 1));
        if ($endOfStatement === false) {
            // Live coding or parse error.
            return;
        }

        $checkOther   = true;
        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), $endOfStatement, true);
        if (isset($this->validUseNames[$tokens[$nextNonEmpty]['content']]) === true) {
            $checkOther = false;
        }

        $checkOtherLocal = true;
        $nextPtr         = $stackPtr;
        $find            = [
            \T_AS             => \T_AS,
            \T_OPEN_USE_GROUP => \T_OPEN_USE_GROUP,
        ];

        //$current = ($stackPtr + 1);
        while (($nextPtr + 1) < $endOfStatement) {
            $nextPtr = $phpcsFile->findNext($find, ($nextPtr + 1), $endOfStatement);
            if ($nextPtr === false) {
                break;
            }

            /*
             * Group use statements can contain substatements for function/const imports.
             * These _do_ have to be checked for the fully reserved names, but the reservation on "other" names
             * does not apply.
             *
             * To allow for this, check the first non-empty token after a group use open bracket and after a
             * comma to see if it is the `function` or `const` keyword.
             *
             * Note: the T_COMMA token is only added to `$find` if we've seen a group use open bracket.
             */
            if ($tokens[$nextPtr]['code'] === \T_OPEN_USE_GROUP
                || $tokens[$nextPtr]['code'] === \T_COMMA
            ) {
                if ($checkOther === false) {
                    // Function/const applies to the whole group use statement.
                    continue;
                }

                $checkOtherLocal = true;

                $nextPtr = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextPtr + 1), $endOfStatement, true);
                if ($nextPtr === false) {
                    // Group use with trailing comma.
                    break;
                }

                if (isset($this->validUseNames[$tokens[$nextPtr]['content']]) === true) {
                    $checkOtherLocal = false;
                }

                if ($tokens[$nextPtr]['code'] === \T_OPEN_USE_GROUP) {
                    $find[\T_COMMA] = \T_COMMA;
                }

                continue;
            }

            // Ok, so this must be an T_AS token.
            $nextPtr = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextPtr + 1), $endOfStatement, true);
            if ($nextPtr === false) {
                break;
            }

            $this->checkName($phpcsFile, $nextPtr, $tokens[$nextPtr]['content']);

            if ($checkOther === false || $checkOtherLocal === false) {
                continue;
            }

            $this->checkOtherName($phpcsFile, $nextPtr, $tokens[$nextPtr]['content'], 'import use alias');
        }
    }

    /**
     * Processes alias declarations in trait use statements.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    protected function processUseTraitStatement(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $openCurly = $phpcsFile->findNext([\T_OPEN_CURLY_BRACKET, \T_SEMICOLON], ($stackPtr + 1));
        if ($openCurly === false || $tokens[$openCurly]['code'] === \T_SEMICOLON) {
            return;
        }

        // OK, so we have an open curly, do we have a closer too ?.
        if (isset($tokens[$openCurly]['bracket_closer']) === false) {
            return;
        }

        $current = $stackPtr;
        $closer  = $tokens[$openCurly]['bracket_closer'];
        do {
            $asPtr = $phpcsFile->findNext(\T_AS, ($current + 1), $closer);
            if ($asPtr === false) {
                break;
            }

            $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($asPtr + 1), $closer, true);
            if ($nextNonEmpty === false) {
                break;
            }

            /*
             * Deal with visibility modifiers.
             * - `use HelloWorld { sayHello as protected; }` => valid.
             * - `use HelloWorld { sayHello as private myPrivateHello; }` => move to the next token to verify.
             */
            if (isset($this->allowedModifiers[$tokens[$nextNonEmpty]['code']]) === true) {
                $maybeUseNext = $phpcsFile->findNext(Tokens::$emptyTokens, ($nextNonEmpty + 1), $closer, true);
                if ($maybeUseNext === false) {
                    // Reached the end of the use statement.
                    break;
                }

                if ($tokens[$maybeUseNext]['code'] === \T_SEMICOLON) {
                    // Reached the end of a sub-statement.
                    $current = $maybeUseNext;
                    continue;
                }

                $nextNonEmpty = $maybeUseNext;
            }

            $this->checkName($phpcsFile, $nextNonEmpty, $tokens[$nextNonEmpty]['content']);

            $current = $nextNonEmpty;
        } while ($current !== false && $current < $closer);
    }

    /**
     * Check whether a particular name is a reserved keyword.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     * @param string                      $name      The declaration/alias name found.
     *
     * @return void
     */
    protected function checkName(File $phpcsFile, $stackPtr, $name)
    {
        $name = \strtolower($name);
        if (isset($this->invalidNames[$name]) === false) {
            return;
        }

        if ($this->invalidNames[$name] === 'all'
            || ScannedCode::shouldRunOnOrAbove($this->invalidNames[$name]) === true
        ) {
            $this->addError($phpcsFile, $stackPtr, $name);
        }
    }

    /**
     * Add the error message.
     *
     * @since 7.1.0
     * @since 10.0.0 Removed the $data parameter.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     * @param string                      $name      The declaration/alias name found in lowercase.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, $name)
    {
        $error     = "Function name, class name, namespace name or constant name can not be reserved keyword '%s' (since version %s)";
        $errorCode = MessageHelper::stringToErrorCode($name, true) . 'Found';

        // Display the magic constants in uppercase.
        $msgName = $name;
        if ($name[0] === '_' && $name[1] === '_') {
            $msgName = \strtoupper($name);
        }

        $data = [
            $msgName,
            $this->invalidNames[$name],
        ];

        $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
    }

    /**
     * Check whether a particular name is one of the "other" reserved keywords.
     *
     * @since 10.0.0 Moved from the ForbiddenNamesAsDeclared sniff to this sniff.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     * @param string                      $name      The declaration/alias name found.
     * @param string                      $type      The type of statement in which the keyword was found.
     *
     * @return void
     */
    protected function checkOtherName(File $phpcsFile, $stackPtr, $name, $type)
    {
        $name = \strtolower($name);
        if (isset($this->allOtherForbiddenNames[$name]) === false) {
            return;
        }

        if (ScannedCode::shouldRunOnOrAbove('7.0') === false) {
            return;
        }

        $this->addOtherReservedError($phpcsFile, $stackPtr, $name, $type);
    }

    /**
     * Add the error message for when one of the "other" reserved keywords is detected.
     *
     * @since 7.0.8
     * @since 10.0.0 Moved from the ForbiddenNamesAsDeclared sniff to this sniff.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     * @param string                      $name      The declaration/alias name found in lowercase.
     * @param string                      $type      The type of statement in which the keyword was found.
     *
     * @return void
     */
    protected function addOtherReservedError(File $phpcsFile, $stackPtr, $name, $type)
    {
        // Build up the error message.
        $error     = "'%s' is a";
        $isError   = null;
        $errorCode = MessageHelper::stringToErrorCode($name, true) . 'Found';
        $data      = [
            $name,
        ];

        if (isset($this->softReservedNames[$name]) === true
            && ScannedCode::shouldRunOnOrAbove($this->softReservedNames[$name]) === true
        ) {
            $error  .= ' soft reserved keyword as of PHP version %s';
            $isError = false;
            $data[]  = $this->softReservedNames[$name];
        }

        if (isset($this->otherForbiddenNames[$name]) === true
            && ScannedCode::shouldRunOnOrAbove($this->otherForbiddenNames[$name]) === true
        ) {
            if (isset($isError) === true) {
                $error .= ' and a';
            }
            $error  .= ' reserved keyword as of PHP version %s';
            $isError = true;
            $data[]  = $this->otherForbiddenNames[$name];
        }

        if (isset($isError) === true) {
            $error .= ' and should not be used to name a class, interface or trait or as part of a namespace (%s)';
            $data[] = $type;

            MessageHelper::addMessage($phpcsFile, $error, $stackPtr, $isError, $errorCode, $data);
        }
    }
}
