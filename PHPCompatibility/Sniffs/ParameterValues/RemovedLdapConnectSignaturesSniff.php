<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\ParameterValues;

use PHPCompatibility\AbstractFunctionCallParameterSniff;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCSUtils\Utils\PassedParameters;
use PHP_CodeSniffer\Files\File;

/**
 * Detect function calls to ldap_connect() using deprecated function signatures.
 *
 * `ldap_connect()` historically supports three signatures:
 * - `ldap_connect(?string $uri = null)`
 * - `ldap_connect(?string $host = null, int $port = 389)`
 * - `ldap_connect(?string $uri, int $port, string $wallet, string $password, int $auth_mode)`
 *
 * The two parameter - $host, $port - signature is deprecated since PHP 8.3 and support will be removed in PHP 9.0.
 *
 * PHP version 8.3
 * PHP version 9.0
 *
 * {@internal Normally this would be handled via the RemovedFunctionParameters sniff, but this
 * function has *three* distinct signatures and the signatures are being deprecated in reverse order,
 * i.e. the 2-param signature is being deprecated in PHP 8.3, while the 3+ param signature is being
 * deprecated in PHP 8.4.
 * For that reason, the RemovedFunctionParameters sniff is not suitable to handle this.}
 *
 * @link https://wiki.php.net/rfc/deprecations_php_8_3#deprecate_calling_ldap_connect_with_2_parameters
 * @link https://www.php.net/ldap_connect
 *
 * @since 10.0.0
 */
final class RemovedLdapConnectSignaturesSniff extends AbstractFunctionCallParameterSniff
{

    /**
     * Functions to check for.
     *
     * @since 10.0.0
     *
     * @var array<string, bool>
     */
    protected $targetFunctions = [
        'ldap_connect' => true,
    ];

    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @since 10.0.0
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        return (ScannedCode::shouldRunOnOrAbove('8.3') === false);
    }

    /**
     * Process the parameters of a matched function.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token in the stack.
     * @param string                      $functionName The token content (function name) which was matched.
     * @param array                       $parameters   Array with information about the parameters.
     *
     * @return int|void Integer stack pointer to skip forward or void to continue
     *                  normal file processing.
     */
    public function processParameters(File $phpcsFile, $stackPtr, $functionName, $parameters)
    {
        $uriParam  = PassedParameters::getParameterFromStack($parameters, 1, ['uri', 'host']);
        $portParam = PassedParameters::getParameterFromStack($parameters, 2, 'port');

        if ($uriParam === false || $portParam === false) {
            // Not the deprecated two param signature.
            return;
        }

        // Check for the, still supported, 3+ param signature and if found, check the second param is `null`.
        if (\count($parameters) > 2) {
            $hasVariableContent = $phpcsFile->findNext([\T_VARIABLE, \T_STRING], $portParam['start'], ($portParam['end'] + 1));
            if ($hasVariableContent !== false) {
                // We don't have access to the contents of the parameter. Bow out.
                return;
            }

            if ($portParam['clean'] === 'null') {
                // This is the correct value to still use the 3+ param signature. Bow out.
                return;
            }

            // Found a 3+ param signature, which doesn't pass `null` as $port.
            $phpcsFile->addWarning(
                'Calling ldap_connect() with a $port which is not `null` is deprecated since PHP 8.3. Call ldap_connect() with an LDAP-URI as the $uri parameter and pass `null` for $port instead.',
                $stackPtr,
                'DeprecatedPortNotNull'
            );
            return;
        }

        // Found the deprecated 2-param signature.
        $phpcsFile->addWarning(
            'Calling ldap_connect() with two parameters is deprecated since PHP 8.3. Call ldap_connect() with an LDAP-URI as the first (and only) parameter instead.',
            $stackPtr,
            'DeprecatedTwoParamSignature'
        );
    }
}
