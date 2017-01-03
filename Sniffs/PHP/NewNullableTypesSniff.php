<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewNullableTypes.
 *
 * PHP version 7.1
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewNullableTypes.
 *
 * Nullable type hints and return types are available since PHP 7.1.
 *
 * PHP version 7.1
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_NewNullableTypesSniff extends PHPCompatibility_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $tokens = array(
            T_FUNCTION,
        );

        if (defined('T_RETURN_TYPE')) {
            $tokens[] = T_RETURN_TYPE;
        }

        return $tokens;

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('7.0') === false) {
            return;
        }

        $tokens    = $phpcsFile->getTokens();
        $tokenCode = $tokens[$stackPtr]['code'];

        if ($tokenCode === T_FUNCTION) {
            $this->processFunctionDeclaration($phpcsFile, $stackPtr);

            // Deal with older PHPCS version which don't recognize return type hints.
            if (isset($tokens[$stackPtr]['parenthesis_closer'], $tokens[$stackPtr]['scope_opener']) === true
                && ($tokens[$stackPtr]['parenthesis_closer'] + 1) !== $tokens[$stackPtr]['scope_opener']
            ) {
                $hasColon = $phpcsFile->findNext(array(T_COLON, T_INLINE_ELSE), ($tokens[$stackPtr]['parenthesis_closer'] + 1), $tokens[$stackPtr]['scope_opener']);
                if ($hasColon !== false) {

                    // `self` and `callable` are not being recognized as return types in PHPCS < 2.6.0.
                    $unrecognizedTypes = array(T_CALLABLE, T_SELF);
                    // Return types are not recognized at all in PHPCS < 2.4.0.
                    if (defined('T_RETURN_TYPE') === false) {
                        $unrecognizedTypes = array(T_CALLABLE, T_SELF, T_ARRAY, T_STRING);
                    }

                    $hasUnrecognizedReturnTypeHint = $phpcsFile->findNext($unrecognizedTypes, ($hasColon + 1), $tokens[$stackPtr]['scope_opener']);
                    if ($hasUnrecognizedReturnTypeHint !== false) {
                        $this->processReturnType($phpcsFile, $hasUnrecognizedReturnTypeHint);
                    }
                }
            }
        } else {
            $this->processReturnType($phpcsFile, $stackPtr);
        }

    }//end process()


    /**
     * Process this test for function tokens.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    protected function processFunctionDeclaration(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $params = $this->getMethodParameters($phpcsFile, $stackPtr);

        if (empty($params) === false && is_array($params)) {
            foreach ($params as $param) {
                if ($param['nullable_type'] === true) {
                    $phpcsFile->addError(
                        'Nullable type declarations are not supported in PHP 7.0 or earlier. Found: %s',
                        $stackPtr,
                        'typeDeclarationFound',
                        array($param['type_hint'])
                    );
                }
            }
        }
    }


    /**
     * Process this test for return type tokens.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    protected function processReturnType(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if ($tokens[($stackPtr - 1)]['code'] === T_INLINE_THEN) {
            $phpcsFile->addError(
                'Nullable return types are not supported in PHP 7.0 or earlier.',
                $stackPtr,
                'returnTypeFound'
            );
        }
    }

}//end class
