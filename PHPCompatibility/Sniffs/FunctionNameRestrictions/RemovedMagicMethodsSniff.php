<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\FunctionNameRestrictions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\Scopes;

/**
 * Detect declarations of deprecated/removed magic methods.
 *
 * Suggests alternative if available.
 *
 * PHP version All
 *
 * @since 10.0.0
 */
final class RemovedMagicMethodsSniff extends Sniff
{
    const REMOVED         = 'removed';
    const DEPRECATED      = 'deprecated';
    const SOFT_DEPRECATED = 'softDeprecated';

    /**
     * List of all violation types and their semantic meaning.
     *
     * @var array<string, array{
     *     errorCode: string,
     *     isError: bool,
     * }>
     */
    private $violationTypes = [
        self::REMOVED => [
            'type' => self::REMOVED,
            'errorCode' => 'Removed',
            'messageTemplate' => 'Magic method %s() is removed since PHP %s.',
            'isError' => true,
        ],
        self::DEPRECATED => [
            'type' => self::DEPRECATED,
            'errorCode' => 'Deprecated',
            'messageTemplate' => 'Magic method %s() is deprecated since PHP %s.',
            'isError' => false,
        ],
        self::SOFT_DEPRECATED => [
            'type' => self::SOFT_DEPRECATED,
            'errorCode' => 'SoftDeprecated',
            'messageTemplate' => 'Magic method %s() is maintained for backward compatibility since PHP %s.',
            'isError' => false,
        ],
    ];

    /**
     * Methods that will be scanned for compatibility.
     *
     * @var array<string, array{
     *     softDeprecated?: string,
     *     deprecated?: string,
     *     removed?: string,
     *     alternative?: string
     * }>
     */
    protected $methodCompatibilityMatrix = [
        // @see https://wiki.php.net/rfc/soft-deprecate-sleep-wakeup#proposal
        // @see https://www.php.net/manual/en/language.oop5.magic.php#object.serialize
        '__sleep' => [
            self::SOFT_DEPRECATED => '8.5',
            'mutuallyExclusiveWith' => '__serialize',
            'alternative' => '__serialize',
        ],
        // @see https://wiki.php.net/rfc/soft-deprecate-sleep-wakeup#proposal
        // @see https://www.php.net/manual/en/language.oop5.magic.php#object.unserialize
        '__wakeup' => [
            self::SOFT_DEPRECATED => '8.5',
            'mutuallyExclusiveWith' => '__unserialize',
            'alternative' => '__unserialize',
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
        return [\T_FUNCTION];
    }

    /**
     * Processes this test when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the
     *                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $magicMethodScopes = [T_CLASS, T_ANON_CLASS, T_TRAIT];
        $parentScope       = Scopes::validDirectScope($phpcsFile, $stackPtr, $magicMethodScopes);
        if (!$parentScope) {
            return;
        }

        $scannedMethod = \strtolower(
            FunctionDeclarations::getName($phpcsFile, $stackPtr)
        );

        if (!isset($this->methodCompatibilityMatrix[$scannedMethod])) {
            return;
        }

        $violation = $this->testOnTargetVersion(
            $this->methodCompatibilityMatrix[$scannedMethod]
        );
        if ($violation === null) {
            return;
        }

        $allMethodsInClass          = ObjectDeclarations::getDeclaredMethods($phpcsFile, $parentScope);
        $mutuallyExclusiveMethod    = $this->methodCompatibilityMatrix[$scannedMethod]['mutuallyExclusiveWith'];
        $hasMutuallyExclusiveMethod = array_key_exists($mutuallyExclusiveMethod, $allMethodsInClass);
        if ($hasMutuallyExclusiveMethod) {
            return;
        }

        $this->addMessage(
            $phpcsFile,
            $stackPtr,
            $scannedMethod,
            $violation
        );
    }


    /**
     * Get the compatibility violation with the target PHP version.
     *
     * @param array $compatibilityMatrix Compatibility matrix for a method.
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
        $compatibilityMatrix = $this->methodCompatibilityMatrix[$scannedMethod];
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
