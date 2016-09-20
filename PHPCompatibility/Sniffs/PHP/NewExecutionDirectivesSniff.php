<?php
/**
 * PHPCompatibility_Sniffs_PHP_NewExecutionDirectivesSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

/**
 * PHPCompatibility_Sniffs_PHP_NewExecutionDirectivesSniff.
 *
 * @category  PHP
 * @package   PHPCompatibility
 * @author    Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PHPCompatibility_Sniffs_PHP_NewExecutionDirectivesSniff extends PHPCompatibility_Sniff
{

    /**
     * A list of new execution directives
     *
     * The array lists : version number with false (not present) or true (present).
     * If the execution order is conditional, add the condition as a string to the version nr.
     * If's sufficient to list the first version where the execution directive appears.
     *
     * @var array(string => array(string => int|string|null))
     */
    protected $newDirectives = array (
                                'ticks' => array(
                                    '3.1' => false,
                                    '4.0' => true,
                                    'valid_value_callback' => 'isNumeric',
                                ),
                                'encoding' => array(
                                    '5.2' => false,
                                    '5.3' => '--enable-zend-multibyte', // Directive ignored unless.
                                    '5.4' => true,
                                    'valid_value_callback' => 'validEncoding',
                                ),
                                'strict_types' => array(
                                    '5.6' => false,
                                    '7.0' => true,
                                    'valid_values' => array(1),
                                ),
                               );


    /**
     * Tokens to ignore when trying to find the value for the directive.
     *
     * @var array
     */
    protected $ignoreTokens = array();


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $this->ignoreTokens          = PHP_CodeSniffer_Tokens::$emptyTokens;
        $this->ignoreTokens[T_EQUAL] = T_EQUAL;

        return array(T_DECLARE);
    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['parenthesis_opener'], $tokens[$stackPtr]['parenthesis_closer']) === true) {
	        $openParenthesis  = $tokens[$stackPtr]['parenthesis_opener'];
	        $closeParenthesis = $tokens[$stackPtr]['parenthesis_closer'];
        }
        else {
	        if (version_compare(PHP_CodeSniffer::VERSION, '2.0', '>=')) {
				return;
			}

			// Deal with PHPCS 1.x which does not set the parenthesis properly for declare statements.
			$openParenthesis = $phpcsFile->findNext(T_OPEN_PARENTHESIS, ($stackPtr + 1), null, false, null, true);
			if ($openParenthesis === false || isset($tokens[$openParenthesis]['parenthesis_closer']) === false) {
				return;
			}
			$closeParenthesis = $tokens[$openParenthesis]['parenthesis_closer'];
		}

        $directivePtr = $phpcsFile->findNext(T_STRING, ($openParenthesis + 1), $closeParenthesis, false);
        if ($directivePtr === false) {
            return;
        }

        $directiveContent = $tokens[$directivePtr]['content'];

        if (isset($this->newDirectives[$directiveContent]) === false) {
            $error = 'Declare can only be used with the directives %s. Found: %s';
            $data  = array(
                implode(', ', array_keys($this->newDirectives)),
                $directiveContent,
            );
            $phpcsFile->addError($error, $stackPtr, 'InvalidDirectiveFound', $data);
        }
        else {
            // Check for valid directive for version.
            $this->maybeAddError($phpcsFile, $stackPtr, $directiveContent);

            // Check for valid directive value.
            $valuePtr = $phpcsFile->findNext($this->ignoreTokens, $directivePtr + 1, $closeParenthesis, true);
            if ($valuePtr === false) {
                return;
            }

            $this->addWarningOnInvalidValue($phpcsFile, $valuePtr, $directiveContent);
        }

    }//end process()


    /**
     * Generates a error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the declare statement
     *                                        in the token array.
     * @param string               $directive The directive.
     *
     * @return void
     */
    protected function maybeAddError($phpcsFile, $stackPtr, $directive)
    {
        $isError            = false;
        $notInVersion       = '';
        $conditionalVersion = '';
        foreach ($this->newDirectives[$directive] as $version => $present) {
            if (strpos($version, 'valid_') === false && $this->supportsBelow($version)) {
                if ($present === false) {
                    $isError      = true;
                    $notInVersion = $version;
                }
                else if (is_string($present)) {
                    // We cannot test for compilation option (ok, except by scraping the output of phpinfo...).
                    $conditionalVersion = $version;
                }
            }
        }
        if ($notInVersion !== '' || $conditionalVersion !== '') {
            if ($isError === true && $notInVersion !== '') {
                $error     = 'Directive %s is not present in PHP version %s or earlier';
                $errorCode = $directive . 'Found';
                $data      = array(
                    $directive,
                    $notInVersion,
                );

                $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
            }
            else if($conditionalVersion !== '') {
                $error     = 'Directive %s is present in PHP version %s but will be disregarded unless PHP is compiled with %s';
                $errorCode = $directive . 'Found';
                $data      = array(
                    $directive,
                    $conditionalVersion,
                    $this->newDirectives[$directive][$conditionalVersion],
                );

                $phpcsFile->addWarning($error, $stackPtr, $errorCode, $data);
            }
        }

    }//end maybeAddError()


    /**
     * Generates a error or warning for this sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the execution directive value
     *                                        in the token array.
     * @param string               $directive The directive.
     *
     * @return void
     */
    protected function addWarningOnInvalidValue($phpcsFile, $stackPtr, $directive)
    {
        $tokens = $phpcsFile->getTokens();

        $value = $tokens[$stackPtr]['content'];
        if ($tokens[$stackPtr]['code'] === T_CONSTANT_ENCAPSED_STRING) {
            $value = $this->stripQuotes($value);
        }

        $isError = false;
        if (isset($this->newDirectives[$directive]['valid_values'])) {
            if (in_array($value, $this->newDirectives[$directive]['valid_values']) === false) {
                $isError = true;
            }
        }
        else if (isset($this->newDirectives[$directive]['valid_value_callback'])) {
            $valid = call_user_func(array($this, $this->newDirectives[$directive]['valid_value_callback']), $value);
            if ($valid === false) {
                $isError = true;
            }
        }

        if ($isError === true) {
            $error = 'The execution directive %s does not seem to have a valid value. Please review. Found: %s';
            $data  = array(
                $directive,
                $value,
            );
            $phpcsFile->addWarning($error, $stackPtr, 'InvalidDirectiveValueFound', $data);
        }
    }// addErrorOnInvalidValue()


    /**
     * Check whether a value is numeric.
     *
     * Callback function to test whether the value for an execution directive is valid.
     *
     * @param mixed $value The value to test.
     *
     * @return bool
     */
    protected function isNumeric($value)
    {
        return is_numeric($value);
    }


    /**
     * Check whether a value is valid encoding.
     *
     * Callback function to test whether the value for an execution directive is valid.
     *
     * @param mixed $value The value to test.
     *
     * @return bool
     */
    protected function validEncoding($value)
    {
        $encodings = array();
        if (function_exists('mb_list_encodings')) {
            $encodings = mb_list_encodings();
        }

        if (empty($encodings)) {
            // If we can't test the encoding, let it pass through.
            return true;
        }

        if (in_array($value, $encodings, true)) {
            return true;
        }
        else {
            return false;
        }
    }

}//end class
