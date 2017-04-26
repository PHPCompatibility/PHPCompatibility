<?php
/**
 * PHPCompatibility_Sniffs_PHP_ForbiddenNamesAsDeclaredClassSniff.
 *
 * PHP version 7
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
 * PHP version 7
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
        T_FALSE => '7.0'
    );

    /**
     * T_STRING keywords to recognize as forbidden names.
     * The following words cannot be used to name a class, interface or trait, and
     * they are also prohibited from being used in namespaces. 
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
     * T_STRING keywords to recognize as reserved names.
     * The following list of words have had soft reservations placed on them.  They
     * may still be used as class, interface, and trait names (as well as in
     * namespaces), usage of them is highly discouraged since they may be used in
     * future versions of PHP.
     *
     * @var array
     */
    protected $reservedNames = array(
        'resource' => '7.0',
        'object'   => '7.0',
        'mixed'    => '7.0',
        'numeric'  => '7.0',
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
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

        // To simplify the rest of the code, we combine the two arrays into a single array.
        $forbiddenNames = array();
        foreach ($this->forbiddenNames as $key => $value) {
            $forbiddenNames[$key] = array($value, true);
        }
        foreach ($this->reservedNames as $key => $value) {
            $forbiddenNames[$key] = array($value, false);
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
            if (isset($forbiddenNames[$nameLc]) === false) {
                return;
            }

        } else if ($tokenCode === T_NAMESPACE) {
            $namespaceName = $this->getDeclaredNamespaceName($phpcsFile, $stackPtr);

            if ($namespaceName === false || $namespaceName === '') {
                return;
            }

            $namespaceParts = explode('\\', $namespaceName);
            foreach ($namespaceParts as $namespacePart) {
                $partLc = strtolower($namespacePart);
                if (isset($forbiddenNames[$partLc]) === true) {
                    $name   = $namespacePart;
                    $nameLc = $partLc;
                    break;
                }
            }
        } else if ($tokenCode === T_STRING) {
            // Traits and namespaces which are not yet tokenized as T_TRAIT/T_NAMESPACE.
            $nextNonEmpty = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
            if ($nextNonEmpty === false) {
                return;
            }

            $nextNonEmptyCode = $tokens[$nextNonEmpty]['code'];

            if ($nextNonEmptyCode !== T_STRING && isset($this->forbiddenTokens[$nextNonEmptyCode]) === true) {
                $name   = $tokens[$nextNonEmpty]['content'];
                $nameLc = strtolower($tokens[$nextNonEmpty]['content']);
            } else if ($nextNonEmptyCode === T_STRING) {
                $endOfStatement = $phpcsFile->findNext(array(T_SEMICOLON, T_OPEN_CURLY_BRACKET), ($stackPtr + 1));

                do {
                    $nextNonEmptyLc = strtolower($tokens[$nextNonEmpty]['content']);

                    if (isset($forbiddenNames[$nextNonEmptyLc]) === true) {
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
        $version = $forbiddenNames[$nameLc][0];
        if ($this->supportsAbove($version) === true) {
            $error     = "'%s' is a reserved keyword as of PHP version %s and cannot be used to name a class, interface or trait or as part of a namespace (%s)";
            $errorCode = $this->stringToErrorCode($nameLc).'Found';
            $data      = array(
                $nameLc,
                $version,
                $tokens[$stackPtr]['type'],
            );

            if ($forbiddenNames[$nameLc][1] === true) {
                $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
            } else {
                $phpcsFile->addWarning($error, $stackPtr, $errorCode, $data);
            }
        }
    }//end process()


}//end class
