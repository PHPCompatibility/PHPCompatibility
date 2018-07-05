<?php
/**
 * \PHPCompatibility\Sniffs\FunctionDeclarations\NewParamTypeDeclarationsSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\AbstractNewFeatureSniff;
use PHPCompatibility\PHPCSHelper;
use PHP_CodeSniffer_File as File;

/**
 * \PHPCompatibility\Sniffs\FunctionDeclarations\NewParamTypeDeclarationsSniff.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Wim Godden <wim.godden@cu.be>
 */
class NewParamTypeDeclarationsSniff extends AbstractNewFeatureSniff
{

    /**
     * A list of new types.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the keyword appears.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newTypes = array(
        'array' => array(
            '5.0' => false,
            '5.1' => true,
        ),
        'self' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'parent' => array(
            '5.1' => false,
            '5.2' => true,
        ),
        'callable' => array(
            '5.3' => false,
            '5.4' => true,
        ),
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
        'iterable' => array(
            '7.0' => false,
            '7.1' => true,
        ),
        'object' => array(
            '7.1' => false,
            '7.2' => true,
        ),
    );


    /**
     * Invalid types
     *
     * The array lists : the invalid type hint => what was probably intended/alternative.
     *
     * @var array(string => string)
     */
    protected $invalidTypes = array(
        'static'  => 'self',
        'boolean' => 'bool',
        'integer' => 'int',
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            \T_FUNCTION,
            \T_CLOSURE,
        );
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // Get all parameters from method signature.
        $paramNames = PHPCSHelper::getMethodParameters($phpcsFile, $stackPtr);
        if (empty($paramNames)) {
            return;
        }

        $supportsPHP4 = $this->supportsBelow('4.4');

        foreach ($paramNames as $param) {
            if ($param['type_hint'] === '') {
                continue;
            }

            // Strip off potential nullable indication.
            $typeHint = ltrim($param['type_hint'], '?');

            if ($supportsPHP4 === true) {
                $phpcsFile->addError(
                    'Type declarations were not present in PHP 4.4 or earlier.',
                    $param['token'],
                    'TypeHintFound'
                );

            } elseif (isset($this->newTypes[$typeHint])) {
                $itemInfo = array(
                    'name' => $typeHint,
                );
                $this->handleFeature($phpcsFile, $param['token'], $itemInfo);

                // As of PHP 7.0, using `self` or `parent` outside class scope throws a fatal error.
                // Only throw this error for PHP 5.2+ as before that the "type hint not supported" error
                // will be thrown.
                if (($typeHint === 'self' || $typeHint === 'parent')
                    && $this->inClassScope($phpcsFile, $stackPtr, false) === false
                    && $this->supportsAbove('5.2') !== false
                ) {
                    $phpcsFile->addError(
                        "'%s' type cannot be used outside of class scope",
                        $param['token'],
                        ucfirst($typeHint) . 'OutsideClassScopeFound',
                        array($typeHint)
                    );
                }
            } elseif (isset($this->invalidTypes[$typeHint])) {
                $error = "'%s' is not a valid type declaration. Did you mean %s ?";
                $data  = array(
                    $typeHint,
                    $this->invalidTypes[$typeHint],
                );

                $phpcsFile->addError($error, $param['token'], 'InvalidTypeHintFound', $data);
            }
        }
    }


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
        return "'%s' type declaration is not present in PHP version %s or earlier";
    }
}
