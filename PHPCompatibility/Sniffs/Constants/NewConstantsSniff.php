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

use PHPCompatibility\Helpers\ComplexVersionNewFeatureTrait;
use PHPCompatibility\Helpers\MiscHelper;
use PHPCompatibility\Helpers\ScannedCode;
use PHPCompatibility\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Detect use of new PHP native global constants.
 *
 * PHP version All
 *
 * @since 8.1.0
 * @since 10.0.0 Now extends the base `Sniff` class and uses the `ComplexVersionNewFeatureTrait`.
 */
class NewConstantsSniff extends Sniff
{
    use ComplexVersionNewFeatureTrait;

    /**
     * A list of new PHP Constants, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the constant appears.
     *
     * Note: PHP constants are case-sensitive!
     *
     * @since 8.1.0
     *
     * @var array<string, array<string, bool|string>>
     */
    protected $newConstants = [
        'E_STRICT' => [
            '4.4' => false,
            '5.0' => true,
        ],
        // Curl:
        'CURLOPT_FTP_USE_EPRT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_NOSIGNAL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_UNRESTRICTED_AUTH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_BUFFERSIZE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_HTTPAUTH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXYPORT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXYTYPE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSLCERTTYPE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_HTTP200ALIASES' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'curl',
        ],
        // Math:
        'M_PI' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_E' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_LOG2E' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_LOG10E' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_LN2' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_LN10' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_PI_2' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_PI_4' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_1_PI' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_2_PI' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_2_SQRTPI' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_SQRT2' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'M_SQRT1_2' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'NAN' => [
            '4.4' => false,
            '5.0' => true,
        ],
        'INF' => [
            '4.4' => false,
            '5.0' => true,
        ],
        // OpenSSL:
        'OPENSSL_ALGO_MD2' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_ALGO_MD4' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_ALGO_MD5' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_ALGO_SHA1' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_ALGO_DSS1' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'openssl',
        ],
        // Tokenizer:
        'T_ABSTRACT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tokenizer',
        ],
        'T_CATCH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tokenizer',
        ],
        // Tidy:
        'TIDY_TAG_UNKNOWN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_A' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_ABBR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_ACRONYM' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_ALIGN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_APPLET' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_AREA' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_B' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_BASE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_BASEFONT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_BDO' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_BGSOUND' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_BIG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_BLINK' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_BLOCKQUOTE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_BODY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_BR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_BUTTON' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_CAPTION' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_CENTER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_CITE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_CODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_COL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_COLGROUP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_COMMENT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_DD' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_DEL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_DFN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_DIR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_DIV' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_DL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_DT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_EM' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_EMBED' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_FIELDSET' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_FONT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_FORM' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_FRAME' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_FRAMESET' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_H1' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_H2' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_H3' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_H4' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_H5' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_H6' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_HEAD' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_HR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_HTML' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_I' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_IFRAME' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_ILAYER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_IMG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_INPUT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_INS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_ISINDEX' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_KBD' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_KEYGEN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_LABEL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_LAYER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_LEGEND' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_LI' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_LINK' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_LISTING' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_MAP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_MARQUEE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_MENU' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_META' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_MULTICOL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_NOBR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_NOEMBED' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_NOFRAMES' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_NOLAYER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_NOSAVE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_NOSCRIPT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_OBJECT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_OL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_OPTGROUP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_OPTION' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_P' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_PARAM' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_PLAINTEXT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_PRE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_Q' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_RB' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_RBC' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_RP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_RT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_RTC' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_RUBY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_S' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SAMP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SCRIPT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SELECT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SERVER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SERVLET' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SMALL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SPACER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SPAN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_STRIKE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_STRONG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_STYLE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SUB' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SUP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TABLE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TBODY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TD' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TEXTAREA' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TFOOT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_THEAD' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TITLE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_U' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_UL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_VAR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_WBR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_XMP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_ROOT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_DOCTYPE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_COMMENT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_PROCINS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_TEXT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_START' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_END' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_STARTEND' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_CDATA' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_SECTION' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_ASP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_JSTE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_PHP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_NODETYPE_XMLDECL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'tidy',
        ],
        // Soap:
        'SOAP_1_1' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_1_2' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_PERSISTENCE_SESSION' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_PERSISTENCE_REQUEST' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_FUNCTIONS_ALL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_ENCODED' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_LITERAL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_RPC' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_DOCUMENT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_ACTOR_NEXT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_ACTOR_NONE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_ACTOR_UNLIMATERECEIVER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_COMPRESSION_ACCEPT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_COMPRESSION_GZIP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_COMPRESSION_DEFLATE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_AUTHENTICATION_BASIC' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_AUTHENTICATION_DIGEST' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'UNKNOWN_TYPE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_STRING' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_BOOLEAN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_DECIMAL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_FLOAT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_DOUBLE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_DURATION' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_DATETIME' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_TIME' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_DATE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_GYEARMONTH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_GYEAR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_GMONTHDAY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_GDAY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_GMONTH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_HEXBINARY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_BASE64BINARY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_ANYURI' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_QNAME' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_NOTATION' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_NORMALIZEDSTRING' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_TOKEN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_LANGUAGE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_NMTOKEN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_NAME' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_NCNAME' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_ID' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_IDREF' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_IDREFS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_ENTITY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_ENTITIES' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_INTEGER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_NONPOSITIVEINTEGER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_NEGATIVEINTEGER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_LONG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_INT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_SHORT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_BYTE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_NONNEGATIVEINTEGER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_UNSIGNEDLONG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_UNSIGNEDINT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_UNSIGNEDSHORT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_UNSIGNEDBYTE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_POSITIVEINTEGER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_NMTOKENS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_ANYTYPE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_ANYXML' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'APACHE_MAP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_ENC_OBJECT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_ENC_ARRAY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_1999_TIMEINSTANT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_NAMESPACE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XSD_1999_NAMESPACE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_SINGLE_ELEMENT_ARRAYS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_WAIT_ONE_WAY_CALLS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'SOAP_USE_XSI_ARRAY_TYPE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'WSDL_CACHE_NONE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'WSDL_CACHE_DISK' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'WSDL_CACHE_MEMORY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'WSDL_CACHE_BOTH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'soap',
        ],
        'XML_ELEMENT_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ATTRIBUTE_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_TEXT_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_CDATA_SECTION_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ENTITY_REF_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ENTITY_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_PI_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_COMMENT_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_DOCUMENT_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_DOCUMENT_TYPE_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_DOCUMENT_FRAG_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_NOTATION_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_HTML_DOCUMENT_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_DTD_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ELEMENT_DECL_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ATTRIBUTE_DECL_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ENTITY_DECL_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_NAMESPACE_DECL_NODE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ATTRIBUTE_CDATA' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ATTRIBUTE_ID' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ATTRIBUTE_IDREF' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ATTRIBUTE_IDREFS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ATTRIBUTE_ENTITY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ATTRIBUTE_NMTOKEN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ATTRIBUTE_NMTOKENS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ATTRIBUTE_ENUMERATION' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XML_ATTRIBUTE_NOTATION' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_PHP_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_INDEX_SIZE_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOMSTRING_SIZE_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_HIERARCHY_REQUEST_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_WRONG_DOCUMENT_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_INVALID_CHARACTER_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_NO_DATA_ALLOWED_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_NO_MODIFICATION_ALLOWED_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_NOT_FOUND_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_NOT_SUPPORTED_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_INUSE_ATTRIBUTE_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_INVALID_STATE_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_SYNTAX_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_INVALID_MODIFICATION_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_NAMESPACE_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_INVALID_ACCESS_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'DOM_VALIDATION_ERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'dom',
        ],
        'XSL_CLONE_AUTO' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'xsl',
        ],
        'XSL_CLONE_NEVER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'xsl',
        ],
        'XSL_CLONE_ALWAYS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'xsl',
        ],
        'XSL_SECPREF_NONE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'xsl',
        ],
        'XSL_SECPREF_READ_FILE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'xsl',
        ],
        'XSL_SECPREF_WRITE_FILE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'xsl',
        ],
        'XSL_SECPREF_CREATE_DIRECTORY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'xsl',
        ],
        'XSL_SECPREF_READ_NETWORK' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'xsl',
        ],
        'XSL_SECPREF_WRITE_NETWORK' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'xsl',
        ],
        'XSL_SECPREF_DEFAULT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'xsl',
        ],
        // SQLite
        'SQLITE_ASSOC' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_BOTH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_NUM' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_OK' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_ERROR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_INTERNAL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_PERM' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_ABORT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_BUSY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_LOCKED' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_NOMEM' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_READONLY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_INTERRUPT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_IOERR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_NOTADB' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_CORRUPT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_FORMAT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_NOTFOUND' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_FULL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_CANTOPEN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_PROTOCOL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_EMPTY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_SCHEMA' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_TOOBIG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_CONSTRAINT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_MISMATCH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_MISUSE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_NOLFS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_AUTH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_ROW' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'SQLITE_DONE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'sqlite',
        ],
        'MYSQLI_READ_DEFAULT_GROUP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_READ_DEFAULT_FILE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_OPT_CONNECT_TIMEOUT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_OPT_LOCAL_INFILE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_INIT_COMMAND' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_CLIENT_SSL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_CLIENT_COMPRESS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_CLIENT_INTERACTIVE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_CLIENT_IGNORE_SPACE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_CLIENT_NO_SCHEMA' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_CLIENT_MULTI_QUERIES' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_STORE_RESULT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_USE_RESULT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_ASSOC' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_NUM' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_BOTH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_NOT_NULL_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_PRI_KEY_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_UNIQUE_KEY_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_MULTIPLE_KEY_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_BLOB_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_UNSIGNED_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_ZEROFILL_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_AUTO_INCREMENT_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TIMESTAMP_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_SET_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_NUM_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_PART_KEY_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_GROUP_FLAG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_DECIMAL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_NEWDECIMAL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_BIT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_TINY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_SHORT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_LONG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_FLOAT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_DOUBLE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_NULL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_TIMESTAMP' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_LONGLONG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_INT24' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_DATE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_TIME' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_DATETIME' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_YEAR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_NEWDATE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_INTERVAL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_ENUM' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_SET' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_TINY_BLOB' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_MEDIUM_BLOB' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_LONG_BLOB' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_BLOB' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_VAR_STRING' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_STRING' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_CHAR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TYPE_GEOMETRY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_NEED_DATA' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_NO_DATA' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_CURSOR_TYPE_FOR_UPDATE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_CURSOR_TYPE_NO_CURSOR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_CURSOR_TYPE_READ_ONLY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_CURSOR_TYPE_SCROLLABLE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_STMT_ATTR_CURSOR_TYPE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_STMT_ATTR_PREFETCH_ROWS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_STMT_ATTR_UPDATE_MAX_LENGTH' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_SET_CHARSET_NAME' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REPORT_INDEX' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REPORT_ERROR' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REPORT_STRICT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REPORT_ALL' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REPORT_OFF' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_DEBUG_TRACE_ENABLED' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_SERVER_QUERY_NO_GOOD_INDEX_USED' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_SERVER_QUERY_NO_INDEX_USED' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REFRESH_GRANT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REFRESH_LOG' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REFRESH_TABLES' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REFRESH_HOSTS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REFRESH_STATUS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REFRESH_THREADS' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REFRESH_SLAVE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_REFRESH_MASTER' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TRANS_COR_AND_CHAIN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TRANS_COR_AND_NO_CHAIN' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TRANS_COR_RELEASE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TRANS_COR_NO_RELEASE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TRANS_START_READ_ONLY' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TRANS_START_READ_WRITE' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_TRANS_START_CONSISTENT_SNAPSHOT' => [
            '4.4'       => false,
            '5.0'       => true,
            'extension' => 'mysqli',
        ],

        'SORT_LOCALE_STRING' => [
            '5.0.1' => false,
            '5.0.2' => true,
        ],
        'PHP_EOL' => [
            '5.0.1' => false,
            '5.0.2' => true,
        ],

        'PHP_INT_MAX' => [
            '5.0.4' => false,
            '5.0.5' => true,
        ],
        'PHP_INT_SIZE' => [
            '5.0.4' => false,
            '5.0.5' => true,
        ],

        '__COMPILER_HALT_OFFSET__' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'GLOB_ERR' => [
            '5.0' => false,
            '5.1' => true,
        ],
        // Curl:
        'CURLFTPAUTH_DEFAULT' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'curl',
        ],
        'CURLFTPAUTH_SSL' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'curl',
        ],
        'CURLFTPAUTH_TLS' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_AUTOREFERER' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_BINARYTRANSFER' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_COOKIESESSION' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_FTPSSLAUTH' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXYAUTH' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_TIMECONDITION' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'curl',
        ],
        // POSIX:
        'POSIX_F_OK' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'posix',
        ],
        'POSIX_R_OK' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'posix',
        ],
        'POSIX_W_OK' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'posix',
        ],
        'POSIX_X_OK' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'posix',
        ],
        'POSIX_S_IFBLK' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'posix',
        ],
        'POSIX_S_IFCHR' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'posix',
        ],
        'POSIX_S_IFIFO' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'posix',
        ],
        'POSIX_S_IFREG' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'posix',
        ],
        'POSIX_S_IFSOCK' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'posix',
        ],
        // Streams:
        'STREAM_IPPROTO_ICMP' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_IPPROTO_IP' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_IPPROTO_RAW' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_IPPROTO_TCP' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_IPPROTO_UDP' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_PF_INET' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_PF_INET6' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_PF_UNIX' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_SOCK_DGRAM' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_SOCK_RAW' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_SOCK_RDM' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_SOCK_SEQPACKET' => [
            '5.0' => false,
            '5.1' => true,
        ],
        'STREAM_SOCK_STREAM' => [
            '5.0' => false,
            '5.1' => true,
        ],
        // Tokenizer:
        'T_HALT_COMPILER' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'tokenizer',
        ],
        // LibXML:
        'LIBXML_COMPACT' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_DOTTED_VERSION' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_DTDATTR' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_DTDLOAD' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_DTDVALID' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_ERR_ERROR' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_ERR_FATAL' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_ERR_NONE' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_ERR_WARNING' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_NOBLANKS' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_NOCDATA' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_NOEMPTYTAG' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_NOENT' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_NOERROR' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_NONET' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_NOWARNING' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_NOXMLDECL' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_NSCLEAN' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_VERSION' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_XINCLUDE' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'libxml',
        ],
        // Mysqli.
        'MYSQLI_DATA_TRUNCATED' => [
            '5.0'       => false,
            '5.1'       => true,
            'extension' => 'mysqli',
        ],

        // Date/Time:
        'DATE_ATOM' => [
            '5.1.0' => false,
            '5.1.1' => true,
        ],
        'DATE_COOKIE' => [
            '5.1.0' => false,
            '5.1.1' => true,
        ],
        'DATE_ISO8601' => [
            '5.1.0' => false,
            '5.1.1' => true,
        ],
        'DATE_RFC822' => [
            '5.1.0' => false,
            '5.1.1' => true,
        ],
        'DATE_RFC850' => [
            '5.1.0' => false,
            '5.1.1' => true,
        ],
        'DATE_RFC1036' => [
            '5.1.0' => false,
            '5.1.1' => true,
        ],
        'DATE_RFC1123' => [
            '5.1.0' => false,
            '5.1.1' => true,
        ],
        'DATE_RFC2822' => [
            '5.1.0' => false,
            '5.1.1' => true,
        ],
        'DATE_RFC3339' => [
            '5.1.0' => false,
            '5.1.1' => true,
        ],
        'DATE_RSS' => [
            '5.1.0' => false,
            '5.1.1' => true,
        ],
        'DATE_W3C' => [
            '5.1.0' => false,
            '5.1.1' => true,
        ],

        // Date/Time:
        'SUNFUNCS_RET_TIMESTAMP' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'SUNFUNCS_RET_STRING' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'SUNFUNCS_RET_DOUBLE' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        // Hash:
        'HASH_HMAC' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'hash',
        ],
        // XSL:
        'LIBXSLT_VERSION' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xsl',
        ],
        'LIBXSLT_DOTTED_VERSION' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xsl',
        ],
        'LIBEXSLT_VERSION' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xsl',
        ],
        'LIBEXSLT_DOTTED_VERSION' => [
            '5.1.1'     => false,
            '5.1.2'     => true,
            'extension' => 'xsl',
        ],
        // URL:
        'PHP_URL_SCHEME' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'PHP_URL_HOST' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'PHP_URL_PORT' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'PHP_URL_USER' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'PHP_URL_PASS' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'PHP_URL_PATH' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'PHP_URL_QUERY' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'PHP_URL_FRAGMENT' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'PHP_QUERY_RFC1738' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],
        'PHP_QUERY_RFC3986' => [
            '5.1.1' => false,
            '5.1.2' => true,
        ],

        // Curl:
        'CURLINFO_HEADER_OUT' => [
            '5.1.2'     => false,
            '5.1.3'     => true,
            'extension' => 'curl',
        ],

        // Core:
        'E_RECOVERABLE_ERROR' => [
            '5.1' => false,
            '5.2' => true,
        ],
        // Math:
        'M_EULER' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'M_LNPI' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'M_SQRT3' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'M_SQRTPI' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'PATHINFO_FILENAME' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'UPLOAD_ERR_EXTENSION' => [
            '5.1' => false,
            '5.2' => true,
        ],
        // Curl:
        'CURLE_FILESIZE_EXCEEDED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'curl',
        ],
        'CURLE_FTP_SSL_FAILED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'curl',
        ],
        'CURLE_LDAP_INVALID_URL' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'curl',
        ],
        'CURLFTPSSL_ALL' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'curl',
        ],
        'CURLFTPSSL_CONTROL' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'curl',
        ],
        'CURLFTPSSL_NONE' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'curl',
        ],
        'CURLFTPSSL_TRY' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_FTP_SSL' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'curl',
        ],
        // Ming:
        'SWFTEXTFIELD_USEFONT' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWFTEXTFIELD_AUTOSIZE' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_NOT_COMPRESSED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_ADPCM_COMPRESSED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_MP3_COMPRESSED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_NOT_COMPRESSED_LE' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_NELLY_COMPRESSED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_5KHZ' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_11KHZ' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_22KHZ' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_44KHZ' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_8BITS' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_16BITS' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_MONO' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        'SWF_SOUND_STEREO' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'ming',
        ],
        // OpenSSL:
        'OPENSSL_KEYTYPE_EC' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_VERSION_NUMBER' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_VERSION_TEXT' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'openssl',
        ],
        // PCRE:
        'PREG_BACKTRACK_LIMIT_ERROR' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'PREG_BAD_UTF8_ERROR' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'PREG_INTERNAL_ERROR' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'PREG_NO_ERROR' => [
            '5.1' => false,
            '5.2' => true,
        ],
        'PREG_RECURSION_LIMIT_ERROR' => [
            '5.1' => false,
            '5.2' => true,
        ],
        // Snmp:
        'SNMP_OID_OUTPUT_FULL' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'snmp',
        ],
        'SNMP_OID_OUTPUT_NUMERIC' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'snmp',
        ],
        // Semaphore:
        'MSG_EAGAIN' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'sem',
        ],
        'MSG_ENOMSG' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'sem',
        ],
        // Filter:
        'INPUT_POST' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'INPUT_GET' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'INPUT_COOKIE' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'INPUT_ENV' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'INPUT_SERVER' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'INPUT_SESSION' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'INPUT_REQUEST' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_NONE' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_REQUIRE_SCALAR' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_REQUIRE_ARRAY' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FORCE_ARRAY' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_NULL_ON_FAILURE' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_VALIDATE_INT' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_VALIDATE_BOOLEAN' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_VALIDATE_FLOAT' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_VALIDATE_REGEXP' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_VALIDATE_URL' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_VALIDATE_EMAIL' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_VALIDATE_IP' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_DEFAULT' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_UNSAFE_RAW' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_SANITIZE_STRING' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_SANITIZE_STRIPPED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_SANITIZE_ENCODED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_SANITIZE_SPECIAL_CHARS' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_SANITIZE_EMAIL' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_SANITIZE_URL' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_SANITIZE_NUMBER_INT' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_SANITIZE_NUMBER_FLOAT' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_SANITIZE_MAGIC_QUOTES' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_CALLBACK' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_ALLOW_OCTAL' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_ALLOW_HEX' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_STRIP_LOW' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_STRIP_HIGH' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_ENCODE_LOW' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_ENCODE_HIGH' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_ENCODE_AMP' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_NO_ENCODE_QUOTES' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_EMPTY_STRING_NULL' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_ALLOW_FRACTION' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_ALLOW_THOUSAND' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_ALLOW_SCIENTIFIC' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_PATH_REQUIRED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_QUERY_REQUIRED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_SCHEME_REQUIRED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_HOST_REQUIRED' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_IPV4' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_IPV6' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_NO_RES_RANGE' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_NO_PRIV_RANGE' => [
            '5.1'       => false,
            '5.2'       => true,
            'extension' => 'filter',
        ],

        // Curl:
        'CURLOPT_TCP_NODELAY' => [
            '5.2.0'     => false,
            '5.2.1'     => true,
            'extension' => 'curl',
        ],

        // Stream:
        'STREAM_SHUT_RD' => [
            '5.2.0' => false,
            '5.2.1' => true,
        ],
        'STREAM_SHUT_WR' => [
            '5.2.0' => false,
            '5.2.1' => true,
        ],
        'STREAM_SHUT_RDWR' => [
            '5.2.0' => false,
            '5.2.1' => true,
        ],

        'GMP_VERSION' => [
            '5.2.1'     => false,
            '5.2.2'     => true,
            'extension' => 'gmp',
        ],

        // Curl:
        'CURLOPT_TIMEOUT_MS' => [
            '5.2.2'     => false,
            '5.2.3'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_CONNECTTIMEOUT_MS' => [
            '5.2.2'     => false,
            '5.2.3'     => true,
            'extension' => 'curl',
        ],

        // Curl:
        'CURLOPT_PRIVATE' => [
            '5.2.3'     => false,
            '5.2.4'     => true,
            'extension' => 'curl',
        ],
        'CURLINFO_PRIVATE' => [
            '5.2.3'     => false,
            '5.2.4'     => true,
            'extension' => 'curl',
        ],
        // GD:
        'GD_VERSION' => [
            '5.2.3'     => false,
            '5.2.4'     => true,
            'extension' => 'gd',
        ],
        'GD_MAJOR_VERSION' => [
            '5.2.3'     => false,
            '5.2.4'     => true,
            'extension' => 'gd',
        ],
        'GD_MINOR_VERSION' => [
            '5.2.3'     => false,
            '5.2.4'     => true,
            'extension' => 'gd',
        ],
        'GD_RELEASE_VERSION' => [
            '5.2.3'     => false,
            '5.2.4'     => true,
            'extension' => 'gd',
        ],
        'GD_EXTRA_VERSION' => [
            '5.2.3'     => false,
            '5.2.4'     => true,
            'extension' => 'gd',
        ],
        // PCRE:
        'PCRE_VERSION' => [
            '5.2.3' => false,
            '5.2.4' => true,
        ],

        'PHP_MAJOR_VERSION' => [
            '5.2.6' => false,
            '5.2.7' => true,
        ],
        'PHP_MINOR_VERSION' => [
            '5.2.6' => false,
            '5.2.7' => true,
        ],
        'PHP_RELEASE_VERSION' => [
            '5.2.6' => false,
            '5.2.7' => true,
        ],
        'PHP_VERSION_ID' => [
            '5.2.6' => false,
            '5.2.7' => true,
        ],
        'PHP_EXTRA_VERSION' => [
            '5.2.6' => false,
            '5.2.7' => true,
        ],
        'PHP_ZTS' => [
            '5.2.6' => false,
            '5.2.7' => true,
        ],
        'PHP_DEBUG' => [
            '5.2.6' => false,
            '5.2.7' => true,
        ],
        'FILE_BINARY' => [
            '5.2.6' => false,
            '5.2.7' => true,
        ],
        'FILE_TEXT' => [
            '5.2.6' => false,
            '5.2.7' => true,
        ],
        // Sockets:
        'TCP_NODELAY' => [
            '5.2.6'     => false,
            '5.2.7'     => true,
            'extension' => 'sockets',
        ],

        // Curl:
        'CURLOPT_PROTOCOLS' => [
            '5.2.9'     => false,
            '5.2.10'    => true,
            'extension' => 'curl',
        ],
        'CURLOPT_REDIR_PROTOCOLS' => [
            '5.2.9'     => false,
            '5.2.10'    => true,
            'extension' => 'curl',
        ],
        'CURLPROXY_SOCKS4' => [
            '5.2.9'     => false,
            '5.2.10'    => true,
            'extension' => 'curl',
        ],

        // Libxml:
        'LIBXML_PARSEHUGE' => [
            '5.2.11'    => false,
            '5.2.12'    => true,
            'extension' => 'libxml',
        ],

        // Core:
        'ENT_IGNORE' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'E_DEPRECATED' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'E_USER_DEPRECATED' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'INI_SCANNER_NORMAL' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'INI_SCANNER_RAW' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_MAXPATHLEN' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_WINDOWS_NT_DOMAIN_CONTROLLER' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_WINDOWS_NT_SERVER' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_WINDOWS_NT_WORKSTATION' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_WINDOWS_VERSION_BUILD' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_WINDOWS_VERSION_MAJOR' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_WINDOWS_VERSION_MINOR' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_WINDOWS_VERSION_PLATFORM' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_WINDOWS_VERSION_PRODUCTTYPE' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_WINDOWS_VERSION_SP_MAJOR' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_WINDOWS_VERSION_SP_MINOR' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_WINDOWS_VERSION_SUITEMASK' => [
            '5.2' => false,
            '5.3' => true,
        ],
        // Curl:
        'CURLINFO_CERTINFO' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROGRESSFUNCTION' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'curl',
        ],
        'CURLE_SSH' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'curl',
        ],
        // Enchant:
        'ENCHANT_MYSPELL' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        'ENCHANT_ISPELL' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'enchant',
        ],
        // GD:
        'IMG_FILTER_PIXELATE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'gd',
        ],
        'IMAGETYPE_ICO' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'gd',
        ],
        // Fileinfo:
        'FILEINFO_NONE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'FILEINFO_SYMLINK' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'FILEINFO_MIME_TYPE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'FILEINFO_MIME_ENCODING' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'FILEINFO_MIME' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'FILEINFO_COMPRESS' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'FILEINFO_DEVICES' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'FILEINFO_CONTINUE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'FILEINFO_PRESERVE_ATIME' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        'FILEINFO_RAW' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'fileinfo',
        ],
        // Intl:
        'INTL_MAX_LOCALE_LEN' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'IDNA_DEFAULT' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ALLOW_UNASSIGNED' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        'IDNA_USE_STD3_RULES' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'intl',
        ],
        // JSON:
        'JSON_ERROR_CTRL_CHAR' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ],
        'JSON_ERROR_DEPTH' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ],
        'JSON_ERROR_NONE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ],
        'JSON_ERROR_STATE_MISMATCH' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ],
        'JSON_ERROR_SYNTAX' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ],
        'JSON_FORCE_OBJECT' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ],
        'JSON_HEX_TAG' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ],
        'JSON_HEX_AMP' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ],
        'JSON_HEX_APOS' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ],
        'JSON_HEX_QUOT' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'json',
        ],
        // LDAP:
        'LDAP_OPT_NETWORK_TIMEOUT' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'ldap',
        ],
        // Libxml:
        'LIBXML_LOADED_VERSION' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'libxml',
        ],
        // Math:
        'PHP_ROUND_HALF_UP' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_ROUND_HALF_DOWN' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_ROUND_HALF_EVEN' => [
            '5.2' => false,
            '5.3' => true,
        ],
        'PHP_ROUND_HALF_ODD' => [
            '5.2' => false,
            '5.3' => true,
        ],
        // Mysqli
        'MYSQLI_OPT_INT_AND_FLOAT_NATIVE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_OPT_NET_CMD_BUFFER_SIZE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_OPT_NET_READ_BUFFER_SIZE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_OPT_SSL_VERIFY_SERVER_CERT' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_ENUM_FLAG' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],
        'MYSQLI_BINARY_FLAG' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'mysqli',
        ],

        // OCI8:
        'OCI_CRED_EXT' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'oci8',
        ],
        // PCRE:
        'PREG_BAD_UTF8_OFFSET_ERROR' => [
            '5.2' => false,
            '5.3' => true,
        ],
        // PCNTL:
        'BUS_ADRALN' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'BUS_ADRERR' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'BUS_OBJERR' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'CLD_CONTIUNED' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'CLD_DUMPED' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'CLD_EXITED' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'CLD_KILLED' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'CLD_STOPPED' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'CLD_TRAPPED' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'FPE_FLTDIV' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'FPE_FLTINV' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'FPE_FLTOVF' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'FPE_FLTRES' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'FPE_FLTSUB' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'FPE_FLTUND' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'FPE_INTDIV' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'FPE_INTOVF' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'ILL_BADSTK' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'ILL_COPROC' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'ILL_ILLADR' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'ILL_ILLOPC' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'ILL_ILLOPN' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'ILL_ILLTRP' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'ILL_PRVOPC' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'ILL_PRVREG' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'POLL_ERR' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'POLL_HUP' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'POLL_IN' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'POLL_MSG' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'POLL_OUT' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'POLL_PRI' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SEGV_ACCERR' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SEGV_MAPERR' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SI_ASYNCIO' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SI_KERNEL' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SI_MSGGQ' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SI_NOINFO' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SI_QUEUE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SI_SIGIO' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SI_TIMER' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SI_TKILL' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SI_USER' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SIG_BLOCK' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SIG_SETMASK' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'SIG_UNBLOCK' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'TRAP_BRKPT' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        'TRAP_TRACE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'pcntl',
        ],
        // Tokenizer:
        'T_DIR' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'tokenizer',
        ],
        'T_GOTO' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'tokenizer',
        ],
        'T_NAMESPACE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'tokenizer',
        ],
        'T_NS_C' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'tokenizer',
        ],
        'T_NS_SEPARATOR' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'tokenizer',
        ],
        'T_USE' => [
            '5.2'       => false,
            '5.3'       => true,
            'extension' => 'tokenizer',
        ],

        // OCI8:
        'OCI_NO_AUTO_COMMIT' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'oci8',
        ],
        // OpenSSL:
        'OPENSSL_TLSEXT_SERVER_NAME' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'openssl',
        ],
        'FILTER_FLAG_STRIP_BACKTICK' => [
            '5.3.1'     => false,
            '5.3.2'     => true,
            'extension' => 'filter',
        ],

        // JSON:
        'JSON_ERROR_UTF8' => [
            '5.3.2'     => false,
            '5.3.3'     => true,
            'extension' => 'json',
        ],
        'JSON_NUMERIC_CHECK' => [
            '5.3.2'     => false,
            '5.3.3'     => true,
            'extension' => 'json',
        ],

        'DEBUG_BACKTRACE_IGNORE_ARGS' => [
            '5.3.5' => false,
            '5.3.6' => true,
        ],

        'CURLINFO_REDIRECT_URL' => [
            '5.3.6'     => false,
            '5.3.7'     => true,
            'extension' => 'curl',
        ],
        'PHP_MANDIR' => [
            '5.3.6' => false,
            '5.3.7' => true,
        ],

        'PHP_BINARY' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'SORT_NATURAL' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'SORT_FLAG_CASE' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'ENT_HTML401' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'ENT_XML1' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'ENT_XHTML' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'ENT_HTML5' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'ENT_SUBSTITUTE' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'ENT_DISALLOWED' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'IPPROTO_IP' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'IPPROTO_IPV6' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'IPV6_MULTICAST_HOPS' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'IPV6_MULTICAST_IF' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'IPV6_MULTICAST_LOOP' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'IP_MULTICAST_IF' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'IP_MULTICAST_LOOP' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'IP_MULTICAST_TTL' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'MCAST_JOIN_GROUP' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'MCAST_LEAVE_GROUP' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'MCAST_BLOCK_SOURCE' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'MCAST_UNBLOCK_SOURCE' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'MCAST_JOIN_SOURCE_GROUP' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'MCAST_LEAVE_SOURCE_GROUP' => [
            '5.3' => false,
            '5.4' => true,
        ],
        // Curl:
        'CURLOPT_MAX_RECV_SPEED_LARGE' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_MAX_SEND_SPEED_LARGE' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'curl',
        ],
        // Directories:
        'SCANDIR_SORT_ASCENDING' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'SCANDIR_SORT_DESCENDING' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'SCANDIR_SORT_NONE' => [
            '5.3' => false,
            '5.4' => true,
        ],
        // LibXML:
        'LIBXML_HTML_NODEFDTD' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_HTML_NOIMPLIED' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'libxml',
        ],
        'LIBXML_PEDANTIC' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'libxml',
        ],
        // OpenSSL:
        'OPENSSL_CIPHER_AES_128_CBC' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_CIPHER_AES_192_CBC' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_CIPHER_AES_256_CBC' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_RAW_DATA' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_ZERO_PADDING' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'openssl',
        ],
        // Output buffering:
        'PHP_OUTPUT_HANDLER_CLEAN' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'PHP_OUTPUT_HANDLER_CLEANABLE' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'PHP_OUTPUT_HANDLER_DISABLED' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'PHP_OUTPUT_HANDLER_FINAL' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'PHP_OUTPUT_HANDLER_FLUSH' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'PHP_OUTPUT_HANDLER_FLUSHABLE' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'PHP_OUTPUT_HANDLER_REMOVABLE' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'PHP_OUTPUT_HANDLER_STARTED' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'PHP_OUTPUT_HANDLER_STDFLAGS' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'PHP_OUTPUT_HANDLER_WRITE' => [
            '5.3' => false,
            '5.4' => true,
        ],
        // Sessions:
        'PHP_SESSION_ACTIVE' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'PHP_SESSION_DISABLED' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'PHP_SESSION_NONE' => [
            '5.3' => false,
            '5.4' => true,
        ],
        // Streams:
        'STREAM_META_ACCESS' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'STREAM_META_GROUP' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'STREAM_META_GROUP_NAME' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'STREAM_META_OWNER' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'STREAM_META_OWNER_NAME' => [
            '5.3' => false,
            '5.4' => true,
        ],
        'STREAM_META_TOUCH' => [
            '5.3' => false,
            '5.4' => true,
        ],
        // Intl:
        'U_IDNA_DOMAIN_NAME_TOO_LONG_ERROR' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_CHECK_BIDI' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_CHECK_CONTEXTJ' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_NONTRANSITIONAL_TO_ASCII' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_NONTRANSITIONAL_TO_UNICODE' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'INTL_IDNA_VARIANT_2003' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'INTL_IDNA_VARIANT_UTS46' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_EMPTY_LABEL' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_LABEL_TOO_LONG' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_DOMAIN_NAME_TOO_LONG' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_LEADING_HYPHEN' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_TRAILING_HYPHEN' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_HYPHEN_3_4' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_LEADING_COMBINING_MARK' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_DISALLOWED' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_PUNYCODE' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_LABEL_HAS_DOT' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_INVALID_ACE_LABEL' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_BIDI' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        'IDNA_ERROR_CONTEXTJ' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'intl',
        ],
        // Json:
        'JSON_PRETTY_PRINT' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'json',
        ],
        'JSON_UNESCAPED_SLASHES' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'json',
        ],
        'JSON_UNESCAPED_UNICODE' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'json',
        ],
        'JSON_BIGINT_AS_STRING' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'json',
        ],
        'JSON_OBJECT_AS_ARRAY' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'json',
        ],
        // Snmp:
        'SNMP_OID_OUTPUT_SUFFIX' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'snmp',
        ],
        'SNMP_OID_OUTPUT_MODULE' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'snmp',
        ],
        'SNMP_OID_OUTPUT_UCD' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'snmp',
        ],
        'SNMP_OID_OUTPUT_NONE' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'snmp',
        ],
        // Tokenizer:
        'T_CALLABLE' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'tokenizer',
        ],
        'T_INSTEADOF' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'tokenizer',
        ],
        'T_TRAIT' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'tokenizer',
        ],
        'T_TRAIT_C' => [
            '5.3'       => false,
            '5.4'       => true,
            'extension' => 'tokenizer',
        ],

        // Curl:
        'CURLINFO_PRIMARY_IP' => [
            '5.4.6'     => false,
            '5.4.7'     => true,
            'extension' => 'curl',
        ],
        'CURLINFO_PRIMARY_PORT' => [
            '5.4.6'     => false,
            '5.4.7'     => true,
            'extension' => 'curl',
        ],
        'CURLINFO_LOCAL_IP' => [
            '5.4.6'     => false,
            '5.4.7'     => true,
            'extension' => 'curl',
        ],
        'CURLINFO_LOCAL_PORT' => [
            '5.4.6'     => false,
            '5.4.7'     => true,
            'extension' => 'curl',
        ],

        // OpenSSL:
        'OPENSSL_ALGO_RMD160' => [
            '5.4.7'     => false,
            '5.4.8'     => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_ALGO_SHA224' => [
            '5.4.7'     => false,
            '5.4.8'     => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_ALGO_SHA256' => [
            '5.4.7'     => false,
            '5.4.8'     => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_ALGO_SHA384' => [
            '5.4.7'     => false,
            '5.4.8'     => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_ALGO_SHA512' => [
            '5.4.7'     => false,
            '5.4.8'     => true,
            'extension' => 'openssl',
        ],

        'SO_REUSEPORT' => [
            '5.4.9'     => false,
            '5.4.10'    => true,
            'extension' => 'sockets',
        ],

        // Filter:
        'FILTER_VALIDATE_MAC' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'filter',
        ],
        // GD
        'IMG_AFFINE_TRANSLATE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_AFFINE_SCALE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_AFFINE_ROTATE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_AFFINE_SHEAR_HORIZONTAL' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_AFFINE_SHEAR_VERTICAL' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_CROP_DEFAULT' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_CROP_TRANSPARENT' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_CROP_BLACK' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_CROP_WHITE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_CROP_SIDES' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_FLIP_BOTH' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_FLIP_HORIZONTAL' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_FLIP_VERTICAL' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_BELL' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_BESSEL' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_BILINEAR_FIXED' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_BICUBIC' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_BICUBIC_FIXED' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_BLACKMAN' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_BOX' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_BSPLINE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_CATMULLROM' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_GAUSSIAN' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_GENERALIZED_CUBIC' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_HERMITE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_HAMMING' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_HANNING' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_MITCHELL' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_POWER' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_QUADRATIC' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_SINC' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_NEAREST_NEIGHBOUR' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_WEIGHTED4' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        'IMG_TRIANGLE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'gd',
        ],
        // JSON:
        'JSON_ERROR_RECURSION' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'json',
        ],
        'JSON_ERROR_INF_OR_NAN' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'json',
        ],
        'JSON_ERROR_UNSUPPORTED_TYPE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'json',
        ],
        'JSON_PARTIAL_OUTPUT_ON_ERROR' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'json',
        ],
        // MySQLi
        'MYSQLI_SERVER_PUBLIC_KEY' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'mysqli',
        ],
        // Curl:
        'CURLOPT_SHARE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSL_OPTIONS' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_COOKIELIST' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_TCP_KEEPALIVE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_TCP_KEEPIDLE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_TCP_KEEPINTVL' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLSSLOPT_ALLOW_BEAST' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_USERNAME' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_RESPONSE_CODE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_HTTP_CONNECTCODE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_HTTPAUTH_AVAIL' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_PROXYAUTH_AVAIL' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_OS_ERRNO' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_NUM_CONNECTS' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_SSL_ENGINES' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_COOKIELIST' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_FTP_ENTRY_PATH' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_APPCONNECT_TIME' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_CONDITION_UNMET' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_RTSP_CLIENT_CSEQ' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_RTSP_CSEQ_RECV' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_RTSP_SERVER_CSEQ' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_RTSP_SESSION_ID' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_HTTP_CODE' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLMOPT_PIPELINING' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLMOPT_MAXCONNECTS' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLPAUSE_ALL' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLPAUSE_CONT' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLPAUSE_RECV' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLPAUSE_RECV_CONT' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLPAUSE_SEND' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        'CURLPAUSE_SEND_CONT' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'curl',
        ],
        // Soap:
        'SOAP_SSL_METHOD_TLS' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'soap',
        ],
        'SOAP_SSL_METHOD_SSLv2' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'soap',
        ],
        'SOAP_SSL_METHOD_SSLv3' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'soap',
        ],
        'SOAP_SSL_METHOD_SSLv23' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'soap',
        ],
        // Tokenizer:
        'T_FINALLY' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'tokenizer',
        ],
        'T_YIELD' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'tokenizer',
        ],
        // Core/Password Hashing:
        'PASSWORD_BCRYPT' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'password',
        ],
        'PASSWORD_DEFAULT' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'password',
        ],
        'PASSWORD_BCRYPT_DEFAULT_COST' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'password',
        ],
        // Readline:
        'READLINE_LIB' => [
            '5.4'       => false,
            '5.5'       => true,
            'extension' => 'readline',
        ],

        // Libxml:
        'LIBXML_SCHEMA_CREATE' => [
            '5.5.1'     => false,
            '5.5.2'     => true,
            'extension' => 'libxml',
        ],

        // Curl:
        'CURL_SSLVERSION_TLSv1_0' => [
            '5.5.18'    => false,
            '5.5.19'    => true,
            'extension' => 'curl',
        ],
        'CURL_SSLVERSION_TLSv1_1' => [
            '5.5.18'    => false,
            '5.5.19'    => true,
            'extension' => 'curl',
        ],
        'CURL_SSLVERSION_TLSv1_2' => [
            '5.5.18'    => false,
            '5.5.19'    => true,
            'extension' => 'curl',
        ],

        'CURLPROXY_SOCKS4A' => [
            '5.5.22'    => false,
            '5.5.23'    => true,
            'extension' => 'curl',
        ],
        'CURLPROXY_SOCKS5_HOSTNAME' => [
            '5.5.22'    => false,
            '5.5.23'    => true,
            'extension' => 'curl',
        ],

        'CURL_VERSION_HTTP2' => [
            '5.5.23'    => false,
            '5.5.24'    => true,
            'extension' => 'curl',
        ],
        'CURL_HTTP_VERSION_2_0' => [
            '5.5.23'    => false,
            '5.5.24'    => true,
            'extension' => 'curl',
        ],

        'ARRAY_FILTER_USE_KEY' => [
            '5.5' => false,
            '5.6' => true,
        ],
        'ARRAY_FILTER_USE_BOTH' => [
            '5.5' => false,
            '5.6' => true,
        ],
        // FTP:
        'FTP_USEPASVADDRESS' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'fp',
        ],
        // LDAP:
        'LDAP_ESCAPE_DN' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_ESCAPE_FILTER' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'ldap',
        ],
        // OpenSSL:
        'OPENSSL_DEFAULT_STREAM_CIPHERS' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'STREAM_CRYPTO_METHOD_ANY_CLIENT' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'STREAM_CRYPTO_METHOD_ANY_SERVER' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'STREAM_CRYPTO_METHOD_TLSv1_0_SERVER' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'STREAM_CRYPTO_METHOD_TLSv1_1_SERVER' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        'STREAM_CRYPTO_METHOD_TLSv1_2_SERVER' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'openssl',
        ],
        // PostgreSQL:
        'PGSQL_CONNECT_ASYNC' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_CONNECTION_AUTH_OK' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_CONNECTION_AWAITING_RESPONSE' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_CONNECTION_MADE' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_CONNECTION_SETENV' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_CONNECTION_SSL_STARTUP' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_CONNECTION_STARTED' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_DML_ESCAPE' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_POLLING_ACTIVE' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_POLLING_FAILED' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_POLLING_OK' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_POLLING_READING' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_POLLING_WRITING' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'pgsql',
        ],
        // Tokenizer:
        'T_ELLIPSIS' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'tokenizer',
        ],
        'T_POW' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'tokenizer',
        ],
        'T_POW_EQUAL' => [
            '5.5'       => false,
            '5.6'       => true,
            'extension' => 'tokenizer',
        ],

        'INI_SCANNER_TYPED' => [
            '5.6.0' => false,
            '5.6.1' => true,
        ],

        'JSON_PRESERVE_ZERO_FRACTION' => [
            '5.6.5'     => false,
            '5.6.6'     => true,
            'extension' => 'json',
        ],

        'MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT' => [
            '5.6.15'    => false,
            '5.6.16'    => true,
            'extension' => 'mysqli',
        ],

        // GD:
        // Also introduced in 7.0.10.
        'IMG_WEBP' => [
            '5.6.24'    => false,
            '5.6.25'    => true,
            'extension' => 'gd',
        ],

        'TOKEN_PARSE' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'tokenizer',
        ],
        'FILTER_VALIDATE_DOMAIN' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'filter',
        ],
        'FILTER_FLAG_HOSTNAME' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'filter',
        ],
        'PHP_INT_MIN' => [
            '5.6' => false,
            '7.0' => true,
        ],
        // Curl:
        'CURLPIPE_NOTHING' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'curl',
        ],
        'CURLPIPE_HTTP1' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'curl',
        ],
        'CURLPIPE_MULTIPLEX' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'curl',
        ],
        // DateTime:
        'DATE_RFC3339_EXTENDED' => [
            '5.6' => false,
            '7.0' => true,
        ],
        // JSON:
        'JSON_ERROR_INVALID_PROPERTY_NAME' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'json',
        ],
        'JSON_ERROR_UTF16' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'json',
        ],
        // LibXML:
        'LIBXML_BIGLINES' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'libxml',
        ],
        // PCRE:
        'PREG_JIT_STACKLIMIT_ERROR' => [
            '5.6' => false,
            '7.0' => true,
        ],
        // POSIX:
        'POSIX_RLIMIT_AS' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_CORE' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_CPU' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_DATA' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_FSIZE' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_LOCKS' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_MEMLOCK' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_MSGQUEUE' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_NICE' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_NOFILE' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_NPROC' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_RSS' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_RTPRIO' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_RTTIME' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_SIGPENDING' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_STACK' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_INFINITY' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'posix',
        ],
        // Tokenizer:
        'T_COALESCE' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'tokenizer',
        ],
        'T_SPACESHIP' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'tokenizer',
        ],
        'T_YIELD_FROM' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'tokenizer',
        ],

        // Zlib:
        // The first three are in the PHP 5.4 changelog, but the Extension constant page says 7.0.
        'ZLIB_ENCODING_RAW' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_ENCODING_DEFLATE' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_ENCODING_GZIP' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_FILTERED' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_HUFFMAN_ONLY' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_FIXED' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_RLE' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_DEFAULT_STRATEGY' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_BLOCK' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_FINISH' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_FULL_FLUSH' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_NO_FLUSH' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_PARTIAL_FLUSH' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        'ZLIB_SYNC_FLUSH' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'zlib',
        ],
        // LDAP:
        'LDAP_OPT_X_TLS_REQUIRE_CERT' => [
            '5.6'       => false,
            '7.0'       => true,
            'extension' => 'ldap',
        ],
        // COM:
        'VT_I8' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'VT_UI8' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'DISP_E_DIVBYZERO' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'DISP_E_OVERFLOW' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'DISP_E_BADINDEX' => [
            '5.6' => false,
            '7.0' => true,
        ],
        'MK_E_UNAVAILABLE' => [
            '5.6' => false,
            '7.0' => true,
        ],

        'CURL_HTTP_VERSION_2' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURL_HTTP_VERSION_2TLS' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURL_REDIR_POST_301' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURL_REDIR_POST_302' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURL_REDIR_POST_303' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURL_REDIR_POST_ALL' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_KERBEROS5' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_PSL' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_UNIX_SOCKETS' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLAUTH_NEGOTIATE' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLAUTH_NTLM_WB' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLFTP_CREATE_DIR' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLFTP_CREATE_DIR_NONE' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLFTP_CREATE_DIR_RETRY' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLHEADER_SEPARATE' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLHEADER_UNIFIED' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLMOPT_CHUNK_LENGTH_PENALTY_SIZE' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLMOPT_CONTENT_LENGTH_PENALTY_SIZE' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLMOPT_MAX_HOST_CONNECTIONS' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLMOPT_MAX_PIPELINE_LENGTH' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLMOPT_MAX_TOTAL_CONNECTIONS' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_CONNECT_TO' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_DEFAULT_PROTOCOL' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_DNS_INTERFACE' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_DNS_LOCAL_IP4' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_DNS_LOCAL_IP6' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_EXPECT_100_TIMEOUT_MS' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_HEADEROPT' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_LOGIN_OPTIONS' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PATH_AS_IS' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PINNEDPUBLICKEY' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PIPEWAIT' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SERVICE_NAME' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXYHEADER' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SASL_IR' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SERVICE_NAME' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSL_ENABLE_ALPN' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSL_ENABLE_NPN' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSL_FALSESTART' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSL_VERIFYSTATUS' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_STREAM_WEIGHT' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_TCP_FASTOPEN' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_TFTP_NO_OPTIONS' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_UNIX_SOCKET_PATH' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLOPT_XOAUTH2_BEARER' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLPROTO_SMB' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLPROTO_SMBS' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLPROXY_HTTP_1_0' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLSSH_AUTH_AGENT' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],
        'CURLSSLOPT_NO_REVOKE' => [
            '7.0.6'     => false,
            '7.0.7'     => true,
            'extension' => 'curl',
        ],

        'DNS_CAA' => [
            '7.0.15' => false,
            '7.0.16' => true, // ... and 7.1.2.
        ],

        'DATE_RFC7231' => [
            '7.0.18' => false,
            '7.0.19' => true,
        ],

        'PHP_FD_SETSIZE' => [
            '7.0' => false,
            '7.1' => true,
        ],
        // Curl:
        'CURLMOPT_PUSHFUNCTION' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'curl',
        ],
        'CURL_PUSH_OK' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'curl',
        ],
        'CURL_PUSH_DENY' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'curl',
        ],
        // Filter:
        'FILTER_FLAG_EMAIL_UNICODE' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'filter',
        ],
        // GD:
        'IMAGETYPE_WEBP' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'gd',
        ],
        // Json:
        'JSON_UNESCAPED_LINE_TERMINATORS' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'json',
        ],
        // LDAP:
        'LDAP_OPT_X_SASL_NOCANON' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_SASL_USERNAME' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_CACERTDIR' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_CACERTFILE' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_CERTFILE' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_CIPHER_SUITE' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_KEYFILE' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_RANDOM_FILE' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_CRLCHECK' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_CRL_NONE' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_CRL_PEER' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_CRL_ALL' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_DHFILE' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_CRLFILE' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_PROTOCOL_MIN' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_PROTOCOL_SSL2' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_PROTOCOL_SSL3' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_PROTOCOL_TLS1_0' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_PROTOCOL_TLS1_1' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_PROTOCOL_TLS1_2' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_TLS_PACKAGE' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_KEEPALIVE_IDLE' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_KEEPALIVE_PROBES' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_OPT_X_KEEPALIVE_INTERVAL' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'ldap',
        ],
        // PostgreSQL:
        'PGSQL_NOTICE_LAST' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_NOTICE_ALL' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_NOTICE_CLEAR' => [
            '7.0'       => false,
            '7.1'       => true,
            'extension' => 'pgsql',
        ],

        'MT_RAND_PHP' => [
            '7.0' => false,
            '7.1' => true,
        ],

        // SQLite3:
        'SQLITE3_DETERMINISTIC' => [
            '7.1.3'     => false,
            '7.1.4'     => true,
            'extension' => 'sqlite3',
        ],

        // Core:
        'PHP_OS_FAMILY' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'PHP_FLOAT_DIG' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'PHP_FLOAT_EPSILON' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'PHP_FLOAT_MIN' => [
            '7.1' => false,
            '7.2' => true,
        ],
        'PHP_FLOAT_MAX' => [
            '7.1' => false,
            '7.2' => true,
        ],

        // Core/Password Hashing:
        'PASSWORD_ARGON2I' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'password',
        ],
        'PASSWORD_ARGON2_DEFAULT_MEMORY_COST' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'password',
        ],
        'PASSWORD_ARGON2_DEFAULT_TIME_COST' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'password',
        ],
        'PASSWORD_ARGON2_DEFAULT_THREADS' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'password',
        ],

        // Fileinfo:
        'FILEINFO_EXTENSION' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'fileinfo',
        ],

        // GD:
        'IMG_EFFECT_MULTIPLY' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'gd',
        ],
        'IMG_BMP' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'gd',
        ],

        // JSON:
        'JSON_INVALID_UTF8_IGNORE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'json',
        ],
        'JSON_INVALID_UTF8_SUBSTITUTE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'json',
        ],

        // LDAP:
        'LDAP_EXOP_START_TLS' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_EXOP_MODIFY_PASSWD' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_EXOP_REFRESH' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_EXOP_WHO_AM_I' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_EXOP_TURN' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'ldap',
        ],

        // PCRE:
        'PREG_UNMATCHED_AS_NULL' => [
            '7.1' => false,
            '7.2' => true,
        ],

        // Sodium:
        'SODIUM_LIBRARY_VERSION' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_LIBRARY_MAJOR_VERSION' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_LIBRARY_MINOR_VERSION' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_BASE64_VARIANT_ORIGINAL' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_BASE64_VARIANT_URLSAFE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_AES256GCM_KEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_AES256GCM_NSECBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_AES256GCM_NPUBBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_AES256GCM_ABYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_NSECBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_NPUBBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_ABYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_KEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NSECBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_ABYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NSECBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_ABYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AUTH_BYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_AUTH_KEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_BOX_SEALBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_BOX_SECRETKEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_BOX_PUBLICKEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_BOX_KEYPAIRBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_BOX_MACBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_BOX_NONCEBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_BOX_SEEDBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_KDF_BYTES_MIN' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_KDF_BYTES_MAX' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_KDF_CONTEXTBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_KDF_KEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_KX_SEEDBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_KX_SESSIONKEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_KX_PUBLICKEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_KX_SECRETKEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_KX_KEYPAIRBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_GENERICHASH_BYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_GENERICHASH_BYTES_MIN' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_GENERICHASH_BYTES_MAX' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_GENERICHASH_KEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_GENERICHASH_KEYBYTES_MIN' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_GENERICHASH_KEYBYTES_MAX' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_ALG_ARGON2I13' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_ALG_ARGON2ID13' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_ALG_DEFAULT' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_SALTBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_STRPREFIX' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_OPSLIMIT_SENSITIVE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_MEMLIMIT_SENSITIVE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_SALTBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_STRPREFIX' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_OPSLIMIT_INTERACTIVE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_MEMLIMIT_INTERACTIVE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_OPSLIMIT_SENSITIVE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_MEMLIMIT_SENSITIVE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SCALARMULT_BYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SCALARMULT_SCALARBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SHORTHASH_BYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SHORTHASH_KEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SECRETBOX_KEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SECRETBOX_MACBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SECRETBOX_NONCEBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_ABYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_HEADERBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_MESSAGEBYTES_MAX' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_MESSAGE' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_PUSH' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_REKEY' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SIGN_BYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SIGN_SEEDBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SIGN_SECRETKEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SIGN_KEYPAIRBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_STREAM_NONCEBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_STREAM_KEYBYTES' => [
            '7.1'       => false,
            '7.2'       => true,
            'extension' => 'sodium',
        ],

        'CURLAUTH_BEARER' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLAUTH_GSSAPI' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLE_WEIRD_SERVER_REPLY' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_APPCONNECT_TIME_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_CONNECT_TIME_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_CONTENT_LENGTH_DOWNLOAD_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_CONTENT_LENGTH_UPLOAD_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_FILETIME_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_HTTP_VERSION' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_NAMELOOKUP_TIME_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_PRETRANSFER_TIME_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_PROTOCOL' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_PROXY_SSL_VERIFYRESULT' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_REDIRECT_TIME_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_SCHEME' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_SIZE_DOWNLOAD_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_SIZE_UPLOAD_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_SPEED_DOWNLOAD_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_SPEED_UPLOAD_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_STARTTRANSFER_TIME_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_TOTAL_TIME_T' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_LOCK_DATA_CONNECT' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_LOCK_DATA_PSL' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_MAX_READ_SIZE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_ABSTRACT_UNIX_SOCKET' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_DISALLOW_USERNAME_IN_URL' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_DNS_SHUFFLE_ADDRESSES' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_HAPPY_EYEBALLS_TIMEOUT_MS' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_HAPROXYPROTOCOL' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_KEEP_SENDING_ON_ERROR' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PRE_PROXY' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_CAINFO' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_CAPATH' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_CRLFILE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_KEYPASSWD' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_PINNEDPUBLICKEY' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SSLCERT' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SSLCERTTYPE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SSL_CIPHER_LIST' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SSLKEY' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SSLKEYTYPE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SSL_OPTIONS' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SSL_VERIFYHOST' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SSL_VERIFYPEER' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SSLVERSION' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_TLS13_CIPHERS' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_TLSAUTH_PASSWORD' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_TLSAUTH_TYPE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_TLSAUTH_USERNAME' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_REQUEST_TARGET' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SOCKS5_AUTH' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSH_COMPRESSION' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SUPPRESS_CONNECT_HEADERS' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_TIMEVALUE_LARGE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_TLS13_CIPHERS' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLPROXY_HTTPS' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURLSSH_AUTH_GSSAPI' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_SSLVERSION_MAX_DEFAULT' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_SSLVERSION_MAX_NONE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_SSLVERSION_MAX_TLSv1_0' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_SSLVERSION_MAX_TLSv1_1' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_SSLVERSION_MAX_TLSv1_2' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_SSLVERSION_MAX_TLSv1_3' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_SSLVERSION_TLSv1_3' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_ASYNCHDNS' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_BROTLI' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_CONV' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_DEBUG' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_GSSAPI' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_GSSNEGOTIATE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_HTTPS_PROXY' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_IDN' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_LARGEFILE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_MULTI_SSL' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_NTLM' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_NTLM_WB' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_SPNEGO' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_SSPI' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_TLSAUTH_SRP' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'curl',
        ],
        'FILTER_SANITIZE_ADD_SLASHES' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'filter',
        ],
        'JSON_THROW_ON_ERROR' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'json',
        ],
        'LDAP_CONTROL_MANAGEDSAIT' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_PROXY_AUTHZ' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_SUBENTRIES' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_VALUESRETURNFILTER' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_ASSERT' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_PRE_READ' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_POST_READ' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_SORTREQUEST' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_SORTRESPONSE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_PAGEDRESULTS' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_AUTHZID_REQUEST' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_AUTHZID_RESPONSE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_SYNC' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_SYNC_STATE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_SYNC_DONE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_DONTUSECOPY' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_PASSWORDPOLICYREQUEST' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_PASSWORDPOLICYRESPONSE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_X_INCREMENTAL_VALUES' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_X_DOMAIN_SCOPE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_X_PERMISSIVE_MODIFY' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_X_SEARCH_OPTIONS' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_X_TREE_DELETE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_X_EXTENDED_DN' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_VLVREQUEST' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'LDAP_CONTROL_VLVRESPONSE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'ldap',
        ],
        'MB_CASE_FOLD' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'mbstring',
        ],
        'MB_CASE_UPPER_SIMPLE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'mbstring',
        ],
        'MB_CASE_LOWER_SIMPLE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'mbstring',
        ],
        'MB_CASE_TITLE_SIMPLE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'mbstring',
        ],
        'MB_CASE_FOLD_SIMPLE' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'mbstring',
        ],
        'PGSQL_DIAG_SCHEMA_NAME' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_DIAG_TABLE_NAME' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_DIAG_COLUMN_NAME' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_DIAG_DATATYPE_NAME' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_DIAG_CONSTRAINT_NAME' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_DIAG_SEVERITY_NONLOCALIZED' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'pgsql',
        ],
        'PASSWORD_ARGON2ID' => [
            '7.2'       => false,
            '7.3'       => true,
            'extension' => 'password',
        ],
        'STREAM_CRYPTO_PROTO_SSLv3' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'STREAM_CRYPTO_PROTO_TLSv1_0' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'STREAM_CRYPTO_PROTO_TLSv1_1' => [
            '7.2' => false,
            '7.3' => true,
        ],
        'STREAM_CRYPTO_PROTO_TLSv1_2' => [
            '7.2' => false,
            '7.3' => true,
        ],

        'CURL_VERSION_ALTSVC' => [
            '7.3.5'     => false,
            '7.3.6'     => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_CURLDEBUG' => [
            '7.3.5'     => false,
            '7.3.6'     => true,
            'extension' => 'curl',
        ],

        'CURLOPT_HTTP09_ALLOWED' => [
            '7.3.14'    => false,
            '7.3.15'    => true,
            'extension' => 'curl',
        ],

        'IMG_FILTER_SCATTER' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'gd',
        ],
        'MB_ONIGURUMA_VERSION' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'mbstring',
        ],
        'SO_LABEL' => [
            '7.3' => false,
            '7.4' => true,
        ],
        'SO_PEERLABEL' => [
            '7.3' => false,
            '7.4' => true,
        ],
        'SO_LISTENQLIMIT' => [
            '7.3' => false,
            '7.4' => true,
        ],
        'SO_LISTENQLEN' => [
            '7.3' => false,
            '7.4' => true,
        ],
        'SO_USER_COOKIE' => [
            '7.3' => false,
            '7.4' => true,
        ],
        'PASSWORD_ARGON2_PROVIDER' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'password',
        ],
        'PHP_WINDOWS_EVENT_CTRL_C' => [
            '7.3' => false,
            '7.4' => true,
        ],
        'PHP_WINDOWS_EVENT_CTRL_BREAK' => [
            '7.3' => false,
            '7.4' => true,
        ],
        'T_BAD_CHARACTER' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tokenizer',
        ],
        'T_COALESCE_EQUAL' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tokenizer',
        ],
        'T_FN' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tokenizer',
        ],

        'TIDY_TAG_ARTICLE' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_ASIDE' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_AUDIO' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_BDI' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_CANVAS' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_COMMAND' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_DATALIST' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_DETAILS' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_DIALOG' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_FIGCAPTION' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_FIGURE' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_FOOTER' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_HEADER' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_HGROUP' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_MAIN' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_MARK' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_MENUITEM' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_METER' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_NAV' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_OUTPUT' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_PROGRESS' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SECTION' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SOURCE' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_SUMMARY' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TEMPLATE' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TIME' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_TRACK' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],
        'TIDY_TAG_VIDEO' => [
            '7.3'       => false,
            '7.4'       => true,
            'extension' => 'tidy',
        ],

        'FILTER_VALIDATE_BOOL' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'filter',
        ],
        'LIBENCHANT_VERSION' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'enchant',
        ],
        'OPENSSL_ENCODING_DER' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_ENCODING_SMIME' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_ENCODING_PEM' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_CMS_DETACHED' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_CMS_TEXT' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_CMS_NOINTERN' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_CMS_NOVERIFY' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_CMS_NOCERTS' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_CMS_NOATTR' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_CMS_BINARY' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'OPENSSL_CMS_NOSIGS' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'openssl',
        ],
        'T_MATCH' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'tokenizer',
        ],
        'T_NAME_FULLY_QUALIFIED' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'tokenizer',
        ],
        'T_NAME_QUALIFIED' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'tokenizer',
        ],
        'T_NAME_RELATIVE' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'tokenizer',
        ],
        'T_NULLSAFE_OBJECT_OPERATOR' => [
            '7.4'       => false,
            '8.0'       => true,
            'extension' => 'tokenizer',
        ],

        'CURLOPT_DOH_URL' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_ISSUERCERT_BLOB' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_ISSUERCERT' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_ISSUERCERT_BLOB' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SSLCERT_BLOB' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_SSLKEY_BLOB' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSLCERT_BLOB' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSLKEY_BLOB' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'curl',
        ],
        'IMG_AVIF' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'gd',
        ],
        'IMG_WEBP_LOSSLESS' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'gd',
        ],
        'MYSQLI_REFRESH_REPLICA' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'mysqli',
        ],
        'POSIX_RLIMIT_KQUEUES' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'posix',
        ],
        'POSIX_RLIMIT_NPTS' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'posix',
        ],
        'SO_ACCEPTFILTER' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sockets',
        ],
        'SO_DONTTRUNC' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sockets',
        ],
        'SO_WANTMORE' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sockets',
        ],
        'SO_MARK' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sockets',
        ],
        'TCP_DEFER_ACCEPT' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sockets',
        ],
        'T_ENUM' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'tokenizer',
        ],
        'T_READONLY' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'tokenizer',
        ],
        'T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'tokenizer',
        ],
        'T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'tokenizer',
        ],
        'SODIUM_CRYPTO_STREAM_XCHACHA20_NONCEBYTES' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_STREAM_XCHACHA20_KEYBYTES' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SCALARMULT_RISTRETTO255_BYTES' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_SCALARMULT_RISTRETTO255_SCALARBYTES' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_CORE_RISTRETTO255_BYTES' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_CORE_RISTRETTO255_HASHBYTES' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_CORE_RISTRETTO255_SCALARBYTES' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],
        'SODIUM_CRYPTO_CORE_RISTRETTO255_NONREDUCEDSCALARBYTES' => [
            '8.0'       => false,
            '8.1'       => true,
            'extension' => 'sodium',
        ],

        'MYSQLI_IS_MARIADB' => [
            '8.1.1'     => false,
            '8.1.2'     => true,
            'extension' => 'mysqli',
        ],

        // COM_DOTNET:
        'DISP_E_PARAMNOTFOUND' => [
            '8.1' => false,
            '8.2' => true,
        ],
        'LOCALE_NEUTRAL' => [
            '8.1' => false,
            '8.2' => true,
        ],
        // Curl:
        'CURLALTSVC_H1' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLALTSVC_H2' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLALTSVC_H3' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLALTSVC_READONLYFILE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLAUTH_AWS_SIGV4' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLE_PROXY' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLFTPMETHOD_DEFAULT' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLHSTS_ENABLE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLHSTS_READONLYFILE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_EFFECTIVE_METHOD' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_PROXY_ERROR' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_REFERER' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_RETRY_AFTER' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLMOPT_MAX_CONCURRENT_STREAMS' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_ALTSVC_CTRL' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_ALTSVC' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_AWS_SIGV4' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_CAINFO_BLOB' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_DOH_SSL_VERIFYHOST' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_DOH_SSL_VERIFYPEER' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_DOH_SSL_VERIFYSTATUS' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_HSTS_CTRL' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_HSTS' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_MAIL_RCPT_ALLLOWFAILS' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_MAXAGE_CONN' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_MAXFILESIZE_LARGE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_MAXLIFETIME_CONN' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROXY_CAINFO_BLOB' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SASL_AUTHZID' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSH_HOST_PUBLIC_KEY_SHA256' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSL_EC_CURVES' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_UPKEEP_INTERVAL_MS' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_UPLOAD_BUFFERSIZE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_XFERINFOFUNCTION' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPROTO_MQTT' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_BAD_ADDRESS_TYPE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_BAD_VERSION' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_CLOSED' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_GSSAPI' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_GSSAPI_PERMSG' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_GSSAPI_PROTECTION' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_IDENTD_DIFFER' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_IDENTD' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_LONG_HOSTNAME' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_LONG_PASSWD' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_LONG_USER' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_NO_AUTH' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_OK' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_RECV_ADDRESS' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_RECV_AUTH' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_RECV_CONNECT' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_RECV_REQACK' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_REPLY_ADDRESS_TYPE_NOT_SUPPORTED' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_REPLY_COMMAND_NOT_SUPPORTED' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_REPLY_CONNECTION_REFUSED' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_REPLY_GENERAL_SERVER_FAILURE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_REPLY_HOST_UNREACHABLE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_REPLY_NETWORK_UNREACHABLE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_REPLY_NOT_ALLOWED' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_REPLY_TTL_EXPIRED' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_REPLY_UNASSIGNED' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_REQUEST_FAILED' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_RESOLVE_HOST' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_SEND_AUTH' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_SEND_CONNECT' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_SEND_REQUEST' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_UNKNOWN_FAIL' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_UNKNOWN_MODE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLPX_USER_REJECTED' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLSSLOPT_AUTO_CLIENT_CERT' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLSSLOPT_NATIVE_CA' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLSSLOPT_NO_PARTIALCHAIN' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURLSSLOPT_REVOKE_BEST_EFFORT' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_GSASL' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_HSTS' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_HTTP3' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_UNICODE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        'CURL_VERSION_ZSTD' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'curl',
        ],
        // DBA:
        'DBA_LMDB_USE_SUB_DIR' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'dba',
        ],
        'DBA_LMDB_NO_SUB_DIR' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'dba',
        ],
        // Filter:
        'FILTER_FLAG_GLOBAL_RANGE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'filter',
        ],
        // Sockets:
        'SO_INCOMING_CPU' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'SO_MEMINFO' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'SO_RTABLE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'TCP_KEEPALIVE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'TCP_KEEPCNT' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'TCP_KEEPIDLE' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'TCP_KEEPINTVL' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'TCP_NOTSENT_LOWAT' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'LOCAL_CREDS_PERSISTENT' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'SCM_CREDS2' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'LOCAL_CREDS' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'SO_BPF_EXTENSIONS' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'SO_SETFIB' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'TCP_CONGESTION' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'SO_ZEROCOPY' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],
        'MSG_ZEROCOPY' => [
            '8.1'       => false,
            '8.2'       => true,
            'extension' => 'sockets',
        ],

        'CURLINFO_CAPATH' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLINFO_CAINFO' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_MIME_OPTIONS' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLMIMEOPT_FORMESCAPE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_WS_OPTIONS' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLWS_RAW_MODE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_SSH_HOSTKEYFUNCTION' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_PROTOCOLS_STR' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_REDIR_PROTOCOLS_STR' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_CA_CACHE_TIMEOUT' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLOPT_QUICK_EXIT' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLKHMATCH_OK' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLKHMATCH_MISMATCH' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLKHMATCH_MISSING' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'CURLKHMATCH_LAST' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'curl',
        ],
        'MIXED_NUMBERS' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'intl',
        ],
        'HIDDEN_OVERLAY' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'intl',
        ],
        'OPENSSL_CMS_OLDMIMETYPE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'openssl',
        ],
        'PKCS7_NOOLDMIMETYPE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'openssl',
        ],
        'SIGINFO' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pcntl',
        ],
        'PDO_ODBC_TYPE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pdo_odbc',
        ],
        'PGSQL_TRACE_SUPPRESS_TIMESTAMPS' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_TRACE_REGRESS_MODE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_ERRORS_SQLSTATE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_PIPELINE_SYNC' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_PIPELINE_ON' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_PIPELINE_OFF' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_PIPELINE_ABORTED' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_SHOW_CONTEXT_NEVER' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_SHOW_CONTEXT_ERRORS' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'PGSQL_SHOW_CONTEXT_ALWAYS' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'pgsql',
        ],
        'POSIX_SC_ARG_MAX' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_SC_PAGESIZE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_SC_NPROCESSORS_CONF' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_SC_NPROCESSORS_ONLN' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_PC_LINK_MAX' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_PC_MAX_CANON' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_PC_MAX_INPUT' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_PC_NAME_MAX' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_PC_PATH_MAX' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_PC_PIPE_BUF' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_PC_CHOWN_RESTRICTED' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_PC_NO_TRUNC' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_PC_ALLOC_SIZE_MIN' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'POSIX_PC_SYMLINK_MAX' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'posix',
        ],
        'SO_ATTACH_REUSEPORT_CBPF' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'SO_DETACH_FILTER' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'SO_DETACH_BPF' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'TCP_QUICKACK' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'IP_DONTFRAG' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'IP_MTU_DISCOVER' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'IP_PMTUDISC_DO' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'IP_PMTUDISC_DONT' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'IP_PMTUDISC_WANT' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'IP_PMTUDISC_PROBE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'IP_PMTUDISC_INTERFACE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'IP_PMTUDISC_OMIT' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'AF_DIVERT' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'SOL_UDPLITE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'UDPLITE_RECV_CSCOV' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'UDPLITE_SEND_CSCOV' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'SO_RERROR' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'SO_ZEROIZE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'SO_SPLICE' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'TCP_REPAIR' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'SO_REUSEPORT_LB' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
        ],
        'IP_BIND_ADDRESS_NO_PORT' => [
            '8.2'       => false,
            '8.3'       => true,
            'extension' => 'sockets',
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
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens       = $phpcsFile->getTokens();
        $constantName = $tokens[$stackPtr]['content'];

        if (isset($this->newConstants[$constantName]) === false) {
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
     * error for a matched item.
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
        $itemArray   = $this->newConstants[$itemInfo['name']];
        $versionInfo = $this->getVersionInfo($itemArray);

        if (empty($versionInfo['not_in_version'])
            || ScannedCode::shouldRunOnOrBelow($versionInfo['not_in_version']) === false
        ) {
            return;
        }

        $this->addError($phpcsFile, $stackPtr, $itemInfo, $versionInfo);
    }


    /**
     * Generates the error for this item.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile   The file being scanned.
     * @param int                         $stackPtr    The position of the relevant token in
     *                                                 the stack.
     * @param array                       $itemInfo    Base information about the item.
     * @param string[]                    $versionInfo Array with detail (version) information
     *                                                 relevant to the item.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, array $itemInfo, array $versionInfo)
    {
        // Overrule the default message template.
        $this->msgTemplate = 'The constant "%s" is not present in PHP version %s or earlier';

        $msgInfo = $this->getMessageInfo($itemInfo['name'], $itemInfo['name'], $versionInfo);

        $phpcsFile->addError($msgInfo['message'], $stackPtr, $msgInfo['errorcode'], $msgInfo['data']);
    }
}
