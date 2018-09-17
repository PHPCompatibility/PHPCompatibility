<?php
/**
 * \PHPCompatibility\Sniffs\PHP\PCRENewModifiers.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */

namespace PHPCompatibility\Sniffs\PHP;

use PHPCompatibility\Sniffs\PHP\PregReplaceEModifierSniff;

/**
 * \PHPCompatibility\Sniffs\PHP\PCRENewModifiers.
 *
 * Check for usage of newly added regex modifiers for PCRE functions.
 *
 * @category PHP
 * @package  PHPCompatibility
 * @author   Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class PCRENewModifiersSniff extends PregReplaceEModifierSniff
{

    /**
     * Functions to check for.
     *
     * @var array
     */
    protected $targetFunctions = array(
        'preg_replace'                => true,
        'preg_filter'                 => true,
        'preg_grep'                   => true,
        'preg_match_all'              => true,
        'preg_match'                  => true,
        'preg_replace_callback_array' => true,
        'preg_replace_callback'       => true,
        'preg_replace'                => true,
        'preg_split'                  => true,
    );

    /**
     * Array listing newly introduced regex modifiers.
     *
     * The key should be the modifier (case-sensitive!).
     * The value should be the PHP version in which the modifier was introduced.
     *
     * @var array
     */
    protected $newModifiers = array(
        'J' => array(
            '7.1' => false,
            '7.2' => true,
        ),
    );


    /**
     * Do a version check to determine if this sniff needs to run at all.
     *
     * @return bool
     */
    protected function bowOutEarly()
    {
        // Version used here should be the highest version from the `$newModifiers` array,
        // i.e. the last PHP version in which a new modifier was introduced.
        return ($this->supportsBelow('7.2') === false);
    }


    /**
     * Examine the regex modifier string.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile    The file being scanned.
     * @param int                   $stackPtr     The position of the current token in the
     *                                            stack passed in $tokens.
     * @param string                $functionName The function which contained the pattern.
     * @param string                $modifiers    The regex modifiers found.
     *
     * @return void
     */
    protected function examineModifiers(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, $functionName, $modifiers)
    {
        $error = 'The PCRE regex modifier "%s" is not present in PHP version %s or earlier';

        foreach ($this->newModifiers as $modifier => $versionArray) {
            if (strpos($modifiers, $modifier) === false) {
                continue;
            }

            $notInVersion = '';
            foreach ($versionArray as $version => $present) {
                if ($notInVersion === '' && $present === false
                    && $this->supportsBelow($version) === true
                ) {
                    $notInVersion = $version;
                }
            }

            if ($notInVersion === '') {
                continue;
            }

            $errorCode = $modifier . 'ModifierFound';
            $data      = array(
                $modifier,
                $notInVersion,
            );

            $phpcsFile->addError($error, $stackPtr, $errorCode, $data);
        }
    }

}//end class
