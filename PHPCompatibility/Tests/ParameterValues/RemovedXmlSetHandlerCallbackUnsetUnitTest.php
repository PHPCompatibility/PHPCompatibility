<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\ParameterValues;

use PHPCompatibility\Tests\BaseSniffTestCase;

/**
 * Test the RemovedXmlSetHandlerCallbackUnset sniff.
 *
 * @group removedXmlSetHandlerCallbackUnset
 * @group parameterValues
 *
 * @covers \PHPCompatibility\Sniffs\ParameterValues\RemovedXmlSetHandlerCallbackUnsetSniff
 *
 * @since 10.0.0
 */
final class RemovedXmlSetHandlerCallbackUnsetUnitTest extends BaseSniffTestCase
{

    /**
     * Test the sniff correctly detects an empty string being passed as a callback parameter.
     *
     * @dataProvider dataRemovedXmlSetHandlerCallbackUnset
     *
     * @param int    $line         Line number where the error should occur.
     * @param string $functionName Expected function name.
     * @param string $paramName    Optional. Expected parameter name.
     *                             Defaults to [$]handler.
     *
     * @return void
     */
    public function testRemovedXmlSetHandlerCallbackUnset($line, $functionName, $paramName = 'handler')
    {
        $file  = $this->sniffFile(__FILE__, '8.4');
        $error = \sprintf(
            'Passing an empty string to reset the $%s for %s() is deprecated since PHP 8.4. Pass `null` instead.',
            $paramName,
            $functionName
        );

        $this->assertWarning($file, $line, $error);

        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testRemovedXmlSetHandlerCallbackUnset()
     *
     * @return array<array<int|string>>
     */
    public static function dataRemovedXmlSetHandlerCallbackUnset()
    {
        return [
            [39, 'xml_set_character_data_handler'],
            [40, 'xml_set_default_handler'],
            [41, 'xml_set_end_namespace_decl_handler'],
            [44, 'xml_set_element_handler', 'start_handler'],
            [45, 'xml_set_element_handler', 'end_handler'],
            [48, 'xml_set_notation_decl_handler'],
            [51, 'xml_set_processing_instruction_handler'],
            [65, 'xml_set_external_entity_ref_handler'],
            [66, 'xml_set_start_namespace_decl_handler'],
            [68, 'xml_set_unparsed_entity_decl_handler'],
            [69, 'xml_set_start_namespace_decl_handler'],
        ];
    }

    /**
     * Verify there are no false positives on code this sniff should ignore.
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line Line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '8.4');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array<array<int>>
     */
    public static function dataNoFalsePositives()
    {
        $data = [];

        // No errors expected on the first 37 lines.
        for ($line = 1; $line <= 37; $line++) {
            $data[] = [$line];
        }

        return $data;
    }

    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '8.3');
        $this->assertNoViolation($file);
    }
}
