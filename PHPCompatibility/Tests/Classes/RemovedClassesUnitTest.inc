<?php

/*
 * These should not be flagged.
 */
$okay = new StdClass();
$okay = new \myNamespace\SWFAction();
$okay = \myNamespace\SWFAction::static_method();
$okay = SomeNamespace\SWFAction::static_method();

$snon = new class extends \My\SWFFill {};
new class extends My\SWFFont {};

class MyClass {
    function SWFSoundTypeHint( myNameSpace\SWFSound $a ) {}
    function SWFSpriteTypeHint( \some\other\SWFSprite $a ) {}
}

try {
} catch (\My\Except\DOMException $e) { // TODO
}

function NsSWFMovieReturnTypeHint( $a ) : \SomeNamespace\SWFMovie {}
function NsNullSWFMovieReturnTypeHint( $a ) : ?SomeNamespace\SWFMovie {}
function NoSWFMovieReturnTypeHint( $a ) : \SomeNamespace\SWFMovie\RealClass {}

/*
 * 1. Verify instantiation without parameters is being flagged.
 * 2. + 3. Verify that instantion with spacing/comments between elements is being flagged.
 * 4. Verify that instation with global namespace indicator is being flagged.
 */
$test = new SWFAction;
$test = new SWFAction ();
$test = new /*comment*/ SWFAction();
$test = new \SWFAction();

class MySWFBitmap extends SWFBitmap {}
echo SWFButton::$property;
echo SWFDisplayItem::method_call();
echo SWFFill::CLASS_CONSTANT;
$anon = new class extends \SWFFont {};

class TypeHints {
    function SWFFontCharTypeHint(SWFFontChar $param) {}
    function SWFGradientTypeHint($a, SWFGradient $b) {}
    function SWFMorphTypeHint(\SWFMorph $param) {}
    function SWFMovieTypeHint( ?SWFMovie $param) {}
    function SWFPrebuiltClipTypeHint(?\SWFPrebuiltClip $param) {}
    function SWFShapeDefault($param = SWFShape::CLASS_CONSTANT) {}
    function SWFSoundDefault($param = \SWFSound::CLASS_CONSTANT) {}
    function SWFSoundInstanceReturnType($param): ?SWFSoundInstance {}
    function SWFSpriteReturnType($param): \SWFSprite {}
}

echo \SWFText::$property;
echo \SWFTextField::method_call();
echo \SWFVideoStream::CLASS_CONSTANT;

$closure = function() : HW_API {};
$closure = function() : \HW_API_Object {};
$closure = function() : ?\HW_API_Attribute {};
$closure = function() : ?HW_API_Error {};
$obj = new HW_API_Content;
echo HW_API_Reason::$property;

$anon = new class extends SQLiteDatabase {};
echo SQLiteResult::numFields();
function SqlLiteTypeHint(SQLiteUnbuffered $param) {}
try {} catch (SQLiteException $e) {}

function XmlRpcServerTypeHint(XmlRpcServer $server) {}
function XmlRpcServerTypeHint(
    $server
): XmlRpcServer {}
