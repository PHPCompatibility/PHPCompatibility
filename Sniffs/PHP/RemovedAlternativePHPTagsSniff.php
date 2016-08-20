<?php
/**
 * PHPCompatibility_Sniffs_PHP_RemovedAlternativePHPTags.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_RemovedAlternativePHPTags.
 *
 * Check for usage of alternative PHP tags - removed in PHP 7.0.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 *
 * Based on `Generic_Sniffs_PHP_DisallowAlternativePHPTags` by Juliette Reinders Folmer
 * which was merged into PHPCS 2.7.0.
 */
class PHPCompatibility_Sniffs_PHP_RemovedAlternativePHPTagsSniff extends PHPCompatibility_Sniff
{

    /**
     * Whether ASP tags are enabled or not.
     *
     * @var bool
     */
    private $_aspTags = false;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        if (version_compare(phpversion(), '7.0', '<') === true) {
            $this->_aspTags = (boolean) ini_get('asp_tags');
        }

        return array(
                T_OPEN_TAG,
                T_OPEN_TAG_WITH_ECHO,
                T_INLINE_HTML,
               );

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsAbove('7.0') === false) {
            return;
        }

        $tokens  = $phpcsFile->getTokens();
        $openTag = $tokens[$stackPtr];
        $content = trim($openTag['content']);

        if ($content === '' || $content === '<?php') {
            return;
        }

        if ($openTag['code'] === T_OPEN_TAG || $openTag['code'] === T_OPEN_TAG_WITH_ECHO) {

            if ($content === '<%' || $content === '<%=') {
                $data = array(
                    'ASP',
                    $content,
                );
                $errorCode = 'ASPOpenTagFound';
            }
            else if (strpos($content, '<script ') !== false) {
                $data = array(
                    'Script',
                    $content,
                );
                $errorCode = 'ScriptOpenTagFound';
            }
            else {
                return;
            }
        }
        // Account for incorrect script open tags.
        // The "(?:<s)?" in the regex is to work-around a bug in the tokenizer in PHP 5.2.
        else if ($openTag['code'] === T_INLINE_HTML
            && preg_match('`((?:<s)?cript (?:[^>]+)?language=[\'"]?php[\'"]?(?:[^>]+)?>)`i', $content, $match) === 1
        ) {
            $found = $match[1];
            if (version_compare(phpversion(), '5.3', '<')) {
                // Add the missing '<s' at the start of the match for PHP 5.2.
                $found = '<s' . $match[1];
            }

            $data = array(
                'Script',
                $found,
            );
            $errorCode = 'ScriptOpenTagFound';
        }

        if (isset($errorCode, $data)) {
            $error = '%s style opening tags have been removed in PHP 7.0. Found "%s"';
            $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
            return;
        }


        // If we're still here, we can't be sure if what we find was really intended as ASP open tags.
        if ($openTag['code'] === T_INLINE_HTML && $this->_aspTags === false) {
            if (strpos($content, '<%') !== false) {
                $error   = 'Possible use of ASP style opening tags detected. ASP style opening tags have been removed in PHP 7.0. Found: %s';
                $snippet = $this->getSnippet($content, '<%');
                $data    = array('<%'.$snippet);

                $phpcsFile->addWarning($error, $stackPtr, 'MaybeASPOpenTagFound', $data);
            }
        }

    }//end process()


    /**
     * Get a snippet from a HTML token.
     *
     * @param string $content  The content of the HTML token.
     * @param string $start_at Partial string to use as a starting point for the snippet.
     * @param int    $length   The target length of the snippet to get. Defaults to 25.
     *
     * @return string
     */
    protected function getSnippet($content, $start_at = '', $length = 25)
    {
        $start_pos = 0;

        if ($start_at !== '') {
            $start_pos = strpos($content, $start_at);
            if ($start_pos !== false) {
                $start_pos += strlen($start_at);
            }
        }

        $snippet = substr($content, $start_pos, $length);
        if ((strlen($content) - $start_pos) > $length) {
            $snippet .= '...';
        }

        return $snippet;

    }//end getSnippet()

}//end class
