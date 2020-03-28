<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionDeclarations;

use PHPCompatibility\Sniff;
use PHP_CodeSniffer_File as File;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\Scopes;

/**
 * Verifies the use of the correct visibility and static properties of magic methods.
 *
 * The requirements have always existed, but as of PHP 5.3, a warning will be thrown
 * when magic methods have the wrong modifiers.
 *
 * PHP version 5.3
 *
 * @link https://www.php.net/manual/en/language.oop5.magic.php
 *
 * @since 5.5
 * @since 5.6 Now extends the base `Sniff` class.
 */
class NonStaticMagicMethodsSniff extends Sniff
{

    /**
     * A list of PHP magic methods and their visibility and static requirements.
     *
     * Method names in the array should be all *lowercase*.
     * Visibility can be either 'public', 'protected' or 'private'.
     * Static can be either 'true' - *must* be static, or 'false' - *must* be non-static.
     * When a method does not have a specific requirement for either visibility or static,
     * do *not* add the key.
     *
     * @since 5.5
     * @since 5.6 The array format has changed to allow the sniff to also verify the
     *            use of the correct visibility for a magic method.
     *
     * @var array(string)
     */
    protected $magicMethods = array(
        '__construct' => array(
            'static' => false,
        ),
        '__destruct' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__clone' => array(
            'static'     => false,
        ),
        '__get' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__set' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__isset' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__unset' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__call' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__callstatic' => array(
            'visibility' => 'public',
            'static'     => true,
        ),
        '__sleep' => array(
            'visibility' => 'public',
        ),
        '__tostring' => array(
            'visibility' => 'public',
        ),
        '__set_state' => array(
            'visibility' => 'public',
            'static'     => true,
        ),
        '__debuginfo' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__invoke' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__serialize' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
        '__unserialize' => array(
            'visibility' => 'public',
            'static'     => false,
        ),
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.5
     * @since 5.6    Now also checks traits.
     * @since 7.1.4  Now also checks anonymous classes.
     * @since 10.0.0 Switch to check based on T_FUNCTION token instead of OO construct token.
     *
     * @return array
     */
    public function register()
    {
        return array(
            \T_FUNCTION,
        );
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.5
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // Should be removed, the requirement was previously also there, 5.3 just started throwing a warning about it.
        if ($this->supportsAbove('5.3') === false) {
            return;
        }

        if (Scopes::isOOMethod($phpcsFile, $stackPtr) === false) {
            // Not a method.
            return;
        }

        $methodName   = FunctionDeclarations::getName($phpcsFile, $stackPtr);
        $methodNameLc = strtolower($methodName);

        if (isset($this->magicMethods[$methodNameLc]) === false) {
            // Not one of the magic methods we're looking for.
            return;
        }

        $methodProperties = FunctionDeclarations::getProperties($phpcsFile, $stackPtr);
        $errorCodeBase    = $this->stringToErrorCode($methodNameLc);

        if (isset($this->magicMethods[$methodNameLc]['visibility']) && $this->magicMethods[$methodNameLc]['visibility'] !== $methodProperties['scope']) {
            $error     = 'Visibility for magic method %s must be %s. Found: %s';
            $errorCode = $errorCodeBase . 'MethodVisibility';
            $data      = array(
                $methodName,
                $this->magicMethods[$methodNameLc]['visibility'],
                $methodProperties['scope'],
            );

            $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
        }

        if (isset($this->magicMethods[$methodNameLc]['static']) && $this->magicMethods[$methodNameLc]['static'] !== $methodProperties['is_static']) {
            $error     = 'Magic method %s cannot be defined as static.';
            $errorCode = $errorCodeBase . 'MethodStatic';
            $data      = array($methodName);

            if ($this->magicMethods[$methodNameLc]['static'] === true) {
                $error     = 'Magic method %s must be defined as static.';
                $errorCode = $errorCodeBase . 'MethodNonStatic';
            }

            $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
        }
    }
}
