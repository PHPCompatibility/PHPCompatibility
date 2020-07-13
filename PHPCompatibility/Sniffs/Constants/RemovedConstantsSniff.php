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

use PHPCompatibility\AbstractRemovedFeatureSniff;
use PHP_CodeSniffer_File as File;

/**
 * Detect use of deprecated and/or removed PHP native global constants.
 *
 * PHP version All
 *
 * @since 8.1.0
 */
class RemovedConstantsSniff extends AbstractRemovedFeatureSniff
{

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
     * @var array(string => array(string => bool|string))
     */
    protected $removedConstants = array(
        'F_DUPFD' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'F_GETFD' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'F_GETFL' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'F_GETLK' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'F_GETOWN' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'F_RDLCK' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'F_SETFL' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'F_SETLK' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'F_SETLKW' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'F_SETOWN' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'F_UNLCK' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'F_WRLCK' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_APPEND' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_ASYNC' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_CREAT' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_EXCL' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_NDELAY' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_NOCTTY' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_NONBLOCK' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_RDONLY' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_RDWR' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_SYNC' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_TRUNC' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'O_WRONLY' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IRGRP' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IROTH' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IRUSR' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IRWXG' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IRWXO' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IRWXU' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IWGRP' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IWOTH' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IWUSR' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IXGRP' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IXOTH' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'S_IXUSR' => array(
            '5.1'       => true,
            'extension' => 'dio',
        ),
        'M_PENDING' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'M_DONE' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'M_ERROR' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'M_FAIL' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'M_SUCCESS' => array(
            '5.1'       => true,
            'extension' => 'mcve',
        ),
        'FAMChanged' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'FAMDeleted' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'FAMStartExecuting' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'FAMStopExecuting' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'FAMCreated' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'FAMMoved' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'FAMAcknowledge' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'FAMExists' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'FAMEndExist' => array(
            '5.1'       => true,
            'extension' => 'fam',
        ),
        'YPERR_ACCESS' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_BADARGS' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_BADDB' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_BUSY' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_DOMAIN' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_KEY' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_MAP' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_NODOM' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_NOMORE' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_PMAP' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_RESRC' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_RPC' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_YPBIND' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_YPERR' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_YPSERV' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'YPERR_VERS' => array(
            '5.1'       => true,
            'extension' => 'yp',
        ),
        'UDM_FIELD_URLID' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_URL' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_CONTENT' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_TITLE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_KEYWORDS' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_DESC' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_DESCRIPTION' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_TEXT' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_SIZE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_RATING' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_SCORE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_MODIFIED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_ORDER' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_CRC' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_CATEGORY' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_LANG' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_FIELD_CHARSET' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_PAGE_SIZE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_PAGE_NUM' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_SEARCH_MODE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_CACHE_MODE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_TRACK_MODE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_PHRASE_MODE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_CHARSET' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_LOCAL_CHARSET' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_BROWSER_CHARSET' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_STOPTABLE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_STOP_TABLE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_STOPFILE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_STOP_FILE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_WEIGHT_FACTOR' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_WORD_MATCH' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_MAX_WORD_LEN' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_MAX_WORDLEN' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_MIN_WORD_LEN' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_MIN_WORDLEN' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_ISPELL_PREFIXES' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_ISPELL_PREFIX' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_PREFIXES' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_PREFIX' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_CROSS_WORDS' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_CROSSWORDS' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_VARDIR' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_DATADIR' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_HLBEG' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_HLEND' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_SYNONYM' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_SEARCHD' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_QSTRING' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_REMOTE_ADDR' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_LIMIT_CAT' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_LIMIT_URL' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_LIMIT_TAG' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_LIMIT_LANG' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_LIMIT_DATE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_FOUND' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_NUM_ROWS' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_WORDINFO' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_WORD_INFO' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_SEARCHTIME' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_SEARCH_TIME' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_FIRST_DOC' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PARAM_LAST_DOC' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_MODE_ALL' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_MODE_ANY' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_MODE_BOOL' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_MODE_PHRASE' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_CACHE_ENABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_CACHE_DISABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_TRACK_ENABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_TRACK_DISABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PHRASE_ENABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PHRASE_DISABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_CROSS_WORDS_ENABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_CROSSWORDS_ENABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_CROSS_WORDS_DISABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_CROSSWORDS_DISABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PREFIXES_ENABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PREFIX_ENABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_ISPELL_PREFIXES_ENABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_ISPELL_PREFIX_ENABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PREFIXES_DISABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_PREFIX_DISABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_ISPELL_PREFIXES_DISABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_ISPELL_PREFIX_DISABLED' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_ISPELL_TYPE_AFFIX' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_ISPELL_TYPE_SPELL' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_ISPELL_TYPE_DB' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_ISPELL_TYPE_SERVER' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_MATCH_WORD' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_MATCH_BEGIN' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_MATCH_SUBSTR' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'UDM_MATCH_END' => array(
            '5.1'       => true,
            'extension' => 'mnogosearch',
        ),
        'DC_MICROSOFT' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'DC_BORLAND' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'DC_CALL_CDECL' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'DC_CALL_STD' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'DC_RETVAL_MATH4' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'DC_RETVAL_MATH8' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'DC_CALL_STD_BO' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'DC_CALL_STD_MS' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'DC_CALL_STD_M8' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'DC_FLAG_ARGPTR' => array(
            '5.1'       => true,
            'extension' => 'w32api',
        ),
        'CPDF_PM_NONE' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'CPDF_PM_OUTLINES' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'CPDF_PM_THUMBS' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'CPDF_PM_FULLSCREEN' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'CPDF_PL_SINGLE' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'CPDF_PL_1COLUMN' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'CPDF_PL_2LCOLUMN' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'CPDF_PL_2RCOLUMN' => array(
            '5.1'       => true,
            'extension' => 'cpdf',
        ),
        'DBX_MYSQL' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_ODBC' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_PGSQL' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_MSSQL' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_FBSQL' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_OCI8' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_SYBASECT' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_SQLITE' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_PERSISTENT' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_RESULT_INFO' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_RESULT_INDEX' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_RESULT_ASSOC' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_RESULT_UNBUFFERED' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_COLNAMES_UNCHANGED' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_COLNAMES_UPPERCASE' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_COLNAMES_LOWERCASE' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_CMP_NATIVE' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_CMP_TEXT' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_CMP_NUMBER' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_CMP_ASC' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'DBX_CMP_DESC' => array(
            '5.1'       => true,
            'extension' => 'dbx',
        ),
        'INGRES_ASSOC' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'INGRES_NUM' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'INGRES_BOTH' => array(
            '5.1'       => true,
            'extension' => 'ingres',
        ),
        'ORA_BIND_INOUT' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ORA_BIND_IN' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ORA_BIND_OUT' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ORA_FETCHINTO_ASSOC' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),
        'ORA_FETCHINTO_NULLS' => array(
            '5.1'       => true,
            'extension' => 'oracle',
        ),

        'IFX_SCROLL' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'IFX_HOLD' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'IFX_LO_RDONLY' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'IFX_LO_WRONLY' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'IFX_LO_APPEND' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'IFX_LO_RDWR' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'IFX_LO_BUFFER' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),
        'IFX_LO_NOBUFFER' => array(
            '5.2.1'     => true,
            'extension' => 'ifx',
        ),

        // Disabled since PHP 5.3.0 due to thread safety issues.
        'FILEINFO_COMPRESS' => array(
            '5.3' => true,
        ),

        'NCURSES_COLOR_BLACK' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_COLOR_WHITE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_COLOR_RED' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_COLOR_GREEN' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_COLOR_YELLOW' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_COLOR_BLUE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_COLOR_CYAN' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_COLOR_MAGENTA' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_F0' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_DOWN' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_UP' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_LEFT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_RIGHT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_HOME' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_BACKSPACE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_DL' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_IL' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_DC' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_IC' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_EIC' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_CLEAR' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_EOS' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_EOL' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SF' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SR' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_NPAGE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_PPAGE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_STAB' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_CTAB' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_CATAB' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SRESET' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_RESET' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_PRINT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_LL' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_A1' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_A3' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_B2' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_C1' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_C3' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_BTAB' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_BEG' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_CANCEL' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_CLOSE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_COMMAND' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_COPY' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_CREATE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_END' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_EXIT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_FIND' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_HELP' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_MARK' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_MESSAGE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_MOVE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_NEXT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_OPEN' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_OPTIONS' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_PREVIOUS' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_REDO' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_REFERENCE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_REFRESH' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_REPLACE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_RESTART' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_RESUME' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SAVE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SBEG' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SCANCEL' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SCOMMAND' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SCOPY' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SCREATE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SDC' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SDL' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SELECT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SEND' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SEOL' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SEXIT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SFIND' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SHELP' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SHOME' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SIC' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SLEFT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SMESSAGE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SMOVE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SNEXT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SOPTIONS' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SPREVIOUS' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SPRINT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SREDO' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SREPLACE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SRIGHT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SRSUME' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SSAVE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_SSUSPEND' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_UNDO' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_MOUSE' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_KEY_MAX' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_BUTTON1_RELEASED' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_BUTTON1_PRESSED' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_BUTTON1_CLICKED' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_BUTTON1_DOUBLE_CLICKED' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_BUTTON1_TRIPLE_CLICKED' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_BUTTON_CTRL' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_BUTTON_SHIFT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_BUTTON_ALT' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_ALL_MOUSE_EVENTS' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'NCURSES_REPORT_MOUSE_POSITION' => array(
            '5.3'       => true,
            'extension' => 'ncurses',
        ),
        'FDFValue' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFStatus' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFFile' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFID' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFFf' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFSetFf' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFClearFf' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFFlags' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFSetF' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFClrF' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFAP' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFAS' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFAction' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFAA' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFAPRef' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFIF' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFEnter' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFExit' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFDown' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFUp' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFFormat' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFValidate' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFKeystroke' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFCalculate' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFNormalAP' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFRolloverAP' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),
        'FDFDownAP' => array(
            '5.3'       => true,
            'extension' => 'fdf',
        ),

        'MING_NEW' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'MING_ZLIB' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBUTTON_HIT' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBUTTON_DOWN' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBUTTON_OVER' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBUTTON_UP' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBUTTON_MOUSEUPOUTSIDE' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBUTTON_DRAGOVER' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBUTTON_DRAGOUT' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBUTTON_MOUSEUP' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBUTTON_MOUSEDOWN' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBUTTON_MOUSEOUT' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBUTTON_MOUSEOVER' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFFILL_RADIAL_GRADIENT' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFFILL_LINEAR_GRADIENT' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFFILL_TILED_BITMAP' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFFILL_CLIPPED_BITMAP' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_HASLENGTH' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_NOEDIT' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_PASSWORD' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_MULTILINE' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_WORDWRAP' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_DRAWBOX' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_NOSELECT' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_HTML' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_ALIGN_LEFT' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_ALIGN_RIGHT' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_ALIGN_CENTER' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_ALIGN_JUSTIFY' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFACTION_ONLOAD' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFACTION_ENTERFRAME' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFACTION_UNLOAD' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFACTION_MOUSEMOVE' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFACTION_MOUSEDOWN' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFACTION_MOUSEUP' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFACTION_KEYDOWN' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFACTION_KEYUP' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFACTION_DATA' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_USEFONT' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTEXTFIELD_AUTOSIZE' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_NOT_COMPRESSED' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_ADPCM_COMPRESSED' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_MP3_COMPRESSED' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_NOT_COMPRESSED_LE' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_NELLY_COMPRESSED' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_5KHZ' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_11KHZ' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_22KHZ' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_44KHZ' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_8BITS' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_16BITS' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_MONO' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWF_SOUND_STEREO' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'FBSQL_ASSOC' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_NUM' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_BOTH' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_LOCK_DEFERRED' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_LOCK_OPTIMISTIC' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_LOCK_PESSIMISTIC' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_ISO_READ_UNCOMMITTED' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_ISO_READ_COMMITTED' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_ISO_REPEATABLE_READ' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_ISO_SERIALIZABLE' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_ISO_VERSIONED' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_UNKNOWN' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_STOPPED' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_STARTING' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_RUNNING' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_STOPPING' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_NOEXEC' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_LOB_DIRECT' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'FBSQL_LOB_HANDLE' => array(
            '5.3'       => true,
            'extension' => 'fbsql',
        ),
        'MSQL_ASSOC' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'MSQL_NUM' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),
        'MSQL_BOTH' => array(
            '5.3'       => true,
            'extension' => 'msql',
        ),

        'SQLITE_ASSOC' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_BOTH' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_NUM' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_OK' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_ERROR' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_INTERNAL' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_PERM' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_ABORT' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_BUSY' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_LOCKED' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_NOMEM' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_READONLY' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_INTERRUPT' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_IOERR' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_NOTADB' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_CORRUPT' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_FORMAT' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_NOTFOUND' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_FULL' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_CANTOPEN' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_PROTOCOL' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_EMPTY' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_SCHEMA' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_TOOBIG' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_CONSTRAINT' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_MISMATCH' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_MISUSE' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_NOLFS' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_AUTH' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_ROW' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLITE_DONE' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),

        'CURLOPT_CLOSEPOLICY' => array(
            '5.6' => true,
        ),
        'CURLCLOSEPOLICY_LEAST_RECENTLY_USED' => array(
            '5.6' => true,
        ),
        'CURLCLOSEPOLICY_LEAST_TRAFFIC' => array(
            '5.6' => true,
        ),
        'CURLCLOSEPOLICY_SLOWEST' => array(
            '5.6' => true,
        ),
        'CURLCLOSEPOLICY_CALLBACK' => array(
            '5.6' => true,
        ),
        'CURLCLOSEPOLICY_OLDEST' => array(
            '5.6' => true,
        ),

        'MYSQL_CLIENT_COMPRESS' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'MYSQL_CLIENT_IGNORE_SPACE' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'MYSQL_CLIENT_INTERACTIVE' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'MYSQL_CLIENT_SSL' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'MYSQL_ASSOC' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'MYSQL_BOTH' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'MYSQL_NUM' => array(
            '5.5'       => false,
            '7.0'       => true,
            'extension' => 'mysql',
        ),
        'PGSQL_ATTR_DISABLE_NATIVE_PREPARED_STATEMENT' => array(
            '7.0' => true,
        ),
        'T_CHARACTER' => array(
            '7.0' => true,
        ),
        'T_BAD_CHARACTER' => array(
            '7.0' => true,
        ),
        'MSSQL_ASSOC' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'MSSQL_NUM' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'MSSQL_BOTH' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'SQLTEXT' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'SQLVARCHAR' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'SQLCHAR' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'SQLINT1' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'SQLINT2' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'SQLINT4' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'SQLBIT' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'SQLFLT4' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),
        'SQLFLT8' => array(
            '7.0'       => true,
            'extension' => 'mssql',
        ),

        'INTL_IDNA_VARIANT_2003' => array(
            '7.2' => false,
            '8.0' => true,
        ),

        'MCRYPT_MODE_ECB' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_MODE_CBC' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_MODE_CFB' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_MODE_OFB' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_MODE_NOFB' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_MODE_STREAM' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_ENCRYPT' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_DECRYPT' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_DEV_RANDOM' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_DEV_URANDOM' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_RAND' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_3DES' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_ARCFOUR_IV' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_ARCFOUR' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_BLOWFISH' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_CAST_128' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_CAST_256' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_CRYPT' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_DES' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_DES_COMPAT' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_ENIGMA' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_GOST' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_IDEA' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_LOKI97' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_MARS' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_PANAMA' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_RIJNDAEL_128' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_RIJNDAEL_192' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_RIJNDAEL_256' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_RC2' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_RC4' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_RC6' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_RC6_128' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_RC6_192' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_RC6_256' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_SAFER64' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_SAFER128' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_SAFERPLUS' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_SERPENT' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_SERPENT_128' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_SERPENT_192' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_SERPENT_256' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_SKIPJACK' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_TEAN' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_THREEWAY' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_TRIPLEDES' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_TWOFISH' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_TWOFISH128' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_TWOFISH192' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_TWOFISH256' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_WAKE' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),
        'MCRYPT_XTEA' => array(
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'mcrypt',
        ),

        'PHPDBG_FILE' => array(
            '7.3' => true,
        ),
        'PHPDBG_METHOD' => array(
            '7.3' => true,
        ),
        'PHPDBG_LINENO' => array(
            '7.3' => true,
        ),
        'PHPDBG_FUNC' => array(
            '7.3' => true,
        ),
        'FILTER_FLAG_SCHEME_REQUIRED' => array(
            '7.3' => false,
        ),
        'FILTER_FLAG_HOST_REQUIRED' => array(
            '7.3' => false,
        ),

        'CURLPIPE_HTTP1' => array(
            '7.4' => false,
        ),
        'FILTER_SANITIZE_MAGIC_QUOTES' => array(
            '7.4'         => false,
            '8.0'         => true,
            'alternative' => 'FILTER_SANITIZE_ADD_SLASHES',
        ),
        'IBASE_BKP_CONVERT' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_BKP_IGNORE_CHECKSUMS' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_BKP_IGNORE_LIMBO' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_BKP_METADATA_ONLY' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_BKP_NO_GARBAGE_COLLECT' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_BKP_NON_TRANSPORTABLE' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_BKP_OLD_DESCRIPTIONS' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_COMMITTED' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_CONCURRENCY' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_CONSISTENCY' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_DEFAULT' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_FETCH_ARRAYS' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_FETCH_BLOBS' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_NOWAIT' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_ACCESS_MODE' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_ACTIVATE' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_AM_READONLY' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_AM_READWRITE' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_DENY_NEW_ATTACHMENTS' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_DENY_NEW_TRANSACTIONS' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_DB_ONLINE' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_PAGE_BUFFERS' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_RES' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_RES_USE_FULL' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_RESERVE_SPACE' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_SET_SQL_DIALECT' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_SHUTDOWN_DB' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_SWEEP_INTERVAL' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_WM_ASYNC' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_WM_SYNC' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_PRP_WRITE_MODE' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_READ' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RES_CREATE' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RES_DEACTIVATE_IDX' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RES_NO_SHADOW' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RES_NO_VALIDITY' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RES_ONE_AT_A_TIME' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RES_REPLACE' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RES_USE_ALL_SPACE' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RPR_CHECK_DB' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RPR_FULL' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RPR_IGNORE_CHECKSUM' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RPR_KILL_SHADOWS' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RPR_MEND_DB' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RPR_SWEEP_DB' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_RPR_VALIDATE_DB' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_STS_DATA_PAGES' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_STS_DB_LOG' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_STS_HDR_PAGES' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_STS_IDX_PAGES' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_STS_SYS_RELATIONS' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_SVC_GET_ENV' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_SVC_GET_ENV_LOCK' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_SVC_GET_ENV_MSG' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_SVC_GET_USERS' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_SVC_IMPLEMENTATION' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_SVC_SERVER_VERSION' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_SVC_SVR_DB_INFO' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_SVC_USER_DBPATH' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_UNIXTIME' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_WAIT' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),
        'IBASE_WRITE' => array(
            '7.4'       => true,
            'extension' => 'ibase',
        ),

        'ASSERT_QUIET_EVAL' => array(
            '8.0' => true,
        ),
        'MB_OVERLOAD_MAIL' => array(
            '8.0' => true,
        ),
        'MB_OVERLOAD_STRING' => array(
            '8.0' => true,
        ),
        'MB_OVERLOAD_REGEX' => array(
            '8.0' => true,
        ),
        'PG_VERSION_STR' => array(
            '8.0'         => false,
            'alternative' => 'PG_VERSION',
        ),
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 8.1.0
     *
     * @return array
     */
    public function register()
    {
        return array(\T_STRING);
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 8.1.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
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

        if ($this->isUseOfGlobalConstant($phpcsFile, $stackPtr) === false) {
            return;
        }

        $itemInfo = array(
            'name' => $constantName,
        );
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
    }


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @since 8.1.0
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->removedConstants[$itemInfo['name']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @since 8.1.0
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The constant "%s" is ';
    }
}
