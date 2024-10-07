<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Constants;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedConstants sniff.
 *
 * @group removedConstants
 * @group constants
 *
 * @covers \PHPCompatibility\Sniffs\Constants\RemovedConstantsSniff
 *
 * @since 8.1.0
 */
class RemovedConstantsUnitTest extends BaseSniffTestCase
{

    /**
     * testDeprecatedConstant
     *
     * @dataProvider dataDeprecatedConstant
     *
     * @param string $constantName      Name of the PHP constant.
     * @param string $deprecatedIn      The PHP version in which the constant was deprecated.
     * @param int    $line              The line number in the test file which applies to this constant.
     * @param string $okVersion         A PHP version in which the constant was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     *
     * @return void
     */
    public function testDeprecatedConstant($constantName, $deprecatedIn, $line, $okVersion, $deprecatedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        $this->assertNoViolation($file, $line);

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn}";
        $this->assertWarning($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedConstant()
     *
     * @return array
     */
    public static function dataDeprecatedConstant()
    {
        return [
            ['CURLPIPE_HTTP1', '7.4', 138, '7.3'],
            ['ENCHANT_MYSPELL', '8.0', 625, '7.4'],
            ['ENCHANT_ISPELL', '8.0', 626, '7.4'],
            ['FILE_BINARY', '8.1', 627, '8.0'],
            ['FILE_TEXT', '8.1', 628, '8.0'],
            ['FILTER_SANITIZE_STRING', '8.1', 629, '8.0'],
            ['FILTER_SANITIZE_STRIPPED', '8.1', 630, '8.0'],
            ['MYSQLI_NO_DATA', '8.1', 632, '8.0'],
            ['MYSQLI_DATA_TRUNCATED', '8.1', 633, '8.0'],
            ['MYSQLI_SERVER_QUERY_NO_GOOD_INDEX_USED', '8.1', 634, '8.0'],
            ['MYSQLI_SERVER_QUERY_NO_INDEX_USED', '8.1', 635, '8.0'],
            ['MYSQLI_SERVER_QUERY_WAS_SLOW', '8.1', 636, '8.0'],
            ['MYSQLI_SERVER_PS_OUT_PARAMS', '8.1', 637, '8.0'],
            ['MYSQLI_STMT_ATTR_UPDATE_MAX_LENGTH', '8.1', 638, '8.0'],
            ['MYSQLI_STORE_RESULT_COPY_DATA', '8.1', 639, '8.0'],

            ['MYSQLI_IS_MARIADB', '8.2', 644, '8.1'],

            ['ASSERT_ACTIVE', '8.3', 646, '8.2'],
            ['ASSERT_BAIL', '8.3', 647, '8.2'],
            ['ASSERT_CALLBACK', '8.3', 648, '8.2'],
            ['ASSERT_EXCEPTION', '8.3', 649, '8.2'],
            ['ASSERT_WARNING', '8.3', 650, '8.2'],
            ['MT_RAND_PHP', '8.3', 651, '8.2'],

            ['DOM_PHP_ERR', '8.4', 801, '8.3'],
            ['SUNFUNCS_RET_DOUBLE', '8.4', 802, '8.3'],
            ['SUNFUNCS_RET_STRING', '8.4', 803, '8.3'],
            ['SUNFUNCS_RET_TIMESTAMP', '8.4', 804, '8.3'],
            ['E_STRICT', '8.4', 805, '8.3'],
            ['SOAP_FUNCTIONS_ALL', '8.4', 806, '8.3'],
            ['MYSQLI_REFRESH_GRANT', '8.4', 807, '8.3'],
            ['MYSQLI_REFRESH_LOG', '8.4', 808, '8.3'],
            ['MYSQLI_REFRESH_TABLES', '8.4', 809, '8.3'],
            ['MYSQLI_REFRESH_HOSTS', '8.4', 810, '8.3'],
            ['MYSQLI_REFRESH_REPLICA', '8.4', 811, '8.3'],
            ['MYSQLI_REFRESH_STATUS', '8.4', 812, '8.3'],
            ['MYSQLI_REFRESH_THREADS', '8.4', 813, '8.3'],
            ['MYSQLI_REFRESH_SLAVE', '8.4', 814, '8.3'],
            ['MYSQLI_REFRESH_MASTER', '8.4', 815, '8.3'],
            ['MYSQLI_REFRESH_BACKUP_LOG', '8.4', 816, '8.3'],
            ['CURLOPT_BINARYTRANSFER', '8.4', 817, '8.3'],
        ];
    }

    /**
     * testDeprecatedConstantWithAlternative
     *
     * @dataProvider dataDeprecatedConstantWithAlternative
     *
     * @param string $constantName      Name of the PHP constant.
     * @param string $deprecatedIn      The PHP version in which the constant was deprecated.
     * @param string $alternative       An alternative constant.
     * @param int    $line              The line number in the test file which applies to this constant.
     * @param string $okVersion         A PHP version in which the constant was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     *
     * @return void
     */
    public function testDeprecatedConstantWithAlternative($constantName, $deprecatedIn, $alternative, $line, $okVersion, $deprecatedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        $this->assertNoViolation($file, $line);

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead";
        $this->assertWarning($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedConstantWithAlternative()
     *
     * @return array
     */
    public static function dataDeprecatedConstantWithAlternative()
    {
        return [
            ['PG_VERSION_STR', '8.0', 'PG_VERSION', 624, '7.4'],
            ['U_MULTIPLE_DECIMAL_SEPERATORS', '8.3', 'U_MULTIPLE_DECIMAL_SEPARATORS', 652, '8.2'],
        ];
    }


    /**
     * testRemovedConstant
     *
     * @dataProvider dataRemovedConstant
     *
     * @param string $constantName   Name of the PHP constant.
     * @param string $removedIn      The PHP version in which the constant was removed.
     * @param int    $line           The line number in the test file which applies to this constant.
     * @param string $okVersion      A PHP version in which the constant was still valid.
     * @param string $removedVersion Optional PHP version to test removed message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedConstant($constantName, $removedIn, $line, $okVersion, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        $this->assertNoViolation($file, $line);

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is removed since PHP {$removedIn}";
        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testRemovedConstant()
     *
     * @return array
     */
    public static function dataRemovedConstant()
    {
        return [
            ['M_PENDING', '5.1', 248, '5.0'],
            ['M_DONE', '5.1', 249, '5.0'],
            ['M_ERROR', '5.1', 250, '5.0'],
            ['M_FAIL', '5.1', 251, '5.0'],
            ['M_SUCCESS', '5.1', 252, '5.0'],
            ['F_DUPFD', '5.1', 254, '5.0'],
            ['F_GETFD', '5.1', 255, '5.0'],
            ['F_GETFL', '5.1', 256, '5.0'],
            ['F_GETLK', '5.1', 257, '5.0'],
            ['F_GETOWN', '5.1', 258, '5.0'],
            ['F_RDLCK', '5.1', 259, '5.0'],
            ['F_SETFL', '5.1', 260, '5.0'],
            ['F_SETLK', '5.1', 261, '5.0'],
            ['F_SETLKW', '5.1', 262, '5.0'],
            ['F_SETOWN', '5.1', 263, '5.0'],
            ['F_UNLCK', '5.1', 264, '5.0'],
            ['F_WRLCK', '5.1', 265, '5.0'],
            ['O_APPEND', '5.1', 266, '5.0'],
            ['O_ASYNC', '5.1', 267, '5.0'],
            ['O_CREAT', '5.1', 268, '5.0'],
            ['O_EXCL', '5.1', 269, '5.0'],
            ['O_NDELAY', '5.1', 270, '5.0'],
            ['O_NOCTTY', '5.1', 271, '5.0'],
            ['O_NONBLOCK', '5.1', 272, '5.0'],
            ['O_RDONLY', '5.1', 273, '5.0'],
            ['O_RDWR', '5.1', 274, '5.0'],
            ['O_SYNC', '5.1', 275, '5.0'],
            ['O_TRUNC', '5.1', 276, '5.0'],
            ['O_WRONLY', '5.1', 277, '5.0'],
            ['S_IRGRP', '5.1', 278, '5.0'],
            ['S_IROTH', '5.1', 279, '5.0'],
            ['S_IRUSR', '5.1', 280, '5.0'],
            ['S_IRWXG', '5.1', 281, '5.0'],
            ['S_IRWXO', '5.1', 282, '5.0'],
            ['S_IRWXU', '5.1', 283, '5.0'],
            ['S_IWGRP', '5.1', 284, '5.0'],
            ['S_IWOTH', '5.1', 285, '5.0'],
            ['S_IWUSR', '5.1', 286, '5.0'],
            ['S_IXGRP', '5.1', 287, '5.0'],
            ['S_IXOTH', '5.1', 288, '5.0'],
            ['S_IXUSR', '5.1', 289, '5.0'],
            ['FAMChanged', '5.1', 373, '5.0'],
            ['FAMDeleted', '5.1', 374, '5.0'],
            ['FAMStartExecuting', '5.1', 375, '5.0'],
            ['FAMStopExecuting', '5.1', 376, '5.0'],
            ['FAMCreated', '5.1', 377, '5.0'],
            ['FAMMoved', '5.1', 378, '5.0'],
            ['FAMAcknowledge', '5.1', 379, '5.0'],
            ['FAMExists', '5.1', 380, '5.0'],
            ['FAMEndExist', '5.1', 381, '5.0'],
            ['YPERR_ACCESS', '5.1', 383, '5.0'],
            ['YPERR_BADARGS', '5.1', 384, '5.0'],
            ['YPERR_BADDB', '5.1', 385, '5.0'],
            ['YPERR_BUSY', '5.1', 386, '5.0'],
            ['YPERR_DOMAIN', '5.1', 387, '5.0'],
            ['YPERR_KEY', '5.1', 388, '5.0'],
            ['YPERR_MAP', '5.1', 389, '5.0'],
            ['YPERR_NODOM', '5.1', 390, '5.0'],
            ['YPERR_NOMORE', '5.1', 391, '5.0'],
            ['YPERR_PMAP', '5.1', 392, '5.0'],
            ['YPERR_RESRC', '5.1', 393, '5.0'],
            ['YPERR_RPC', '5.1', 394, '5.0'],
            ['YPERR_YPBIND', '5.1', 395, '5.0'],
            ['YPERR_YPERR', '5.1', 396, '5.0'],
            ['YPERR_YPSERV', '5.1', 397, '5.0'],
            ['YPERR_VERS', '5.1', 398, '5.0'],
            ['UDM_FIELD_URLID', '5.1', 400, '5.0'],
            ['UDM_FIELD_URL', '5.1', 401, '5.0'],
            ['UDM_FIELD_CONTENT', '5.1', 402, '5.0'],
            ['UDM_FIELD_TITLE', '5.1', 403, '5.0'],
            ['UDM_FIELD_KEYWORDS', '5.1', 404, '5.0'],
            ['UDM_FIELD_DESC', '5.1', 405, '5.0'],
            ['UDM_FIELD_DESCRIPTION', '5.1', 406, '5.0'],
            ['UDM_FIELD_TEXT', '5.1', 407, '5.0'],
            ['UDM_FIELD_SIZE', '5.1', 408, '5.0'],
            ['UDM_FIELD_RATING', '5.1', 409, '5.0'],
            ['UDM_FIELD_SCORE', '5.1', 410, '5.0'],
            ['UDM_FIELD_MODIFIED', '5.1', 411, '5.0'],
            ['UDM_FIELD_ORDER', '5.1', 412, '5.0'],
            ['UDM_FIELD_CRC', '5.1', 413, '5.0'],
            ['UDM_FIELD_CATEGORY', '5.1', 414, '5.0'],
            ['UDM_FIELD_LANG', '5.1', 415, '5.0'],
            ['UDM_FIELD_CHARSET', '5.1', 416, '5.0'],
            ['UDM_PARAM_PAGE_SIZE', '5.1', 417, '5.0'],
            ['UDM_PARAM_PAGE_NUM', '5.1', 418, '5.0'],
            ['UDM_PARAM_SEARCH_MODE', '5.1', 419, '5.0'],
            ['UDM_PARAM_CACHE_MODE', '5.1', 420, '5.0'],
            ['UDM_PARAM_TRACK_MODE', '5.1', 421, '5.0'],
            ['UDM_PARAM_PHRASE_MODE', '5.1', 422, '5.0'],
            ['UDM_PARAM_CHARSET', '5.1', 423, '5.0'],
            ['UDM_PARAM_LOCAL_CHARSET', '5.1', 424, '5.0'],
            ['UDM_PARAM_BROWSER_CHARSET', '5.1', 425, '5.0'],
            ['UDM_PARAM_STOPTABLE', '5.1', 426, '5.0'],
            ['UDM_PARAM_STOP_TABLE', '5.1', 427, '5.0'],
            ['UDM_PARAM_STOPFILE', '5.1', 428, '5.0'],
            ['UDM_PARAM_STOP_FILE', '5.1', 429, '5.0'],
            ['UDM_PARAM_WEIGHT_FACTOR', '5.1', 430, '5.0'],
            ['UDM_PARAM_WORD_MATCH', '5.1', 431, '5.0'],
            ['UDM_PARAM_MAX_WORD_LEN', '5.1', 432, '5.0'],
            ['UDM_PARAM_MAX_WORDLEN', '5.1', 433, '5.0'],
            ['UDM_PARAM_MIN_WORD_LEN', '5.1', 434, '5.0'],
            ['UDM_PARAM_MIN_WORDLEN', '5.1', 435, '5.0'],
            ['UDM_PARAM_ISPELL_PREFIXES', '5.1', 436, '5.0'],
            ['UDM_PARAM_ISPELL_PREFIX', '5.1', 437, '5.0'],
            ['UDM_PARAM_PREFIXES', '5.1', 438, '5.0'],
            ['UDM_PARAM_PREFIX', '5.1', 439, '5.0'],
            ['UDM_PARAM_CROSS_WORDS', '5.1', 440, '5.0'],
            ['UDM_PARAM_CROSSWORDS', '5.1', 441, '5.0'],
            ['UDM_PARAM_VARDIR', '5.1', 442, '5.0'],
            ['UDM_PARAM_DATADIR', '5.1', 443, '5.0'],
            ['UDM_PARAM_HLBEG', '5.1', 444, '5.0'],
            ['UDM_PARAM_HLEND', '5.1', 445, '5.0'],
            ['UDM_PARAM_SYNONYM', '5.1', 446, '5.0'],
            ['UDM_PARAM_SEARCHD', '5.1', 447, '5.0'],
            ['UDM_PARAM_QSTRING', '5.1', 448, '5.0'],
            ['UDM_PARAM_REMOTE_ADDR', '5.1', 449, '5.0'],
            ['UDM_LIMIT_CAT', '5.1', 450, '5.0'],
            ['UDM_LIMIT_URL', '5.1', 451, '5.0'],
            ['UDM_LIMIT_TAG', '5.1', 452, '5.0'],
            ['UDM_LIMIT_LANG', '5.1', 453, '5.0'],
            ['UDM_LIMIT_DATE', '5.1', 454, '5.0'],
            ['UDM_PARAM_FOUND', '5.1', 455, '5.0'],
            ['UDM_PARAM_NUM_ROWS', '5.1', 456, '5.0'],
            ['UDM_PARAM_WORDINFO', '5.1', 457, '5.0'],
            ['UDM_PARAM_WORD_INFO', '5.1', 458, '5.0'],
            ['UDM_PARAM_SEARCHTIME', '5.1', 459, '5.0'],
            ['UDM_PARAM_SEARCH_TIME', '5.1', 460, '5.0'],
            ['UDM_PARAM_FIRST_DOC', '5.1', 461, '5.0'],
            ['UDM_PARAM_LAST_DOC', '5.1', 462, '5.0'],
            ['UDM_MODE_ALL', '5.1', 463, '5.0'],
            ['UDM_MODE_ANY', '5.1', 464, '5.0'],
            ['UDM_MODE_BOOL', '5.1', 465, '5.0'],
            ['UDM_MODE_PHRASE', '5.1', 466, '5.0'],
            ['UDM_CACHE_ENABLED', '5.1', 467, '5.0'],
            ['UDM_CACHE_DISABLED', '5.1', 468, '5.0'],
            ['UDM_TRACK_ENABLED', '5.1', 469, '5.0'],
            ['UDM_TRACK_DISABLED', '5.1', 470, '5.0'],
            ['UDM_PHRASE_ENABLED', '5.1', 471, '5.0'],
            ['UDM_PHRASE_DISABLED', '5.1', 472, '5.0'],
            ['UDM_CROSS_WORDS_ENABLED', '5.1', 473, '5.0'],
            ['UDM_CROSSWORDS_ENABLED', '5.1', 474, '5.0'],
            ['UDM_CROSS_WORDS_DISABLED', '5.1', 475, '5.0'],
            ['UDM_CROSSWORDS_DISABLED', '5.1', 476, '5.0'],
            ['UDM_PREFIXES_ENABLED', '5.1', 477, '5.0'],
            ['UDM_PREFIX_ENABLED', '5.1', 478, '5.0'],
            ['UDM_ISPELL_PREFIXES_ENABLED', '5.1', 479, '5.0'],
            ['UDM_ISPELL_PREFIX_ENABLED', '5.1', 480, '5.0'],
            ['UDM_PREFIXES_DISABLED', '5.1', 481, '5.0'],
            ['UDM_PREFIX_DISABLED', '5.1', 482, '5.0'],
            ['UDM_ISPELL_PREFIXES_DISABLED', '5.1', 483, '5.0'],
            ['UDM_ISPELL_PREFIX_DISABLED', '5.1', 484, '5.0'],
            ['UDM_ISPELL_TYPE_AFFIX', '5.1', 485, '5.0'],
            ['UDM_ISPELL_TYPE_SPELL', '5.1', 486, '5.0'],
            ['UDM_ISPELL_TYPE_DB', '5.1', 487, '5.0'],
            ['UDM_ISPELL_TYPE_SERVER', '5.1', 488, '5.0'],
            ['UDM_MATCH_WORD', '5.1', 489, '5.0'],
            ['UDM_MATCH_BEGIN', '5.1', 490, '5.0'],
            ['UDM_MATCH_SUBSTR', '5.1', 491, '5.0'],
            ['UDM_MATCH_END', '5.1', 492, '5.0'],
            ['DC_MICROSOFT', '5.1', 493, '5.0'],
            ['DC_BORLAND', '5.1', 494, '5.0'],
            ['DC_CALL_CDECL', '5.1', 495, '5.0'],
            ['DC_CALL_STD', '5.1', 496, '5.0'],
            ['DC_RETVAL_MATH4', '5.1', 497, '5.0'],
            ['DC_RETVAL_MATH8', '5.1', 498, '5.0'],
            ['DC_CALL_STD_BO', '5.1', 499, '5.0'],
            ['DC_CALL_STD_MS', '5.1', 500, '5.0'],
            ['DC_CALL_STD_M8', '5.1', 501, '5.0'],
            ['DC_FLAG_ARGPTR', '5.1', 502, '5.0'],
            ['CPDF_PM_NONE', '5.1', 503, '5.0'],
            ['CPDF_PM_OUTLINES', '5.1', 504, '5.0'],
            ['CPDF_PM_THUMBS', '5.1', 505, '5.0'],
            ['CPDF_PM_FULLSCREEN', '5.1', 506, '5.0'],
            ['CPDF_PL_SINGLE', '5.1', 507, '5.0'],
            ['CPDF_PL_1COLUMN', '5.1', 508, '5.0'],
            ['CPDF_PL_2LCOLUMN', '5.1', 509, '5.0'],
            ['CPDF_PL_2RCOLUMN', '5.1', 510, '5.0'],
            ['DBX_MYSQL', '5.1', 511, '5.0'],
            ['DBX_ODBC', '5.1', 512, '5.0'],
            ['DBX_PGSQL', '5.1', 513, '5.0'],
            ['DBX_MSSQL', '5.1', 514, '5.0'],
            ['DBX_FBSQL', '5.1', 515, '5.0'],
            ['DBX_OCI8', '5.1', 516, '5.0'],
            ['DBX_SYBASECT', '5.1', 517, '5.0'],
            ['DBX_SQLITE', '5.1', 518, '5.0'],
            ['DBX_PERSISTENT', '5.1', 519, '5.0'],
            ['DBX_RESULT_INFO', '5.1', 520, '5.0'],
            ['DBX_RESULT_INDEX', '5.1', 521, '5.0'],
            ['DBX_RESULT_ASSOC', '5.1', 522, '5.0'],
            ['DBX_RESULT_UNBUFFERED', '5.1', 523, '5.0'],
            ['DBX_COLNAMES_UNCHANGED', '5.1', 524, '5.0'],
            ['DBX_COLNAMES_UPPERCASE', '5.1', 525, '5.0'],
            ['DBX_COLNAMES_LOWERCASE', '5.1', 526, '5.0'],
            ['DBX_CMP_NATIVE', '5.1', 527, '5.0'],
            ['DBX_CMP_TEXT', '5.1', 528, '5.0'],
            ['DBX_CMP_NUMBER', '5.1', 529, '5.0'],
            ['DBX_CMP_ASC', '5.1', 530, '5.0'],
            ['DBX_CMP_DESC', '5.1', 531, '5.0'],
            ['INGRES_ASSOC', '5.1', 551, '5.0'],
            ['INGRES_NUM', '5.1', 552, '5.0'],
            ['INGRES_BOTH', '5.1', 553, '5.0'],
            ['ORA_BIND_INOUT', '5.1', 607, '5.0'],
            ['ORA_BIND_IN', '5.1', 608, '5.0'],
            ['ORA_BIND_OUT', '5.1', 609, '5.0'],
            ['ORA_FETCHINTO_ASSOC', '5.1', 610, '5.0'],
            ['ORA_FETCHINTO_NULLS', '5.1', 611, '5.0'],

            ['IFX_SCROLL', '5.2.1', 612, '5.2', '5.3'],
            ['IFX_HOLD', '5.2.1', 613, '5.2', '5.3'],
            ['IFX_LO_RDONLY', '5.2.1', 614, '5.2', '5.3'],
            ['IFX_LO_WRONLY', '5.2.1', 615, '5.2', '5.3'],
            ['IFX_LO_APPEND', '5.2.1', 616, '5.2', '5.3'],
            ['IFX_LO_RDWR', '5.2.1', 617, '5.2', '5.3'],
            ['IFX_LO_BUFFER', '5.2.1', 618, '5.2', '5.3'],
            ['IFX_LO_NOBUFFER', '5.2.1', 619, '5.2', '5.3'],

            ['FILEINFO_COMPRESS', '5.3', 8, '5.2'],
            ['NCURSES_COLOR_BLACK', '5.3', 142, '5.2'],
            ['NCURSES_COLOR_WHITE', '5.3', 143, '5.2'],
            ['NCURSES_COLOR_RED', '5.3', 144, '5.2'],
            ['NCURSES_COLOR_GREEN', '5.3', 145, '5.2'],
            ['NCURSES_COLOR_YELLOW', '5.3', 146, '5.2'],
            ['NCURSES_COLOR_BLUE', '5.3', 147, '5.2'],
            ['NCURSES_COLOR_CYAN', '5.3', 148, '5.2'],
            ['NCURSES_COLOR_MAGENTA', '5.3', 149, '5.2'],
            ['NCURSES_KEY_F0', '5.3', 150, '5.2'],
            ['NCURSES_KEY_DOWN', '5.3', 151, '5.2'],
            ['NCURSES_KEY_UP', '5.3', 152, '5.2'],
            ['NCURSES_KEY_LEFT', '5.3', 153, '5.2'],
            ['NCURSES_KEY_RIGHT', '5.3', 154, '5.2'],
            ['NCURSES_KEY_HOME', '5.3', 155, '5.2'],
            ['NCURSES_KEY_BACKSPACE', '5.3', 156, '5.2'],
            ['NCURSES_KEY_DL', '5.3', 157, '5.2'],
            ['NCURSES_KEY_IL', '5.3', 158, '5.2'],
            ['NCURSES_KEY_DC', '5.3', 159, '5.2'],
            ['NCURSES_KEY_IC', '5.3', 160, '5.2'],
            ['NCURSES_KEY_EIC', '5.3', 161, '5.2'],
            ['NCURSES_KEY_CLEAR', '5.3', 162, '5.2'],
            ['NCURSES_KEY_EOS', '5.3', 163, '5.2'],
            ['NCURSES_KEY_EOL', '5.3', 164, '5.2'],
            ['NCURSES_KEY_SF', '5.3', 165, '5.2'],
            ['NCURSES_KEY_SR', '5.3', 166, '5.2'],
            ['NCURSES_KEY_NPAGE', '5.3', 167, '5.2'],
            ['NCURSES_KEY_PPAGE', '5.3', 168, '5.2'],
            ['NCURSES_KEY_STAB', '5.3', 169, '5.2'],
            ['NCURSES_KEY_CTAB', '5.3', 170, '5.2'],
            ['NCURSES_KEY_CATAB', '5.3', 171, '5.2'],
            ['NCURSES_KEY_SRESET', '5.3', 172, '5.2'],
            ['NCURSES_KEY_RESET', '5.3', 173, '5.2'],
            ['NCURSES_KEY_PRINT', '5.3', 174, '5.2'],
            ['NCURSES_KEY_LL', '5.3', 175, '5.2'],
            ['NCURSES_KEY_A1', '5.3', 176, '5.2'],
            ['NCURSES_KEY_A3', '5.3', 177, '5.2'],
            ['NCURSES_KEY_B2', '5.3', 178, '5.2'],
            ['NCURSES_KEY_C1', '5.3', 179, '5.2'],
            ['NCURSES_KEY_C3', '5.3', 180, '5.2'],
            ['NCURSES_KEY_BTAB', '5.3', 181, '5.2'],
            ['NCURSES_KEY_BEG', '5.3', 182, '5.2'],
            ['NCURSES_KEY_CANCEL', '5.3', 183, '5.2'],
            ['NCURSES_KEY_CLOSE', '5.3', 184, '5.2'],
            ['NCURSES_KEY_COMMAND', '5.3', 185, '5.2'],
            ['NCURSES_KEY_COPY', '5.3', 186, '5.2'],
            ['NCURSES_KEY_CREATE', '5.3', 187, '5.2'],
            ['NCURSES_KEY_END', '5.3', 188, '5.2'],
            ['NCURSES_KEY_EXIT', '5.3', 189, '5.2'],
            ['NCURSES_KEY_FIND', '5.3', 190, '5.2'],
            ['NCURSES_KEY_HELP', '5.3', 191, '5.2'],
            ['NCURSES_KEY_MARK', '5.3', 192, '5.2'],
            ['NCURSES_KEY_MESSAGE', '5.3', 193, '5.2'],
            ['NCURSES_KEY_MOVE', '5.3', 194, '5.2'],
            ['NCURSES_KEY_NEXT', '5.3', 195, '5.2'],
            ['NCURSES_KEY_OPEN', '5.3', 196, '5.2'],
            ['NCURSES_KEY_OPTIONS', '5.3', 197, '5.2'],
            ['NCURSES_KEY_PREVIOUS', '5.3', 198, '5.2'],
            ['NCURSES_KEY_REDO', '5.3', 199, '5.2'],
            ['NCURSES_KEY_REFERENCE', '5.3', 200, '5.2'],
            ['NCURSES_KEY_REFRESH', '5.3', 201, '5.2'],
            ['NCURSES_KEY_REPLACE', '5.3', 202, '5.2'],
            ['NCURSES_KEY_RESTART', '5.3', 203, '5.2'],
            ['NCURSES_KEY_RESUME', '5.3', 204, '5.2'],
            ['NCURSES_KEY_SAVE', '5.3', 205, '5.2'],
            ['NCURSES_KEY_SBEG', '5.3', 206, '5.2'],
            ['NCURSES_KEY_SCANCEL', '5.3', 207, '5.2'],
            ['NCURSES_KEY_SCOMMAND', '5.3', 208, '5.2'],
            ['NCURSES_KEY_SCOPY', '5.3', 209, '5.2'],
            ['NCURSES_KEY_SCREATE', '5.3', 210, '5.2'],
            ['NCURSES_KEY_SDC', '5.3', 211, '5.2'],
            ['NCURSES_KEY_SDL', '5.3', 212, '5.2'],
            ['NCURSES_KEY_SELECT', '5.3', 213, '5.2'],
            ['NCURSES_KEY_SEND', '5.3', 214, '5.2'],
            ['NCURSES_KEY_SEOL', '5.3', 215, '5.2'],
            ['NCURSES_KEY_SEXIT', '5.3', 216, '5.2'],
            ['NCURSES_KEY_SFIND', '5.3', 217, '5.2'],
            ['NCURSES_KEY_SHELP', '5.3', 218, '5.2'],
            ['NCURSES_KEY_SHOME', '5.3', 219, '5.2'],
            ['NCURSES_KEY_SIC', '5.3', 220, '5.2'],
            ['NCURSES_KEY_SLEFT', '5.3', 221, '5.2'],
            ['NCURSES_KEY_SMESSAGE', '5.3', 222, '5.2'],
            ['NCURSES_KEY_SMOVE', '5.3', 223, '5.2'],
            ['NCURSES_KEY_SNEXT', '5.3', 224, '5.2'],
            ['NCURSES_KEY_SOPTIONS', '5.3', 225, '5.2'],
            ['NCURSES_KEY_SPREVIOUS', '5.3', 226, '5.2'],
            ['NCURSES_KEY_SPRINT', '5.3', 227, '5.2'],
            ['NCURSES_KEY_SREDO', '5.3', 228, '5.2'],
            ['NCURSES_KEY_SREPLACE', '5.3', 229, '5.2'],
            ['NCURSES_KEY_SRIGHT', '5.3', 230, '5.2'],
            ['NCURSES_KEY_SRSUME', '5.3', 231, '5.2'],
            ['NCURSES_KEY_SSAVE', '5.3', 232, '5.2'],
            ['NCURSES_KEY_SSUSPEND', '5.3', 233, '5.2'],
            ['NCURSES_KEY_UNDO', '5.3', 234, '5.2'],
            ['NCURSES_KEY_MOUSE', '5.3', 235, '5.2'],
            ['NCURSES_KEY_MAX', '5.3', 236, '5.2'],
            ['NCURSES_BUTTON1_RELEASED', '5.3', 237, '5.2'],
            ['NCURSES_BUTTON1_PRESSED', '5.3', 238, '5.2'],
            ['NCURSES_BUTTON1_CLICKED', '5.3', 239, '5.2'],
            ['NCURSES_BUTTON1_DOUBLE_CLICKED', '5.3', 240, '5.2'],
            ['NCURSES_BUTTON1_TRIPLE_CLICKED', '5.3', 241, '5.2'],
            ['NCURSES_BUTTON_CTRL', '5.3', 242, '5.2'],
            ['NCURSES_BUTTON_SHIFT', '5.3', 243, '5.2'],
            ['NCURSES_BUTTON_ALT', '5.3', 244, '5.2'],
            ['NCURSES_ALL_MOUSE_EVENTS', '5.3', 245, '5.2'],
            ['NCURSES_REPORT_MOUSE_POSITION', '5.3', 246, '5.2'],
            ['FDFValue', '5.3', 291, '5.2'],
            ['FDFStatus', '5.3', 292, '5.2'],
            ['FDFFile', '5.3', 293, '5.2'],
            ['FDFID', '5.3', 294, '5.2'],
            ['FDFFf', '5.3', 295, '5.2'],
            ['FDFSetFf', '5.3', 296, '5.2'],
            ['FDFClearFf', '5.3', 297, '5.2'],
            ['FDFFlags', '5.3', 298, '5.2'],
            ['FDFSetF', '5.3', 299, '5.2'],
            ['FDFClrF', '5.3', 300, '5.2'],
            ['FDFAP', '5.3', 301, '5.2'],
            ['FDFAS', '5.3', 302, '5.2'],
            ['FDFAction', '5.3', 303, '5.2'],
            ['FDFAA', '5.3', 304, '5.2'],
            ['FDFAPRef', '5.3', 305, '5.2'],
            ['FDFIF', '5.3', 306, '5.2'],
            ['FDFEnter', '5.3', 307, '5.2'],
            ['FDFExit', '5.3', 308, '5.2'],
            ['FDFDown', '5.3', 309, '5.2'],
            ['FDFUp', '5.3', 310, '5.2'],
            ['FDFFormat', '5.3', 311, '5.2'],
            ['FDFValidate', '5.3', 312, '5.2'],
            ['FDFKeystroke', '5.3', 313, '5.2'],
            ['FDFCalculate', '5.3', 314, '5.2'],
            ['FDFNormalAP', '5.3', 315, '5.2'],
            ['FDFRolloverAP', '5.3', 316, '5.2'],
            ['FDFDownAP', '5.3', 317, '5.2'],
            ['MING_NEW', '5.3', 319, '5.2'],
            ['MING_ZLIB', '5.3', 320, '5.2'],
            ['SWFBUTTON_HIT', '5.3', 321, '5.2'],
            ['SWFBUTTON_DOWN', '5.3', 322, '5.2'],
            ['SWFBUTTON_OVER', '5.3', 323, '5.2'],
            ['SWFBUTTON_UP', '5.3', 324, '5.2'],
            ['SWFBUTTON_MOUSEUPOUTSIDE', '5.3', 325, '5.2'],
            ['SWFBUTTON_DRAGOVER', '5.3', 326, '5.2'],
            ['SWFBUTTON_DRAGOUT', '5.3', 327, '5.2'],
            ['SWFBUTTON_MOUSEUP', '5.3', 328, '5.2'],
            ['SWFBUTTON_MOUSEDOWN', '5.3', 329, '5.2'],
            ['SWFBUTTON_MOUSEOUT', '5.3', 330, '5.2'],
            ['SWFBUTTON_MOUSEOVER', '5.3', 331, '5.2'],
            ['SWFFILL_RADIAL_GRADIENT', '5.3', 332, '5.2'],
            ['SWFFILL_LINEAR_GRADIENT', '5.3', 333, '5.2'],
            ['SWFFILL_TILED_BITMAP', '5.3', 334, '5.2'],
            ['SWFFILL_CLIPPED_BITMAP', '5.3', 335, '5.2'],
            ['SWFTEXTFIELD_HASLENGTH', '5.3', 336, '5.2'],
            ['SWFTEXTFIELD_NOEDIT', '5.3', 337, '5.2'],
            ['SWFTEXTFIELD_PASSWORD', '5.3', 338, '5.2'],
            ['SWFTEXTFIELD_MULTILINE', '5.3', 339, '5.2'],
            ['SWFTEXTFIELD_WORDWRAP', '5.3', 340, '5.2'],
            ['SWFTEXTFIELD_DRAWBOX', '5.3', 341, '5.2'],
            ['SWFTEXTFIELD_NOSELECT', '5.3', 342, '5.2'],
            ['SWFTEXTFIELD_HTML', '5.3', 343, '5.2'],
            ['SWFTEXTFIELD_ALIGN_LEFT', '5.3', 344, '5.2'],
            ['SWFTEXTFIELD_ALIGN_RIGHT', '5.3', 345, '5.2'],
            ['SWFTEXTFIELD_ALIGN_CENTER', '5.3', 346, '5.2'],
            ['SWFTEXTFIELD_ALIGN_JUSTIFY', '5.3', 347, '5.2'],
            ['SWFACTION_ONLOAD', '5.3', 348, '5.2'],
            ['SWFACTION_ENTERFRAME', '5.3', 349, '5.2'],
            ['SWFACTION_UNLOAD', '5.3', 350, '5.2'],
            ['SWFACTION_MOUSEMOVE', '5.3', 351, '5.2'],
            ['SWFACTION_MOUSEDOWN', '5.3', 352, '5.2'],
            ['SWFACTION_MOUSEUP', '5.3', 353, '5.2'],
            ['SWFACTION_KEYDOWN', '5.3', 354, '5.2'],
            ['SWFACTION_KEYUP', '5.3', 355, '5.2'],
            ['SWFACTION_DATA', '5.3', 356, '5.2'],
            ['SWFTEXTFIELD_USEFONT', '5.3', 357, '5.2'],
            ['SWFTEXTFIELD_AUTOSIZE', '5.3', 358, '5.2'],
            ['SWF_SOUND_NOT_COMPRESSED', '5.3', 359, '5.2'],
            ['SWF_SOUND_ADPCM_COMPRESSED', '5.3', 360, '5.2'],
            ['SWF_SOUND_MP3_COMPRESSED', '5.3', 361, '5.2'],
            ['SWF_SOUND_NOT_COMPRESSED_LE', '5.3', 362, '5.2'],
            ['SWF_SOUND_NELLY_COMPRESSED', '5.3', 363, '5.2'],
            ['SWF_SOUND_5KHZ', '5.3', 364, '5.2'],
            ['SWF_SOUND_11KHZ', '5.3', 365, '5.2'],
            ['SWF_SOUND_22KHZ', '5.3', 366, '5.2'],
            ['SWF_SOUND_44KHZ', '5.3', 367, '5.2'],
            ['SWF_SOUND_8BITS', '5.3', 368, '5.2'],
            ['SWF_SOUND_16BITS', '5.3', 369, '5.2'],
            ['SWF_SOUND_MONO', '5.3', 370, '5.2'],
            ['SWF_SOUND_STEREO', '5.3', 371, '5.2'],
            ['FBSQL_ASSOC', '5.3', 532, '5.2'],
            ['FBSQL_NUM', '5.3', 533, '5.2'],
            ['FBSQL_BOTH', '5.3', 534, '5.2'],
            ['FBSQL_LOCK_DEFERRED', '5.3', 535, '5.2'],
            ['FBSQL_LOCK_OPTIMISTIC', '5.3', 536, '5.2'],
            ['FBSQL_LOCK_PESSIMISTIC', '5.3', 537, '5.2'],
            ['FBSQL_ISO_READ_UNCOMMITTED', '5.3', 538, '5.2'],
            ['FBSQL_ISO_READ_COMMITTED', '5.3', 539, '5.2'],
            ['FBSQL_ISO_REPEATABLE_READ', '5.3', 540, '5.2'],
            ['FBSQL_ISO_SERIALIZABLE', '5.3', 541, '5.2'],
            ['FBSQL_ISO_VERSIONED', '5.3', 542, '5.2'],
            ['FBSQL_UNKNOWN', '5.3', 543, '5.2'],
            ['FBSQL_STOPPED', '5.3', 544, '5.2'],
            ['FBSQL_STARTING', '5.3', 545, '5.2'],
            ['FBSQL_RUNNING', '5.3', 546, '5.2'],
            ['FBSQL_STOPPING', '5.3', 547, '5.2'],
            ['FBSQL_NOEXEC', '5.3', 548, '5.2'],
            ['FBSQL_LOB_DIRECT', '5.3', 549, '5.2'],
            ['FBSQL_LOB_HANDLE', '5.3', 550, '5.2'],
            ['MSQL_ASSOC', '5.3', 554, '5.2'],
            ['MSQL_NUM', '5.3', 555, '5.2'],
            ['MSQL_BOTH', '5.3', 556, '5.2'],

            ['SQLITE_ASSOC', '5.4', 569, '5.3'],
            ['SQLITE_BOTH', '5.4', 570, '5.3'],
            ['SQLITE_NUM', '5.4', 571, '5.3'],
            ['SQLITE_OK', '5.4', 572, '5.3'],
            ['SQLITE_ERROR', '5.4', 573, '5.3'],
            ['SQLITE_INTERNAL', '5.4', 574, '5.3'],
            ['SQLITE_PERM', '5.4', 575, '5.3'],
            ['SQLITE_ABORT', '5.4', 576, '5.3'],
            ['SQLITE_BUSY', '5.4', 577, '5.3'],
            ['SQLITE_LOCKED', '5.4', 578, '5.3'],
            ['SQLITE_NOMEM', '5.4', 579, '5.3'],
            ['SQLITE_READONLY', '5.4', 580, '5.3'],
            ['SQLITE_INTERRUPT', '5.4', 581, '5.3'],
            ['SQLITE_IOERR', '5.4', 582, '5.3'],
            ['SQLITE_NOTADB', '5.4', 583, '5.3'],
            ['SQLITE_CORRUPT', '5.4', 584, '5.3'],
            ['SQLITE_FORMAT', '5.4', 585, '5.3'],
            ['SQLITE_NOTFOUND', '5.4', 586, '5.3'],
            ['SQLITE_FULL', '5.4', 587, '5.3'],
            ['SQLITE_CANTOPEN', '5.4', 588, '5.3'],
            ['SQLITE_PROTOCOL', '5.4', 589, '5.3'],
            ['SQLITE_EMPTY', '5.4', 590, '5.3'],
            ['SQLITE_SCHEMA', '5.4', 591, '5.3'],
            ['SQLITE_TOOBIG', '5.4', 592, '5.3'],
            ['SQLITE_CONSTRAINT', '5.4', 593, '5.3'],
            ['SQLITE_MISMATCH', '5.4', 594, '5.3'],
            ['SQLITE_MISUSE', '5.4', 595, '5.3'],
            ['SQLITE_NOLFS', '5.4', 596, '5.3'],
            ['SQLITE_AUTH', '5.4', 597, '5.3'],
            ['SQLITE_ROW', '5.4', 598, '5.3'],
            ['SQLITE_DONE', '5.4', 599, '5.3'],

            ['CURLOPT_CLOSEPOLICY', '5.6', 9, '5.5'],
            ['CURLCLOSEPOLICY_LEAST_RECENTLY_USED', '5.6', 10, '5.5'],
            ['CURLCLOSEPOLICY_LEAST_TRAFFIC', '5.6', 11, '5.5'],
            ['CURLCLOSEPOLICY_SLOWEST', '5.6', 12, '5.5'],
            ['CURLCLOSEPOLICY_CALLBACK', '5.6', 13, '5.5'],
            ['CURLCLOSEPOLICY_OLDEST', '5.6', 14, '5.5'],

            ['PGSQL_ATTR_DISABLE_NATIVE_PREPARED_STATEMENT', '7.0', 15, '5.6'],
            ['T_CHARACTER', '7.0', 139, '5.6'],
            ['MSSQL_ASSOC', '7.0', 557, '5.6'],
            ['MSSQL_NUM', '7.0', 558, '5.6'],
            ['MSSQL_BOTH', '7.0', 559, '5.6'],
            ['SQLTEXT', '7.0', 560, '5.6'],
            ['SQLVARCHAR', '7.0', 561, '5.6'],
            ['SQLCHAR', '7.0', 562, '5.6'],
            ['SQLINT1', '7.0', 563, '5.6'],
            ['SQLINT2', '7.0', 564, '5.6'],
            ['SQLINT4', '7.0', 565, '5.6'],
            ['SQLBIT', '7.0', 566, '5.6'],
            ['SQLFLT4', '7.0', 567, '5.6'],
            ['SQLFLT8', '7.0', 568, '5.6'],

            ['PHPDBG_FILE', '7.3', 69, '7.2'],
            ['PHPDBG_METHOD', '7.3', 70, '7.2'],
            ['PHPDBG_LINENO', '7.3', 71, '7.2'],
            ['PHPDBG_FUNC', '7.3', 72, '7.2'],

            ['IBASE_DEFAULT', '7.4', 75, '7.3'],
            ['IBASE_READ', '7.4', 76, '7.3'],
            ['IBASE_WRITE', '7.4', 77, '7.3'],
            ['IBASE_CONSISTENCY', '7.4', 78, '7.3'],
            ['IBASE_CONCURRENCY', '7.4', 79, '7.3'],
            ['IBASE_COMMITTED', '7.4', 80, '7.3'],
            ['IBASE_WAIT', '7.4', 81, '7.3'],
            ['IBASE_NOWAIT', '7.4', 82, '7.3'],
            ['IBASE_FETCH_BLOBS', '7.4', 83, '7.3'],
            ['IBASE_FETCH_ARRAYS', '7.4', 84, '7.3'],
            ['IBASE_UNIXTIME', '7.4', 85, '7.3'],
            ['IBASE_BKP_IGNORE_CHECKSUMS', '7.4', 86, '7.3'],
            ['IBASE_BKP_IGNORE_LIMBO', '7.4', 87, '7.3'],
            ['IBASE_BKP_METADATA_ONLY', '7.4', 88, '7.3'],
            ['IBASE_BKP_NO_GARBAGE_COLLECT', '7.4', 89, '7.3'],
            ['IBASE_BKP_OLD_DESCRIPTIONS', '7.4', 90, '7.3'],
            ['IBASE_BKP_NON_TRANSPORTABLE', '7.4', 91, '7.3'],
            ['IBASE_BKP_CONVERT', '7.4', 92, '7.3'],
            ['IBASE_RES_DEACTIVATE_IDX', '7.4', 93, '7.3'],
            ['IBASE_RES_NO_SHADOW', '7.4', 94, '7.3'],
            ['IBASE_RES_NO_VALIDITY', '7.4', 95, '7.3'],
            ['IBASE_RES_ONE_AT_A_TIME', '7.4', 96, '7.3'],
            ['IBASE_RES_REPLACE', '7.4', 97, '7.3'],
            ['IBASE_RES_CREATE', '7.4', 98, '7.3'],
            ['IBASE_RES_USE_ALL_SPACE', '7.4', 99, '7.3'],
            ['IBASE_PRP_PAGE_BUFFERS', '7.4', 100, '7.3'],
            ['IBASE_PRP_SWEEP_INTERVAL', '7.4', 101, '7.3'],
            ['IBASE_PRP_SHUTDOWN_DB', '7.4', 102, '7.3'],
            ['IBASE_PRP_DENY_NEW_TRANSACTIONS', '7.4', 103, '7.3'],
            ['IBASE_PRP_DENY_NEW_ATTACHMENTS', '7.4', 104, '7.3'],
            ['IBASE_PRP_RESERVE_SPACE', '7.4', 105, '7.3'],
            ['IBASE_PRP_RES_USE_FULL', '7.4', 106, '7.3'],
            ['IBASE_PRP_RES', '7.4', 107, '7.3'],
            ['IBASE_PRP_WRITE_MODE', '7.4', 108, '7.3'],
            ['IBASE_PRP_WM_ASYNC', '7.4', 109, '7.3'],
            ['IBASE_PRP_WM_SYNC', '7.4', 110, '7.3'],
            ['IBASE_PRP_ACCESS_MODE', '7.4', 111, '7.3'],
            ['IBASE_PRP_AM_READONLY', '7.4', 112, '7.3'],
            ['IBASE_PRP_AM_READWRITE', '7.4', 113, '7.3'],
            ['IBASE_PRP_SET_SQL_DIALECT', '7.4', 114, '7.3'],
            ['IBASE_PRP_ACTIVATE', '7.4', 115, '7.3'],
            ['IBASE_PRP_DB_ONLINE', '7.4', 116, '7.3'],
            ['IBASE_RPR_CHECK_DB', '7.4', 117, '7.3'],
            ['IBASE_RPR_IGNORE_CHECKSUM', '7.4', 118, '7.3'],
            ['IBASE_RPR_KILL_SHADOWS', '7.4', 119, '7.3'],
            ['IBASE_RPR_MEND_DB', '7.4', 120, '7.3'],
            ['IBASE_RPR_VALIDATE_DB', '7.4', 121, '7.3'],
            ['IBASE_RPR_FULL', '7.4', 122, '7.3'],
            ['IBASE_RPR_SWEEP_DB', '7.4', 123, '7.3'],
            ['IBASE_STS_DATA_PAGES', '7.4', 124, '7.3'],
            ['IBASE_STS_DB_LOG', '7.4', 125, '7.3'],
            ['IBASE_STS_HDR_PAGES', '7.4', 126, '7.3'],
            ['IBASE_STS_IDX_PAGES', '7.4', 127, '7.3'],
            ['IBASE_STS_SYS_RELATIONS', '7.4', 128, '7.3'],
            ['IBASE_SVC_SERVER_VERSION', '7.4', 129, '7.3'],
            ['IBASE_SVC_IMPLEMENTATION', '7.4', 130, '7.3'],
            ['IBASE_SVC_GET_ENV', '7.4', 131, '7.3'],
            ['IBASE_SVC_GET_ENV_LOCK', '7.4', 132, '7.3'],
            ['IBASE_SVC_GET_ENV_MSG', '7.4', 133, '7.3'],
            ['IBASE_SVC_USER_DBPATH', '7.4', 134, '7.3'],
            ['IBASE_SVC_SVR_DB_INFO', '7.4', 135, '7.3'],
            ['IBASE_SVC_GET_USERS', '7.4', 136, '7.3'],

            ['ASSERT_QUIET_EVAL', '8.0', 623, '7.4'],
            ['INPUT_REQUEST', '8.0', 641, '7.4'],
            ['INPUT_SESSION', '8.0', 642, '7.4'],
            ['MB_OVERLOAD_MAIL', '8.0', 620, '7.4'],
            ['MB_OVERLOAD_STRING', '8.0', 621, '7.4'],
            ['MB_OVERLOAD_REGEX', '8.0', 622, '7.4'],

            ['OP_DEBUG', '8.4', 654, '8.3'],
            ['OP_READONLY', '8.4', 655, '8.3'],
            ['OP_ANONYMOUS', '8.4', 656, '8.3'],
            ['OP_SHORTCACHE', '8.4', 657, '8.3'],
            ['OP_SILENT', '8.4', 658, '8.3'],
            ['OP_PROTOTYPE', '8.4', 659, '8.3'],
            ['OP_HALFOPEN', '8.4', 660, '8.3'],
            ['OP_EXPUNGE', '8.4', 661, '8.3'],
            ['OP_SECURE', '8.4', 662, '8.3'],
            ['CL_EXPUNGE', '8.4', 663, '8.3'],
            ['FT_UID', '8.4', 664, '8.3'],
            ['FT_PEEK', '8.4', 665, '8.3'],
            ['FT_NOT', '8.4', 666, '8.3'],
            ['FT_INTERNAL', '8.4', 667, '8.3'],
            ['FT_PREFETCHTEXT', '8.4', 668, '8.3'],
            ['ST_UID', '8.4', 669, '8.3'],
            ['ST_SILENT', '8.4', 670, '8.3'],
            ['ST_SET', '8.4', 671, '8.3'],
            ['CP_UID', '8.4', 672, '8.3'],
            ['CP_MOVE', '8.4', 673, '8.3'],
            ['SE_UID', '8.4', 674, '8.3'],
            ['SE_FREE', '8.4', 675, '8.3'],
            ['SE_NOPREFETCH', '8.4', 676, '8.3'],
            ['SO_FREE', '8.4', 677, '8.3'],
            ['SO_NOSERVER', '8.4', 678, '8.3'],
            ['SA_MESSAGES', '8.4', 679, '8.3'],
            ['SA_RECENT', '8.4', 680, '8.3'],
            ['SA_UNSEEN', '8.4', 681, '8.3'],
            ['SA_UIDNEXT', '8.4', 682, '8.3'],
            ['SA_UIDVALIDITY', '8.4', 683, '8.3'],
            ['SA_ALL', '8.4', 684, '8.3'],
            ['LATT_NOINFERIORS', '8.4', 685, '8.3'],
            ['LATT_NOSELECT', '8.4', 686, '8.3'],
            ['LATT_MARKED', '8.4', 687, '8.3'],
            ['LATT_UNMARKED', '8.4', 688, '8.3'],
            ['LATT_REFERRAL', '8.4', 689, '8.3'],
            ['LATT_HASCHILDREN', '8.4', 690, '8.3'],
            ['LATT_HASNOCHILDREN', '8.4', 691, '8.3'],
            ['SORTDATE', '8.4', 692, '8.3'],
            ['SORTARRIVAL', '8.4', 693, '8.3'],
            ['SORTFROM', '8.4', 694, '8.3'],
            ['SORTSUBJECT', '8.4', 695, '8.3'],
            ['SORTTO', '8.4', 696, '8.3'],
            ['SORTCC', '8.4', 697, '8.3'],
            ['SORTSIZE', '8.4', 698, '8.3'],
            ['TYPETEXT', '8.4', 699, '8.3'],
            ['TYPEMULTIPART', '8.4', 700, '8.3'],
            ['TYPEMESSAGE', '8.4', 701, '8.3'],
            ['TYPEAPPLICATION', '8.4', 702, '8.3'],
            ['TYPEAUDIO', '8.4', 703, '8.3'],
            ['TYPEIMAGE', '8.4', 704, '8.3'],
            ['TYPEVIDEO', '8.4', 705, '8.3'],
            ['TYPEMODEL', '8.4', 706, '8.3'],
            ['TYPEOTHER', '8.4', 707, '8.3'],
            ['ENC7BIT', '8.4', 708, '8.3'],
            ['ENC8BIT', '8.4', 709, '8.3'],
            ['ENCBINARY', '8.4', 710, '8.3'],
            ['ENCBASE64', '8.4', 711, '8.3'],
            ['ENCQUOTEDPRINTABLE', '8.4', 712, '8.3'],
            ['ENCOTHER', '8.4', 713, '8.3'],
            ['IMAP_OPENTIMEOUT', '8.4', 714, '8.3'],
            ['IMAP_READTIMEOUT', '8.4', 715, '8.3'],
            ['IMAP_WRITETIMEOUT', '8.4', 716, '8.3'],
            ['IMAP_CLOSETIMEOUT', '8.4', 717, '8.3'],
            ['IMAP_GC_ELT', '8.4', 718, '8.3'],
            ['IMAP_GC_ENV', '8.4', 719, '8.3'],
            ['IMAP_GC_TEXTS', '8.4', 720, '8.3'],
            ['OCI_ASSOC', '8.4', 722, '8.3'],
            ['OCI_BOTH', '8.4', 723, '8.3'],
            ['OCI_COMMIT_ON_SUCCESS', '8.4', 724, '8.3'],
            ['OCI_CRED_EXT', '8.4', 725, '8.3'],
            ['OCI_DEFAULT', '8.4', 726, '8.3'],
            ['OCI_DESCRIBE_ONLY', '8.4', 727, '8.3'],
            ['OCI_EXACT_FETCH', '8.4', 728, '8.3'],
            ['OCI_FETCHSTATEMENT_BY_COLUMN', '8.4', 729, '8.3'],
            ['OCI_FETCHSTATEMENT_BY_ROW', '8.4', 730, '8.3'],
            ['OCI_LOB_BUFFER_FREE', '8.4', 731, '8.3'],
            ['OCI_NO_AUTO_COMMIT', '8.4', 732, '8.3'],
            ['OCI_NUM', '8.4', 733, '8.3'],
            ['OCI_RETURN_LOBS', '8.4', 734, '8.3'],
            ['OCI_RETURN_NULLS', '8.4', 735, '8.3'],
            ['OCI_SEEK_CUR', '8.4', 736, '8.3'],
            ['OCI_SEEK_END', '8.4', 737, '8.3'],
            ['OCI_SEEK_SET', '8.4', 738, '8.3'],
            ['OCI_SYSDATE', '8.4', 739, '8.3'],
            ['OCI_SYSDBA', '8.4', 740, '8.3'],
            ['OCI_SYSOPER', '8.4', 741, '8.3'],
            ['OCI_TEMP_BLOB', '8.4', 742, '8.3'],
            ['OCI_TEMP_CLOB', '8.4', 743, '8.3'],
            ['OCI_B_BFILE', '8.4', 744, '8.3'],
            ['OCI_B_BIN', '8.4', 745, '8.3'],
            ['OCI_B_BLOB', '8.4', 746, '8.3'],
            ['OCI_B_BOL', '8.4', 747, '8.3'],
            ['OCI_B_CFILEE', '8.4', 748, '8.3'],
            ['OCI_B_CLOB', '8.4', 749, '8.3'],
            ['OCI_B_CURSOR', '8.4', 750, '8.3'],
            ['OCI_B_INT', '8.4', 751, '8.3'],
            ['OCI_B_NTY', '8.4', 752, '8.3'],
            ['OCI_B_NUM', '8.4', 753, '8.3'],
            ['OCI_B_ROWID', '8.4', 754, '8.3'],
            ['SQLT_AFC', '8.4', 755, '8.3'],
            ['SQLT_AVC', '8.4', 756, '8.3'],
            ['SQLT_BDOUBLE', '8.4', 757, '8.3'],
            ['SQLT_BFILEE', '8.4', 758, '8.3'],
            ['SQLT_BFLOAT', '8.4', 759, '8.3'],
            ['SQLT_BIN', '8.4', 760, '8.3'],
            ['SQLT_BLOB', '8.4', 761, '8.3'],
            ['SQLT_BOL', '8.4', 762, '8.3'],
            ['SQLT_CFILEE', '8.4', 763, '8.3'],
            ['SQLT_CHR', '8.4', 764, '8.3'],
            ['SQLT_CLOB', '8.4', 765, '8.3'],
            ['SQLT_FLT', '8.4', 766, '8.3'],
            ['SQLT_INT', '8.4', 767, '8.3'],
            ['SQLT_LBI', '8.4', 768, '8.3'],
            ['SQLT_LNG', '8.4', 769, '8.3'],
            ['SQLT_LVC', '8.4', 770, '8.3'],
            ['SQLT_NTY', '8.4', 771, '8.3'],
            ['SQLT_NUM', '8.4', 772, '8.3'],
            ['SQLT_ODT', '8.4', 773, '8.3'],
            ['SQLT_RDD', '8.4', 774, '8.3'],
            ['SQLT_RSET', '8.4', 775, '8.3'],
            ['SQLT_STR', '8.4', 776, '8.3'],
            ['SQLT_UIN', '8.4', 777, '8.3'],
            ['SQLT_VCS', '8.4', 778, '8.3'],
            ['OCI_DTYPE_FILE', '8.4', 779, '8.3'],
            ['OCI_DTYPE_LOB', '8.4', 780, '8.3'],
            ['OCI_DTYPE_ROWID', '8.4', 781, '8.3'],
            ['OCI_D_FILE', '8.4', 782, '8.3'],
            ['OCI_D_LOB', '8.4', 783, '8.3'],
            ['OCI_D_ROWID', '8.4', 784, '8.3'],
            ['OCI_FO_ABORT', '8.4', 785, '8.3'],
            ['OCI_FO_BEGIN', '8.4', 786, '8.3'],
            ['OCI_FO_END', '8.4', 787, '8.3'],
            ['OCI_FO_ERROR', '8.4', 788, '8.3'],
            ['OCI_FO_NONE', '8.4', 789, '8.3'],
            ['OCI_FO_REAUTH', '8.4', 790, '8.3'],
            ['OCI_FO_RETRY', '8.4', 791, '8.3'],
            ['OCI_FO_SELECT', '8.4', 792, '8.3'],
            ['OCI_FO_SESSION', '8.4', 793, '8.3'],
            ['OCI_FO_TXNAL', '8.4', 794, '8.3'],
            ['PSPELL_FAST', '8.4', 796, '8.3'],
            ['PSPELL_NORMAL', '8.4', 797, '8.3'],
            ['PSPELL_BAD_SPELLERS', '8.4', 798, '8.3'],
            ['PSPELL_RUN_TOGETHER', '8.4', 799, '8.3'],
        ];
    }


    /**
     * testDeprecatedRemovedConstant
     *
     * @dataProvider dataDeprecatedRemovedConstant
     *
     * @param string $constantName      Name of the PHP constant.
     * @param string $deprecatedIn      The PHP version in which the constant was deprecated.
     * @param string $removedIn         The PHP version in which the constant was removed.
     * @param int    $line              The line number in the test file which applies to this constant.
     * @param string $okVersion         A PHP version in which the constant was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $removedVersion    Optional PHP version to test removed message with -
     *                                  if different from the $removedIn version.
     *
     * @return void
     */
    public function testDeprecatedRemovedConstant($constantName, $deprecatedIn, $removedIn, $line, $okVersion, $deprecatedVersion = null, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        $this->assertNoViolation($file, $line);

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn}";
        $this->assertWarning($file, $line, $error);

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}";
        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedRemovedConstant()
     *
     * @return array
     */
    public static function dataDeprecatedRemovedConstant()
    {
        return [
            ['MYSQL_CLIENT_COMPRESS', '5.5', '7.0', 600, '5.4'],
            ['MYSQL_CLIENT_IGNORE_SPACE', '5.5', '7.0', 601, '5.4'],
            ['MYSQL_CLIENT_INTERACTIVE', '5.5', '7.0', 602, '5.4'],
            ['MYSQL_CLIENT_SSL', '5.5', '7.0', 603, '5.4'],
            ['MYSQL_ASSOC', '5.5', '7.0', 604, '5.4'],
            ['MYSQL_BOTH', '5.5', '7.0', 605, '5.4'],
            ['MYSQL_NUM', '5.5', '7.0', 606, '5.4'],

            ['MCRYPT_MODE_ECB', '7.1', '7.2', 17, '7.0'],
            ['MCRYPT_MODE_CBC', '7.1', '7.2', 18, '7.0'],
            ['MCRYPT_MODE_CFB', '7.1', '7.2', 19, '7.0'],
            ['MCRYPT_MODE_OFB', '7.1', '7.2', 20, '7.0'],
            ['MCRYPT_MODE_NOFB', '7.1', '7.2', 21, '7.0'],
            ['MCRYPT_MODE_STREAM', '7.1', '7.2', 22, '7.0'],
            ['MCRYPT_ENCRYPT', '7.1', '7.2', 23, '7.0'],
            ['MCRYPT_DECRYPT', '7.1', '7.2', 24, '7.0'],
            ['MCRYPT_DEV_RANDOM', '7.1', '7.2', 25, '7.0'],
            ['MCRYPT_DEV_URANDOM', '7.1', '7.2', 26, '7.0'],
            ['MCRYPT_RAND', '7.1', '7.2', 27, '7.0'],
            ['MCRYPT_3DES', '7.1', '7.2', 28, '7.0'],
            ['MCRYPT_ARCFOUR_IV', '7.1', '7.2', 29, '7.0'],
            ['MCRYPT_ARCFOUR', '7.1', '7.2', 30, '7.0'],
            ['MCRYPT_BLOWFISH', '7.1', '7.2', 31, '7.0'],
            ['MCRYPT_CAST_128', '7.1', '7.2', 32, '7.0'],
            ['MCRYPT_CAST_256', '7.1', '7.2', 33, '7.0'],
            ['MCRYPT_CRYPT', '7.1', '7.2', 34, '7.0'],
            ['MCRYPT_DES', '7.1', '7.2', 35, '7.0'],
            ['MCRYPT_DES_COMPAT', '7.1', '7.2', 36, '7.0'],
            ['MCRYPT_ENIGMA', '7.1', '7.2', 37, '7.0'],
            ['MCRYPT_GOST', '7.1', '7.2', 38, '7.0'],
            ['MCRYPT_IDEA', '7.1', '7.2', 39, '7.0'],
            ['MCRYPT_LOKI97', '7.1', '7.2', 40, '7.0'],
            ['MCRYPT_MARS', '7.1', '7.2', 41, '7.0'],
            ['MCRYPT_PANAMA', '7.1', '7.2', 42, '7.0'],
            ['MCRYPT_RIJNDAEL_128', '7.1', '7.2', 43, '7.0'],
            ['MCRYPT_RIJNDAEL_192', '7.1', '7.2', 44, '7.0'],
            ['MCRYPT_RIJNDAEL_256', '7.1', '7.2', 45, '7.0'],
            ['MCRYPT_RC2', '7.1', '7.2', 46, '7.0'],
            ['MCRYPT_RC4', '7.1', '7.2', 47, '7.0'],
            ['MCRYPT_RC6', '7.1', '7.2', 48, '7.0'],
            ['MCRYPT_RC6_128', '7.1', '7.2', 49, '7.0'],
            ['MCRYPT_RC6_192', '7.1', '7.2', 50, '7.0'],
            ['MCRYPT_RC6_256', '7.1', '7.2', 51, '7.0'],
            ['MCRYPT_SAFER64', '7.1', '7.2', 52, '7.0'],
            ['MCRYPT_SAFER128', '7.1', '7.2', 53, '7.0'],
            ['MCRYPT_SAFERPLUS', '7.1', '7.2', 54, '7.0'],
            ['MCRYPT_SERPENT', '7.1', '7.2', 55, '7.0'],
            ['MCRYPT_SERPENT_128', '7.1', '7.2', 56, '7.0'],
            ['MCRYPT_SERPENT_192', '7.1', '7.2', 57, '7.0'],
            ['MCRYPT_SERPENT_256', '7.1', '7.2', 58, '7.0'],
            ['MCRYPT_SKIPJACK', '7.1', '7.2', 59, '7.0'],
            ['MCRYPT_TEAN', '7.1', '7.2', 60, '7.0'],
            ['MCRYPT_THREEWAY', '7.1', '7.2', 61, '7.0'],
            ['MCRYPT_TRIPLEDES', '7.1', '7.2', 62, '7.0'],
            ['MCRYPT_TWOFISH', '7.1', '7.2', 63, '7.0'],
            ['MCRYPT_TWOFISH128', '7.1', '7.2', 64, '7.0'],
            ['MCRYPT_TWOFISH192', '7.1', '7.2', 65, '7.0'],
            ['MCRYPT_TWOFISH256', '7.1', '7.2', 66, '7.0'],
            ['MCRYPT_WAKE', '7.1', '7.2', 67, '7.0'],
            ['MCRYPT_XTEA', '7.1', '7.2', 68, '7.0'],

            ['INTL_IDNA_VARIANT_2003', '7.2', '8.0', 16, '7.1'],

            ['FILTER_FLAG_SCHEME_REQUIRED', '7.3', '8.0', 73, '7.2'],
            ['FILTER_FLAG_HOST_REQUIRED', '7.3', '8.0', 74, '7.2'],
        ];
    }


    /**
     * testDeprecatedRemovedConstantWithAlternative
     *
     * @dataProvider dataDeprecatedRemovedConstantWithAlternative
     *
     * @param string $constantName      Name of the PHP constant.
     * @param string $deprecatedIn      The PHP version in which the constant was deprecated.
     * @param string $removedIn         The PHP version in which the constant was removed.
     * @param string $alternative       An alternative constant.
     * @param int    $line              The line number in the test file which applies to this constant.
     * @param string $okVersion         A PHP version in which the constant was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $removedVersion    Optional PHP version to test removed message with -
     *                                  if different from the $removedIn version.
     *
     * @return void
     */
    public function testDeprecatedRemovedConstantWithAlternative($constantName, $deprecatedIn, $removedIn, $alternative, $line, $okVersion, $deprecatedVersion = null, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        $this->assertNoViolation($file, $line);

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead";
        $this->assertWarning($file, $line, $error);

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}";
        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedRemovedConstantWithAlternative()
     *
     * @return array
     */
    public static function dataDeprecatedRemovedConstantWithAlternative()
    {
        return [
            ['FILTER_SANITIZE_MAGIC_QUOTES', '7.4', '8.0', 'FILTER_SANITIZE_ADD_SLASHES', 137, '7.3'],
            ['NIL', '8.1', '8.4', 'integer 0', 631, '8.0'],
        ];
    }


    /**
     * Test constants that shouldn't be flagged by this sniff.
     *
     * These are either userland constants or namespaced constants.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond latest deprecation.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public static function dataNoFalsePositives()
    {
        return [
            [3],
            [4],
            [5],
        ];
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.0'); // Low version below the first deprecation.
        $this->assertNoViolation($file);
    }

    /**
     * The T_BAD_CHARACTER constant is a special case
     *
     * @dataProvider dataTBadCharacter
     *
     * @param string $phpVersion  PHP version (or range) tot test with.
     * @param bool   $shouldError If we expect to get an error or not from the sniff
     *
     * @return void
     */
    public function testTBadCharacter($phpVersion, $shouldError)
    {
        $line    = 140;
        $message = 'The constant "T_BAD_CHARACTER" is not present in PHP versions 7.0 through 7.3';
        $file    = $this->sniffFile(__FILE__, $phpVersion);

        if ($shouldError) {
            $this->assertError($file, $line, $message);
        } else {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider
     *
     * @see testTBadCharacter
     *
     * @return array<array<string|bool>>
     */
    public static function dataTBadCharacter()
    {
        // This could be more elegantly written with a generator, but this project supports PHP v5.4 which is before generators were introduced (in PHP 5.5).
        return [
            ['5.6', false], // Last version before removal
            ['7.0', true],  // Removed
            ['7.1', true],  // Removed
            ['7.2', true],  // Removed
            ['7.3', true],  // Removed
            ['7.4', false], // Added again

            ['-5.6', false], // Before
            ['-7.2', true],  // Inside
            ['-8.2', true],  // After

            ['5.4-', true],  // Before
            ['7.2-', true],  // Inside
            ['7.4-', false], // After

            ['5.0-5.6', false], // Before
            ['7.4-8.3', false], // After
            ['5.0-8.3', true],  // Inside and both sides
            ['5.0-7.2', true],  // Inside and before
            ['7.0-7.3', true],  // Inside only
            ['7.2-8.1', true],  // Inside and after
        ];
    }
}
