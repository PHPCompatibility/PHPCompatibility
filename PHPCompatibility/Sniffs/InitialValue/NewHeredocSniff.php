<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\InitialValue;

use PHPCompatibility\AbstractInitialValueSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\GetTokensAsString;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Detect a heredoc being used to set an initial value.
 *
 * As of PHP 5.3.0, it's possible to initialize static variables, class properties
 * and constants declared using the `const` keyword, using the Heredoc syntax.
 * And while not documented, heredoc initialization can now also be used for function param defaults.
 * See: https://3v4l.org/JVH8W
 *
 * These heredocs (still) cannot contain variables. That's, however, outside the scope of the
 * PHPCompatibility library until such time as there is a PHP version in which this would be accepted.
 *
 * PHP version 5.3
 *
 * @link https://www.php.net/manual/en/migration53.new-features.php
 * @link https://www.php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
 *
 * @since 7.1.4
 * @since 8.2.0  Now extends the NewConstantScalarExpressionsSniff instead of the base Sniff class.
 * @since 9.0.0  Renamed from `NewHeredocInitializeSniff` to `NewHeredocSniff`.
 * @since 10.0.0 This sniff now extends the `AbstractInitialValueSniff` class instead of the
 *               `NewConstantScalarExpressionsSniff` class.
 */
class NewHeredocSniff extends AbstractInitialValueSniff
{

    /**
     * Error message.
     *
     * @since 8.2.0
     *
     * @var string
     */
    const ERROR_PHRASE = 'Initializing %s using the Heredoc syntax was not supported in PHP 5.2 or earlier. Found: %s';

    /**
     * Partial error phrases to be used in combination with the error message constant.
     *
     * @since 8.2.0
     * @since 10.0.0 Renamed from `$errorPhrases` to `$initialValueTypes`.
     *
     * @var array<string, string> Type indicator => suggested partial error phrase.
     */
    protected $initialValueTypes = [
        'const'     => 'constants',
        'property'  => 'class properties',
        'staticvar' => 'static variables',
        'default'   => 'default parameter values',
    ];

    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 8.2.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrBelow('5.2') === false);
    }

    /**
     * Process a token which has an initial value.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the variable/constant name token
     *                                               in the stack passed in $tokens.
     * @param int                         $start     The stackPtr to the start of the initial value.
     * @param int                         $end       The stackPtr to the end of the initial value.
     *                                               This will normally be a comma or semi-colon.
     * @param string                      $type      The "type" of initial value declaration being examined.
     *                                               The type will match one of the keys in the
     *                                               `AbstractInitialValueSniff::$initialValueTypes` property.
     *
     * @return void
     */
    protected function processInitialValue(File $phpcsFile, $stackPtr, $start, $end, $type)
    {
        $hasHeredoc = $phpcsFile->findNext(\T_START_HEREDOC, $start, $end);
        if ($hasHeredoc === false) {
            // Initial value doesn't contain a heredoc, nothing to do.
            return;
        }

        $error       = static::ERROR_PHRASE;
        $errorCode   = 'Found';
        $phrase      = '';
        $codeSnippet = \trim(GetTokensAsString::noComments($phpcsFile, $stackPtr, $hasHeredoc));

        if (isset($this->initialValueTypes[$type]) === true) {
            $errorCode = MessageHelper::stringToErrorCode($type) . 'Found';
            $phrase    = $this->initialValueTypes[$type];
        }

        $data = [$phrase, $codeSnippet];

        $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
    }
}
