<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Operators;

use PHPCompatibility\AbstractNewFeatureSniff;
use PHP_CodeSniffer_File as File;
use PHPCSUtils\BackCompat\Helper;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Detect use of new PHP operators.
 *
 * PHP version All
 *
 * @link https://wiki.php.net/rfc/pow-operator
 * @link https://wiki.php.net/rfc/combined-comparison-operator
 * @link https://wiki.php.net/rfc/isset_ternary
 * @link https://wiki.php.net/rfc/null_coalesce_equal_operator
 * @link https://wiki.php.net/rfc/nullsafe_operator
 *
 * @since 9.0.0 Detection of new operators was originally included in the
 *              `NewLanguageConstruct` sniff (since 5.6).
 */
class NewOperatorsSniff extends AbstractNewFeatureSniff
{

    /**
     * A list of new operators, not present in older versions.
     *
     * The array lists : version number with false (not present) or true (present).
     * If's sufficient to list the first version where the operator appears.
     *
     * @since 5.6
     *
     * @var array(string => array(string => bool|string))
     */
    protected $newOperators = array(
        'T_POW' => array(
            '5.5' => false,
            '5.6' => true,
            'description' => 'The power operator (**)',
        ),
        'T_POW_EQUAL' => array(
            '5.5' => false,
            '5.6' => true,
            'description' => 'The power assignment operator (**=)',
        ),
        'T_SPACESHIP' => array(
            '5.6' => false,
            '7.0' => true,
            'description' => 'The spaceship operator (<=>)',
        ),
        'T_COALESCE' => array(
            '5.6' => false,
            '7.0' => true,
            'description' => 'The null coalescing operator (??)',
        ), // Identified in PHP < 7.0 icw PHPCS < 2.6.2 as T_INLINE_THEN + T_INLINE_THEN.
        'T_COALESCE_EQUAL' => array(
            '7.3' => false,
            '7.4' => true,
            'description' => 'The null coalesce equal operator (??=)',
        ), // Identified in PHP < 7.0 icw PHPCS < 2.6.2 as T_INLINE_THEN + T_INLINE_THEN + T_EQUAL and between PHPCS 2.6.2 and PHPCS 2.8.1 as T_COALESCE + T_EQUAL.
        'T_NULLSAFE_OBJECT_OPERATOR' => array(
            '7.4' => false,
            '8.0' => true,
            'description' => 'The nullsafe object operator (?->)',
        ),
    );


    /**
     * A list of new operators which are not recognized in older PHPCS versions.
     *
     * The array lists an alternative token to listen for.
     *
     * @since 7.0.3
     *
     * @var array(string => int)
     */
    protected $newOperatorsPHPCSCompat = array(
        'T_COALESCE'                 => \T_INLINE_THEN,
        'T_COALESCE_EQUAL'           => \T_EQUAL,
        'T_NULLSAFE_OBJECT_OPERATOR' => \T_OBJECT_OPERATOR,
    );

    /**
     * Token translation table for older PHPCS versions.
     *
     * The 'before' index lists the token which would have to be directly before the
     * token found for it to be one of the new operators.
     * The 'real_token' index indicates which operator was found in that case.
     *
     * If the token combination has multi-layer complexity, such as is the case
     * with T_COALESCE(_EQUAL), a 'callback' index is added instead pointing to a
     * separate function which can determine whether this is the targetted token across
     * PHP and PHPCS versions.
     *
     * {@internal 'before' was chosen rather than 'after' as that allowed for a 1-on-1
     * translation list with the current tokens.}
     *
     * @since 7.0.3
     *
     * @var array(string => array(string => string))
     */
    protected $PHPCSCompatTranslate = array(
        'T_INLINE_THEN' => array(
            'callback'   => 'isTCoalesce',
            'real_token' => 'T_COALESCE',
        ),
        'T_EQUAL' => array(
            'callback'   => 'isTCoalesceEqual',
            'real_token' => 'T_COALESCE_EQUAL',
        ),
        'T_OBJECT_OPERATOR' => array(
            'before'     => 'T_INLINE_THEN',
            'real_token' => 'T_NULLSAFE_OBJECT_OPERATOR',
        ),
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 5.6
     *
     * @return array
     */
    public function register()
    {
echo PHP_EOL, '======================================================================', PHP_EOL;
echo 'TestVersion: ', Helper::getConfigData('testVersion'),PHP_EOL;

        $tokens = array();
//var_dump($this->newOperators);
        foreach ($this->newOperators as $token => $versions) {
echo "token $token defined ?", PHP_EOL;
var_dump(defined($token));
            if (\defined($token)) {
                $tokens[] = constant($token);
            } elseif (isset($this->newOperatorsPHPCSCompat[$token])) {
                $tokens[] = $this->newOperatorsPHPCSCompat[$token];
            }
        }

$names = [];
foreach ($tokens as $token) {
	$names[$token] = Tokens::tokenName($token);
}
var_dump($names);

        return $tokens;
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 5.6
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $tokenType = $tokens[$stackPtr]['type'];
/*
static $dumped = false;
if ($dumped === false) {
ini_set( 'xdebug.overload_var_dump', 1 );
//$temp = new \PHPCSDebug\Sniffs\Debug\TokenListSniff();
//$temp->process($phpcsFile, 0);
$dumped = true;
}

echo '-------------------------------', PHP_EOL;
echo 'Current token: ', $tokenType, ' on line ', $tokens[$stackPtr]['line'], PHP_EOL;
if (isset($this->newOperators[$tokenType]) === false) {
var_dump($tokens[$stackPtr]);
}
*/
        // Translate older PHPCS token combis for new operators to the actual operator.
        if (isset($this->newOperators[$tokenType]) === false) {
/*
echo 'Line: ', __LINE__, ' - ', $tokenType, ' is not a new operator type token',PHP_EOL;
echo 'Token in translate table:',PHP_EOL;
var_dump(isset($this->PHPCSCompatTranslate[$tokenType]));
echo 'before key in translate table set and token before set:',PHP_EOL;
var_dump(isset($this->PHPCSCompatTranslate[$tokenType]['before'], $tokens[$stackPtr - 1]));
if (isset($this->PHPCSCompatTranslate[$tokenType]['before'], $tokens[$stackPtr - 1])) {
	echo 'type of token before is expected type of token before:',PHP_EOL;
	var_dump($tokens[$stackPtr - 1]['type'] === $this->PHPCSCompatTranslate[$tokenType]['before']);
}
echo 'callback key in translate table set:',PHP_EOL;
var_dump(isset($this->PHPCSCompatTranslate[$tokenType]['callback']));
if (isset($this->PHPCSCompatTranslate[$tokenType]['callback'])) {
	echo 'callback output:',PHP_EOL;
	var_dump(call_user_func(array($this, $this->PHPCSCompatTranslate[$tokenType]['callback']), $tokens, $stackPtr));
}
*/
            if (isset($this->PHPCSCompatTranslate[$tokenType])
                && ((isset($this->PHPCSCompatTranslate[$tokenType]['before'], $tokens[$stackPtr - 1]) === true
                    && $tokens[$stackPtr - 1]['type'] === $this->PHPCSCompatTranslate[$tokenType]['before'])
                || (isset($this->PHPCSCompatTranslate[$tokenType]['callback']) === true
                    && \call_user_func(array($this, $this->PHPCSCompatTranslate[$tokenType]['callback']), $tokens, $stackPtr) === true))
            ) {
                $tokenType = $this->PHPCSCompatTranslate[$tokenType]['real_token'];
            }
        } elseif ($tokenType === 'T_COALESCE') {
            // Make sure that T_COALESCE is not confused with T_COALESCE_EQUAL.
            if (isset($tokens[($stackPtr + 1)]) !== false && $tokens[($stackPtr + 1)]['code'] === \T_EQUAL) {
                // Ignore as will be dealt with via the T_EQUAL token.
                return;
            }
        }

        // If the translation did not yield one of the tokens we are looking for, bow out.
        if (isset($this->newOperators[$tokenType]) === false) {
            return;
        }

        $itemInfo = array(
            'name' => $tokenType,
        );
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
    }


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @since 7.1.0
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->newOperators[$itemInfo['name']];
    }


    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * @since 7.1.0
     *
     * @return array
     */
    protected function getNonVersionArrayKeys()
    {
        return array('description');
    }


    /**
     * Retrieve the relevant detail (version) information for use in an error message.
     *
     * @since 7.1.0
     *
     * @param array $itemArray Version and other information about the item.
     * @param array $itemInfo  Base information about the item.
     *
     * @return array
     */
    public function getErrorInfo(array $itemArray, array $itemInfo)
    {
        $errorInfo                = parent::getErrorInfo($itemArray, $itemInfo);
        $errorInfo['description'] = $itemArray['description'];

        return $errorInfo;
    }


    /**
     * Filter the error data before it's passed to PHPCS.
     *
     * @since 7.1.0
     *
     * @param array $data      The error data array which was created.
     * @param array $itemInfo  Base information about the item this error message applies to.
     * @param array $errorInfo Detail information about an item this error message applies to.
     *
     * @return array
     */
    protected function filterErrorData(array $data, array $itemInfo, array $errorInfo)
    {
        $data[0] = $errorInfo['description'];
        return $data;
    }


    /**
     * Callback function to determine whether a T_EQUAL token is really a T_COALESCE_EQUAL token.
     *
     * @since 7.1.2
     *
     * @param array $tokens   The token stack.
     * @param int   $stackPtr The current position in the token stack.
     *
     * @return bool
     */
    private function isTCoalesceEqual($tokens, $stackPtr)
    {
        if ($tokens[$stackPtr]['code'] !== \T_EQUAL || isset($tokens[($stackPtr - 1)]) === false) {
            // Function called for wrong token or token has no predecessor.
            return false;
        }

        if ($tokens[($stackPtr - 1)]['type'] === 'T_COALESCE') {
            return true;
        }
        if ($tokens[($stackPtr - 1)]['type'] === 'T_INLINE_THEN'
            && (isset($tokens[($stackPtr - 2)]) && $tokens[($stackPtr - 2)]['type'] === 'T_INLINE_THEN')
        ) {
            return true;
        }

        return false;
    }

    /**
     * Callback function to determine whether a T_INLINE_THEN token is really a T_COALESCE token.
     *
     * @since 7.1.2
     *
     * @param array $tokens   The token stack.
     * @param int   $stackPtr The current position in the token stack.
     *
     * @return bool
     */
    private function isTCoalesce($tokens, $stackPtr)
    {
        if ($tokens[$stackPtr]['code'] !== \T_INLINE_THEN || isset($tokens[($stackPtr - 1)]) === false) {
            // Function called for wrong token or token has no predecessor.
            return false;
        }

        if ($tokens[($stackPtr - 1)]['code'] === \T_INLINE_THEN) {
            // Make sure not to confuse it with the T_COALESCE_EQUAL token.
            if (isset($tokens[($stackPtr + 1)]) === false || $tokens[($stackPtr + 1)]['code'] !== \T_EQUAL) {
                return true;
            }
        }

        return false;
    }
}
