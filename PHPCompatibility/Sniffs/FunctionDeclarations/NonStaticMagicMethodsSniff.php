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

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Verifies the use of the correct visibility and static properties of magic methods.
 *
 * The requirements have always existed, but as of PHP 5.3, a warning will be thrown
 * when magic methods have the wrong modifiers.
 *
 * For unknown reasons, this check was not executed for the __wakeup() method until PHP 8.0.
 *
 * PHP version 5.3
 * PHP version 8.0
 *
 * @link https://www.php.net/manual/en/language.oop5.magic.php
 *
 * @since 5.5
 * @since 5.6    Now extends the base `Sniff` class.
 * @since 10.0.0 This class is now `final`.
 */
final class NonStaticMagicMethodsSniff extends Sniff
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
     * @var array<string, array<string, string|bool>>
     */
    protected $magicMethods = [
        '__construct' => [
            'static' => false,
        ],
        '__destruct' => [
            'static' => false,
        ],
        '__clone' => [
            'static' => false,
        ],
        '__get' => [
            'visibility' => 'public',
            'static'     => false,
        ],
        '__set' => [
            'visibility' => 'public',
            'static'     => false,
        ],
        '__isset' => [
            'visibility' => 'public',
            'static'     => false,
        ],
        '__unset' => [
            'visibility' => 'public',
            'static'     => false,
        ],
        '__call' => [
            'visibility' => 'public',
            'static'     => false,
        ],
        '__callstatic' => [
            'visibility' => 'public',
            'static'     => true,
        ],
        '__sleep' => [
            'visibility' => 'public',
        ],
        '__tostring' => [
            'visibility' => 'public',
        ],
        '__set_state' => [
            'visibility' => 'public',
            'static'     => true,
        ],
        '__debuginfo' => [
            'visibility' => 'public',
            'static'     => false,
        ],
        '__invoke' => [
            'visibility' => 'public',
            'static'     => false,
        ],
        '__serialize' => [
            'visibility' => 'public',
            'static'     => false,
        ],
        '__unserialize' => [
            'visibility' => 'public',
            'static'     => false,
        ],
        // Enforced since PHP 8.0.
        '__wakeup' => [
            'visibility' => 'public',
            'static'     => false,
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.5
     * @since 5.6   Now also checks traits.
     * @since 7.1.4 Now also checks anonymous classes.
     *
     * @return array<int|string>
     */
    public function register()
    {
        return Tokens::$ooScopeTokens;
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
        // Should be removed, the requirement was previously also there, 5.3 just started throwing a warning about it.
        if (ScannedCode::shouldRunOnOrAbove('5.3') === false) {
            return;
        }

        $ooMethods = ObjectDeclarations::getDeclaredMethods($phpcsFile, $stackPtr);
        if (empty($ooMethods)) {
            // No methods declared in the OO construct at all. Bow out.
            return;
        }

        $shouldRunOnOrAbove80 = ScannedCode::shouldRunOnOrAbove('8.0');

        foreach ($ooMethods as $methodName => $functionPtr) {
            $methodNameLc = \strtolower($methodName);
            if (isset($this->magicMethods[$methodNameLc]) === false) {
                // Not one of the magic methods we're looking for.
                continue;
            }

            // Special case __wakeup() for which the signature modifiers are only enforced since PHP 8.0.
            $qualifyingPhrase = '';
            if ($methodNameLc === '__wakeup') {
                if ($shouldRunOnOrAbove80 === false) {
                    continue;
                }

                $qualifyingPhrase = ' since PHP 8.0';
            }

            $methodProperties = FunctionDeclarations::getProperties($phpcsFile, $functionPtr);
            $errorCodeBase    = MessageHelper::stringToErrorCode($methodNameLc);

            if (isset($this->magicMethods[$methodNameLc]['visibility'])
                && $this->magicMethods[$methodNameLc]['visibility'] !== $methodProperties['scope']
            ) {
                $error     = 'Visibility for magic method %s must be %s%s. Found: %s';
                $errorCode = $errorCodeBase . 'MethodVisibility';
                $data      = [
                    $methodName,
                    $this->magicMethods[$methodNameLc]['visibility'],
                    $qualifyingPhrase,
                    $methodProperties['scope'],
                ];

                $phpcsFile->addError($error, $functionPtr, $errorCode, $data);
            }

            if (isset($this->magicMethods[$methodNameLc]['static'])
                && $this->magicMethods[$methodNameLc]['static'] !== $methodProperties['is_static']
            ) {
                $error     = 'Magic method %s cannot be defined as static%s.';
                $errorCode = $errorCodeBase . 'MethodStatic';
                $data      = [
                    $methodName,
                    $qualifyingPhrase,
                ];

                if ($this->magicMethods[$methodNameLc]['static'] === true) {
                    $error     = 'Magic method %s must be defined as static.';
                    $errorCode = $errorCodeBase . 'MethodNonStatic';
                }

                $phpcsFile->addError($error, $functionPtr, $errorCode, $data);
            }
        }
    }
}
