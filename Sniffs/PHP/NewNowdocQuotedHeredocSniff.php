<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewNowdocQuotedHeredocSniff.
 *
 * PHP version 5.3
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewNowdocQuotedHeredocSniff.
 *
 * PHP 5.3 introduces Nowdoc syntax and (double) quoted identifiers for heredocs.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_NewNowdocQuotedHeredocSniff extends PHPCompatibility_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $targets = array();

        if (version_compare(PHP_VERSION, '5.3', '<') === true) {
            $targets[] = T_SL;
        }

        if (defined('T_START_NOWDOC')) {
            $targets[] = constant('T_START_NOWDOC');
        }
        if (defined('T_END_NOWDOC')) {
            $targets[] = constant('T_END_NOWDOC');
        }

        $targets[] = T_START_HEREDOC;

        return $targets;

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return int|void On older PHP versions passes a pointer to the nowdoc/heredoc closer
     *                  to skip passed anything in between in regards to processing
     *                  the file for this sniff.
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->supportsBelow('5.2') === false) {
            return;
        }

        $tokens    = $phpcsFile->getTokens();
        $isNowdoc  = false;
        $isHeredoc = false;

        switch ($tokens[$stackPtr]['type']) {
            case 'T_START_NOWDOC':
            case 'T_END_NOWDOC':
                $isNowdoc = true;
                break;

            case 'T_START_HEREDOC':
                /*
                 * If we have a heredoc opener, make sure we only report on double quoted identifiers.
                 * A double quoted identifier will have the opening quote on position 3 in the string:
                 * `<<<"ID"`
                 */
                if ($tokens[$stackPtr]['content'][3] !== '"') {
                    return;
                }

                $isHeredoc = true;
                break;
        }

        /*
         * In PHP 5.2 the T_NOWDOC and the quoted T_HEREDOC tokens aren't recognized yet
         * and PHPCS does not backfill for it, so we have to sniff for a specific
         * combination of tokens.
         */
        if ($tokens[$stackPtr]['code'] === T_SL) {
            /*
             * Check for the right token combination.
             */
            if (isset($tokens[($stackPtr + 1)]) === false || $tokens[($stackPtr + 1)]['code'] !== T_LESS_THAN) {
                return;
            }

            if (isset($tokens[($stackPtr + 2)]) === false
                || $tokens[($stackPtr + 2)]['code'] !== T_CONSTANT_ENCAPSED_STRING) {
                return;
            }

            /*
             * Heredoc and nowdoc naming rules:
             * "it must contain only alphanumeric characters and underscores and
             *  must start with a non-digit character or underscore"
             * @link http://php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
             */
            if (preg_match('`^(["\'])([a-z][a-z0-9_]*)\1$`iD', $tokens[($stackPtr + 2)]['content'], $matches) < 1) {
                return;
            }

            // Remember whether we found a nowdoc or a heredoc.
            switch ($matches[1]) {
                case "'":
                    $isNowdoc = true;
                    break;

                case '"':
                    $isHeredoc = true;
                    break;
            }


            /*
             * See if we can find the nowdoc/heredoc closer.
             */
            $closer = null;

            for ($i = ($stackPtr + 3); $i < $phpcsFile->numTokens; $i++) {
                $maybeCloser = $phpcsFile->findNext(T_STRING, $i, null, false, $matches[2]);
                if ($maybeCloser === false) {
                    return;
                }

                // The closing identifier must begin in the first column of the line.
                if ($tokens[$maybeCloser]['column'] !== 1) {
                    continue;
                }

                // The closing identifier must be the only content on that line, except for maybe a semi-colon.
                $next = $phpcsFile->findNext(array(T_WHITESPACE, T_SEMICOLON), ($maybeCloser + 1), null, true);
                if ($tokens[$maybeCloser]['line'] === $tokens[$next]['line']) {
                    continue;
                }

                $closer = $maybeCloser;
                break;
            }

            if (isset($closer) === false) {
                // No valid closer found.
                return;
            }
        }

        if ($isNowdoc === true) {
            $error = 'Nowdocs are not present in PHP version 5.2 or earlier.';
            $phpcsFile->addError($error, $stackPtr, 'Found');

            if (isset($closer) !== false) {
                // Also throw an error for the closer on older PHP versions and skip forward past it.
                // Not needed for newer PHP versions as those will trigger this sniff for the closer token itself.
                $phpcsFile->addError($error, $closer, 'Found');
                return ($closer + 1);
            }
        }

        if ($isHeredoc === true) {
            // Only throw an error for the opener.
            $phpcsFile->addError('The Heredoc identifier may not be enclosed in (double) quotes in PHP version 5.2 or earlier.', $stackPtr, 'Found');

            if (isset($closer) !== false) {
                return ($closer + 1);
            }
        }

    }//end process()

}//end class
