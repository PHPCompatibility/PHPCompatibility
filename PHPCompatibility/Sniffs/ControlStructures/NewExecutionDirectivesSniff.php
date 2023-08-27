<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ControlStructures;

use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\TextStrings;

/**
 * Check for valid execution directives set with `declare()`.
 *
 * The sniff contains three distinct checks:
 * - Check if the execution directive used is valid. PHP currently only supports
 *   three execution directives.
 * - Check if the execution directive used is available in the PHP versions
 *   for which support is being checked.
 *   In the case of the `encoding` directive on PHP 5.3, support is conditional
 *   on the `--enable-zend-multibyte` compilation option. This will be indicated as such.
 * - Check whether the value for the directive is valid.
 *
 * PHP version All
 *
 * @link https://www.php.net/manual/en/control-structures.declare.php
 * @link https://wiki.php.net/rfc/scalar_type_hints_v5#strict_types_declare_directive
 *
 * @since 7.0.3
 * @since 7.1.0  Now extends the `AbstractNewFeatureSniff` instead of the base `Sniff` class.
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionNewFeatureTrait`.
 */
class NewExecutionDirectivesSniff extends Sniff
{
    use ComplexVersionNewFeatureTrait;

    /**
     * A list of new execution directives
     *
     * The array lists : version number with false (not present) or true (present).
     * If the execution order is conditional, add the condition as a string to the version nr.
     * If's sufficient to list the first version where the execution directive appears.
     *
     * @since 7.0.3
     *
     * @var array<string, array<string, bool|string|array>>
     */
    protected $newDirectives = [
        'ticks' => [
            '3.1'                  => false,
            '4.0'                  => true,
            'valid_value_callback' => 'isNumeric',
        ],
        'encoding' => [
            '5.2'                  => false,
            '5.3'                  => '--enable-zend-multibyte', // Directive ignored unless.
            '5.4'                  => true,
            'valid_value_callback' => 'validEncoding',
        ],
        'strict_types' => [
            '5.6'          => false,
            '7.0'          => true,
            'valid_values' => ['0', '1'],
        ],
    ];

    /**
     * Tokens to ignore when trying to find the value for the directive.
     *
     * @since 7.0.3
     *
     * @var array
     */
    protected $ignoreTokens = [];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 7.0.3
     *
     * @return array<int|string>
     */
    public function register()
    {
        $this->ignoreTokens           = Tokens::$emptyTokens;
        $this->ignoreTokens[\T_EQUAL] = \T_EQUAL;

        return [\T_DECLARE];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 7.0.3
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['parenthesis_opener'], $tokens[$stackPtr]['parenthesis_closer']) === false) {
            return;
        }

        $openParenthesis  = $tokens[$stackPtr]['parenthesis_opener'];
        $closeParenthesis = $tokens[$stackPtr]['parenthesis_closer'];

        $start = ($openParenthesis + 1);
        do {
            $comma = $phpcsFile->findNext(\T_COMMA, $start, $closeParenthesis);
            if ($comma === false) {
                // Found last directive.
                $this->processDirective($phpcsFile, $start, $closeParenthesis);
                break;
            }

            // Multi-directive declare statement.
            $this->processDirective($phpcsFile, $start, $comma);

            $start = ($comma + 1);
        } while ($start < $closeParenthesis);
    }


    /**
     * Processes one individual declare execution directive.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $start     The position of the start of the directive.
     * @param int                         $end       The position of the end of the directive.
     *
     * @return void
     */
    protected function processDirective($phpcsFile, $start, $end)
    {
        $tokens       = $phpcsFile->getTokens();
        $directivePtr = $phpcsFile->findNext(\T_STRING, $start, $end);

        if ($directivePtr === false) {
            return;
        }

        $directiveContent   = $tokens[$directivePtr]['content'];
        $directiveContentLC = \strtolower($directiveContent);

        if (isset($this->newDirectives[$directiveContentLC]) === false) {
            $error = 'Declare can only be used with the directives %s. Found: %s';
            $data  = [
                \implode(', ', \array_keys($this->newDirectives)),
                $directiveContent,
            ];

            $phpcsFile->addError($error, $directivePtr, 'InvalidDirectiveFound', $data);
        } else {
            // Check for valid directive for version.
            $itemInfo = [
                'name' => $directiveContentLC,
            ];
            $this->handleFeature($phpcsFile, $directivePtr, $itemInfo);

            // Check for valid directive value.
            $valuePtr = $phpcsFile->findNext($this->ignoreTokens, $directivePtr + 1, $end, true);
            if ($valuePtr === false) {
                return;
            }

            $this->addWarningOnInvalidValue($phpcsFile, $valuePtr, $directiveContentLC);
        }
    }


    /**
     * Retrieve the "last version before" and potential conditions from an array with arbitrary contents.
     *
     * The array is expected to have at least one entry with a PHP version number as a key
     * and `false` as the value.
     *
     * @param array $itemArray Sub-array for a specific matched item from a complex version array.
     *
     * @return string[] Array with three keys `'not_in_version'`, `'conditional_version'`, `'condition'`.
     *                  The array values will always be strings and will be either the values retrieved
     *                  from the $itemArray or an empty string if the value for a key was unavailable
     *                  or could not be determined.
     */
    protected function getExtendedVersionInfo(array $itemArray)
    {
        $versionInfo                        = $this->getVersionInfo($itemArray);
        $versionInfo['conditional_version'] = '';
        $versionInfo['condition']           = '';

        foreach ($itemArray as $version => $present) {
            if (\preg_match('`^\d\.\d(\.\d{1,2})?$`', $version) !== 1) {
                // Not a version key.
                continue;
            }

            if (\is_string($present) === true) {
                // We cannot test for compilation option (ok, except by scraping the output of phpinfo...).
                $versionInfo['conditional_version'] = $version;
                $versionInfo['condition']           = $present;
            }
        }

        return $versionInfo;
    }


    /**
     * Handle the retrieval of relevant information and - if necessary - throwing of an
     * error for a matched item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the relevant token in
     *                                               the stack.
     * @param array                       $itemInfo  Base information about the item.
     *
     * @return void
     */
    protected function handleFeature(File $phpcsFile, $stackPtr, array $itemInfo)
    {
        $itemArray   = $this->newDirectives[$itemInfo['name']];
        $versionInfo = $this->getExtendedVersionInfo($itemArray);
        $shouldError = false;

        if (empty($versionInfo['not_in_version']) === false
            && ScannedCode::shouldRunOnOrBelow($versionInfo['not_in_version']) === true
        ) {
            $shouldError = true;
        } elseif (empty($versionInfo['conditional_version']) === false
            && ScannedCode::shouldRunOnOrBelow($versionInfo['conditional_version']) === true
        ) {
            $shouldError = true;

            // Reset the 'not_in_version' info as it is not relevant for the current notice.
            $versionInfo['not_in_version'] = '';
        }

        if ($shouldError === false) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $itemInfo, $itemArray, $versionInfo);
    }


    /**
     * Generates the error or warning for this item.
     *
     * @since 7.0.3
     * @since 7.1.0  This method now overloads the method from the `AbstractNewFeatureSniff` class.
     *               - Renamed from `maybeAddError()` to `addError()`.
     *               - Changed visibility from `protected` to `public`.
     * @since 10.0.0 - Added new $itemArray parameter.
     *               - Changed visibility from `public` to `protected`.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param array                       $itemInfo    Base information about the item.
     * @param array                       $itemArray   The sub-array with all the details about
     *                                                 this item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, array $itemInfo, array $itemArray, array $versionInfo)
    {
        if ($versionInfo['not_in_version'] !== '') {
            // Overrule the default message template.
            $this->msgTemplate = 'Directive %s is not present in PHP version %s or earlier';

            $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

            $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);

        } elseif ($versionInfo['conditional_version'] !== '') {
            $error     = 'Directive %s is present in PHP version %s but will be disregarded unless PHP is compiled with %s';
            $errorCode = MessageHelper::stringToErrorCode($itemInfo['name'], true) . 'WithConditionFound';
            $data      = [
                $itemInfo['name'],
                $versionInfo['conditional_version'],
                $versionInfo['condition'],
            ];

            $phpcsFile->addWarning($error, $stackPtr, $errorCode, $data);
        }
    }


    /**
     * Generates a error or warning for this sniff.
     *
     * @since 7.0.3
     * @since 7.0.6 Renamed from `addErrorOnInvalidValue()` to `addWarningOnInvalidValue()`.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the execution directive value
     *                                               in the token array.
     * @param string                      $directive The directive.
     *
     * @return void
     */
    protected function addWarningOnInvalidValue(File $phpcsFile, $stackPtr, $directive)
    {
        $tokens = $phpcsFile->getTokens();

        $value = $tokens[$stackPtr]['content'];
        if ($directive === 'encoding' && isset(Tokens::$stringTokens[$tokens[$stackPtr]['code']]) === true) {
            $value = TextStrings::stripQuotes($value);
        }

        $isError = false;
        if (isset($this->newDirectives[$directive]['valid_values'])) {
            if (\in_array($value, $this->newDirectives[$directive]['valid_values'], true) === false) {
                $isError = true;
            }
        } elseif (isset($this->newDirectives[$directive]['valid_value_callback'])) {
            $valid = \call_user_func([$this, $this->newDirectives[$directive]['valid_value_callback']], $value);
            if ($valid === false) {
                $isError = true;
            }
        }

        if ($isError === true) {
            $error     = 'The execution directive %s does not seem to have a valid value. Please review. Found: %s';
            $errorCode = MessageHelper::stringToErrorCode($directive, true) . 'InvalidValueFound';
            $data      = [
                $directive,
                $value,
            ];

            $phpcsFile->addWarning($error, $stackPtr, $errorCode, $data);
        }
    }


    /**
     * Check whether a value is numeric.
     *
     * Callback function to test whether the value for an execution directive is valid.
     *
     * @since 7.0.3
     *
     * @param mixed $value The value to test.
     *
     * @return bool
     */
    protected function isNumeric($value)
    {
        return \is_numeric($value);
    }


    /**
     * Check whether a value is a valid encoding.
     *
     * Callback function to test whether the value for an execution directive is valid.
     *
     * @since 7.0.3
     *
     * @param mixed $value The value to test.
     *
     * @return bool
     */
    protected function validEncoding($value)
    {
        static $encodings;
        if (isset($encodings) === false && \function_exists('mb_list_encodings')) {
            $encodings = \mb_list_encodings();
        }

        if (empty($encodings) || \is_array($encodings) === false) {
            // If we can't test the encoding, let it pass through.
            return true;
        }

        return \in_array($value, $encodings, true);
    }
}
