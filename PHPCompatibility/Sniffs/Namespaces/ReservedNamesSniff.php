<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Namespaces;

use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\Namespaces;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Detect declarations of namespace names reserved for, or in use by, PHP.
 *
 * > The Namespace name PHP, and compound names starting with this name (like PHP\Classes) are reserved
 * > for internal language use and should not be used in the userspace code.
 *
 * Also includes namespace names actually in use by PHP.
 *
 * PHP version 5.3+
 *
 * @link https://www.php.net/manual/en/language.namespaces.rationale.php
 * @link https://wiki.php.net/rfc/namespaces_in_bundled_extensions
 *
 * @since 10.0.0
 */
class ReservedNamesSniff extends Sniff
{

    /**
     * A list of new reserved namespace names, which prohibits the ability of userland
     * code to use these names.
     *
     * @since 10.0.0
     *
     * @var array<string, string>
     */
    protected $reservedNames = [
        /*
         * The top-level namespace name "PHP" is reserved by PHP since the introduction
         * of namespaces in PHP 5.3, but not yet in use.
         */
        'PHP'    => '5.3',

        // Top-level namespace names in use in bundled extensions.
        'FFI'    => '7.4',
        'FTP'    => '8.1',
        'IMAP'   => '8.1', // Unbundled from PHP in PHP 8.4.
        'LDAP'   => '8.1',
        'PgSql'  => '8.1',
        'PSpell' => '8.1',
        'Random' => '8.2',
        'Dba'    => '8.4',
        'Odbc'   => '8.4',
        'Soap'   => '8.4',
    ];

    /**
     * A list of namespace names which are in use by PHP extensions which are not bundled with PHP.
     *
     * Extensions are often initially developed in PECL, but may be moved to PHP src at a later point.
     *
     * For most users, conflicts with these top level names will not be problematic, but they can be
     * if the server the code runs on happens to have that particular extension installed and enabled.
     *
     * As these extensions are not bundled with PHP, we cannot relate the incompatibility to a PHP version,
     * unless the extension is only available for a certain PHP version (and higher).
     *
     * When these names are detected as being declared, a warning will be thrown.
     *
     * @since 10.0.0
     *
     * @var array<string, string>
     */
    protected $peclReservedNames = [
        // Top-level namespace names in use in PECL extensions which are documented in the PHP manual.
        'CommonMark'    => '*',
        'Componere'     => '*',
        'Ds'            => '7.0', // Data Structures extension.
        'Gender'        => '*',
        'HRTime'        => '*',
        'MongoDB'       => '*',
        'mysql_xdevapi' => '*',
        'parallel'      => '*',
        'Parle'         => '7.4',
        'Swoole'        => '*',
        'UI'            => '*',
        // Officially the prefix is Vtiful\Kernel, but the Vtiful namespace is a vendor name, so should not be
        // used in a declarative way by userland code anyway (aside from userland code writen by the Vtiful vendor).
        'Vtiful'        => '7.0', // XLSWriter extension.
        'wkhtmltox'     => '*',
        'XMLDiff'       => '*',

        // These extensions are not in the manual, but mentioned in the "namespaces in bundled extensions" RFC.
        'Aerospike'     => '*',
        'Cassandra'     => '*',
        'Couchbase'     => '*',
        'Crypto'        => '*',
        'Decimal'       => '*',
        'Grpc'          => '*',
        'http'          => '*',
        'Mosquitto'     => '*',
        'pcov'          => '*',
        'pq'            => '*',
        'RdKafka'       => '*',
        'Zstd'          => '*',
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
        // Handle case-insensitivity of namespace names.
        $this->reservedNames     = \array_change_key_case($this->reservedNames, \CASE_LOWER);
        $this->peclReservedNames = \array_change_key_case($this->peclReservedNames, \CASE_LOWER);

        return [
            \T_NAMESPACE,
        ];
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
        if (ScannedCode::shouldRunOnOrAbove('5.3') === false) {
            // Namespaces were introduced in PHP 5.3.
            return;
        }

        $name = Namespaces::getDeclaredName($phpcsFile, $stackPtr);
        if (empty($name)) {
            // Use of namespace operator, global namespace or parse error/live coding.
            return;
        }

        $nameParts = \explode('\\', $name);
        $firstPart = \strtolower($nameParts[0]);

        /*
         * Handle names reserved by PHP.
         */
        if (isset($this->reservedNames[$firstPart])) {
            if (ScannedCode::shouldRunOnOrAbove($this->reservedNames[$firstPart]) === false) {
                return;
            }

            // Throw the message on the first part of the namespace name.
            $firstNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);

            // Special case "PHP" to a warning with a custom message.
            if ($firstPart === 'php') {
                $phpcsFile->addWarning(
                    'Namespace name "%s" is discouraged; PHP has reserved the namespace name "PHP" and compound names starting with "PHP" for internal language use.',
                    $firstNonEmpty,
                    'phpFound',
                    [$name]
                );
                return;
            }

            // Throw an error for other reserved names.
            $phpcsFile->addError(
                'The top-level namespace name "%s" is reserved for, and in use by, PHP since PHP version %s. Found: %s',
                $firstNonEmpty,
                MessageHelper::stringToErrorCode($firstPart, true) . 'Found',
                [
                    $nameParts[0],
                    $this->reservedNames[$firstPart],
                    $name,
                ]
            );
        }

        /*
         * Handle names reserved by PECL extensions.
         */
        if (isset($this->peclReservedNames[$firstPart])) {
            if ($this->peclReservedNames[$firstPart] !== '*'
                && ScannedCode::shouldRunOnOrAbove($this->peclReservedNames[$firstPart]) === false
            ) {
                return;
            }

            // Throw the message on the first part of the namespace name.
            $firstNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);

            $warning = 'The top-level namespace name "%s" is reserved for, and in use by, a PECL extension%s. Found: %s';
            $code    = MessageHelper::stringToErrorCode($firstPart, true) . 'PeclReserved';
            $data    = [
                $nameParts[0],
                $this->peclReservedNames[$firstPart] === '*' ? '' : ' since PHP version ' . $this->peclReservedNames[$firstPart],
                $name,
            ];

            // Throw a warning for namespace names reserved for PECL extensions.
            $phpcsFile->addWarning($warning, $firstNonEmpty, $code, $data);
        }
    }
}
