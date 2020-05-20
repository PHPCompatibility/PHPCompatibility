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

use PHPCompatibility\Tests\BaseSniffTest;

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
class RemovedConstantsUnitTest extends BaseSniffTest
{

    /**
     * testDeprecatedConstant
     *
     * @dataProvider dataDeprecatedConstant
     *
     * @param string $constantName      Name of the PHP constant.
     * @param string $deprecatedIn      The PHP version in which the constant was deprecated.
     * @param array  $lines             The line numbers in the test file which apply to this constant.
     * @param string $okVersion         A PHP version in which the constant was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     *
     * @return void
     */
    public function testDeprecatedConstant($constantName, $deprecatedIn, $lines, $okVersion, $deprecatedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn}";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedConstant()
     *
     * @return array
     */
    public function dataDeprecatedConstant()
    {
        return array(
            array('INTL_IDNA_VARIANT_2003', '7.2', array(16), '7.1'),
            array('FILTER_FLAG_SCHEME_REQUIRED', '7.3', array(73), '7.2'),
            array('FILTER_FLAG_HOST_REQUIRED', '7.3', array(74), '7.2'),
            array('CURLPIPE_HTTP1', '7.4', array(138), '7.3'),
        );
    }


    /**
     * testDeprecatedConstantWithAlternative
     *
     * @dataProvider dataDeprecatedConstantWithAlternative
     *
     * @param string $constantName      Name of the PHP constant.
     * @param string $deprecatedIn      The PHP version in which the constant was deprecated.
     * @param string $alternative       An alternative constant.
     * @param array  $lines             The line numbers in the test file which apply to this constant.
     * @param string $okVersion         A PHP version in which the constant was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     *
     * @return void
     */
    public function testDeprecatedConstantWithAlternative($constantName, $deprecatedIn, $alternative, $lines, $okVersion, $deprecatedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn}; Use {$alternative} instead";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedConstantWithAlternative()
     *
     * @return array
     */
    public function dataDeprecatedConstantWithAlternative()
    {
        return array(
            array('FILTER_SANITIZE_MAGIC_QUOTES', '7.4', 'FILTER_SANITIZE_ADD_SLASHES', array(137), '7.3'),
        );
    }


    /**
     * testRemovedConstant
     *
     * @dataProvider dataRemovedConstant
     *
     * @param string $constantName   Name of the PHP constant.
     * @param string $removedIn      The PHP version in which the constant was removed.
     * @param array  $lines          The line numbers in the test file which apply to this constant.
     * @param string $okVersion      A PHP version in which the constant was still valid.
     * @param string $removedVersion Optional PHP version to test removed message with -
     *                               if different from the $removedIn version.
     *
     * @return void
     */
    public function testRemovedConstant($constantName, $removedIn, $lines, $okVersion, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is removed since PHP {$removedIn}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testRemovedConstant()
     *
     * @return array
     */
    public function dataRemovedConstant()
    {
        return array(
            array('M_PENDING', '5.1', array(248), '5.0'),
            array('M_DONE', '5.1', array(249), '5.0'),
            array('M_ERROR', '5.1', array(250), '5.0'),
            array('M_FAIL', '5.1', array(251), '5.0'),
            array('M_SUCCESS', '5.1', array(252), '5.0'),
            array('F_DUPFD', '5.1', array(254), '5.0'),
            array('F_GETFD', '5.1', array(255), '5.0'),
            array('F_GETFL', '5.1', array(256), '5.0'),
            array('F_GETLK', '5.1', array(257), '5.0'),
            array('F_GETOWN', '5.1', array(258), '5.0'),
            array('F_RDLCK', '5.1', array(259), '5.0'),
            array('F_SETFL', '5.1', array(260), '5.0'),
            array('F_SETLK', '5.1', array(261), '5.0'),
            array('F_SETLKW', '5.1', array(262), '5.0'),
            array('F_SETOWN', '5.1', array(263), '5.0'),
            array('F_UNLCK', '5.1', array(264), '5.0'),
            array('F_WRLCK', '5.1', array(265), '5.0'),
            array('O_APPEND', '5.1', array(266), '5.0'),
            array('O_ASYNC', '5.1', array(267), '5.0'),
            array('O_CREAT', '5.1', array(268), '5.0'),
            array('O_EXCL', '5.1', array(269), '5.0'),
            array('O_NDELAY', '5.1', array(270), '5.0'),
            array('O_NOCTTY', '5.1', array(271), '5.0'),
            array('O_NONBLOCK', '5.1', array(272), '5.0'),
            array('O_RDONLY', '5.1', array(273), '5.0'),
            array('O_RDWR', '5.1', array(274), '5.0'),
            array('O_SYNC', '5.1', array(275), '5.0'),
            array('O_TRUNC', '5.1', array(276), '5.0'),
            array('O_WRONLY', '5.1', array(277), '5.0'),
            array('S_IRGRP', '5.1', array(278), '5.0'),
            array('S_IROTH', '5.1', array(279), '5.0'),
            array('S_IRUSR', '5.1', array(280), '5.0'),
            array('S_IRWXG', '5.1', array(281), '5.0'),
            array('S_IRWXO', '5.1', array(282), '5.0'),
            array('S_IRWXU', '5.1', array(283), '5.0'),
            array('S_IWGRP', '5.1', array(284), '5.0'),
            array('S_IWOTH', '5.1', array(285), '5.0'),
            array('S_IWUSR', '5.1', array(286), '5.0'),
            array('S_IXGRP', '5.1', array(287), '5.0'),
            array('S_IXOTH', '5.1', array(288), '5.0'),
            array('S_IXUSR', '5.1', array(289), '5.0'),
            array('FAMChanged', '5.1', array(373), '5.0'),
            array('FAMDeleted', '5.1', array(374), '5.0'),
            array('FAMStartExecuting', '5.1', array(375), '5.0'),
            array('FAMStopExecuting', '5.1', array(376), '5.0'),
            array('FAMCreated', '5.1', array(377), '5.0'),
            array('FAMMoved', '5.1', array(378), '5.0'),
            array('FAMAcknowledge', '5.1', array(379), '5.0'),
            array('FAMExists', '5.1', array(380), '5.0'),
            array('FAMEndExist', '5.1', array(381), '5.0'),
            array('YPERR_ACCESS', '5.1', array(383), '5.0'),
            array('YPERR_BADARGS', '5.1', array(384), '5.0'),
            array('YPERR_BADDB', '5.1', array(385), '5.0'),
            array('YPERR_BUSY', '5.1', array(386), '5.0'),
            array('YPERR_DOMAIN', '5.1', array(387), '5.0'),
            array('YPERR_KEY', '5.1', array(388), '5.0'),
            array('YPERR_MAP', '5.1', array(389), '5.0'),
            array('YPERR_NODOM', '5.1', array(390), '5.0'),
            array('YPERR_NOMORE', '5.1', array(391), '5.0'),
            array('YPERR_PMAP', '5.1', array(392), '5.0'),
            array('YPERR_RESRC', '5.1', array(393), '5.0'),
            array('YPERR_RPC', '5.1', array(394), '5.0'),
            array('YPERR_YPBIND', '5.1', array(395), '5.0'),
            array('YPERR_YPERR', '5.1', array(396), '5.0'),
            array('YPERR_YPSERV', '5.1', array(397), '5.0'),
            array('YPERR_VERS', '5.1', array(398), '5.0'),
            array('UDM_FIELD_URLID', '5.1', array(400), '5.0'),
            array('UDM_FIELD_URL', '5.1', array(401), '5.0'),
            array('UDM_FIELD_CONTENT', '5.1', array(402), '5.0'),
            array('UDM_FIELD_TITLE', '5.1', array(403), '5.0'),
            array('UDM_FIELD_KEYWORDS', '5.1', array(404), '5.0'),
            array('UDM_FIELD_DESC', '5.1', array(405), '5.0'),
            array('UDM_FIELD_DESCRIPTION', '5.1', array(406), '5.0'),
            array('UDM_FIELD_TEXT', '5.1', array(407), '5.0'),
            array('UDM_FIELD_SIZE', '5.1', array(408), '5.0'),
            array('UDM_FIELD_RATING', '5.1', array(409), '5.0'),
            array('UDM_FIELD_SCORE', '5.1', array(410), '5.0'),
            array('UDM_FIELD_MODIFIED', '5.1', array(411), '5.0'),
            array('UDM_FIELD_ORDER', '5.1', array(412), '5.0'),
            array('UDM_FIELD_CRC', '5.1', array(413), '5.0'),
            array('UDM_FIELD_CATEGORY', '5.1', array(414), '5.0'),
            array('UDM_FIELD_LANG', '5.1', array(415), '5.0'),
            array('UDM_FIELD_CHARSET', '5.1', array(416), '5.0'),
            array('UDM_PARAM_PAGE_SIZE', '5.1', array(417), '5.0'),
            array('UDM_PARAM_PAGE_NUM', '5.1', array(418), '5.0'),
            array('UDM_PARAM_SEARCH_MODE', '5.1', array(419), '5.0'),
            array('UDM_PARAM_CACHE_MODE', '5.1', array(420), '5.0'),
            array('UDM_PARAM_TRACK_MODE', '5.1', array(421), '5.0'),
            array('UDM_PARAM_PHRASE_MODE', '5.1', array(422), '5.0'),
            array('UDM_PARAM_CHARSET', '5.1', array(423), '5.0'),
            array('UDM_PARAM_LOCAL_CHARSET', '5.1', array(424), '5.0'),
            array('UDM_PARAM_BROWSER_CHARSET', '5.1', array(425), '5.0'),
            array('UDM_PARAM_STOPTABLE', '5.1', array(426), '5.0'),
            array('UDM_PARAM_STOP_TABLE', '5.1', array(427), '5.0'),
            array('UDM_PARAM_STOPFILE', '5.1', array(428), '5.0'),
            array('UDM_PARAM_STOP_FILE', '5.1', array(429), '5.0'),
            array('UDM_PARAM_WEIGHT_FACTOR', '5.1', array(430), '5.0'),
            array('UDM_PARAM_WORD_MATCH', '5.1', array(431), '5.0'),
            array('UDM_PARAM_MAX_WORD_LEN', '5.1', array(432), '5.0'),
            array('UDM_PARAM_MAX_WORDLEN', '5.1', array(433), '5.0'),
            array('UDM_PARAM_MIN_WORD_LEN', '5.1', array(434), '5.0'),
            array('UDM_PARAM_MIN_WORDLEN', '5.1', array(435), '5.0'),
            array('UDM_PARAM_ISPELL_PREFIXES', '5.1', array(436), '5.0'),
            array('UDM_PARAM_ISPELL_PREFIX', '5.1', array(437), '5.0'),
            array('UDM_PARAM_PREFIXES', '5.1', array(438), '5.0'),
            array('UDM_PARAM_PREFIX', '5.1', array(439), '5.0'),
            array('UDM_PARAM_CROSS_WORDS', '5.1', array(440), '5.0'),
            array('UDM_PARAM_CROSSWORDS', '5.1', array(441), '5.0'),
            array('UDM_PARAM_VARDIR', '5.1', array(442), '5.0'),
            array('UDM_PARAM_DATADIR', '5.1', array(443), '5.0'),
            array('UDM_PARAM_HLBEG', '5.1', array(444), '5.0'),
            array('UDM_PARAM_HLEND', '5.1', array(445), '5.0'),
            array('UDM_PARAM_SYNONYM', '5.1', array(446), '5.0'),
            array('UDM_PARAM_SEARCHD', '5.1', array(447), '5.0'),
            array('UDM_PARAM_QSTRING', '5.1', array(448), '5.0'),
            array('UDM_PARAM_REMOTE_ADDR', '5.1', array(449), '5.0'),
            array('UDM_LIMIT_CAT', '5.1', array(450), '5.0'),
            array('UDM_LIMIT_URL', '5.1', array(451), '5.0'),
            array('UDM_LIMIT_TAG', '5.1', array(452), '5.0'),
            array('UDM_LIMIT_LANG', '5.1', array(453), '5.0'),
            array('UDM_LIMIT_DATE', '5.1', array(454), '5.0'),
            array('UDM_PARAM_FOUND', '5.1', array(455), '5.0'),
            array('UDM_PARAM_NUM_ROWS', '5.1', array(456), '5.0'),
            array('UDM_PARAM_WORDINFO', '5.1', array(457), '5.0'),
            array('UDM_PARAM_WORD_INFO', '5.1', array(458), '5.0'),
            array('UDM_PARAM_SEARCHTIME', '5.1', array(459), '5.0'),
            array('UDM_PARAM_SEARCH_TIME', '5.1', array(460), '5.0'),
            array('UDM_PARAM_FIRST_DOC', '5.1', array(461), '5.0'),
            array('UDM_PARAM_LAST_DOC', '5.1', array(462), '5.0'),
            array('UDM_MODE_ALL', '5.1', array(463), '5.0'),
            array('UDM_MODE_ANY', '5.1', array(464), '5.0'),
            array('UDM_MODE_BOOL', '5.1', array(465), '5.0'),
            array('UDM_MODE_PHRASE', '5.1', array(466), '5.0'),
            array('UDM_CACHE_ENABLED', '5.1', array(467), '5.0'),
            array('UDM_CACHE_DISABLED', '5.1', array(468), '5.0'),
            array('UDM_TRACK_ENABLED', '5.1', array(469), '5.0'),
            array('UDM_TRACK_DISABLED', '5.1', array(470), '5.0'),
            array('UDM_PHRASE_ENABLED', '5.1', array(471), '5.0'),
            array('UDM_PHRASE_DISABLED', '5.1', array(472), '5.0'),
            array('UDM_CROSS_WORDS_ENABLED', '5.1', array(473), '5.0'),
            array('UDM_CROSSWORDS_ENABLED', '5.1', array(474), '5.0'),
            array('UDM_CROSS_WORDS_DISABLED', '5.1', array(475), '5.0'),
            array('UDM_CROSSWORDS_DISABLED', '5.1', array(476), '5.0'),
            array('UDM_PREFIXES_ENABLED', '5.1', array(477), '5.0'),
            array('UDM_PREFIX_ENABLED', '5.1', array(478), '5.0'),
            array('UDM_ISPELL_PREFIXES_ENABLED', '5.1', array(479), '5.0'),
            array('UDM_ISPELL_PREFIX_ENABLED', '5.1', array(480), '5.0'),
            array('UDM_PREFIXES_DISABLED', '5.1', array(481), '5.0'),
            array('UDM_PREFIX_DISABLED', '5.1', array(482), '5.0'),
            array('UDM_ISPELL_PREFIXES_DISABLED', '5.1', array(483), '5.0'),
            array('UDM_ISPELL_PREFIX_DISABLED', '5.1', array(484), '5.0'),
            array('UDM_ISPELL_TYPE_AFFIX', '5.1', array(485), '5.0'),
            array('UDM_ISPELL_TYPE_SPELL', '5.1', array(486), '5.0'),
            array('UDM_ISPELL_TYPE_DB', '5.1', array(487), '5.0'),
            array('UDM_ISPELL_TYPE_SERVER', '5.1', array(488), '5.0'),
            array('UDM_MATCH_WORD', '5.1', array(489), '5.0'),
            array('UDM_MATCH_BEGIN', '5.1', array(490), '5.0'),
            array('UDM_MATCH_SUBSTR', '5.1', array(491), '5.0'),
            array('UDM_MATCH_END', '5.1', array(492), '5.0'),
            array('DC_MICROSOFT', '5.1', array(493), '5.0'),
            array('DC_BORLAND', '5.1', array(494), '5.0'),
            array('DC_CALL_CDECL', '5.1', array(495), '5.0'),
            array('DC_CALL_STD', '5.1', array(496), '5.0'),
            array('DC_RETVAL_MATH4', '5.1', array(497), '5.0'),
            array('DC_RETVAL_MATH8', '5.1', array(498), '5.0'),
            array('DC_CALL_STD_BO', '5.1', array(499), '5.0'),
            array('DC_CALL_STD_MS', '5.1', array(500), '5.0'),
            array('DC_CALL_STD_M8', '5.1', array(501), '5.0'),
            array('DC_FLAG_ARGPTR', '5.1', array(502), '5.0'),
            array('CPDF_PM_NONE', '5.1', array(503), '5.0'),
            array('CPDF_PM_OUTLINES', '5.1', array(504), '5.0'),
            array('CPDF_PM_THUMBS', '5.1', array(505), '5.0'),
            array('CPDF_PM_FULLSCREEN', '5.1', array(506), '5.0'),
            array('CPDF_PL_SINGLE', '5.1', array(507), '5.0'),
            array('CPDF_PL_1COLUMN', '5.1', array(508), '5.0'),
            array('CPDF_PL_2LCOLUMN', '5.1', array(509), '5.0'),
            array('CPDF_PL_2RCOLUMN', '5.1', array(510), '5.0'),
            array('DBX_MYSQL', '5.1', array(511), '5.0'),
            array('DBX_ODBC', '5.1', array(512), '5.0'),
            array('DBX_PGSQL', '5.1', array(513), '5.0'),
            array('DBX_MSSQL', '5.1', array(514), '5.0'),
            array('DBX_FBSQL', '5.1', array(515), '5.0'),
            array('DBX_OCI8', '5.1', array(516), '5.0'),
            array('DBX_SYBASECT', '5.1', array(517), '5.0'),
            array('DBX_SQLITE', '5.1', array(518), '5.0'),
            array('DBX_PERSISTENT', '5.1', array(519), '5.0'),
            array('DBX_RESULT_INFO', '5.1', array(520), '5.0'),
            array('DBX_RESULT_INDEX', '5.1', array(521), '5.0'),
            array('DBX_RESULT_ASSOC', '5.1', array(522), '5.0'),
            array('DBX_RESULT_UNBUFFERED', '5.1', array(523), '5.0'),
            array('DBX_COLNAMES_UNCHANGED', '5.1', array(524), '5.0'),
            array('DBX_COLNAMES_UPPERCASE', '5.1', array(525), '5.0'),
            array('DBX_COLNAMES_LOWERCASE', '5.1', array(526), '5.0'),
            array('DBX_CMP_NATIVE', '5.1', array(527), '5.0'),
            array('DBX_CMP_TEXT', '5.1', array(528), '5.0'),
            array('DBX_CMP_NUMBER', '5.1', array(529), '5.0'),
            array('DBX_CMP_ASC', '5.1', array(530), '5.0'),
            array('DBX_CMP_DESC', '5.1', array(531), '5.0'),
            array('INGRES_ASSOC', '5.1', array(551), '5.0'),
            array('INGRES_NUM', '5.1', array(552), '5.0'),
            array('INGRES_BOTH', '5.1', array(553), '5.0'),

            array('FILEINFO_COMPRESS', '5.3', array(8), '5.2'),
            array('NCURSES_COLOR_BLACK', '5.3', array(142), '5.2'),
            array('NCURSES_COLOR_WHITE', '5.3', array(143), '5.2'),
            array('NCURSES_COLOR_RED', '5.3', array(144), '5.2'),
            array('NCURSES_COLOR_GREEN', '5.3', array(145), '5.2'),
            array('NCURSES_COLOR_YELLOW', '5.3', array(146), '5.2'),
            array('NCURSES_COLOR_BLUE', '5.3', array(147), '5.2'),
            array('NCURSES_COLOR_CYAN', '5.3', array(148), '5.2'),
            array('NCURSES_COLOR_MAGENTA', '5.3', array(149), '5.2'),
            array('NCURSES_KEY_F0', '5.3', array(150), '5.2'),
            array('NCURSES_KEY_DOWN', '5.3', array(151), '5.2'),
            array('NCURSES_KEY_UP', '5.3', array(152), '5.2'),
            array('NCURSES_KEY_LEFT', '5.3', array(153), '5.2'),
            array('NCURSES_KEY_RIGHT', '5.3', array(154), '5.2'),
            array('NCURSES_KEY_HOME', '5.3', array(155), '5.2'),
            array('NCURSES_KEY_BACKSPACE', '5.3', array(156), '5.2'),
            array('NCURSES_KEY_DL', '5.3', array(157), '5.2'),
            array('NCURSES_KEY_IL', '5.3', array(158), '5.2'),
            array('NCURSES_KEY_DC', '5.3', array(159), '5.2'),
            array('NCURSES_KEY_IC', '5.3', array(160), '5.2'),
            array('NCURSES_KEY_EIC', '5.3', array(161), '5.2'),
            array('NCURSES_KEY_CLEAR', '5.3', array(162), '5.2'),
            array('NCURSES_KEY_EOS', '5.3', array(163), '5.2'),
            array('NCURSES_KEY_EOL', '5.3', array(164), '5.2'),
            array('NCURSES_KEY_SF', '5.3', array(165), '5.2'),
            array('NCURSES_KEY_SR', '5.3', array(166), '5.2'),
            array('NCURSES_KEY_NPAGE', '5.3', array(167), '5.2'),
            array('NCURSES_KEY_PPAGE', '5.3', array(168), '5.2'),
            array('NCURSES_KEY_STAB', '5.3', array(169), '5.2'),
            array('NCURSES_KEY_CTAB', '5.3', array(170), '5.2'),
            array('NCURSES_KEY_CATAB', '5.3', array(171), '5.2'),
            array('NCURSES_KEY_SRESET', '5.3', array(172), '5.2'),
            array('NCURSES_KEY_RESET', '5.3', array(173), '5.2'),
            array('NCURSES_KEY_PRINT', '5.3', array(174), '5.2'),
            array('NCURSES_KEY_LL', '5.3', array(175), '5.2'),
            array('NCURSES_KEY_A1', '5.3', array(176), '5.2'),
            array('NCURSES_KEY_A3', '5.3', array(177), '5.2'),
            array('NCURSES_KEY_B2', '5.3', array(178), '5.2'),
            array('NCURSES_KEY_C1', '5.3', array(179), '5.2'),
            array('NCURSES_KEY_C3', '5.3', array(180), '5.2'),
            array('NCURSES_KEY_BTAB', '5.3', array(181), '5.2'),
            array('NCURSES_KEY_BEG', '5.3', array(182), '5.2'),
            array('NCURSES_KEY_CANCEL', '5.3', array(183), '5.2'),
            array('NCURSES_KEY_CLOSE', '5.3', array(184), '5.2'),
            array('NCURSES_KEY_COMMAND', '5.3', array(185), '5.2'),
            array('NCURSES_KEY_COPY', '5.3', array(186), '5.2'),
            array('NCURSES_KEY_CREATE', '5.3', array(187), '5.2'),
            array('NCURSES_KEY_END', '5.3', array(188), '5.2'),
            array('NCURSES_KEY_EXIT', '5.3', array(189), '5.2'),
            array('NCURSES_KEY_FIND', '5.3', array(190), '5.2'),
            array('NCURSES_KEY_HELP', '5.3', array(191), '5.2'),
            array('NCURSES_KEY_MARK', '5.3', array(192), '5.2'),
            array('NCURSES_KEY_MESSAGE', '5.3', array(193), '5.2'),
            array('NCURSES_KEY_MOVE', '5.3', array(194), '5.2'),
            array('NCURSES_KEY_NEXT', '5.3', array(195), '5.2'),
            array('NCURSES_KEY_OPEN', '5.3', array(196), '5.2'),
            array('NCURSES_KEY_OPTIONS', '5.3', array(197), '5.2'),
            array('NCURSES_KEY_PREVIOUS', '5.3', array(198), '5.2'),
            array('NCURSES_KEY_REDO', '5.3', array(199), '5.2'),
            array('NCURSES_KEY_REFERENCE', '5.3', array(200), '5.2'),
            array('NCURSES_KEY_REFRESH', '5.3', array(201), '5.2'),
            array('NCURSES_KEY_REPLACE', '5.3', array(202), '5.2'),
            array('NCURSES_KEY_RESTART', '5.3', array(203), '5.2'),
            array('NCURSES_KEY_RESUME', '5.3', array(204), '5.2'),
            array('NCURSES_KEY_SAVE', '5.3', array(205), '5.2'),
            array('NCURSES_KEY_SBEG', '5.3', array(206), '5.2'),
            array('NCURSES_KEY_SCANCEL', '5.3', array(207), '5.2'),
            array('NCURSES_KEY_SCOMMAND', '5.3', array(208), '5.2'),
            array('NCURSES_KEY_SCOPY', '5.3', array(209), '5.2'),
            array('NCURSES_KEY_SCREATE', '5.3', array(210), '5.2'),
            array('NCURSES_KEY_SDC', '5.3', array(211), '5.2'),
            array('NCURSES_KEY_SDL', '5.3', array(212), '5.2'),
            array('NCURSES_KEY_SELECT', '5.3', array(213), '5.2'),
            array('NCURSES_KEY_SEND', '5.3', array(214), '5.2'),
            array('NCURSES_KEY_SEOL', '5.3', array(215), '5.2'),
            array('NCURSES_KEY_SEXIT', '5.3', array(216), '5.2'),
            array('NCURSES_KEY_SFIND', '5.3', array(217), '5.2'),
            array('NCURSES_KEY_SHELP', '5.3', array(218), '5.2'),
            array('NCURSES_KEY_SHOME', '5.3', array(219), '5.2'),
            array('NCURSES_KEY_SIC', '5.3', array(220), '5.2'),
            array('NCURSES_KEY_SLEFT', '5.3', array(221), '5.2'),
            array('NCURSES_KEY_SMESSAGE', '5.3', array(222), '5.2'),
            array('NCURSES_KEY_SMOVE', '5.3', array(223), '5.2'),
            array('NCURSES_KEY_SNEXT', '5.3', array(224), '5.2'),
            array('NCURSES_KEY_SOPTIONS', '5.3', array(225), '5.2'),
            array('NCURSES_KEY_SPREVIOUS', '5.3', array(226), '5.2'),
            array('NCURSES_KEY_SPRINT', '5.3', array(227), '5.2'),
            array('NCURSES_KEY_SREDO', '5.3', array(228), '5.2'),
            array('NCURSES_KEY_SREPLACE', '5.3', array(229), '5.2'),
            array('NCURSES_KEY_SRIGHT', '5.3', array(230), '5.2'),
            array('NCURSES_KEY_SRSUME', '5.3', array(231), '5.2'),
            array('NCURSES_KEY_SSAVE', '5.3', array(232), '5.2'),
            array('NCURSES_KEY_SSUSPEND', '5.3', array(233), '5.2'),
            array('NCURSES_KEY_UNDO', '5.3', array(234), '5.2'),
            array('NCURSES_KEY_MOUSE', '5.3', array(235), '5.2'),
            array('NCURSES_KEY_MAX', '5.3', array(236), '5.2'),
            array('NCURSES_BUTTON1_RELEASED', '5.3', array(237), '5.2'),
            array('NCURSES_BUTTON1_PRESSED', '5.3', array(238), '5.2'),
            array('NCURSES_BUTTON1_CLICKED', '5.3', array(239), '5.2'),
            array('NCURSES_BUTTON1_DOUBLE_CLICKED', '5.3', array(240), '5.2'),
            array('NCURSES_BUTTON1_TRIPLE_CLICKED', '5.3', array(241), '5.2'),
            array('NCURSES_BUTTON_CTRL', '5.3', array(242), '5.2'),
            array('NCURSES_BUTTON_SHIFT', '5.3', array(243), '5.2'),
            array('NCURSES_BUTTON_ALT', '5.3', array(244), '5.2'),
            array('NCURSES_ALL_MOUSE_EVENTS', '5.3', array(245), '5.2'),
            array('NCURSES_REPORT_MOUSE_POSITION', '5.3', array(246), '5.2'),
            array('FDFValue', '5.3', array(291), '5.2'),
            array('FDFStatus', '5.3', array(292), '5.2'),
            array('FDFFile', '5.3', array(293), '5.2'),
            array('FDFID', '5.3', array(294), '5.2'),
            array('FDFFf', '5.3', array(295), '5.2'),
            array('FDFSetFf', '5.3', array(296), '5.2'),
            array('FDFClearFf', '5.3', array(297), '5.2'),
            array('FDFFlags', '5.3', array(298), '5.2'),
            array('FDFSetF', '5.3', array(299), '5.2'),
            array('FDFClrF', '5.3', array(300), '5.2'),
            array('FDFAP', '5.3', array(301), '5.2'),
            array('FDFAS', '5.3', array(302), '5.2'),
            array('FDFAction', '5.3', array(303), '5.2'),
            array('FDFAA', '5.3', array(304), '5.2'),
            array('FDFAPRef', '5.3', array(305), '5.2'),
            array('FDFIF', '5.3', array(306), '5.2'),
            array('FDFEnter', '5.3', array(307), '5.2'),
            array('FDFExit', '5.3', array(308), '5.2'),
            array('FDFDown', '5.3', array(309), '5.2'),
            array('FDFUp', '5.3', array(310), '5.2'),
            array('FDFFormat', '5.3', array(311), '5.2'),
            array('FDFValidate', '5.3', array(312), '5.2'),
            array('FDFKeystroke', '5.3', array(313), '5.2'),
            array('FDFCalculate', '5.3', array(314), '5.2'),
            array('FDFNormalAP', '5.3', array(315), '5.2'),
            array('FDFRolloverAP', '5.3', array(316), '5.2'),
            array('FDFDownAP', '5.3', array(317), '5.2'),
            array('MING_NEW', '5.3', array(319), '5.2'),
            array('MING_ZLIB', '5.3', array(320), '5.2'),
            array('SWFBUTTON_HIT', '5.3', array(321), '5.2'),
            array('SWFBUTTON_DOWN', '5.3', array(322), '5.2'),
            array('SWFBUTTON_OVER', '5.3', array(323), '5.2'),
            array('SWFBUTTON_UP', '5.3', array(324), '5.2'),
            array('SWFBUTTON_MOUSEUPOUTSIDE', '5.3', array(325), '5.2'),
            array('SWFBUTTON_DRAGOVER', '5.3', array(326), '5.2'),
            array('SWFBUTTON_DRAGOUT', '5.3', array(327), '5.2'),
            array('SWFBUTTON_MOUSEUP', '5.3', array(328), '5.2'),
            array('SWFBUTTON_MOUSEDOWN', '5.3', array(329), '5.2'),
            array('SWFBUTTON_MOUSEOUT', '5.3', array(330), '5.2'),
            array('SWFBUTTON_MOUSEOVER', '5.3', array(331), '5.2'),
            array('SWFFILL_RADIAL_GRADIENT', '5.3', array(332), '5.2'),
            array('SWFFILL_LINEAR_GRADIENT', '5.3', array(333), '5.2'),
            array('SWFFILL_TILED_BITMAP', '5.3', array(334), '5.2'),
            array('SWFFILL_CLIPPED_BITMAP', '5.3', array(335), '5.2'),
            array('SWFTEXTFIELD_HASLENGTH', '5.3', array(336), '5.2'),
            array('SWFTEXTFIELD_NOEDIT', '5.3', array(337), '5.2'),
            array('SWFTEXTFIELD_PASSWORD', '5.3', array(338), '5.2'),
            array('SWFTEXTFIELD_MULTILINE', '5.3', array(339), '5.2'),
            array('SWFTEXTFIELD_WORDWRAP', '5.3', array(340), '5.2'),
            array('SWFTEXTFIELD_DRAWBOX', '5.3', array(341), '5.2'),
            array('SWFTEXTFIELD_NOSELECT', '5.3', array(342), '5.2'),
            array('SWFTEXTFIELD_HTML', '5.3', array(343), '5.2'),
            array('SWFTEXTFIELD_ALIGN_LEFT', '5.3', array(344), '5.2'),
            array('SWFTEXTFIELD_ALIGN_RIGHT', '5.3', array(345), '5.2'),
            array('SWFTEXTFIELD_ALIGN_CENTER', '5.3', array(346), '5.2'),
            array('SWFTEXTFIELD_ALIGN_JUSTIFY', '5.3', array(347), '5.2'),
            array('SWFACTION_ONLOAD', '5.3', array(348), '5.2'),
            array('SWFACTION_ENTERFRAME', '5.3', array(349), '5.2'),
            array('SWFACTION_UNLOAD', '5.3', array(350), '5.2'),
            array('SWFACTION_MOUSEMOVE', '5.3', array(351), '5.2'),
            array('SWFACTION_MOUSEDOWN', '5.3', array(352), '5.2'),
            array('SWFACTION_MOUSEUP', '5.3', array(353), '5.2'),
            array('SWFACTION_KEYDOWN', '5.3', array(354), '5.2'),
            array('SWFACTION_KEYUP', '5.3', array(355), '5.2'),
            array('SWFACTION_DATA', '5.3', array(356), '5.2'),
            array('SWFTEXTFIELD_USEFONT', '5.3', array(357), '5.2'),
            array('SWFTEXTFIELD_AUTOSIZE', '5.3', array(358), '5.2'),
            array('SWF_SOUND_NOT_COMPRESSED', '5.3', array(359), '5.2'),
            array('SWF_SOUND_ADPCM_COMPRESSED', '5.3', array(360), '5.2'),
            array('SWF_SOUND_MP3_COMPRESSED', '5.3', array(361), '5.2'),
            array('SWF_SOUND_NOT_COMPRESSED_LE', '5.3', array(362), '5.2'),
            array('SWF_SOUND_NELLY_COMPRESSED', '5.3', array(363), '5.2'),
            array('SWF_SOUND_5KHZ', '5.3', array(364), '5.2'),
            array('SWF_SOUND_11KHZ', '5.3', array(365), '5.2'),
            array('SWF_SOUND_22KHZ', '5.3', array(366), '5.2'),
            array('SWF_SOUND_44KHZ', '5.3', array(367), '5.2'),
            array('SWF_SOUND_8BITS', '5.3', array(368), '5.2'),
            array('SWF_SOUND_16BITS', '5.3', array(369), '5.2'),
            array('SWF_SOUND_MONO', '5.3', array(370), '5.2'),
            array('SWF_SOUND_STEREO', '5.3', array(371), '5.2'),
            array('FBSQL_ASSOC', '5.3', array(532), '5.2'),
            array('FBSQL_NUM', '5.3', array(533), '5.2'),
            array('FBSQL_BOTH', '5.3', array(534), '5.2'),
            array('FBSQL_LOCK_DEFERRED', '5.3', array(535), '5.2'),
            array('FBSQL_LOCK_OPTIMISTIC', '5.3', array(536), '5.2'),
            array('FBSQL_LOCK_PESSIMISTIC', '5.3', array(537), '5.2'),
            array('FBSQL_ISO_READ_UNCOMMITTED', '5.3', array(538), '5.2'),
            array('FBSQL_ISO_READ_COMMITTED', '5.3', array(539), '5.2'),
            array('FBSQL_ISO_REPEATABLE_READ', '5.3', array(540), '5.2'),
            array('FBSQL_ISO_SERIALIZABLE', '5.3', array(541), '5.2'),
            array('FBSQL_ISO_VERSIONED', '5.3', array(542), '5.2'),
            array('FBSQL_UNKNOWN', '5.3', array(543), '5.2'),
            array('FBSQL_STOPPED', '5.3', array(544), '5.2'),
            array('FBSQL_STARTING', '5.3', array(545), '5.2'),
            array('FBSQL_RUNNING', '5.3', array(546), '5.2'),
            array('FBSQL_STOPPING', '5.3', array(547), '5.2'),
            array('FBSQL_NOEXEC', '5.3', array(548), '5.2'),
            array('FBSQL_LOB_DIRECT', '5.3', array(549), '5.2'),
            array('FBSQL_LOB_HANDLE', '5.3', array(550), '5.2'),
            array('MSQL_ASSOC', '5.3', array(554), '5.2'),
            array('MSQL_NUM', '5.3', array(555), '5.2'),
            array('MSQL_BOTH', '5.3', array(556), '5.2'),

            array('SQLITE_ASSOC', '5.4', array(569), '5.3'),
            array('SQLITE_BOTH', '5.4', array(570), '5.3'),
            array('SQLITE_NUM', '5.4', array(571), '5.3'),
            array('SQLITE_OK', '5.4', array(572), '5.3'),
            array('SQLITE_ERROR', '5.4', array(573), '5.3'),
            array('SQLITE_INTERNAL', '5.4', array(574), '5.3'),
            array('SQLITE_PERM', '5.4', array(575), '5.3'),
            array('SQLITE_ABORT', '5.4', array(576), '5.3'),
            array('SQLITE_BUSY', '5.4', array(577), '5.3'),
            array('SQLITE_LOCKED', '5.4', array(578), '5.3'),
            array('SQLITE_NOMEM', '5.4', array(579), '5.3'),
            array('SQLITE_READONLY', '5.4', array(580), '5.3'),
            array('SQLITE_INTERRUPT', '5.4', array(581), '5.3'),
            array('SQLITE_IOERR', '5.4', array(582), '5.3'),
            array('SQLITE_NOTADB', '5.4', array(583), '5.3'),
            array('SQLITE_CORRUPT', '5.4', array(584), '5.3'),
            array('SQLITE_FORMAT', '5.4', array(585), '5.3'),
            array('SQLITE_NOTFOUND', '5.4', array(586), '5.3'),
            array('SQLITE_FULL', '5.4', array(587), '5.3'),
            array('SQLITE_CANTOPEN', '5.4', array(588), '5.3'),
            array('SQLITE_PROTOCOL', '5.4', array(589), '5.3'),
            array('SQLITE_EMPTY', '5.4', array(590), '5.3'),
            array('SQLITE_SCHEMA', '5.4', array(591), '5.3'),
            array('SQLITE_TOOBIG', '5.4', array(592), '5.3'),
            array('SQLITE_CONSTRAINT', '5.4', array(593), '5.3'),
            array('SQLITE_MISMATCH', '5.4', array(594), '5.3'),
            array('SQLITE_MISUSE', '5.4', array(595), '5.3'),
            array('SQLITE_NOLFS', '5.4', array(596), '5.3'),
            array('SQLITE_AUTH', '5.4', array(597), '5.3'),
            array('SQLITE_ROW', '5.4', array(598), '5.3'),
            array('SQLITE_DONE', '5.4', array(599), '5.3'),

            array('CURLOPT_CLOSEPOLICY', '5.6', array(9), '5.5'),
            array('CURLCLOSEPOLICY_LEAST_RECENTLY_USED', '5.6', array(10), '5.5'),
            array('CURLCLOSEPOLICY_LEAST_TRAFFIC', '5.6', array(11), '5.5'),
            array('CURLCLOSEPOLICY_SLOWEST', '5.6', array(12), '5.5'),
            array('CURLCLOSEPOLICY_CALLBACK', '5.6', array(13), '5.5'),
            array('CURLCLOSEPOLICY_OLDEST', '5.6', array(14), '5.5'),

            array('PGSQL_ATTR_DISABLE_NATIVE_PREPARED_STATEMENT', '7.0', array(15), '5.6'),
            array('T_CHARACTER', '7.0', array(139), '5.6'),
            array('T_BAD_CHARACTER', '7.0', array(140), '5.6'),
            array('MSSQL_ASSOC', '7.0', array(557), '5.6'),
            array('MSSQL_NUM', '7.0', array(558), '5.6'),
            array('MSSQL_BOTH', '7.0', array(559), '5.6'),
            array('SQLTEXT', '7.0', array(560), '5.6'),
            array('SQLVARCHAR', '7.0', array(561), '5.6'),
            array('SQLCHAR', '7.0', array(562), '5.6'),
            array('SQLINT1', '7.0', array(563), '5.6'),
            array('SQLINT2', '7.0', array(564), '5.6'),
            array('SQLINT4', '7.0', array(565), '5.6'),
            array('SQLBIT', '7.0', array(566), '5.6'),
            array('SQLFLT4', '7.0', array(567), '5.6'),
            array('SQLFLT8', '7.0', array(568), '5.6'),

            array('PHPDBG_FILE', '7.3', array(69), '7.2'),
            array('PHPDBG_METHOD', '7.3', array(70), '7.2'),
            array('PHPDBG_LINENO', '7.3', array(71), '7.2'),
            array('PHPDBG_FUNC', '7.3', array(72), '7.2'),

            array('IBASE_DEFAULT', '7.4', array(75), '7.3'),
            array('IBASE_READ', '7.4', array(76), '7.3'),
            array('IBASE_WRITE', '7.4', array(77), '7.3'),
            array('IBASE_CONSISTENCY', '7.4', array(78), '7.3'),
            array('IBASE_CONCURRENCY', '7.4', array(79), '7.3'),
            array('IBASE_COMMITTED', '7.4', array(80), '7.3'),
            array('IBASE_WAIT', '7.4', array(81), '7.3'),
            array('IBASE_NOWAIT', '7.4', array(82), '7.3'),
            array('IBASE_FETCH_BLOBS', '7.4', array(83), '7.3'),
            array('IBASE_FETCH_ARRAYS', '7.4', array(84), '7.3'),
            array('IBASE_UNIXTIME', '7.4', array(85), '7.3'),
            array('IBASE_BKP_IGNORE_CHECKSUMS', '7.4', array(86), '7.3'),
            array('IBASE_BKP_IGNORE_LIMBO', '7.4', array(87), '7.3'),
            array('IBASE_BKP_METADATA_ONLY', '7.4', array(88), '7.3'),
            array('IBASE_BKP_NO_GARBAGE_COLLECT', '7.4', array(89), '7.3'),
            array('IBASE_BKP_OLD_DESCRIPTIONS', '7.4', array(90), '7.3'),
            array('IBASE_BKP_NON_TRANSPORTABLE', '7.4', array(91), '7.3'),
            array('IBASE_BKP_CONVERT', '7.4', array(92), '7.3'),
            array('IBASE_RES_DEACTIVATE_IDX', '7.4', array(93), '7.3'),
            array('IBASE_RES_NO_SHADOW', '7.4', array(94), '7.3'),
            array('IBASE_RES_NO_VALIDITY', '7.4', array(95), '7.3'),
            array('IBASE_RES_ONE_AT_A_TIME', '7.4', array(96), '7.3'),
            array('IBASE_RES_REPLACE', '7.4', array(97), '7.3'),
            array('IBASE_RES_CREATE', '7.4', array(98), '7.3'),
            array('IBASE_RES_USE_ALL_SPACE', '7.4', array(99), '7.3'),
            array('IBASE_PRP_PAGE_BUFFERS', '7.4', array(100), '7.3'),
            array('IBASE_PRP_SWEEP_INTERVAL', '7.4', array(101), '7.3'),
            array('IBASE_PRP_SHUTDOWN_DB', '7.4', array(102), '7.3'),
            array('IBASE_PRP_DENY_NEW_TRANSACTIONS', '7.4', array(103), '7.3'),
            array('IBASE_PRP_DENY_NEW_ATTACHMENTS', '7.4', array(104), '7.3'),
            array('IBASE_PRP_RESERVE_SPACE', '7.4', array(105), '7.3'),
            array('IBASE_PRP_RES_USE_FULL', '7.4', array(106), '7.3'),
            array('IBASE_PRP_RES', '7.4', array(107), '7.3'),
            array('IBASE_PRP_WRITE_MODE', '7.4', array(108), '7.3'),
            array('IBASE_PRP_WM_ASYNC', '7.4', array(109), '7.3'),
            array('IBASE_PRP_WM_SYNC', '7.4', array(110), '7.3'),
            array('IBASE_PRP_ACCESS_MODE', '7.4', array(111), '7.3'),
            array('IBASE_PRP_AM_READONLY', '7.4', array(112), '7.3'),
            array('IBASE_PRP_AM_READWRITE', '7.4', array(113), '7.3'),
            array('IBASE_PRP_SET_SQL_DIALECT', '7.4', array(114), '7.3'),
            array('IBASE_PRP_ACTIVATE', '7.4', array(115), '7.3'),
            array('IBASE_PRP_DB_ONLINE', '7.4', array(116), '7.3'),
            array('IBASE_RPR_CHECK_DB', '7.4', array(117), '7.3'),
            array('IBASE_RPR_IGNORE_CHECKSUM', '7.4', array(118), '7.3'),
            array('IBASE_RPR_KILL_SHADOWS', '7.4', array(119), '7.3'),
            array('IBASE_RPR_MEND_DB', '7.4', array(120), '7.3'),
            array('IBASE_RPR_VALIDATE_DB', '7.4', array(121), '7.3'),
            array('IBASE_RPR_FULL', '7.4', array(122), '7.3'),
            array('IBASE_RPR_SWEEP_DB', '7.4', array(123), '7.3'),
            array('IBASE_STS_DATA_PAGES', '7.4', array(124), '7.3'),
            array('IBASE_STS_DB_LOG', '7.4', array(125), '7.3'),
            array('IBASE_STS_HDR_PAGES', '7.4', array(126), '7.3'),
            array('IBASE_STS_IDX_PAGES', '7.4', array(127), '7.3'),
            array('IBASE_STS_SYS_RELATIONS', '7.4', array(128), '7.3'),
            array('IBASE_SVC_SERVER_VERSION', '7.4', array(129), '7.3'),
            array('IBASE_SVC_IMPLEMENTATION', '7.4', array(130), '7.3'),
            array('IBASE_SVC_GET_ENV', '7.4', array(131), '7.3'),
            array('IBASE_SVC_GET_ENV_LOCK', '7.4', array(132), '7.3'),
            array('IBASE_SVC_GET_ENV_MSG', '7.4', array(133), '7.3'),
            array('IBASE_SVC_USER_DBPATH', '7.4', array(134), '7.3'),
            array('IBASE_SVC_SVR_DB_INFO', '7.4', array(135), '7.3'),
            array('IBASE_SVC_GET_USERS', '7.4', array(136), '7.3'),
        );
    }


    /**
     * testDeprecatedRemovedConstant
     *
     * @dataProvider dataDeprecatedRemovedConstant
     *
     * @param string $constantName      Name of the PHP constant.
     * @param string $deprecatedIn      The PHP version in which the constant was deprecated.
     * @param string $removedIn         The PHP version in which the constant was removed.
     * @param array  $lines             The line numbers in the test file which apply to this constant.
     * @param string $okVersion         A PHP version in which the constant was still valid.
     * @param string $deprecatedVersion Optional PHP version to test deprecation message with -
     *                                  if different from the $deprecatedIn version.
     * @param string $removedVersion    Optional PHP version to test removed message with -
     *                                  if different from the $removedIn version.
     *
     * @return void
     */
    public function testDeprecatedRemovedConstant($constantName, $deprecatedIn, $removedIn, $lines, $okVersion, $deprecatedVersion = null, $removedVersion = null)
    {
        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }

        $errorVersion = (isset($deprecatedVersion)) ? $deprecatedVersion : $deprecatedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn}";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $errorVersion = (isset($removedVersion)) ? $removedVersion : $removedIn;
        $file         = $this->sniffFile(__FILE__, $errorVersion);
        $error        = "The constant \"{$constantName}\" is deprecated since PHP {$deprecatedIn} and removed since PHP {$removedIn}";
        foreach ($lines as $line) {
            $this->assertError($file, $line, $error);
        }
    }

    /**
     * Data provider.
     *
     * @see testDeprecatedRemovedConstant()
     *
     * @return array
     */
    public function dataDeprecatedRemovedConstant()
    {
        return array(
            array('MYSQL_CLIENT_COMPRESS', '5.5', '7.0', array(600), '5.4'),
            array('MYSQL_CLIENT_IGNORE_SPACE', '5.5', '7.0', array(601), '5.4'),
            array('MYSQL_CLIENT_INTERACTIVE', '5.5', '7.0', array(602), '5.4'),
            array('MYSQL_CLIENT_SSL', '5.5', '7.0', array(603), '5.4'),
            array('MYSQL_ASSOC', '5.5', '7.0', array(604), '5.4'),
            array('MYSQL_BOTH', '5.5', '7.0', array(605), '5.4'),
            array('MYSQL_NUM', '5.5', '7.0', array(606), '5.4'),

            array('MCRYPT_MODE_ECB', '7.1', '7.2', array(17), '7.0'),
            array('MCRYPT_MODE_CBC', '7.1', '7.2', array(18), '7.0'),
            array('MCRYPT_MODE_CFB', '7.1', '7.2', array(19), '7.0'),
            array('MCRYPT_MODE_OFB', '7.1', '7.2', array(20), '7.0'),
            array('MCRYPT_MODE_NOFB', '7.1', '7.2', array(21), '7.0'),
            array('MCRYPT_MODE_STREAM', '7.1', '7.2', array(22), '7.0'),
            array('MCRYPT_ENCRYPT', '7.1', '7.2', array(23), '7.0'),
            array('MCRYPT_DECRYPT', '7.1', '7.2', array(24), '7.0'),
            array('MCRYPT_DEV_RANDOM', '7.1', '7.2', array(25), '7.0'),
            array('MCRYPT_DEV_URANDOM', '7.1', '7.2', array(26), '7.0'),
            array('MCRYPT_RAND', '7.1', '7.2', array(27), '7.0'),
            array('MCRYPT_3DES', '7.1', '7.2', array(28), '7.0'),
            array('MCRYPT_ARCFOUR_IV', '7.1', '7.2', array(29), '7.0'),
            array('MCRYPT_ARCFOUR', '7.1', '7.2', array(30), '7.0'),
            array('MCRYPT_BLOWFISH', '7.1', '7.2', array(31), '7.0'),
            array('MCRYPT_CAST_128', '7.1', '7.2', array(32), '7.0'),
            array('MCRYPT_CAST_256', '7.1', '7.2', array(33), '7.0'),
            array('MCRYPT_CRYPT', '7.1', '7.2', array(34), '7.0'),
            array('MCRYPT_DES', '7.1', '7.2', array(35), '7.0'),
            array('MCRYPT_DES_COMPAT', '7.1', '7.2', array(36), '7.0'),
            array('MCRYPT_ENIGMA', '7.1', '7.2', array(37), '7.0'),
            array('MCRYPT_GOST', '7.1', '7.2', array(38), '7.0'),
            array('MCRYPT_IDEA', '7.1', '7.2', array(39), '7.0'),
            array('MCRYPT_LOKI97', '7.1', '7.2', array(40), '7.0'),
            array('MCRYPT_MARS', '7.1', '7.2', array(41), '7.0'),
            array('MCRYPT_PANAMA', '7.1', '7.2', array(42), '7.0'),
            array('MCRYPT_RIJNDAEL_128', '7.1', '7.2', array(43), '7.0'),
            array('MCRYPT_RIJNDAEL_192', '7.1', '7.2', array(44), '7.0'),
            array('MCRYPT_RIJNDAEL_256', '7.1', '7.2', array(45), '7.0'),
            array('MCRYPT_RC2', '7.1', '7.2', array(46), '7.0'),
            array('MCRYPT_RC4', '7.1', '7.2', array(47), '7.0'),
            array('MCRYPT_RC6', '7.1', '7.2', array(48), '7.0'),
            array('MCRYPT_RC6_128', '7.1', '7.2', array(49), '7.0'),
            array('MCRYPT_RC6_192', '7.1', '7.2', array(50), '7.0'),
            array('MCRYPT_RC6_256', '7.1', '7.2', array(51), '7.0'),
            array('MCRYPT_SAFER64', '7.1', '7.2', array(52), '7.0'),
            array('MCRYPT_SAFER128', '7.1', '7.2', array(53), '7.0'),
            array('MCRYPT_SAFERPLUS', '7.1', '7.2', array(54), '7.0'),
            array('MCRYPT_SERPENT', '7.1', '7.2', array(55), '7.0'),
            array('MCRYPT_SERPENT_128', '7.1', '7.2', array(56), '7.0'),
            array('MCRYPT_SERPENT_192', '7.1', '7.2', array(57), '7.0'),
            array('MCRYPT_SERPENT_256', '7.1', '7.2', array(58), '7.0'),
            array('MCRYPT_SKIPJACK', '7.1', '7.2', array(59), '7.0'),
            array('MCRYPT_TEAN', '7.1', '7.2', array(60), '7.0'),
            array('MCRYPT_THREEWAY', '7.1', '7.2', array(61), '7.0'),
            array('MCRYPT_TRIPLEDES', '7.1', '7.2', array(62), '7.0'),
            array('MCRYPT_TWOFISH', '7.1', '7.2', array(63), '7.0'),
            array('MCRYPT_TWOFISH128', '7.1', '7.2', array(64), '7.0'),
            array('MCRYPT_TWOFISH192', '7.1', '7.2', array(65), '7.0'),
            array('MCRYPT_TWOFISH256', '7.1', '7.2', array(66), '7.0'),
            array('MCRYPT_WAKE', '7.1', '7.2', array(67), '7.0'),
            array('MCRYPT_XTEA', '7.1', '7.2', array(68), '7.0'),
        );
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
    public function dataNoFalsePositives()
    {
        return array(
            array(3),
            array(4),
            array(5),
        );
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
}
