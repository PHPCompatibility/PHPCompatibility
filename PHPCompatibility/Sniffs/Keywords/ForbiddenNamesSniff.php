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
 */
class ForbiddenNamesSniff extends Sniff
{

    /**
     * A list of keywords that can not be used as function, class and namespace name or constant name.
     * Mentions since which version it's not allowed.
     *
     * @since 5.5
     *
     * @var array(string => string)
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
        'namespace'     => '5.3',
        'new'           => 'all',
        'or'            => 'all',
        'print'         => 'all',
        'private'       => '5.0',
        'protected'     => '5.0',
        'public'        => '5.0',
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
     * Scope modifiers and other keywords allowed in trait use statements.
     *
     * @since 7.1.4
     *
     * @var array
     */
    private $allowedModifiers = [];

    /**
     * Targeted tokens.
     *
     * @since 5.5
     *
     * @var array
     */
    protected $targetedTokens = [
        \T_NAMESPACE,
        \T_CLASS,
        \T_INTERFACE,
        \T_TRAIT,
        \T_FUNCTION,
        \T_CONST,
        \T_STRING, // Function calls to `define()`.
        \T_USE,
        \T_ANON_CLASS,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.5
     *
     * @return array
     */
    public function register()
    {
        $this->allowedModifiers           = Tokens::$scopeModifiers;
        $this->allowedModifiers[\T_FINAL] = \T_FINAL;

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

        switch ($tokens[$stackPtr]['type']) {
            case 'T_NAMESPACE':
                $this->processNamespaceDeclaration($phpcsFile, $stackPtr);
                return;

            case 'T_CLASS':
            case 'T_INTERFACE':
            case 'T_TRAIT':
                $this->processOODeclaration($phpcsFile, $stackPtr);
                return;

            case 'T_FUNCTION':
                $this->processFunctionDeclaration($phpcsFile, $stackPtr);
                return;

            case 'T_CONST':
                $this->processConstDeclaration($phpcsFile, $stackPtr);
                return;

            case 'T_STRING':
                $this->processString($phpcsFile, $stackPtr);
                return;

            case 'T_USE':
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
        }

        /*
         * We distinguish between the class, function and namespace names vs the define statements.
         */
        if ($tokens[$stackPtr]['type'] !== 'T_STRING') {
            $this->processNonString($phpcsFile, $stackPtr, $tokens);
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

        for ($i = $next; $i < $endOfStatement; $i++) {
            if (isset(Tokens::$emptyTokens[$tokens[$i]['code']]) === true
                || $tokens[$i]['code'] === \T_NS_SEPARATOR
            ) {
                continue;
            }

            $this->checkName($phpcsFile, $i, $tokens[$i]['content']);
        }
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
        $name = ObjectDeclarations::getName($phpcsFile, $stackPtr);
        if (isset($name) === false || $name === '') {
            return;
        }

        $this->checkName($phpcsFile, $stackPtr, $name);
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
        /*
         * Deal with PHP 7 relaxing the rules.
         * "As of PHP 7.0.0 these keywords are allowed as property, constant, and method names
         * of classes, interfaces and traits."
         */
        if (Scopes::isOOMethod($phpcsFile, $stackPtr) === true
            && $this->supportsBelow('5.6') === false
        ) {
            return;
        }

        $name = FunctionDeclarations::getName($phpcsFile, $stackPtr);
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
         */
        if ($nameLc !== 'class'
            && Scopes::isOOConstant($phpcsFile, $stackPtr) === true
            && $this->supportsBelow('5.6') === false
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
        $firstParam = PassedParameters::getParameter($phpcsFile, $stackPtr, 1);
        if ($firstParam === false) {
            return;
        }

        $defineName = TextStrings::stripQuotes($firstParam['clean']);
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

        $current = ($stackPtr + 1);
        while ($current < $endOfStatement) {
            $asPtr = $phpcsFile->findNext(\T_AS, $current, $endOfStatement);
            if ($asPtr === false) {
                break;
            }

            $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($asPtr + 1), $endOfStatement, true);
            if ($nextNonEmpty === false) {
                break;
            }

            $this->checkName($phpcsFile, $nextNonEmpty, $tokens[$nextNonEmpty]['content']);

            $current = ($nextNonEmpty + 1);
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
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     * @param array                       $tokens    The stack of tokens that make up
     *                                               the file.
     *
     * @return void
     */
    public function processNonString(File $phpcsFile, $stackPtr, $tokens)
    {
        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextNonEmpty === false) {
            return;
        }

        /*
         * Deal with anonymous classes - `class` before a reserved keyword is sometimes
         * misidentified as `T_ANON_CLASS`.
         */
        if ($tokens[$stackPtr]['code'] === \T_ANON_CLASS || $tokens[$stackPtr]['code'] === \T_CLASS) {
            $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
            if ($prevNonEmpty !== false && $tokens[$prevNonEmpty]['code'] === \T_NEW) {
                return;
            }
        }

        $this->checkName($phpcsFile, $stackPtr, $tokens[$nextNonEmpty]['content']);
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
            || $this->supportsAbove($this->invalidNames[$name])
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
}
