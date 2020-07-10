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
 * Trait used in removed extension sniffs
 */
trait RemovedExtensionsTrait
{

    /**
     * A list of removed extensions with their alternative, if any.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the extension was deprecated/removed.
     *
     * Optionally, an `alternative` key can be added to add the name of an alternative which can be
     * used after this extension was removed from PHP Core.
     *
     * @since 10.0.0 This array in a slightly different form previously existed in the RemovedExtensionsSniff.
     *
     * @var array(string => array(string => bool|string|null))
     */
    public $removedExtensions = [
        'cpdf' => [
            '5.1'         => true,
            'alternative' => 'pecl/pdflib',
        ],
        'crack' => [
            '5.0'         => true,
            'alternative' => 'pecl/crack',
        ],
        'dbase' => [
            '5.3'         => true,
            'alternative' => 'pecl/dbase',
        ],
        'dbx' => [
            '5.1'         => true,
            'alternative' => 'pecl/dbx',
        ],
        'dio' => [
            '5.1'         => true,
            'alternative' => 'pecl/dio',
        ],
        'ereg' => [
            '5.3'         => false,
            '7.0'         => true,
            'alternative' => 'pcre',
        ],
        'fam' => [
            '5.1' => true,
        ],
        'fbsql' => [
            '5.3'         => true,
            'alternative' => 'pecl/fbsql',
        ],
        'fdf' => [
            '5.3'         => true,
            'alternative' => 'pecl/fdf',
        ],
        'filepro' => [
            '5.2'         => true,
            'alternative' => 'pecl/filepro',
        ],
        'hwapi' => [
            '5.2'         => true,
            'alternative' => 'pecl/hwapi',
        ],
        'ibase' => [
            '7.4'         => true,
            'alternative' => 'pecl/ibase',
        ],
        'ifx' => [
            '5.2.1'       => true,
            'alternative' => 'pecl/ifx',
        ],
        'ingres' => [
            '5.1'         => true,
            'alternative' => 'pecl/ingres',
        ],
        'ircg' => [
            '5.1' => true,
        ],
        'mcrypt' => [
            '7.1'         => false,
            '7.2'         => true,
            'alternative' => 'the sodium or openssl extensions (preferred) or pecl/mcrypt',
        ],
        'mcve' => [
            '5.1'         => true,
            'alternative' => 'pecl/mcve',
        ],
        'mimetype' => [
            '5.3'         => true,
            'alternative' => 'fileinfo',
        ],
        'ming' => [
            '5.3'         => true,
            'alternative' => 'pecl/ming',
        ],
        'mnogosearch' => [
            '5.1' => true,
        ],
        'msession' => [
            '5.1.3'       => true,
            'alternative' => 'pecl/msession',
        ],
        'msql' => [
            '5.3' => true,
        ],
        'mssql' => [
            '7.0' => true,
        ],
        'mysql' => [
            '5.5'         => false,
            '7.0'         => true,
            'alternative' => 'mysqli',
        ],
        'ncurses' => [
            '5.3'         => true,
            'alternative' => 'pecl/ncurses',
        ],
        'oracle' => [
            '5.1'         => true,
            'alternative' => 'oci8 or pdo_oci',
        ],
        'ovrimos' => [
            '5.1'         => true,
            'alternative' => 'pecl/ovrimos',
        ],
        'pfpro' => [
            '5.1' => true,
        ],
        'recode' => [
            '7.4'         => true,
            'alternative' => 'iconv or mbstring',
        ],
        'sqlite' => [
            '5.4'         => true,
            'alternative' => 'sqlite3, PDO sqlite or pecl/sqlite',
        ],
        /*
         * This also covers sybase_ct.
         * The sybase extension was removed in 5.3, sybase_ct in 7.0 but function names were identical.
         */
        'sybase' => [
            '7.0' => true,
        ],
        'w32api' => [
            '5.1'         => true,
            'alternative' => 'pecl/ffi',
        ],
        'wddx' => [
            '7.4'         => true,
            'alternative' => 'pecl/wddx',
        ],
        'xmlrpc' => [
            '8.0'         => true,
            'alternative' => 'pecl/xmlrpc',
        ],
        'yp' => [
            '5.1' => true,
        ],
    ];

    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * By default, removed feature version arrays, contain an additional 'alternative' and a potential
     * 'extension' array key.
     *
     * @since 10.0.0
     *
     * @return array
     */
    protected function getNonVersionArrayKeys()
    {
        return ['alternative', 'extension'];
    }


    /*
     * TODO:
     * - add function to check if an error message needs to be adjusted as something is a removed extension.
     * - add function to adjust the error message
     * - implement use of the trait in the relevant sniffs.
     */
}
