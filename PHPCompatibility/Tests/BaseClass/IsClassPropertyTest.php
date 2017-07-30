<?php
/**
 * Is class property ? test file
 *
 * @package PHPCompatibility
 */

namespace PHPCompatibility\Tests\BaseClass;

use PHPCompatibility\PHPCSHelper;

/**
 * isClassProperty() function tests
 *
 * @group utilityIsClassProperty
 * @group utilityFunctions
 *
 * @uses    \PHPCompatibility\Tests\BaseClass\MethodTestFrame
 * @package PHPCompatibility
 * @author  Juliette Reinders Folmer <phpcompatibility_nospam@adviesenzo.nl>
 */
class IsClassPropertyTest extends MethodTestFrame
{

    /**
     * The file name for the file containing the test cases within the
     * `sniff-examples/utility-functions/` directory.
     *
     * @var string
     */
    protected $filename = 'is_class_property.php';

    /**
     * Whether or not traits will be recognized in PHPCS.
     *
     * @var bool
     */
    protected static $recognizesTraits = true;


    /**
     * Set up skip condition.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        // When using PHPCS 2.3.4 or lower combined with PHP 5.3 or lower, traits are not recognized.
        if (version_compare(PHPCSHelper::getVersion(), '2.4.0', '<') && version_compare(PHP_VERSION_ID, '50400', '<')) {
            self::$recognizesTraits = false;
        }

        parent::setUpBeforeClass();
    }


    /**
     * testIsClassProperty
     *
     * @dataProvider dataIsClassProperty
     *
     * @covers \PHPCompatibility\Sniff::isClassProperty
     *
     * @param string $commentString The comment which prefaces the target token in the test file.
     * @param string $expected      The expected boolean return value.
     * @param bool   $isTrait       Whether the test relates to a variable in a trait.
     *
     * @return void
     */
    public function testIsClassProperty($commentString, $expected, $isTrait = false)
    {
        if ($isTrait === true && self::$recognizesTraits === false) {
            $this->markTestSkipped('Traits are not recognized on PHPCS < 2.4.0 in combination with PHP < 5.4');
            return;
        }

        $stackPtr = $this->getTargetToken($commentString, T_VARIABLE);
        $result   = $this->helperClass->isClassProperty($this->phpcsFile, $stackPtr);
        $this->assertSame($expected, $result);
    }

    /**
     * dataIsClassProperty
     *
     * @see testIsClassProperty()
     *
     * @return array
     */
    public function dataIsClassProperty()
    {
        return array(
            array('/* Case 1 */', false),
            array('/* Case 2 */', false),
            array('/* Case 3 */', false),
            array('/* Case 4 */', true),
            array('/* Case 5 */', true),
            array('/* Case 6 */', true),
            array('/* Case 7 */', true),
            array('/* Case 8 */', false),
            array('/* Case 9 */', false),
            array('/* Case 10 */', true),
            array('/* Case 11 */', true),
            array('/* Case 12 */', true),
            array('/* Case 13 */', true),
            array('/* Case 14 */', false),
            array('/* Case 15 */', false),
            array('/* Case 16 */', false),
            array('/* Case 17 */', false),
            array('/* Case 18 */', false),
            array('/* Case 19 */', false),
            array('/* Case 20 */', false),
            array('/* Case 21 */', false),
            array('/* Case 22 */', true, true),
            array('/* Case 23 */', true, true),
            array('/* Case 24 */', true, true),
            array('/* Case 25 */', true, true),
            array('/* Case 26 */', false, true),
            array('/* Case 27 */', false, true),
        );
    }
}
