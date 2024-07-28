<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Attributes;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;

/**
 * Attributes as a form of structured, syntactic metadata to declarations of classes, properties,
 * functions, methods, parameters and constants is supported as of PHP 8.0.
 *
 * {@internal This sniff does not check whether attributes are used correctly and in
 * combination with syntaxes for which attributes are valid.
 * If that's not the case, PHP 8.0 would throw a parse error anyway.}
 *
 * PHP version 8.0
 *
 * @link https://wiki.php.net/rfc/attributes_v2
 * @link https://wiki.php.net/rfc/attribute_amendments
 * @link https://wiki.php.net/rfc/shorter_attribute_syntax
 * @link https://wiki.php.net/rfc/shorter_attribute_syntax_change
 * @link https://www.php.net/manual/en/language.attributes.php
 *
 * @since 10.0.0
 */
class NewAttributesSniff extends Sniff
{

    /**
     * List of PHP native attributes which were introduced to allow for making code cross-version compatible.
     *
     * These attributes can be safely ignored.
     *
     * @since 10.0.0
     *
     * @var array<string, string> Key is the attribute name, value is the PHP version in which the attribute was introduced.
     */
    private $phpCrossVersionAttributes = [
        'AllowDynamicProperties' => '8.2',
        'ReturnTypeWillChange'   => '8.1',
    ];

    /**
     * List of other PHP native attributes.
     *
     * These attributes will be bundled into one error code to allow for easily selectively ignoring them.
     *
     * Source of the attributes list: https://www.php.net/manual/en/reserved.attributes.php
     *
     * @since 10.0.0
     *
     * @var array<string, string> Key is the attribute name, value is the PHP version in which the attribute was introduced.
     */
    private $otherPHPNativeAttributes = [
        'Attribute'          => '8.0',
        'Override'           => '8.3',
        'SensitiveParameter' => '8.2',
    ];

    /**
     * List of Doctrine ORM native attributes.
     *
     * These attributes will be bundled into one error code to allow for easily selectively ignoring them.
     *
     * {@internal Once the PHPCSUtils name resolution with namespace and import statements becomes available,
     * this list should no longer be necessary and all Doctrine attributes can be ignored based on their
     * fully qualified name starting with "Doctrine\ORM\Mapping".}
     *
     * Source of the attributes list: https://www.doctrine-project.org/projects/doctrine-orm/en/3.2/reference/attributes-reference.html
     *
     * This list is ordered as per the documentation for maintainability.
     *
     * @since 10.0.0
     *
     * @var array<string, bool> Key is the attribute name, value is irrelevant.
     */
    private $doctrineAttributes = [
        'AssociationOverride'   => true,
        'AttributeOverride'     => true,
        'Column'                => true,
        'Cache'                 => true,
        'ChangeTrackingPolicy'  => true,
        'CustomIdGenerator'     => true,
        'DiscriminatorColumn'   => true,
        'DiscriminatorMap'      => true,
        'Embeddable'            => true,
        'Embedded'              => true,
        'Entity'                => true,
        'GeneratedValue'        => true,
        'HasLifecycleCallbacks' => true,
        'Index'                 => true,
        'Id'                    => true,
        'InheritanceType'       => true,
        'InverseJoinColumn'     => true,
        'JoinColumn'            => true,
        'JoinTable'             => true,
        'ManyToOne'             => true,
        'ManyToMany'            => true,
        'MappedSuperclass'      => true,
        'OneToOne'              => true,
        'OneToMany'             => true,
        'OrderBy'               => true,
        'PostLoad'              => true,
        'PostPersist'           => true,
        'PostRemove'            => true,
        'PostUpdate'            => true,
        'PrePersist'            => true,
        'PreRemove'             => true,
        'PreUpdate'             => true,
        'SequenceGenerator'     => true,
        'Table'                 => true,
        'UniqueConstraint'      => true,
        'Version'               => true,
    ];

    /**
     * List of PHPStorm native attributes.
     *
     * These attributes will bundled into one error code to allow for easily selectively ignoring them.
     *
     * {@internal Once the PHPCSUtils name resolution with namespace and import statements becomes available,
     * this list should no longer be necessary and all PHPStorm attributes can be ignored based on their
     * fully qualified name starting with "JetBrains\PhpStorm".}
     *
     * Source of the attributes list: https://github.com/JetBrains/phpstorm-attributes
     *
     * This list is ordered as per the documentation for maintainability.
     *
     * @since 10.0.0
     *
     * @var array<string, bool> Key is the attribute name, value is irrelevant.
     */
    private $phpstormAttributes = [
        'ArrayShape'     => true,
        'Deprecated'     => true,
        'ExpectedValues' => true,
        'Immutable'      => true,
        'Language'       => true,
        'NoReturn'       => true,
        'ObjectShape'    => true,
        'Pure'           => true,
    ];

    /**
     * List of PHPUnit native attributes.
     *
     * These attributes will be bundled into one error code to allow for easily selectively ignoring them.
     *
     * {@internal Once the PHPCSUtils name resolution with namespace and import statements becomes available,
     * this list should no longer be necessary and all PHPUnit attributes can be ignored based on their
     * fully qualified name starting with "PHPUnit\Framework\Attributes".}
     *
     * Source of the attributes list: https://docs.phpunit.de/en/main/attributes.html
     *
     * This list is ordered as per the documentation for maintainability.
     *
     * @since 10.0.0
     *
     * @var array<string, bool> Key is the attribute name, value is irrelevant.
     */
    private $phpunitAttributes = [
        // Generic.
        'Test'                                       => true,
        'TestDox'                                    => true,
        'DisableReturnValueGenerationForTestDoubles' => true,
        'DoesNotPerformAssertions'                   => true,
        'IgnoreDeprecations'                         => true,
        'WithoutErrorHandler'                        => true,

        // Code Coverage.
        'CoversClass'                                => true,
        'CoversTrait'                                => true,
        'CoversMethod'                               => true,
        'CoversFunction'                             => true,
        'CoversNothing'                              => true,
        'UsesClass'                                  => true,
        'UsesTrait'                                  => true,
        'UsesMethod'                                 => true,
        'UsesFunction'                               => true,

        // Data Provider.
        'DataProvider'                               => true,
        'DataProviderExternal'                       => true,
        'TestWith'                                   => true,
        'TestWithJson'                               => true,

        // Test Dependencies.
        'Depends'                                    => true,
        'DependsUsingDeepClone'                      => true,
        'DependsUsingShallowClone'                   => true,
        'DependsExternal'                            => true,
        'DependsExternalUsingDeepClone'              => true,
        'DependsExternalUsingShallowClone'           => true,
        'DependsOnClass'                             => true,
        'DependsOnClassUsingDeepClone'               => true,
        'DependsOnClassUsingShallowClone'            => true,

        // Test Groups.
        'Group'                                      => true,
        'Small'                                      => true,
        'Medium'                                     => true,
        'Large'                                      => true,
        'Ticket'                                     => true,

        // Template Methods.
        'BeforeClass'                                => true,
        'Before'                                     => true,
        'PreCondition'                               => true,
        'PostCondition'                              => true,
        'After'                                      => true,
        'AfterClass'                                 => true,

        // Test Isolation.
        'BackupGlobals'                              => true,
        'ExcludeGlobalVariableFromBackup'            => true,
        'BackupStaticProperties'                     => true,
        'ExcludeStaticPropertyFromBackup'            => true,
        'RunInSeparateProcess'                       => true,
        'RunTestsInSeparateProcesses'                => true,
        'RunClassInSeparateProcess'                  => true,
        'PreserveGlobalState'                        => true,

        // Skipping Tests.
        'RequiresPhp'                                => true,
        'RequiresPhpExtension'                       => true,
        'RequiresSetting'                            => true,
        'RequiresPhpunit'                            => true,
        'RequiresFunction'                           => true,
        'RequiresMethod'                             => true,
        'RequiresOperatingSystem'                    => true,
        'RequiresOperatingSystemFamily'              => true,
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
        // Handle case-insensitivity of attribute names.
        $this->phpCrossVersionAttributes = \array_change_key_case($this->phpCrossVersionAttributes, \CASE_LOWER);
        $this->otherPHPNativeAttributes  = \array_change_key_case($this->otherPHPNativeAttributes, \CASE_LOWER);
        $this->doctrineAttributes        = \array_change_key_case($this->doctrineAttributes, \CASE_LOWER);
        $this->phpstormAttributes        = \array_change_key_case($this->phpstormAttributes, \CASE_LOWER);
        $this->phpunitAttributes         = \array_change_key_case($this->phpunitAttributes, \CASE_LOWER);

        return [\T_ATTRIBUTE];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        if (ScannedCode::shouldRunOnOrBelow('7.4') === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['attribute_closer']) === false) {
            // Live coding or parse error. Shouldn't be possible as shouldn't have retokenized in that case.
            return; // @codeCoverageIgnore
        }

        $opener = $stackPtr;
        $closer = $tokens[$stackPtr]['attribute_closer'];

        /*
         * Check if the attribute is cross-version compatible with PHP < 8.0.
         */
        if ($tokens[$opener]['line'] !== $tokens[$closer]['line']) {
            $phpcsFile->addError(
                'Multi-line attributes will result in a parse error in PHP 7.4 and earlier.',
                $opener,
                'FoundMultiLine'
            );
        }

        $nextAfter = $phpcsFile->findNext(Tokens::$emptyTokens, ($closer + 1), null, true);
        if ($nextAfter !== false
            && $tokens[$nextAfter]['code'] !== \T_ATTRIBUTE
            && $tokens[$closer]['line'] === $tokens[$nextAfter]['line']
        ) {
            $phpcsFile->addError(
                'Code after an inline attribute on the same line will be ignored in PHP 7.4 and earlier and is likely to cause a parse error or functional error.',
                $closer,
                'FoundInline'
            );
        }

        $textPtr = $opener;
        while (($textPtr = $phpcsFile->findNext(\T_CONSTANT_ENCAPSED_STRING, ($textPtr + 1), $closer)) !== false) {
            if ($tokens[$textPtr]['line'] !== $tokens[$opener]['line']) {
                // We only need to examine text strings on the same line as the opener.
                break;
            }

            if (\strpos($tokens[$textPtr]['content'], '?>') !== false) {
                $phpcsFile->addError(
                    'Text string containing text which looks like a PHP close tag found on the same line as an attribute opener. This will cause PHP to switch to inline HTML in PHP 7.4 and earlier, which may lead to code exposure and will break functionality. Found: %s',
                    $textPtr,
                    'FoundCloseTag',
                    [$tokens[$textPtr]['content']]
                );
            }
        }

        /*
         * Collect the names of all attribute classes referenced.
         */
        $attributeNames = [];
        $currentName    = '';
        $startsAt       = null;

        for ($i = ($opener + 1); $i <= $closer; $i++) {
            if (isset(Tokens::$emptyTokens[$tokens[$i]['code']])) {
                continue;
            }

            if ($tokens[$i]['code'] === \T_OPEN_PARENTHESIS) {
                if (isset($tokens[$i]['parenthesis_closer']) === false) {
                    // Shouldn't be possible as in that case the attribute opener is not linked with the closer, but just in case.
                    break;
                }

                // Skip over whatever is passed to the Attribute constructor.
                $i = $tokens[$i]['parenthesis_closer'];
                continue;
            }

            if ($tokens[$i]['code'] === \T_COMMA
                || $i === $closer
            ) {
                // We've reached the end of the name.
                if ($currentName === '') {
                    // Parse error. Stop parsing this attribute.
                    break;
                }

                $attributeNames[$startsAt] = $currentName;
                $currentName               = '';
                $startsAt                  = null;
                continue;
            }

            if (isset(Collections::namespacedNameTokens()[$tokens[$i]['code']])) {
                $currentName .= $tokens[$i]['content'];

                if (isset($startsAt) === false) {
                    $startsAt = $i;
                }
            }
        }

        if (empty($attributeNames)) {
            // Parse error. Shouldn't be possible.
            return;
        }

        // Lowercase all found attributes to allow for case-insensitive name comparisons.
        $attributeNamesLC = \array_map('strtolower', $attributeNames);

        /*
         * Throw a warning for each attribute class encountered.
         */
        foreach ($attributeNamesLC as $startPtr => $attributeName) {
            $attributeName = \ltrim($attributeName, '\\');

            if (isset($this->phpCrossVersionAttributes[$attributeName])) {
                // Attribute referencing a PHP native cross-version compatibility feature. Ignore.
                continue;
            }

            if (\stripos($attributeName, 'PHPUnit\\Framework\\Attributes\\') === 0
                || isset($this->phpunitAttributes[$attributeName])
            ) {
                // Either a fully qualified PHPUnit attribute or an unqualified PHPUnit attribute.
                $errorCode = 'PHPUnitAttributeFound';
            } elseif (\stripos($attributeName, 'Doctrine\\ORM\\Mapping\\') === 0
                || isset($this->doctrineAttributes[$attributeName])
            ) {
                // Either a fully qualified Doctrine attribute or an unqualified Doctrine attribute.
                $errorCode = 'DoctrineORMAttributeFound';
            } elseif (\stripos($attributeName, 'JetBrains\\PhpStorm\\') === 0
                || isset($this->phpstormAttributes[$attributeName])
            ) {
                // Either a fully qualified PHPStorm attribute or an unqualified PHPStorm attribute.
                $errorCode = 'PHPStormAttributeFound';
            } elseif (isset($this->otherPHPNativeAttributes[$attributeName])) {
                // A PHP native attribute, not introduced for PHP cross-version compatibility.
                $errorCode = 'PHPNativeAttributeFound';
            } else {
                // All other attributes.
                $errorCode = 'Found';
            }

            $phpcsFile->addWarning(
                'Attributes are not supported in PHP 7.4 or earlier. They will be ignored and the application may not work as expected. Found: %s',
                $startPtr,
                $errorCode,
                ['#[' . $attributeNames[$startPtr] . '...]']
            );
        }
    }
}
