<?php

/*
 * Make sure that global constants are correctly identified.
 *
 * The below should *NOT* be recognized as global constant usage.
 */

/* test 1 */
namespace PHP_VERSION_ID {}

/* test 2 */
namespace MY\OTHER\PHP_VERSION_ID\NS {}

/* test 3 */
use PHP_VERSION_ID;

/* test 4 */
use Something, PHP_VERSION_ID, SomethingElse;

/* test 5 */
class PHP_VERSION_ID {
    /* test 6 */
    const PHP_VERSION_ID = 'something';
    /* test 7 */
    private function PHP_VERSION_ID() {}
}

/* test 8 */
class ABC extends PHP_VERSION_ID {}

/* test 9 */
class DEF implements PHP_VERSION_ID {}

/* test 10 */
interface PHP_VERSION_ID {}

/* test 11 */
trait PHP_VERSION_ID {}

/* test 12 */
$a = new PHP_VERSION_ID;

/* test 13 */
$a = new PHP_VERSION_ID();

/* test 14 */
function PHP_VERSION_ID() {}

/* test 15 */
echo PHP_VERSION_ID();

/* test 16 */
echo My\UsedAsNamespace\PHP_VERSION_ID\something;

/* test 17 */
My\UsedAsNamespace\PHP_VERSION_ID\something::something_else();

/* test 18 */
if ( $abc instanceof PHP_VERSION_ID ) {}

/* test 19 */
goto PHP_VERSION_ID;

/* test 20 */
echo \mynamespace\PHP_VERSION_ID;

/* test 21 */
echo My_Class::PHP_VERSION_ID;

/* test 22 */
echo $this->PHP_VERSION_ID;

/* test 23 */
use const SomeNamespace\PHP_VERSION_ID as SSP; // PHP 5.6+

/* test 24 */
use const ABC as PHP_VERSION_ID;

/* test 25 */
use const SomeNamespace\{PHP_VERSION_ID, TEMPLATEPATH}; // PHP 7.0+

class Talker {
    /* test 26 */
    use A, PHP_VERSION_ID, C {
        /* test 27 */
        PHP_VERSION_ID::smallTalk insteadof A;
        /* test 28 */
        A::bigTalk insteadof PHP_VERSION_ID;
    }
}

class MyClass2 {
    use HelloWorld {
        /* test 29 */
        sayHello as private PHP_VERSION_ID;
        /* test 30 */
        sayGoodbye as protected PHP_VERSION_ID;
        /* test 31 */
        sayHowdy as public PHP_VERSION_ID;
    }
}

/* test 32 */
echo $this?->PHP_VERSION_ID;

/* test 33 */
enum PHP_VERSION_ID: string {}

/* test 34 */
function &PHP_VERSION_ID() {}

/* test 35 */
echo namespace\PHP_VERSION_ID;

/* test 36 */
const PHP_VERSION_ID = 'something';

/* test 37 */
const ABC = '123',
      DEF = '456',
      PHP_VERSION_ID = 'something',
      GHI = 789;

/* test 38 */
use SomeNamespace\{
    PHP_VERSION_ID,
    TEMPLATEPATH,
};

/* test 39 */
use SomeNamespace\{
    PHP_VERSION_ID,
};

/* test 40 */
use const SomeNamespace\{
    PHP_VERSION_ID,
    TEMPLATEPATH,
};

/* test 41 */
use SomeNamespace\{
    const PHP_VERSION_ID,
};

/* test 42 */
use SomeNamespace\{
    CONST PHP_VERSION_ID,
};

class EverythingTyped {
    /* test 43 */
    final PHP_VERSION_ID $typedPropA = 'Type is class name, not constant';

    /* test 44 */
    public ?PHP_VERSION_ID $typedPropB = 'Type is class name, not constant';

    /* test 45 */
    protected PHP_VERSION_ID|false $typedPropC = 'Type is class name, not constant';

    /* test 46 */
    private false|PHP_VERSION_ID $typedPropD = 'Type is class name, not constant';

    /* test 47 */
    var string|PHP_VERSION_ID|false $typedPropE = 'Type is class name, not constant';

    /* test 48 */
    static PHP_VERSION_ID&Something $typedPropF = 'Type is class name, not constant';

    /* test 49 */
    readonly Something&PHP_VERSION_ID $typedPropG = 'Type is class name, not constant';

    /* test 50 */
    public(set) Something&PHP_VERSION_ID&SomethingElse $typedPropH = 'Type is class name, not constant';

    /* test 51 */
    protected(set) (Something&PHP_VERSION_ID)|null $typedPropI = 'Type is class name, not constant';

    /* test 52 */
    private(set) false|(PHP_VERSION_ID&Something) $typedPropJ = 'Type is class name, not constant';

    public function paramTypes(
        /* test 53 */
        PHP_VERSION_ID $typedParamA,
        /* test 54 */
        ?PHP_VERSION_ID $typedParamB,
        /* test 55 */
        PHP_VERSION_ID|false $typedParamC,
        /* test 56 */
        false|PHP_VERSION_ID $typedParamD,
        /* test 57 */
        string|PHP_VERSION_ID|false $typedParamE,
        /* test 58 */
        PHP_VERSION_ID&Something $typedParamF,
        /* test 59 */
        Something&PHP_VERSION_ID $typedParamG,
        /* test 60 */
        Something&PHP_VERSION_ID&SomethingElse $typedParamH,
        /* test 61 */
        (Something&PHP_VERSION_ID)|null $typedParamI,
        /* test 62 */
        false|(PHP_VERSION_ID&Something) $typedParamJ,
    ) {}

    /* test 63 */
    public function returnTypeA(): PHP_VERSION_ID {}
    /* test 64 */
    public function returnTypeB(): ?PHP_VERSION_ID {}
    /* test 65 */
    public function returnTypeC(): PHP_VERSION_ID|false {}
    /* test 66 */
    public function returnTypeD(): false|PHP_VERSION_ID {}
    /* test 67 */
    public function returnTypeE(): string|PHP_VERSION_ID|false {}
    /* test 68 */
    public function returnTypeF(): PHP_VERSION_ID&Something {}
    /* test 69 */
    public function returnTypeG(): Something&PHP_VERSION_ID {}
    /* test 70 */
    public function returnTypeH(): Something&PHP_VERSION_ID&SomethingElse {}
    /* test 71 */
    public function returnTypeI(): (Something&PHP_VERSION_ID)|null {}
    /* test 72 */
    public function returnTypeJ(): false|(PHP_VERSION_ID&Something) {}

    /* test 73 */
    abstract public function returnTypeK(): PHP_VERSION_ID;
}

/* test 74 */
#[PHP_VERSION_ID]
function HasSingleAttribute() {}

/* test 75 */
#[MyAttribute(), PHP_VERSION_ID]
function HasMultiAttribute() {}

/* test 76 */
attributeNestedInParenthesis1(
    new #[MyAttribute(), PHP_VERSION_ID] class () {}
);

/* test 77 */
try {
} catch (PHP_VERSION_ID $e) {
/* test 78 */
} catch (PHP_VERSION_ID) {}

enum AllowsConstants: string {
    /* test 79 */
    public const PHP_VERSION_ID = true;
}

trait ConstantInTrait {
    /* test 80 */
    final const PHP_VERSION_ID = true;
}

/*
 * Make sure that global constants are correctly identified.
 *
 * The below should be recognized as global constant usage.
 */

/* test A1 */
echo PHP_VERSION_ID;

/* test A2 */
echo \PHP_VERSION_ID; // Global constant.

/* test A3 */
$folder = basename( PHP_VERSION_ID );

/* test A4 */
include PHP_VERSION_ID . '/js/myfile.js';

/* test A5 */
use const PHP_VERSION_ID as SSP;

/* test A6 */
switch( PHP_VERSION_ID ) {
    /* test A7 */
    case PHP_VERSION_ID:
        break;
}

/* test A8 */
$array[PHP_VERSION_ID] = 'something';

/* test A9 */
const NOT_TARGET = null ?? PHP_VERSION_ID[1] ?? 1;

?>
<?= /* test A10 */ PHP_VERSION_ID; ?>
<?php

/* test A11 */
$arrow = fn() int => PHP_VERSION_ID;

/* test A12 */
$spreadInArray = [...PHP_VERSION_ID];

/* test A13 */
$matchCondition = match(PHP_VERSION_ID) {
    10      => true,
    default => false,
};

/* test A14 */
#[MyAttribute(PHP_VERSION_ID)]
function HasAttribute() {}

/* test A15 */
attributeNestedInParenthesis2(
    new #[MyAttribute(PHP_VERSION_ID)] class () {}
);
