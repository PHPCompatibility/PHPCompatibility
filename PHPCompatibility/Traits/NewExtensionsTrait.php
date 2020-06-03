<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Traits;

/*
 * TODO/ THINK OVER:
 * Consider a property to allow ignoring errors about a specific extension (for BC layers etc).
 * Difficulty: can this property be made file specific ? I.e. the errors about removed/new functionality
 * from an extension is only ignored when used in a specific file/directory.
 * If not, it may not be worth adding.
 *
 * Think: wp-db.php containing references to mysql for BC purposes.
 * However, in other files Mysql should still not be allowed.
 */

/**
 * Trait used in new extension sniffs
 */
trait NewExtensionsTrait
{

    /**
     * A list of new PHP extensions.
     *
     * The array lists : version number with false (available in PECL) and true (shipped with PHP).
     * If's sufficient to list the first version where the extension was introduced.
     *
     * Optionally, an `alternative` key can be added to add the name of an alternative which can be
     * used before this extension became available in PHP Core.
     *
     * @since 10.0.0
     *
     * @var array(string => array(string => bool|string|null))
     */
    public $newExtensions = [
        'csprng' => [
            '7.0'         => true,
            'alternative' => 'https://github.com/paragonie/random_compat',
        ],
        'dom' => [
            '5.0' => true,
        ],
        'enchant' => [
            '5.3'         => true,
            'alternative' => 'pecl/enchant',
        ],
        'ffi' => [
            '7.4' => true,
        ],
        'fileinfo' => [
            '5.3'         => true,
            'alternative' => 'pecl/fileinfo',
        ],
        'filter' => [
            '5.2' => true,
        ],
        'fpm' => [
            '5.3.3' => true,
        ],
        'hash' => [
            '5.1.2'       => true,
            'alternative' => 'pecl/hash',
        ],
        'intl' => [
            '5.3'         => true,
            'alternative' => 'pecl/intl',
        ],
        'json' => [
            '5.2' => true,
        ],
        'libxml' => [
            '5.1' => true,
        ],
        'mysqli' => [
            '5.0'         => true,
            'alternative' => 'mysql',
        ],
        'mysqlnd' => [
            '5.3' => true,
        ],
        'opcache' => [
            '5.5'         => true,
            'alternative' => 'pecl/opcache',
        ],
        'password' => [
            '5.5'         => true,
            'alternative' => 'https://github.com/ircmaxell/password_compat',
        ],
        'pdo' => [
            '5.1' => true,
        ],
        'phar' => [
            '5.3'         => true,
            'alternative' => 'pecl/phar',
        ],
        'phpdbg' => [
            '5.6' => true,
        ],
        'reflection' => [
            '5.0' => true,
        ],
        'simplexml' => [
            '5.0' => true,
        ],
        'soap' => [
            '5.0' => true,
        ],
        'sodium' => [
            '7.2'         => true,
            'alternative' => 'pecl/libsodium or https://github.com/paragonie/sodium_compat/',
        ],
        'spl' => [
            '5.0' => true,
        ],
        'sqlite' => [
            '5.0' => true,
        ],
        'sqlite3' => [
            '5.3'         => true,
            'alternative' => 'sqlite',
        ],
        'tidy' => [
            '5.0' => true,
        ],
        'xmlreader' => [
            '5.1'         => true,
            'alternative' => 'pecl/xmlreader',
        ],
        'xmlwriter' => [
            '5.1.2'       => true,
            'alternative' => 'pecl/xmlwriter',
        ],
        'xsl' => [
            '5.0' => true,
        ],
    ];

    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * By default, new feature version arrays, contain a potential 'extension' array key.
     *
     * @since 10.0.0
     *
     * @return array
     */
    protected function getNonVersionArrayKeys()
    {
        return ['extension'];
    }

    /*
     * TODO:
     * - add function to check if an error message needs to be adjusted as something is a new extension.
     * - add function to adjust the error message
     * - implement use of the trait in the relevant sniffs.
     */
}
