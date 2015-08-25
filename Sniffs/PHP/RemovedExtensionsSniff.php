<?php
/**
 * PHPCompatibility_Sniffs_PHP_RemovedExtensionsSniff.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */

/**
 * PHPCompatibility_Sniffs_PHP_RemovedExtensionsSniff.
 *
 * Discourages the use of removed extensions. Suggests alternative extensions if available
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class PHPCompatibility_Sniffs_PHP_RemovedExtensionsSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of removed extensions with their alternative, if any
     * Array codes : 0 = removed/unavailable, -1 = deprecated, 1 = active
     *
     * @var array(string|null)
     */
    protected $removedExtensions = array(
        'activescript' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'pecl/activescript'
        ),
        'cpdf' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'pecl/pdflib'
        ),
        'dbase' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
        'dbx' => array(
                '5.0' => 1,
                '5.1' => 0,
                '5.2' => 0,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'pecl/dbx'
        ),
        'dio' => array(
                '5.0' => 1,
                '5.1' => 0,
                '5.2' => 0,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'pecl/dio'
        ),
        'fam' => array(
                '5.0' => 1,
                '5.1' => 0,
                '5.2' => 0,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
        'fbsql' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
        'fdf' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'pecl/fdf'
        ),
        'filepro' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 0,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
        'hw_api' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 0,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
        'ingres' => array(
                '5.0' => 1,
                '5.1' => 0,
                '5.2' => 0,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'pecl/ingres'
        ),
        'ircg' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
        'mcve' => array(
                '5.0' => 1,
                '5.1' => 0,
                '5.2' => 0,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'pecl/mvce'
        ),
        'ming' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'pecl/ming'
        ),
        'mnogosearch' => array(
                '5.0' => 1,
                '5.1' => 0,
                '5.2' => 0,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
        'msql' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
        'mysql_' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 1,
                '5.4' => 1,
                '5.5' => -1,
                'alternative' => 'mysqli',
        ),
        'ncurses' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'pecl/ncurses'
        ),
        'oracle' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'oci8 or pdo_oci'
        ),
        'ovrimos' => array(
                '5.0' => 1,
                '5.1' => 0,
                '5.2' => 0,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
        'pfpro' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
        'sqlite' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 1,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
        'sybase' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'sybase_ct'
        ),
        'w32api' => array(
                '5.0' => 1,
                '5.1' => 0,
                '5.2' => 0,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => 'pecl/ffi'
        ),
        'yp' => array(
                '5.0' => 1,
                '5.1' => 1,
                '5.2' => 1,
                '5.3' => 0,
                '5.4' => 0,
                '5.5' => 0,
                'alternative' => null
        ),
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING);

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Find the next non-empty token.
        $openBracket = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);

        if ($tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS) {
            // Not a function call.
            return;
        }

        if (isset($tokens[$openBracket]['parenthesis_closer']) === false) {
            // Not a function call.
            return;
        }

        // Find the previous non-empty token.
        $search   = PHP_CodeSniffer_Tokens::$emptyTokens;
        $search[] = T_BITWISE_AND;
        $previous = $phpcsFile->findPrevious($search, ($stackPtr - 1), null, true);
        if ($tokens[$previous]['code'] === T_FUNCTION) {
            // It's a function definition, not a function call.
            return;
        }

        if ($tokens[$previous]['code'] === T_NEW) {
            // We are creating an object, not calling a function.
            return;
        }

        if ( $tokens[$previous]['code'] === T_OBJECT_OPERATOR ) {
            // We are calling a method of an object
            return;
        }

        foreach ($this->removedExtensions as $extension => $versionList) {
            if (strpos(strtolower($tokens[$stackPtr]['content']), strtolower($extension)) === 0) {
                $error = '';
                foreach ($versionList as $version => $status) {
                    if ($version != 'alternative') {
                        if ($status == -1 || $status == 0) {
                            if ($this->supportsAbove($version)) {
                                switch ($status) {
                                    case -1:
                                        $error .= 'deprecated since PHP ' . $version . ' and ';
                                        break;
                                    case 0:
                                        $error .= 'removed since PHP ' . $version . ' and ';
                                        break 2;
                                }
                            }
                        }
                    }
                }
                if (strlen($error) > 0) {
                    $error = "Extension '" . $extension . "' is " . $error;
                    $error = substr($error, 0, strlen($error) - 5);
                    if (!is_null($versionList['alternative'])) {
                        $error .= ' - use ' . $versionList['alternative'] . ' instead.';
                    }
                    $phpcsFile->addError($error, $stackPtr);
                }
            }
        }

    }//end process()

}//end class
