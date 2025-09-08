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

class TypedConstants {
    /* test 81 */
    public const int PHP_VERSION_ID = 10;
    /* test 82 */
    protected const ?string PHP_VERSION_ID = null;
    /* test 83 */
    private const int|false PHP_VERSION_ID = 10;
    /* test 84 */
    final const TypeA&TypeB PHP_VERSION_ID = new TypeA();
    /* test 85 */
    final protected const (TypeA&TypeB)|null PHP_VERSION_ID = null;
}

class NameInConstantType {
    /* test 86 */
    final const PHP_VERSION_ID CONST_NAME_A = 'Type is class name, not constant';

    /* test 87 */
    public const ?PHP_VERSION_ID CONST_NAME_B = 'Type is class name, not constant';

    /* test 88 */
    protected const PHP_VERSION_ID|false CONST_NAME_C = 'Type is class name, not constant';

    /* test 89 */
    private const false|PHP_VERSION_ID CONST_NAME_D = 'Type is class name, not constant';

    /* test 90 */
    const string|PHP_VERSION_ID|false CONST_NAME_E = 'Type is class name, not constant';

    /* test 91 */
    final const PHP_VERSION_ID&Something CONST_NAME_F = 'Type is class name, not constant';

    /* test 92 */
    public const Something&PHP_VERSION_ID CONST_NAME_G = 'Type is class name, not constant';

    /* test 93 */
    protected const Something&PHP_VERSION_ID&SomethingElse CONST_NAME_H = 'Type is class name, not constant';

    /* test 94 */
    private const (Something&PHP_VERSION_ID)|null CONST_NAME_I = 'Type is class name, not constant';

    /* test 95 */
    public const false|(PHP_VERSION_ID&Something) CONST_NAME_J = 'Type is class name, not constant';
}

/* test 96 */
#[MyAttribute(), \PHP_VERSION_ID]
function HasMultiAttribute() {}

/* test 97 */
attributeNestedInParenthesis1(
    new #[MyAttribute(), \PHP_VERSION_ID] class () {}
);

/* test 98 */
try {
} catch (\PHP_VERSION_ID $e) {
/* test 99 */
} catch (\PHP_VERSION_ID) {}

/* test 100 */
if ( $abc instanceof \PHP_VERSION_ID ) {}

class EverythingTypedFQN {
    /* test 101 */
    final const \PHP_VERSION_ID CONST_NAME_A = 'Type is class name, not constant';

    /* test 102 */
    public const ?\PHP_VERSION_ID CONST_NAME_B = 'Type is class name, not constant';

    /* test 103 */
    protected const \PHP_VERSION_ID|false CONST_NAME_C = 'Type is class name, not constant';

    /* test 104 */
    private const false|\PHP_VERSION_ID CONST_NAME_D = 'Type is class name, not constant';

    /* test 105 */
    const string|\PHP_VERSION_ID|false CONST_NAME_E = 'Type is class name, not constant';

    /* test 106 */
    final const \PHP_VERSION_ID&Something CONST_NAME_F = 'Type is class name, not constant';

    /* test 107 */
    public const Something&\PHP_VERSION_ID CONST_NAME_G = 'Type is class name, not constant';

    /* test 108 */
    protected const Something&\PHP_VERSION_ID&SomethingElse CONST_NAME_H = 'Type is class name, not constant';

    /* test 109 */
    private const (Something&\PHP_VERSION_ID)|null CONST_NAME_I = 'Type is class name, not constant';

    /* test 110 */
    public const false|(\PHP_VERSION_ID&Something) CONST_NAME_J = 'Type is class name, not constant';

    /* test 111 */
    final \PHP_VERSION_ID $typedPropA = 'Type is class name, not constant';

    /* test 112 */
    public ?\PHP_VERSION_ID $typedPropB = 'Type is class name, not constant';

    /* test 113 */
    protected \PHP_VERSION_ID|false $typedPropC = 'Type is class name, not constant';

    /* test 114 */
    private false|\PHP_VERSION_ID $typedPropD = 'Type is class name, not constant';

    /* test 115 */
    var string|\PHP_VERSION_ID|false $typedPropE = 'Type is class name, not constant';

    /* test 116 */
    static \PHP_VERSION_ID&Something $typedPropF = 'Type is class name, not constant';

    /* test 117 */
    readonly Something&\PHP_VERSION_ID $typedPropG = 'Type is class name, not constant';

    /* test 118 */
    public(set) Something&\PHP_VERSION_ID&SomethingElse $typedPropH = 'Type is class name, not constant';

    /* test 119 */
    protected(set) (Something&\PHP_VERSION_ID)|null $typedPropI = 'Type is class name, not constant';

    /* test 120 */
    private(set) false|(\PHP_VERSION_ID&Something) $typedPropJ = 'Type is class name, not constant';

    public function paramTypes(
        /* test 121 */
        \PHP_VERSION_ID $typedParamA,
        /* test 122 */
        ?\PHP_VERSION_ID $typedParamB,
        /* test 123 */
        \PHP_VERSION_ID|false $typedParamC,
        /* test 124 */
        false|\PHP_VERSION_ID $typedParamD,
        /* test 125 */
        string|\PHP_VERSION_ID|false $typedParamE,
        /* test 126 */
        \PHP_VERSION_ID&Something $typedParamF,
        /* test 127 */
        Something&\PHP_VERSION_ID $typedParamG,
        /* test 128 */
        Something&\PHP_VERSION_ID&SomethingElse $typedParamH,
        /* test 129 */
        (Something&\PHP_VERSION_ID)|null $typedParamI,
        /* test 130 */
        false|(\PHP_VERSION_ID&Something) $typedParamJ,
    ) {}

    /* test 131 */
    public function returnTypeA(): \PHP_VERSION_ID {}
    /* test 132 */
    public function returnTypeB(): ?\PHP_VERSION_ID {}
    /* test 133 */
    public function returnTypeC(): \PHP_VERSION_ID|false {}
    /* test 134 */
    public function returnTypeD(): false|\PHP_VERSION_ID {}
    /* test 135 */
    public function returnTypeE(): string|\PHP_VERSION_ID|false {}
    /* test 136 */
    public function returnTypeF(): \PHP_VERSION_ID&Something {}
    /* test 137 */
    public function returnTypeG(): Something&\PHP_VERSION_ID {}
    /* test 138 */
    public function returnTypeH(): Something&\PHP_VERSION_ID&SomethingElse {}
    /* test 139 */
    public function returnTypeI(): (Something&\PHP_VERSION_ID)|null {}
    /* test 140 */
    public function returnTypeJ(): false|(\PHP_VERSION_ID&Something) {}

    /* test 141 */
    abstract public function returnTypeK(): \PHP_VERSION_ID;
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

/* test A16 */
\exit(PHP_VERSION_ID);

class UseOfGlobalConstantsWithinAClass {
    /* test A17 */
    const int FOO = PHP_VERSION_ID;

    /* test A18 */
    const int BAR = \PHP_VERSION_ID;

    /* test A19 */
    public $property = PHP_VERSION_ID;

    /* test A20 */
    protected function name(string $defaultValue = \PHP_VERSION_ID) {
        /* test A21 */
        echo PHP_VERSION_ID;

        /* test A22 */
        $var = $a + PHP_VERSION_ID;
    }
}

/* test A23 */
#[MyAttribute(\PHP_VERSION_ID)]
function HasAttribute() {}

/* test A24 */
attributeNestedInParenthesis2(
    new #[MyAttribute(\PHP_VERSION_ID)] class () {}
);
