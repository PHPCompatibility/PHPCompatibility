<?php
/**
 * \PHPCompatibility\Sniffs\PHP\NewNullableTypes.
 *
 * PHP version 7.1
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniff;
use PHPCompatibility\PHPCSHelper;

/**
 * \PHPCompatibility\Sniffs\PHP\NewNullableTypes.
 *
 * Nullable type hints and return types are available since PHP 7.1.
 *
 * PHP version 7.1
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class NewNullableTypesSniff extends Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * {@internal Not sniffing for T_NULLABLE which was introduced in PHPCS 2.7.2
     * as in that case we can't distinguish between parameter type hints and
     * return type hints for the error message.}}
     *
     * @return array
     */
    public function register()
    {
        $tokens = array(
            T_FUNCTION,
            T_CLOSURE,
        );

        if (defined('T_RETURN_TYPE')) {
            $tokens[] = T_RETURN_TYPE;
        }

        return $tokens;

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('7.0') === false) {
            return;
        }

        $tokens    = $phpcsFile->getTokens();
        $tokenCode = $tokens[$stackPtr]['code'];

        if ($tokenCode === T_FUNCTION || $tokenCode === T_CLOSURE) {
            $this->processFunctionDeclaration($phpcsFile, $stackPtr);

            // Deal with older PHPCS version which don't recognize return type hints
            // as well as newer PHPCS versions (3.3.0+) where the tokenization has changed.
            $returnTypeHint = $this->getReturnTypeHintToken($phpcsFile, $stackPtr);
            if ($returnTypeHint !== false) {
                $this->processReturnType($phpcsFile, $returnTypeHint);
            }
        } else {
            $this->processReturnType($phpcsFile, $stackPtr);
        }

    }//end process()


    /**
     * Process this test for function tokens.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    protected function processFunctionDeclaration(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $params = PHPCSHelper::getMethodParameters($phpcsFile, $stackPtr);

        if (empty($params) === false && is_array($params)) {
            foreach ($params as $param) {
                if ($param['nullable_type'] === true) {
                    $phpcsFile->addError(
                        'Nullable type declarations are not supported in PHP 7.0 or earlier. Found: %s',
                        $param['token'],
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
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token
     *                                         in the stack passed in $tokens.
     *
     * @return void
     */
    protected function processReturnType(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[($stackPtr - 1)]['code']) === false) {
            return;
        }

        $previous = $phpcsFile->findPrevious(\PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);

        // Deal with namespaced class names.
        if ($tokens[$previous]['code'] === T_NS_SEPARATOR) {
            $validTokens   = \PHP_CodeSniffer_Tokens::$emptyTokens;
            $validTokens[] = T_STRING;
            $validTokens[] = T_NS_SEPARATOR;

            $stackPtr--;

            while (in_array($tokens[($stackPtr - 1)]['code'], $validTokens, true) === true) {
                $stackPtr--;
            }

            $previous = $phpcsFile->findPrevious(\PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        }

        // T_NULLABLE token was introduced in PHPCS 2.7.2. Before that it identified as T_INLINE_THEN.
        if ((defined('T_NULLABLE') === true && $tokens[$previous]['type'] === 'T_NULLABLE')
            || (defined('T_NULLABLE') === false && $tokens[$previous]['code'] === T_INLINE_THEN)
        ) {
            $phpcsFile->addError(
                'Nullable return types are not supported in PHP 7.0 or earlier.',
                $stackPtr,
                'returnTypeFound'
            );
        }
    }

}//end class
