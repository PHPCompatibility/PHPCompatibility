<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Constants;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Detect the use of removed PHP class constants.
 *
 * PHP version All
 *
 * @since 10.0.0
 */
final class RemovedClassConstantsSniff extends Sniff
{
    const REMOVED         = 'removed';
    const DEPRECATED      = 'deprecated';
    const SOFT_DEPRECATED = 'softDeprecated';

    /**
     * List of all violation types and their semantic meaning.
     *
     * @var array<string, array{
     *     type: string,
     *     errorCode: string,
     *     messageTemplate: string,
     *     isError: bool,
     * }>
     */
    private $violationTypes = [
        self::REMOVED => [
            'type' => self::REMOVED,
            'errorCode' => 'Removed',
            'messageTemplate' => 'Class constant %s is removed since PHP %s.',
            'isError' => true,
        ],
        self::DEPRECATED => [
            'type' => self::DEPRECATED,
            'errorCode' => 'Deprecated',
            'messageTemplate' => 'Class constant %s is deprecated since PHP %s.',
            'isError' => false,
        ],
        self::SOFT_DEPRECATED => [
            'type' => self::SOFT_DEPRECATED,
            'errorCode' => 'SoftDeprecated',
            'messageTemplate' => 'Class constant %s is maintained for backward compatibility since PHP %s.',
            'isError' => false,
        ],
    ];

    /**
     * Class constants that will be scanned for compatibility.
     *
     * @var array<string, array{
     *     softDeprecated?: string,
     *     deprecated?: string,
     *     removed?: string,
     *     alternative?: string,
     *     extension?: string
     * }>
     */
    protected $classConstantCompatibilityMatrix = [
        // @link https://wiki.php.net/rfc/deprecations_php_8_3#the_numberformattertype_currency_constant
        // @link https://www.php.net/manual/en/migration83.deprecated.php#migration83.deprecated.intl
        // @link https://www.php.net/manual/en/class.numberformatter.php#numberformatter.constants.type-currency
        'NumberFormatter::TYPE_CURRENCY' => [
            self::DEPRECATED => '8.3',
            'extension' => 'intl',
        ],
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_DOUBLE_COLON];
    }

    /**
     * Processes this test when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // We can't know runtime values, so tokens on each side of the double colon can only be strings for this sniff.
        $previousNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, $stackPtr - 1, null, true);
        $nextNonEmpty     = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true);
        if (!$previousNonEmpty || !$nextNonEmpty) {
            return;
        }

        $tokens           = $phpcsFile->getTokens();
        $previousIsString = $tokens[$previousNonEmpty]['code'] === \T_STRING;
        $nextIsString     = $tokens[$nextNonEmpty]['code'] === \T_STRING;

        if (!$previousIsString || !$nextIsString) {
            return;
        }

        $previous             = $tokens[$previousNonEmpty]['content'];
        $next                 = $tokens[$nextNonEmpty]['content'];
        $scannedClassConstant = sprintf('%s::%s', $previous, $next);

        if (!isset($this->classConstantCompatibilityMatrix[$scannedClassConstant])) {
            return;
        }

        $violation = $this->testOnTargetVersion(
            $this->classConstantCompatibilityMatrix[$scannedClassConstant]
        );
        if ($violation === null) {
            return;
        }

        $this->addMessage(
            $phpcsFile,
            $nextNonEmpty,
            $scannedClassConstant,
            $violation
        );
    }

    /**
     * Get the compatibility violation with the target PHP version.
     *
     * @param array $compatibilityMatrix Compatibility matrix for a class constant.
     *
     * @return array|null Found violation on the target PHP version.
     */
    private function testOnTargetVersion(array $compatibilityMatrix)
    {
        $violationTypeIds = array_keys($this->violationTypes);

        foreach ($violationTypeIds as $violationTypeId) {
            if (!isset($compatibilityMatrix[$violationTypeId])) {
                continue;
            }
            if (ScannedCode::shouldRunOnOrAbove($compatibilityMatrix[$violationTypeId])) {
                return $this->violationTypes[$violationTypeId];
            }
        }

        return null;
    }

    /**
     * Assemble available information into a user-facing message.
     *
     * @param File   $phpcsFile      The file being scanned.
     * @param int    $stackPtr       Stack pointer
     * @param string $scannedMethod  Method for which the violation was found.
     * @param array  $foundViolation Found violation.
     *
     * @return void
     */
    private function addMessage(File $phpcsFile, $stackPtr, $scannedMethod, array $foundViolation)
    {
        $compatibilityMatrix = $this->classConstantCompatibilityMatrix[$scannedMethod];
        $version             = $compatibilityMatrix[$foundViolation['type']];
        $hasAlternative      = isset($compatibilityMatrix['alternative']);

        $message = sprintf(
            $foundViolation['messageTemplate'],
            $scannedMethod,
            $version
        );

        if ($hasAlternative) {
            $message .= sprintf(' Use %s instead.', $compatibilityMatrix['alternative']);
        }

        $messageData = [
            $scannedMethod,
            $version,
        ];

        MessageHelper::addMessage(
            $phpcsFile,
            $message,
            $stackPtr,
            $foundViolation['isError'],
            $foundViolation['errorCode'],
            $messageData
        );
    }
}
