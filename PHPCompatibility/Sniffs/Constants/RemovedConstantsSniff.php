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

use PHPCompatibility\Helpers\ComplexVersionDeprecatedRemovedFeatureTrait;
use PHPCompatibility\Helpers\MiscHelper;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\MessageHelper;

/**
 * Detect use of deprecated and/or removed PHP native global constants.
 *
 * PHP version All
 *
 * @since 8.1.0
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionDeprecatedRemovedFeatureTrait`.
 */
class RemovedConstantsSniff extends Sniff
{
    use ComplexVersionDeprecatedRemovedFeatureTrait;

    /**
     * A list of removed PHP Constants.
     *
     * The array lists : version number with false (deprecated) or true (removed).
     * If's sufficient to list the first version where the constant was deprecated/removed.
     *
     * Optional, the array can contain an `alternative` key listing an alternative constant
     * to be used instead.
     *
     * Note: PHP Constants are case-sensitive!
     *
     * @since 8.1.0
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $removedConstants = [
        'F_DUPFD' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'F_GETFD' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'F_GETFL' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'F_GETLK' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'F_GETOWN' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'F_RDLCK' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'F_SETFL' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'F_SETLK' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'F_SETLKW' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'F_SETOWN' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'F_UNLCK' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'F_WRLCK' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_APPEND' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_ASYNC' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_CREAT' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_EXCL' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_NDELAY' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_NOCTTY' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_NONBLOCK' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_RDONLY' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_RDWR' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_SYNC' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_TRUNC' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'O_WRONLY' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IRGRP' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IROTH' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IRUSR' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IRWXG' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IRWXO' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IRWXU' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IWGRP' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IWOTH' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IWUSR' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IXGRP' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IXOTH' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'S_IXUSR' => [
            '5.1'       => true,
            'extension' => 'dio',
        ],
        'M_PENDING' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'M_DONE' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'M_ERROR' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'M_FAIL' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'M_SUCCESS' => [
            '5.1'       => true,
            'extension' => 'mcve',
        ],
        'FAMChanged' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'FAMDeleted' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'FAMStartExecuting' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'FAMStopExecuting' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'FAMCreated' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'FAMMoved' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'FAMAcknowledge' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'FAMExists' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'FAMEndExist' => [
            '5.1'       => true,
            'extension' => 'fam',
        ],
        'YPERR_ACCESS' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_BADARGS' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_BADDB' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_BUSY' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_DOMAIN' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_KEY' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_MAP' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_NODOM' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_NOMORE' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_PMAP' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_RESRC' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_RPC' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_YPBIND' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_YPERR' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_YPSERV' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'YPERR_VERS' => [
            '5.1'       => true,
            'extension' => 'yp',
        ],
        'UDM_FIELD_URLID' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_URL' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_CONTENT' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_TITLE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_KEYWORDS' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_DESC' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_DESCRIPTION' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_TEXT' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_SIZE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_RATING' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_SCORE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_MODIFIED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_ORDER' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_CRC' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_CATEGORY' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_LANG' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_FIELD_CHARSET' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_PAGE_SIZE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_PAGE_NUM' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_SEARCH_MODE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_CACHE_MODE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_TRACK_MODE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_PHRASE_MODE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_CHARSET' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_LOCAL_CHARSET' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_BROWSER_CHARSET' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_STOPTABLE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_STOP_TABLE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_STOPFILE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_STOP_FILE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_WEIGHT_FACTOR' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_WORD_MATCH' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_MAX_WORD_LEN' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_MAX_WORDLEN' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_MIN_WORD_LEN' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_MIN_WORDLEN' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_ISPELL_PREFIXES' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_ISPELL_PREFIX' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_PREFIXES' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_PREFIX' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_CROSS_WORDS' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_CROSSWORDS' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_VARDIR' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_DATADIR' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_HLBEG' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_HLEND' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_SYNONYM' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_SEARCHD' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_QSTRING' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_REMOTE_ADDR' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_LIMIT_CAT' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_LIMIT_URL' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_LIMIT_TAG' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_LIMIT_LANG' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_LIMIT_DATE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_FOUND' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_NUM_ROWS' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_WORDINFO' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_WORD_INFO' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_SEARCHTIME' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_SEARCH_TIME' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_FIRST_DOC' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PARAM_LAST_DOC' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_MODE_ALL' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_MODE_ANY' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_MODE_BOOL' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_MODE_PHRASE' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_CACHE_ENABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_CACHE_DISABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_TRACK_ENABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_TRACK_DISABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PHRASE_ENABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PHRASE_DISABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_CROSS_WORDS_ENABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_CROSSWORDS_ENABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_CROSS_WORDS_DISABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_CROSSWORDS_DISABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PREFIXES_ENABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PREFIX_ENABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_ISPELL_PREFIXES_ENABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_ISPELL_PREFIX_ENABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PREFIXES_DISABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_PREFIX_DISABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_ISPELL_PREFIXES_DISABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_ISPELL_PREFIX_DISABLED' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_ISPELL_TYPE_AFFIX' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_ISPELL_TYPE_SPELL' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_ISPELL_TYPE_DB' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_ISPELL_TYPE_SERVER' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_MATCH_WORD' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_MATCH_BEGIN' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_MATCH_SUBSTR' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'UDM_MATCH_END' => [
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ],
        'DC_MICROSOFT' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'DC_BORLAND' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'DC_CALL_CDECL' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'DC_CALL_STD' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'DC_RETVAL_MATH4' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'DC_RETVAL_MATH8' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'DC_CALL_STD_BO' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'DC_CALL_STD_MS' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'DC_CALL_STD_M8' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'DC_FLAG_ARGPTR' => [
            '5.1'       => true,
            'extension' => 'w32api',
        ],
        'CPDF_PM_NONE' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'CPDF_PM_OUTLINES' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'CPDF_PM_THUMBS' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'CPDF_PM_FULLSCREEN' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'CPDF_PL_SINGLE' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'CPDF_PL_1COLUMN' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'CPDF_PL_2LCOLUMN' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'CPDF_PL_2RCOLUMN' => [
            '5.1'       => true,
            'extension' => 'cpdf',
        ],
        'DBX_MYSQL' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_ODBC' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_PGSQL' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_MSSQL' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_FBSQL' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_OCI8' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_SYBASECT' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_SQLITE' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_PERSISTENT' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_RESULT_INFO' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_RESULT_INDEX' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_RESULT_ASSOC' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_RESULT_UNBUFFERED' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_COLNAMES_UNCHANGED' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_COLNAMES_UPPERCASE' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_COLNAMES_LOWERCASE' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_CMP_NATIVE' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_CMP_TEXT' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_CMP_NUMBER' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_CMP_ASC' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'DBX_CMP_DESC' => [
            '5.1'       => true,
            'extension' => 'dbx',
        ],
        'INGRES_ASSOC' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'INGRES_NUM' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'INGRES_BOTH' => [
            '5.1'       => true,
            'extension' => 'ingres',
        ],
        'ORA_BIND_INOUT' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ORA_BIND_IN' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ORA_BIND_OUT' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ORA_FETCHINTO_ASSOC' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],
        'ORA_FETCHINTO_NULLS' => [
            '5.1'       => true,
            'extension' => 'oracle',
        ],

        'IFX_SCROLL' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'IFX_HOLD' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'IFX_LO_RDONLY' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'IFX_LO_WRONLY' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'IFX_LO_APPEND' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'IFX_LO_RDWR' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'IFX_LO_BUFFER' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],
        'IFX_LO_NOBUFFER' => [
            '5.2.1'     => true,
            'extension' => 'ifx',
        ],

        // Disabled since PHP 5.3.0 due to thread safety issues.
        'FILEINFO_COMPRESS' => [
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],

        'NCURSES_COLOR_BLACK' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_COLOR_WHITE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_COLOR_RED' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_COLOR_GREEN' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_COLOR_YELLOW' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_COLOR_BLUE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_COLOR_CYAN' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_COLOR_MAGENTA' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_F0' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_DOWN' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_UP' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_LEFT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_RIGHT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_HOME' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_BACKSPACE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_DL' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_IL' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_DC' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_IC' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_EIC' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_CLEAR' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_EOS' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_EOL' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SF' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SR' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_NPAGE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_PPAGE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_STAB' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_CTAB' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_CATAB' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SRESET' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_RESET' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_PRINT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_LL' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_A1' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_A3' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_B2' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_C1' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_C3' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_BTAB' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_BEG' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_CANCEL' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_CLOSE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_COMMAND' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_COPY' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_CREATE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_END' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_EXIT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_FIND' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_HELP' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_MARK' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_MESSAGE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_MOVE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_NEXT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_OPEN' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_OPTIONS' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_PREVIOUS' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_REDO' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_REFERENCE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_REFRESH' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_REPLACE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_RESTART' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_RESUME' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SAVE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SBEG' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SCANCEL' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SCOMMAND' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SCOPY' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SCREATE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SDC' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SDL' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SELECT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SEND' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SEOL' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SEXIT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SFIND' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SHELP' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SHOME' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SIC' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SLEFT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SMESSAGE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SMOVE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SNEXT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SOPTIONS' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SPREVIOUS' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SPRINT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SREDO' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SREPLACE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SRIGHT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SRSUME' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SSAVE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_SSUSPEND' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_UNDO' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_MOUSE' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_KEY_MAX' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_BUTTON1_RELEASED' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_BUTTON1_PRESSED' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_BUTTON1_CLICKED' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_BUTTON1_DOUBLE_CLICKED' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_BUTTON1_TRIPLE_CLICKED' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_BUTTON_CTRL' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_BUTTON_SHIFT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_BUTTON_ALT' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_ALL_MOUSE_EVENTS' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'NCURSES_REPORT_MOUSE_POSITION' => [
            '5.3'       => true,
            'extension' => 'ncurses',
        ],
        'FDFValue' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFStatus' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFFile' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFID' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFFf' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFSetFf' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFClearFf' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFFlags' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFSetF' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFClrF' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFAP' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFAS' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFAction' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFAA' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFAPRef' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFIF' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFEnter' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFExit' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFDown' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFUp' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFFormat' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFValidate' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFKeystroke' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFCalculate' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFNormalAP' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFRolloverAP' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],
        'FDFDownAP' => [
            '5.3'       => true,
            'extension' => 'fdf',
        ],

        'MING_NEW' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'MING_ZLIB' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBUTTON_HIT' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBUTTON_DOWN' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBUTTON_OVER' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBUTTON_UP' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBUTTON_MOUSEUPOUTSIDE' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBUTTON_DRAGOVER' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBUTTON_DRAGOUT' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBUTTON_MOUSEUP' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBUTTON_MOUSEDOWN' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBUTTON_MOUSEOUT' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFBUTTON_MOUSEOVER' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFFILL_RADIAL_GRADIENT' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFFILL_LINEAR_GRADIENT' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFFILL_TILED_BITMAP' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFFILL_CLIPPED_BITMAP' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_HASLENGTH' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_NOEDIT' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_PASSWORD' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_MULTILINE' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_WORDWRAP' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_DRAWBOX' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_NOSELECT' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_HTML' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_ALIGN_LEFT' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_ALIGN_RIGHT' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_ALIGN_CENTER' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_ALIGN_JUSTIFY' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFACTION_ONLOAD' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFACTION_ENTERFRAME' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFACTION_UNLOAD' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFACTION_MOUSEMOVE' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFACTION_MOUSEDOWN' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFACTION_MOUSEUP' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFACTION_KEYDOWN' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFACTION_KEYUP' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFACTION_DATA' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_USEFONT' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_AUTOSIZE' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_NOT_COMPRESSED' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_ADPCM_COMPRESSED' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_MP3_COMPRESSED' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_NOT_COMPRESSED_LE' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_NELLY_COMPRESSED' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_5KHZ' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_11KHZ' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_22KHZ' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_44KHZ' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_8BITS' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_16BITS' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_MONO' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_STEREO' => [
            '5.3'       => true,
            'extension' => 'ming',
        ],
        'FBSQL_ASSOC' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_NUM' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_BOTH' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_LOCK_DEFERRED' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_LOCK_OPTIMISTIC' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_LOCK_PESSIMISTIC' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_ISO_READ_UNCOMMITTED' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_ISO_READ_COMMITTED' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_ISO_REPEATABLE_READ' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_ISO_SERIALIZABLE' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_ISO_VERSIONED' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_UNKNOWN' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_STOPPED' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_STARTING' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_RUNNING' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_STOPPING' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_NOEXEC' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_LOB_DIRECT' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'FBSQL_LOB_HANDLE' => [
            '5.3'       => true,
            'extension' => 'fbsql',
        ],
        'MSQL_ASSOC' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'MSQL_NUM' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],
        'MSQL_BOTH' => [
            '5.3'       => true,
            'extension' => 'msql',
        ],

        'SQLITE_ASSOC' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_BOTH' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_NUM' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_OK' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_ERROR' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_INTERNAL' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_PERM' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_ABORT' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_BUSY' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_LOCKED' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_NOMEM' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_READONLY' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_INTERRUPT' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_IOERR' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_NOTADB' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_CORRUPT' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_FORMAT' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_NOTFOUND' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_FULL' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_CANTOPEN' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_PROTOCOL' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_EMPTY' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_SCHEMA' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_TOOBIG' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_CONSTRAINT' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_MISMATCH' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_MISUSE' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_NOLFS' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_AUTH' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_ROW' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_DONE' => [
            '5.4'       => true,
            'extension' => 'sqlite',
        ],

        'CURLOPT_CLOSEPOLICY' => [
            '5.6'       => true,
            'extension' => 'curl',
        ],
        'CURLCLOSEPOLICY_LEAST_RECENTLY_USED' => [
            '5.6'       => true,
            'extension' => 'curl',
        ],
        'CURLCLOSEPOLICY_LEAST_TRAFFIC' => [
            '5.6'       => true,
            'extension' => 'curl',
        ],
        'CURLCLOSEPOLICY_SLOWEST' => [
            '5.6'       => true,
            'extension' => 'curl',
        ],
        'CURLCLOSEPOLICY_CALLBACK' => [
            '5.6'       => true,
            'extension' => 'curl',
        ],
        'CURLCLOSEPOLICY_OLDEST' => [
            '5.6'       => true,
            'extension' => 'curl',
        ],

        'MYSQL_CLIENT_COMPRESS' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'MYSQL_CLIENT_IGNORE_SPACE' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'MYSQL_CLIENT_INTERACTIVE' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'MYSQL_CLIENT_SSL' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'MYSQL_ASSOC' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'MYSQL_BOTH' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'MYSQL_NUM' => [
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ],
        'PGSQL_ATTR_DISABLE_NATIVE_PREPARED_STATEMENT' => [
            '7.0'       => true,
            'extension' => 'pgsql',
        ],
        'T_CHARACTER' => [
            '7.0'       => true,
            'extension' => 'tokenizer',
        ],
        'T_BAD_CHARACTER' => [
            '7.0'       => true,
            'extension' => 'tokenizer',
        ],
        'MSSQL_ASSOC' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'MSSQL_NUM' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'MSSQL_BOTH' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'SQLTEXT' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'SQLVARCHAR' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'SQLCHAR' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'SQLINT1' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'SQLINT2' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'SQLINT4' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'SQLBIT' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'SQLFLT4' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],
        'SQLFLT8' => [
            '7.0'       => true,
            'extension' => 'mssql',
        ],

        'INTL_IDNA_VARIANT_2003' => [
            '7.2'       => false,
            '8.0'       => true,
            'extension' => 'intl',
        ],

        'MCRYPT_MODE_ECB' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_MODE_CBC' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_MODE_CFB' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_MODE_OFB' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_MODE_NOFB' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_MODE_STREAM' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_ENCRYPT' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_DECRYPT' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_DEV_RANDOM' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_DEV_URANDOM' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_RAND' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_3DES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_ARCFOUR_IV' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_ARCFOUR' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_BLOWFISH' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_CAST_128' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_CAST_256' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_CRYPT' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_DES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_DES_COMPAT' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_ENIGMA' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_GOST' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_IDEA' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_LOKI97' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_MARS' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_PANAMA' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_RIJNDAEL_128' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_RIJNDAEL_192' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_RIJNDAEL_256' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_RC2' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_RC4' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_RC6' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_RC6_128' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_RC6_192' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_RC6_256' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_SAFER64' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_SAFER128' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_SAFERPLUS' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_SERPENT' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_SERPENT_128' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_SERPENT_192' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_SERPENT_256' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_SKIPJACK' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_TEAN' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_THREEWAY' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_TRIPLEDES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_TWOFISH' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_TWOFISH128' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_TWOFISH192' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_TWOFISH256' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_WAKE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],
        'MCRYPT_XTEA' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ],

        'PHPDBG_FILE' => [
            '7.3' => true,
        ],
        'PHPDBG_METHOD' => [
            '7.3' => true,
        ],
        'PHPDBG_LINENO' => [
            '7.3' => true,
        ],
        'PHPDBG_FUNC' => [
            '7.3' => true,
        ],
        'FILTER_FLAG_SCHEME_REQUIRED' => [
            '7.3'       => false,
            '8.0'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_HOST_REQUIRED' => [
            '7.3'       => false,
            '8.0'       => true,
            'extension' => 'filter',
        ],

        'CURLPIPE_HTTP1' => [
            '7.4'       => false,
            'extension' => 'curl',
        ],
        'FILTER_SANITIZE_MAGIC_QUOTES' => [
            '7.4'         => false,
            '8.0'         => true,
            'alternative' => 'FILTER_SANITIZE_ADD_SLASHES',
            'extension'   => 'filter',
        ],
        'IBASE_BKP_CONVERT' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_BKP_IGNORE_CHECKSUMS' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_BKP_IGNORE_LIMBO' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_BKP_METADATA_ONLY' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_BKP_NO_GARBAGE_COLLECT' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_BKP_NON_TRANSPORTABLE' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_BKP_OLD_DESCRIPTIONS' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_COMMITTED' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_CONCURRENCY' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_CONSISTENCY' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_DEFAULT' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_FETCH_ARRAYS' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_FETCH_BLOBS' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_NOWAIT' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_ACCESS_MODE' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_ACTIVATE' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_AM_READONLY' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_AM_READWRITE' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_DENY_NEW_ATTACHMENTS' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_DENY_NEW_TRANSACTIONS' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_DB_ONLINE' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_PAGE_BUFFERS' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_RES' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_RES_USE_FULL' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_RESERVE_SPACE' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_SET_SQL_DIALECT' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_SHUTDOWN_DB' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_SWEEP_INTERVAL' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_WM_ASYNC' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_WM_SYNC' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_PRP_WRITE_MODE' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_READ' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RES_CREATE' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RES_DEACTIVATE_IDX' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RES_NO_SHADOW' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RES_NO_VALIDITY' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RES_ONE_AT_A_TIME' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RES_REPLACE' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RES_USE_ALL_SPACE' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RPR_CHECK_DB' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RPR_FULL' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RPR_IGNORE_CHECKSUM' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RPR_KILL_SHADOWS' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RPR_MEND_DB' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RPR_SWEEP_DB' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_RPR_VALIDATE_DB' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_STS_DATA_PAGES' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_STS_DB_LOG' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_STS_HDR_PAGES' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_STS_IDX_PAGES' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_STS_SYS_RELATIONS' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_SVC_GET_ENV' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_SVC_GET_ENV_LOCK' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_SVC_GET_ENV_MSG' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_SVC_GET_USERS' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_SVC_IMPLEMENTATION' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_SVC_SERVER_VERSION' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_SVC_SVR_DB_INFO' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_SVC_USER_DBPATH' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_UNIXTIME' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_WAIT' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],
        'IBASE_WRITE' => [
            '7.4'       => true,
            'extension' => 'ibase',
        ],

        'ASSERT_QUIET_EVAL' => [
            '8.0' => true,
        ],
        'ENCHANT_MYSPELL' => [
            '8.0'       => false,
            'extension' => 'enchant',
        ],
        'ENCHANT_ISPELL' => [
            '8.0'       => false,
            'extension' => 'enchant',
        ],
        'INPUT_REQUEST' => [
            '8.0'       => true,
            'extension' => 'filter',
        ],
        'INPUT_SESSION' => [
            '8.0'       => true,
            'extension' => 'filter',
        ],
        'MB_OVERLOAD_MAIL' => [
            '8.0'       => true,
            'extension' => 'mbstring',
        ],
        'MB_OVERLOAD_STRING' => [
            '8.0'       => true,
            'extension' => 'mbstring',
        ],
        'MB_OVERLOAD_REGEX' => [
            '8.0'       => true,
            'extension' => 'mbstring',
        ],
        'PG_VERSION_STR' => [
            '8.0'         => false,
            'alternative' => 'PG_VERSION',
            'extension'   => 'pgsql',
        ],

        'FILE_BINARY' => [
            '8.1' => false,
        ],
        'FILE_TEXT' => [
            '8.1' => false,
        ],
        'FILTER_SANITIZE_STRING' => [
            '8.1'       => false,
            'extension' => 'filter',
        ],
        'FILTER_SANITIZE_STRIPPED' => [
            '8.1'       => false,
            'extension' => 'filter',
        ],
        'NIL' => [
            '8.1'         => false,
            'alternative' => 'integer 0',
            'extension'   => 'imap',
        ],
        'MYSQLI_NO_DATA' => [
            '8.1'       => false,
            'extension' => 'mysqli',
        ],
        'MYSQLI_DATA_TRUNCATED' => [
            '8.1'       => false,
            'extension' => 'mysqli',
        ],
        'MYSQLI_SERVER_QUERY_NO_GOOD_INDEX_USED' => [
            '8.1'       => false,
            'extension' => 'mysqli',
        ],
        'MYSQLI_SERVER_QUERY_NO_INDEX_USED' => [
            '8.1'       => false,
            'extension' => 'mysqli',
        ],
        'MYSQLI_SERVER_QUERY_WAS_SLOW' => [
            '8.1'       => false,
            'extension' => 'mysqli',
        ],
        'MYSQLI_SERVER_PS_OUT_PARAMS' => [
            '8.1'       => false,
            'extension' => 'mysqli',
        ],
        'MYSQLI_STMT_ATTR_UPDATE_MAX_LENGTH' => [
            '8.1'       => false,
            'extension' => 'mysqli',
        ],
        'MYSQLI_STORE_RESULT_COPY_DATA' => [
            '8.1'       => false,
            'extension' => 'mysqli',
        ],

        'MYSQLI_IS_MARIADB' => [
            '8.2'       => false,
            'extension' => 'mysqli',
        ],

        'ASSERT_ACTIVE' => [
            '8.3' => false,
        ],
        'ASSERT_BAIL' => [
            '8.3' => false,
        ],
        'ASSERT_CALLBACK' => [
            '8.3' => false,
        ],
        'ASSERT_EXCEPTION' => [
            '8.3' => false,
        ],
        'ASSERT_WARNING' => [
            '8.3' => false,
        ],
        'U_MULTIPLE_DECIMAL_SEPERATORS' => [
            '8.3'         => false,
            'alternative' => 'U_MULTIPLE_DECIMAL_SEPARATORS',
            'extension'   => 'intl',
        ],
        'MT_RAND_PHP' => [
            '8.3'       => false,
            'extension' => 'random',
        ],
    ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.1.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [\T_STRING];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 8.1.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens       = $phpcsFile->getTokens();
        $constantName = $tokens[$stackPtr]['content'];

        if (isset($this->removedConstants[$constantName]) === false) {
            return;
        }

        if (MiscHelper::isUseOfGlobalConstant($phpcsFile, $stackPtr) === false) {
            return;
        }

        $itemInfo = [
            'name' => $constantName,
        ];
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
    }


    /**
     * Handle the retrieval of relevant information and - if necessary - throwing of an
     * error/warning for a matched item.
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
        $itemArray   = $this->removedConstants[$itemInfo['name']];
        $versionInfo = $this->getVersionInfo($itemArray);
        $isError     = null;

        if (empty($versionInfo['removed']) === false
            && ScannedCode::shouldRunOnOrAbove($versionInfo['removed']) === true
        ) {
            $isError = true;
        } elseif (empty($versionInfo['deprecated']) === false
            && ScannedCode::shouldRunOnOrAbove($versionInfo['deprecated']) === true
        ) {
            $isError = false;

            // Reset the 'removed' info as it is not relevant for the current notice.
            $versionInfo['removed'] = '';
        }

        if (isset($isError) === false) {
            return;
        }

        $this->addMessage($phpcsFile, $stackPtr, $isError, $itemInfo, $versionInfo);
    }


    /**
     * Generates the error or warning for this item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param bool                        $isError     Whether this should be an error or a warning.
     * @param array                       $itemInfo    Base information about the item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addMessage(File $phpcsFile, $stackPtr, $isError, array $itemInfo, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'The constant "%s" is ';

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        MessageHelper::addMessage(
            $phpcsFile,
            $msgInfo['message'],
            $stackPtr,
            $isError,
            $msgInfo['errorcode'],
            $msgInfo['data']
        );
    }
}
