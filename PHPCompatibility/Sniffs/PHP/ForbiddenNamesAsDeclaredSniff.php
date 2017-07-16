<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenNamesAsDeclaredClassSniff.
 *
 * PHP version 7.0+
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenNamesAsDeclaredClassSniff.
 *
 * Prohibits the use of some reserved keywords to name a class, interface, trait or namespace.
 * Emits errors for reserved words and warnings for soft-reserved words.
 *
 * @see http://php.net/manual/en/reserved.other-reserved-words.php
 *
 * PHP version 7.0+
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_ForbiddenNamesAsDeclaredSniff extends PHPCompatibility_Sniff
{

    /**
     * List of tokens which can not be used as class, interface, trait names or as part of a namespace.
     *
     * @var array
     */
    protected $forbiddenTokens = array(
        T_NULL  => '7.0',
        T_TRUE  => '7.0',
        T_FALSE => '7.0',
    );

    /**
     * T_STRING keywords to recognize as forbidden names.
     *
     * @var array
     */
    protected $forbiddenNames = array(
        'null'     => '7.0',
        'true'     => '7.0',
        'false'    => '7.0',
        'bool'     => '7.0',
        'int'      => '7.0',
        'float'    => '7.0',
        'string'   => '7.0',
        'iterable' => '7.1',
        'void'     => '7.1',
    );

    /**
     * T_STRING keywords to recognize as soft reserved names.
     *
     * Using any of these keywords to name a class, interface, trait or namespace
     * is highly discouraged since they may be used in future versions of PHP.
     *
     * @var array
     */
    protected $softReservedNames = array(
        'resource' => '7.0',
        'object'   => '7.0',
        'mixed'    => '7.0',
        'numeric'  => '7.0',
    );

    /**
     * Combined list of the two lists above.
     *
     * Used for quick check whether or not something is a reserved
     * word.
     * Set from the `register()` method.
     *
     * @var array
     */
    private $allForbiddenNames = array();


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // Do the list merge only once.
        $this->allForbiddenNames = array_merge($this->forbiddenNames, $this->softReservedNames);

        return array(
            T_CLASS,
            T_INTERFACE,
            T_NAMESPACE,
            T_TRAIT,
            T_STRING, // Compat for PHPCS 1.x and PHP < 5.3.
        );

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
        if ($this->supportsAbove('7.0') === false) {
            return;
        }

        $tokens         = $phpcsFile->getTokens();
        $tokenCode      = $tokens[$stackPtr]['code'];
        $tokenContentLc = strtolower($tokens[$stackPtr]['content']);

        // For string tokens we only care about 'trait' and 'namespace' as those are
        // the only ones which may not be correctly recognized as it's own token.
        // This only happens in older versions of PHP where the token doesn't exist yet as a keyword.
        if ($tokenCode === T_STRING && ($tokenContentLc !== 'trait' && $tokenContentLc !== 'namespace')) {
            return;
        }

        if (in_array($tokenCode, array(T_CLASS, T_INTERFACE, T_TRAIT), true)) {
            // Check for the declared name being a name which is not tokenized as T_STRING.
            $nextNonEmpty = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
            if ($nextNonEmpty !== false && isset($this->forbiddenTokens[$tokens[$nextNonEmpty]['code']]) === true) {
                $name = $tokens[$nextNonEmpty]['content'];
            } else {
                // Get the declared name if it's a T_STRING.
                $name = $phpcsFile->getDeclarationName($stackPtr);
            }
            unset($nextNonEmpty);

            if (isset($name) === false || is_string($name) === false || $name === '') {
                return;
            }

            $nameLc = strtolower($name);
            if (isset($this->allForbiddenNames[$nameLc]) === false) {
                return;
            }

        } elseif ($tokenCode === T_NAMESPACE) {
            $namespaceName = $this->getDeclaredNamespaceName($phpcsFile, $stackPtr);

            if ($namespaceName === false || $namespaceName === '') {
                return;
            }

            $namespaceParts = explode('\\', $namespaceName);
            foreach ($namespaceParts as $namespacePart) {
                $partLc = strtolower($namespacePart);
                if (isset($this->allForbiddenNames[$partLc]) === true) {
                    $name   = $namespacePart;
                    $nameLc = $partLc;
                    break;
                }
            }
        } elseif ($tokenCode === T_STRING) {
            // Traits and namespaces which are not yet tokenized as T_TRAIT/T_NAMESPACE.
            $nextNonEmpty = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
            if ($nextNonEmpty === false) {
                return;
            }

            $nextNonEmptyCode = $tokens[$nextNonEmpty]['code'];

            if ($nextNonEmptyCode !== T_STRING && isset($this->forbiddenTokens[$nextNonEmptyCode]) === true) {
                $name   = $tokens[$nextNonEmpty]['content'];
                $nameLc = strtolower($tokens[$nextNonEmpty]['content']);
            } elseif ($nextNonEmptyCode === T_STRING) {
                $endOfStatement = $phpcsFile->findNext(array(T_SEMICOLON, T_OPEN_CURLY_BRACKET), ($stackPtr + 1));
                if ($endOfStatement === false) {
                    return;
                }

                do {
                    $nextNonEmptyLc = strtolower($tokens[$nextNonEmpty]['content']);

                    if (isset($this->allForbiddenNames[$nextNonEmptyLc]) === true) {
                        $name   = $tokens[$nextNonEmpty]['content'];
                        $nameLc = $nextNonEmptyLc;
                        break;
                    }

                    $nextNonEmpty = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($nextNonEmpty + 1), $endOfStatement, true);
                } while ($nextNonEmpty !== false);
            }
            unset($nextNonEmptyCode, $nextNonEmptyLc, $endOfStatement);
        }

        if (isset($name, $nameLc) === false) {
            return;
        }

        // Still here, so this is one of the reserved words.
        // Build up the error message.
        $error     = "'%s' is a";
        $isError   = null;
        $errorCode = $this->stringToErrorCode($nameLc).'Found';
        $data      = array(
            $nameLc,
        );

        if (isset($this->softReservedNames[$nameLc]) === true
            && $this->supportsAbove($this->softReservedNames[$nameLc]) === true
        ) {
            $error  .= ' soft reserved keyword as of PHP version %s';
            $isError = false;
            $data[]  = $this->softReservedNames[$nameLc];
        }

        if (isset($this->forbiddenNames[$nameLc]) === true
            && $this->supportsAbove($this->forbiddenNames[$nameLc]) === true
        ) {
            if (isset($isError) === true) {
                $error .= ' and a';
            }
            $error  .= ' reserved keyword as of PHP version %s';
            $isError = true;
            $data[]  = $this->forbiddenNames[$nameLc];
        }

        if (isset($isError) === true) {
            $error .= ' and should not be used to name a class, interface or trait or as part of a namespace (%s)';
            $data[] = $tokens[$stackPtr]['type'];

            $this->addMessage($phpcsFile, $error, $stackPtr, $isError, $errorCode, $data);
        }

    }//end process()

}//end class
