<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Traits;

/**
 * Trait used in removed extension sniffs
 */
trait RemovedExtensionsTrait
{

    /**
     * A list of removed extensions with their alternative, if any.
     *
     * The array lists : version number with false (deprecated) and true (removed).
     * If's sufficient to list the first version where the extension was deprecated/removed.
     *
     * Optionally, an `alternative` key can be added to add the name of an alternative which can be
     * used after this extension was removed from PHP Core.
     *
     * @since 10.0.0 This array in a slightly different form previously existed in the RemovedExtensionsSniff.
     *
     * @var array(string => array(string => bool|string|null))
     */
    public $removedExtensions = [

    ];

    /**
     * Get an array of the non-PHP-version array keys used in a sub-array.
     *
     * By default, removed feature version arrays, contain an additional 'alternative' and a potential
     * 'extension' array key.
     *
     * @since 10.0.0
     *
     * @return array
     */
    protected function getNonVersionArrayKeys()
    {
        return ['alternative', 'extension'];
    }


    /*
     * TODO:
     * - add function to check if an error message needs to be adjusted as something is a removed extension.
     * - add function to adjust the error message
     * - implement use of the trait in the relevant sniffs.
     */
}
