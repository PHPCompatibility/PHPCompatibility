<?php

/*
 * Not attributes.
 */

/*[StarComment*/
//[SlashComment]
# [HashComment]

$var = 10; # Hash comment

/*
 * PHP 8.0: Attributes.
 */

#[Attribute]
class MyAttribute {}

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
class MyOtherAttribute {}

#[MyAttribute]
#[\MyExample\MyAttribute]
#[MyAttribute(1234)]
#[MyAttribute(value: 1234)]
/**
 * docblock
 */
#[MyAttribute(MyAttribute::VALUE)]
#[MyAttribute(array("key" => "value"))]
#[MyAttribute(100 + 200)]
class Thing {}

#[MyAttributeA(1234), MyAttributeB(5678)]
class MultipleAttributesGrouped {}

$object = new #[ExampleAttribute] class () { };

#[WithoutArgument]
#[Another\SingleArgument(0)]
#[FewArguments('Hello', 'World')]
#[\My\Attributes\FewArguments("foo", "bar")]
function multipleAttributesMultipleLines() {}

$f2 = #[ExampleAttribute] function () { };

$f3 = #[ExampleAttribute] fn () => 1;

#[WithoutArgument]#[SingleArgument(0)]#[FewArguments('Hello', 'World')]
function multipleAttributesSingleLines() {}

#[ORM\Entity]
class User
{
    #[ORM\Id]#[ORM\Column("integer")]   #[ORM\GeneratedValue]
    private $id;

    #[Assert\Range(["min" => 120, "max" => 180, "minMessage" => "You must be at least {{ limit }}cm tall to enter"])]
    #[ORM\Column(ORM\Column::T_INTEGER)]
    protected $height;
}

#[Attr1, Attr2]
class MultilineAttributeExamples
{
    #[Attr2("foo"),
      Attr2("bar")]
    public function testMultiLine()
    {
    }

    #[
      Attr1("foo"),
      Attr2("bar"),
    ]
    public function testMultiLineDifferentFormatAndTrailingComma()
    {
    }
}

function attributeUsedForTypedParameter(
    #[MyAttr([1, 2])] Type $myParam,
) {}

#[
  ORM\Entity,
  ORM\Table("user")
]
class AnotherMultilineAttributeTest
{
    #[ORM\Id, ORM\Column("integer"), ORM\GeneratedValue]
    private $id;

    #[ORM\Column("string", ORM\Column::UNIQUE)]
    #[Assert\Email(["message" => "text"])] #[Assert\Text(["message" => "text"]),
        Assert\Domain(["message" => "text"]),
        Assert\Id(Assert\Id::REGEX[10]),
    ]
    private $email;
}

function MultiLineAndInlineErrorForSameAttribute(
    #[MultilineAttribute(
    )] $inlineParam
) {}

#[]
function emptyAttributeShouldBeIgnored() {}

#[MyAttribute('value', NewAttribute]
function attributeWithUnclosedParenthesesWillBeIgnored() {}

#[/*comment*/ \MyAttribute /*comment*/ ('param')]
function commentsShouldBeIgnoredWhenRetrievingTheAttributeList() {}

#[\ReturnTypeWillChange, AllowDynamicProperties]
function phpNativeCrossVersionCompatibilityAttributesWillBeIgnored() {}

#[\returnTYPEwillCHANGE, allowdynamicproperties]
function phpNativeCrossVersionCompatibilityAttributesAreTreatedCaseInsensitively() {}

#[\ReturnTypeWillChange, MySecondAttribute]
function phpNativeCrossVersionCompatibilityAttributesWillNotInhibitFlaggingOfSecondAttribute() {}

/*
 * Verify that attributes provided by a limited set of applications are flagged with their own error code.
 * Needs manual check until the test framework would be changed to handle this.
 */
#[CoversClass(Something::class)]
#[RequiresPhp('5.6')]
class PHPUnitAttributesTest extends TestCase {
    #[Before]
    public function setMeUp() {}
}

#[\Doctrine\ORM\Mapping\Entity]
class MyEntity {
    #[\JetBrains\PhpStorm\ArrayShape]
    private $info;

    #[\Override]
    #[Embeddable]
    #[NoReturn]
    public function fooBar(
        #[SensitiveParameter]
        $pswd
    ) {}
}
