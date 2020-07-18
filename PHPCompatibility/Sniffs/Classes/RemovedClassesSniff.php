<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Sniffs\Classes;

use PHPCompatibility\AbstractRemovedFeatureSniff;
use PHP_CodeSniffer_File as File;

/**
 * Detect use of removed PHP native classes.
 *
 * The sniff analyses the following constructs to find usage of removed classes:
 * - Class instantiation using the `new` keyword.
 * - (Anonymous) Class declarations to detect removed classes being extended by userland classes.
 * - Static use of class properties, constants or functions using the double colon.
 * - Function/closure declarations to detect removed classes used as parameter type declarations.
 * - Function/closure declarations to detect removed classes used as return type declarations.
 * - Try/catch statements to detect removed exception classes being caught.
 *
 * PHP version All
 *
 * @since 10.0.0
 */
class RemovedClassesSniff extends AbstractRemovedFeatureSniff
{

    /**
     * A list of deprecated/removed classes, not present in older versions.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the class was deprecated/removed.
     *
     * The optional `extension` key should be used to list the name of the extension
     * the class comes from if this class is part of a removed extension and should
     * match the array in the RemovedExtensionsTrait.
     *
     * @since 10.0.0
     *
     * @var array(string => array(string => bool))
     */
    protected $removedClasses = array(
        'HW_API' => array(
            '5.2'       => true,
            'extension' => 'hwapi',
        ),
        'HW_API_Object' => array(
            '5.2'       => true,
            'extension' => 'hwapi',
        ),
        'HW_API_Attribute' => array(
            '5.2'       => true,
            'extension' => 'hwapi',
        ),
        'HW_API_Error' => array(
            '5.2'       => true,
            'extension' => 'hwapi',
        ),
        'HW_API_Content' => array(
            '5.2'       => true,
            'extension' => 'hwapi',
        ),
        'HW_API_Reason' => array(
            '5.2'       => true,
            'extension' => 'hwapi',
        ),

        'SWFAction' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFBitmap' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFButton' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFDisplayItem' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFFill' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFFont' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFFontChar' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFGradient' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFMorph' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFMovie' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFPrebuiltClip' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFShape' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFSound' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFSoundInstance' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFSprite' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFText' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFTextField' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SWFVideoStream' => array(
            '5.3'       => true,
            'extension' => 'ming',
        ),
        'SQLiteDatabase' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLiteResult' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
        'SQLiteUnbuffered' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),

        'XmlRpcServer' => array(
            '8.0'       => true,
            'extension' => 'xmlrpc',
        ),
    );

    /**
     * A list of deprecated/removed Exception classes.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the class was deprecated/removed.
     *
     * The optional `extension` key should be used to list the name of the extension
     * the class comes from if this class is part of a removed extension and should
     * match the array in the ReovedExtensionsTrait.
     *
     * {@internal Classes listed here do not need to be added to the $removedClasses
     *            property as well.
     *            This list is automatically added to the $removedClasses property
     *            in the `register()` method.}
     *
     * @since 10.0.0
     *
     * @var array(string => array(string => bool))
     */
    protected $removedExceptions = array(
        'SQLiteException' => array(
            '5.4'       => true,
            'extension' => 'sqlite',
        ),
    );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 10.0.0
     *
     * @return array
     */
    public function register()
    {
        // Handle case-insensitivity of class names.
        $this->removedClasses    = \array_change_key_case($this->removedClasses, \CASE_LOWER);
        $this->removedExceptions = \array_change_key_case($this->removedExceptions, \CASE_LOWER);

        // Add the Exception classes to the Classes list.
        $this->removedClasses = array_merge($this->removedClasses, $this->removedExceptions);

        return array(
            \T_NEW,
            \T_CLASS,
            \T_ANON_CLASS,
            \T_DOUBLE_COLON,
            \T_FUNCTION,
            \T_CLOSURE,
            \T_CATCH,
            \T_RETURN_TYPE,
        );
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        switch ($tokens[$stackPtr]['type']) {
            case 'T_FUNCTION':
            case 'T_CLOSURE':
                $this->processFunctionToken($phpcsFile, $stackPtr);

                // Deal with older PHPCS version which don't recognize return type hints
                // as well as newer PHPCS versions (3.3.0+) where the tokenization has changed.
                $returnTypeHint = $this->getReturnTypeHintToken($phpcsFile, $stackPtr);
                if ($returnTypeHint !== false) {
                    $this->processReturnTypeToken($phpcsFile, $returnTypeHint);
                }
                break;

            case 'T_CATCH':
                $this->processCatchToken($phpcsFile, $stackPtr);
                break;

            case 'T_RETURN_TYPE':
                $this->processReturnTypeToken($phpcsFile, $stackPtr);
                break;

            default:
                $this->processSingularToken($phpcsFile, $stackPtr);
                break;
        }
    }


    /**
     * Processes this test for when a token resulting in a singular class name is encountered.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    private function processSingularToken(File $phpcsFile, $stackPtr)
    {
        $tokens      = $phpcsFile->getTokens();
        $FQClassName = '';

        if ($tokens[$stackPtr]['type'] === 'T_NEW') {
            $FQClassName = $this->getFQClassNameFromNewToken($phpcsFile, $stackPtr);

        } elseif ($tokens[$stackPtr]['type'] === 'T_CLASS' || $tokens[$stackPtr]['type'] === 'T_ANON_CLASS') {
            $FQClassName = $this->getFQExtendedClassName($phpcsFile, $stackPtr);

        } elseif ($tokens[$stackPtr]['type'] === 'T_DOUBLE_COLON') {
            $FQClassName = $this->getFQClassNameFromDoubleColonToken($phpcsFile, $stackPtr);
        }

        if ($FQClassName === '') {
            return;
        }

        $className   = substr($FQClassName, 1); // Remove global namespace indicator.
        $classNameLc = strtolower($className);

        if (isset($this->removedClasses[$classNameLc]) === false) {
            return;
        }

        $itemInfo = array(
            'name'   => $className,
            'nameLc' => $classNameLc,
        );
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
    }


    /**
     * Processes this test for when a function token is encountered.
     *
     * - Detect removed classes when used as a parameter type declaration.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    private function processFunctionToken(File $phpcsFile, $stackPtr)
    {
        // Retrieve typehints stripped of global NS indicator and/or nullable indicator.
        $typeHints = $this->getTypeHintsFromFunctionDeclaration($phpcsFile, $stackPtr);
        if (empty($typeHints) || \is_array($typeHints) === false) {
            return;
        }

        foreach ($typeHints as $hint) {

            $typeHintLc = strtolower($hint);

            if (isset($this->removedClasses[$typeHintLc]) === true) {
                $itemInfo = array(
                    'name'   => $hint,
                    'nameLc' => $typeHintLc,
                );
                $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
            }
        }
    }


    /**
     * Processes this test for when a catch token is encountered.
     *
     * - Detect exceptions when used in a catch statement.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    private function processCatchToken(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Bow out during live coding.
        if (isset($tokens[$stackPtr]['parenthesis_opener'], $tokens[$stackPtr]['parenthesis_closer']) === false) {
            return;
        }

        $opener = $tokens[$stackPtr]['parenthesis_opener'];
        $closer = ($tokens[$stackPtr]['parenthesis_closer'] + 1);
        $name   = '';
        $listen = array(
            // Parts of a (namespaced) class name.
            \T_STRING              => true,
            \T_NS_SEPARATOR        => true,
            // End/split tokens.
            \T_VARIABLE            => false,
            \T_BITWISE_OR          => false,
            \T_CLOSE_CURLY_BRACKET => false, // Shouldn't be needed as we expect a var before this.
        );

        for ($i = ($opener + 1); $i < $closer; $i++) {
            if (isset($listen[$tokens[$i]['code']]) === false) {
                continue;
            }

            if ($listen[$tokens[$i]['code']] === true) {
                $name .= $tokens[$i]['content'];
                continue;
            } else {
                if (empty($name) === true) {
                    // Weird, we should have a name by the time we encounter a variable or |.
                    // So this may be the closer.
                    continue;
                }

                $name   = ltrim($name, '\\');
                $nameLC = strtolower($name);

                if (isset($this->removedExceptions[$nameLC]) === true) {
                    $itemInfo = array(
                        'name'   => $name,
                        'nameLc' => $nameLC,
                    );
                    $this->handleFeature($phpcsFile, $i, $itemInfo);
                }

                // Reset for a potential multi-catch.
                $name = '';
            }
        }
    }


    /**
     * Processes this test for when a return type token is encountered.
     *
     * - Detect removed classes when used as a return type declaration.
     *
     * @since 10.0.0
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in
     *                                         the stack passed in $tokens.
     *
     * @return void
     */
    private function processReturnTypeToken(File $phpcsFile, $stackPtr)
    {
        $returnTypeHint = $this->getReturnTypeHintName($phpcsFile, $stackPtr);
        if (empty($returnTypeHint)) {
            return;
        }

        $returnTypeHint   = ltrim($returnTypeHint, '\\');
        $returnTypeHintLc = strtolower($returnTypeHint);

        if (isset($this->removedClasses[$returnTypeHintLc]) === false) {
            return;
        }

        // Still here ? Then this is a return type declaration using a new class.
        $itemInfo = array(
            'name'   => $returnTypeHint,
            'nameLc' => $returnTypeHintLc,
        );
        $this->handleFeature($phpcsFile, $stackPtr, $itemInfo);
    }


    /**
     * Get the relevant sub-array for a specific item from a multi-dimensional array.
     *
     * @since 10.0.0
     *
     * @param array $itemInfo Base information about the item.
     *
     * @return array Version and other information about the item.
     */
    public function getItemArray(array $itemInfo)
    {
        return $this->removedClasses[$itemInfo['nameLc']];
    }


    /**
     * Get the error message template for this sniff.
     *
     * @since 10.0.0
     *
     * @return string
     */
    protected function getErrorMsgTemplate()
    {
        return 'The built-in class %s is ';
    }
}
