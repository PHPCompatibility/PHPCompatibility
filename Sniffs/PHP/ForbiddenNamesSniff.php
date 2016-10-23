<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenNamesSniff.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenNamesSniff.
 *
 * Prohibits the use of reserved keywords as class, function, namespace or constant names.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenNamesSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of keywords that can not be used as function, class and namespace name or constant name.
     * Mentions since which version it's not allowed.
     *
     * @var array(string => string)
     */
    protected $invalidNames = array(
        'abstract' => '5.0',
        'and' => 'all',
        'array' => 'all',
        'as' => 'all',
        'break' => 'all',
        'callable' => '5.4',
        'case' => 'all',
        'catch' => '5.0',
        'class' => 'all',
        'clone' => '5.0',
        'const' => 'all',
        'continue' => 'all',
        'declare' => 'all',
        'default' => 'all',
        'do' => 'all',
        'else' => 'all',
        'elseif' => 'all',
        'enddeclare' => 'all',
        'endfor' => 'all',
        'endforeach' => 'all',
        'endif' => 'all',
        'endswitch' => 'all',
        'endwhile' => 'all',
        'extends' => 'all',
        'final' => '5.0',
        'finally' => '5.5',
        'for' => 'all',
        'foreach' => 'all',
        'function' => 'all',
        'global' => 'all',
        'goto' => '5.3',
        'if' => 'all',
        'implements' => '5.0',
        'interface' => '5.0',
        'instanceof' => '5.0',
        'insteadof' => '5.4',
        'namespace' => '5.3',
        'new' => 'all',
        'or' => 'all',
        'private' => '5.0',
        'protected' => '5.0',
        'public' => '5.0',
        'static' => 'all',
        'switch' => 'all',
        'throw' => '5.0',
        'trait' => '5.4',
        'try' => '5.0',
        'use' => 'all',
        'var' => 'all',
        'while' => 'all',
        'xor' => 'all',
        '__class__' => 'all',
        '__dir__' => '5.3',
        '__file__' => 'all',
        '__function__' => 'all',
        '__method__' => 'all',
        '__namespace__' => '5.3',
    );

    /**
     * A list of keywords that can follow use statements.
     *
     * @var array(string => string)
     */
    protected $validUseNames = array(
        'const'    => true,
        'function' => true,
    );

    /**
     * Whether PHPCS 1.x is used or not.
     *
     * @var bool
     */
    protected $isLowPHPCS = false;

    /**
     * targetedTokens
     *
     * @var array
     */
    protected $targetedTokens = array(
        T_CLASS,
        T_FUNCTION,
        T_NAMESPACE,
        T_STRING,
        T_CONST,
        T_USE,
        T_AS,
        T_EXTENDS,
        T_TRAIT,
        T_INTERFACE,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $this->isLowPHPCS = version_compare(PHP_CodeSniffer::VERSION, '2.0', '<');

        $tokens = $this->targetedTokens;
        if (defined('T_ANON_CLASS')) {
            $tokens[] = constant('T_ANON_CLASS');
        }
        return $tokens;
    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        /**
         * We distinguish between the class, function and namespace names vs the define statements.
         */
        if ($tokens[$stackPtr]['type'] === 'T_STRING') {
            $this->processString($phpcsFile, $stackPtr, $tokens);
        } else {
            $this->processNonString($phpcsFile, $stackPtr, $tokens);
        }
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     * @param array                $tokens    The stack of tokens that make up
     *                                        the file.
     *
     * @return void
     */
    public function processNonString(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
    {
        $nextNonEmpty = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($nextNonEmpty === false) {
            return;
        }

        /*
         * PHP 5.6 allows for use const and use function, but only if followed by the function/constant name.
         * - `use function HelloWorld` => move to the next token (HelloWorld) to verify.
         * - `use const HelloWorld` => move to the next token (HelloWorld) to verify.
         */
        if ($tokens[$stackPtr]['type'] === 'T_USE'
            && isset($this->validUseNames[strtolower($tokens[$nextNonEmpty]['content'])]) === true
            && $this->supportsAbove('5.6')
        ) {
            $maybeUseNext = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($nextNonEmpty + 1), null, true, null, true);
            if ($maybeUseNext !== false && $this->isEndOfUseStatement($tokens[$maybeUseNext]) === false) {
                // Prevent duplicate messages: `const` is T_CONST in PHPCS 1.x and T_STRING in PHPCS 2.x.
                if ($this->isLowPHPCS === true) {
                    return;
                }
                $nextNonEmpty = $maybeUseNext;
            }
        }

        /*
         * Deal with visibility modifiers.
         * - `use HelloWorld { sayHello as protected; }` => valid, bow out.
         * - `use HelloWorld { sayHello as private myPrivateHello; }` => move to the next token to verify.
         */
        else if ($tokens[$stackPtr]['type'] === 'T_AS'
            && in_array($tokens[$nextNonEmpty]['code'], PHP_CodeSniffer_Tokens::$scopeModifiers, true) === true
            && $this->inUseScope($phpcsFile, $stackPtr) === true
        ) {
            $maybeUseNext = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($nextNonEmpty + 1), null, true, null, true);
            if ($maybeUseNext === false || $this->isEndOfUseStatement($tokens[$maybeUseNext]) === true) {
                return;
            }

            $nextNonEmpty = $maybeUseNext;
        }

        $nextContentLc = strtolower($tokens[$nextNonEmpty]['content']);
        if (isset($this->invalidNames[$nextContentLc]) === false) {
            return;
        }

        // Deal with anonymous classes.
        $prevNonEmpty = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($prevNonEmpty !== false
            && $tokens[$prevNonEmpty]['type'] === 'T_NEW'
            && $tokens[$stackPtr]['type'] === 'T_ANON_CLASS'
        ) {
            return;
        }

        if ($this->supportsAbove($this->invalidNames[$nextContentLc])) {
            $error = "Function name, class name, namespace name or constant name can not be reserved keyword '%s' (since version %s)";
            $data  = array(
                $tokens[$nextNonEmpty]['content'],
                $this->invalidNames[$nextContentLc],
            );
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
        }

    }//end processNonString()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     * @param array                $tokens    The stack of tokens that make up
     *                                        the file.
     *
     * @return void
     */
    public function processString(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
    {
        $tokenContentLc = strtolower($tokens[$stackPtr]['content']);

        // Special case for 5.3 where we want to find usage of traits, but
        // trait is not a token.
        if ($tokenContentLc === 'trait') {
            $this->processNonString($phpcsFile, $stackPtr, $tokens);
            return;
        }

        // Look for any define/defined tokens (both T_STRING ones, blame Tokenizer).
        if ($tokenContentLc !== 'define' && $tokenContentLc !== 'defined') {
            return;
        }

        // Retrieve the define(d) constant name.
        $firstParam = $this->getFunctionCallParameter($phpcsFile, $stackPtr, 1);
        if ($firstParam === false) {
            return;
        }

        $defineName = strtolower($firstParam['raw']);
        $defineName = $this->stripQuotes($defineName);

        if (isset($this->invalidNames[$defineName]) && $this->supportsAbove($this->invalidNames[$defineName])) {
            $error = "Function name, class name, namespace name or constant name can not be reserved keyword '%s' (since PHP version %s)";
            $data  = array(
                $defineName,
                $this->invalidNames[$defineName],
            );
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
        }
    }//end processString()


    /**
     * Check if the current token code is for a token which can be considered
     * the end of a (partial) use statement.
     *
     * @param int $token The current token information.
     *
     * @return bool
     */
    protected function isEndOfUseStatement($token)
    {
        return in_array($token['code'], array(T_CLOSE_CURLY_BRACKET, T_SEMICOLON, T_COMMA), true);
    }
}//end class
