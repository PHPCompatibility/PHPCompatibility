<?php
/**
 * \PHPCompatibility\Sniffs\PHP\NewReturnTypeDeclarationsSniff.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\AbstractNewFeatureSniff;

/**
 * \PHPCompatibility\Sniffs\PHP\NewReturnTypeDeclarationsSniff.
 *
 * PHP version 7.0
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */
class NewReturnTypeDeclarationsSniff extends AbstractNewFeatureSniff
{

    /**
     * A list of new types
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newTypes = array(
        'int' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'float' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'bool' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'string' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'array' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'callable' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'parent' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'self' => array(
            '5.6' => false,
            '7.0' => true,
        ),
        'Class name' => array(
            '5.6' => false,
            '7.0' => true,
        ),

        'iterable' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'void' => array(
            '7.0' => false,
            '7.1' => true,
        ),

        'object' => array(
            '7.1' => false,
            '7.2' => true,
        ),
    );


    /**
     * Returns an array of tokens this test wants to listen for.
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
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Deal with older PHPCS version which don't recognize return type hints.
        if ($tokens[$stackPtr]['code'] === T_FUNCTION || $tokens[$stackPtr]['code'] === T_CLOSURE) {
            $returnTypeHint = $this->getReturnTypeHintToken($phpcsFile, $stackPtr);
            if ($returnTypeHint !== false) {
                $stackPtr = $returnTypeHint;
            }
        }

        if (isset($this->newTypes[$tokens[$stackPtr]['content']]) === true) {
            $itemInfo = array(
                'name'   => $tokens[$stackPtr]['content'],
            );
            $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
        }
        // Handle class name based return types.
        elseif ($tokens[$stackPtr]['code'] === T_STRING
            || (defined('T_RETURN_TYPE') && $tokens[$stackPtr]['code'] === T_RETURN_TYPE)
        ) {
            $itemInfo = array(
                'name'   => 'Class name',
            );
            $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
        }
    }//end process()


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->newTypes[$itemInfo['name']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return '%s return type is not present in PHP version %s or earlier';
    }


}//end class
