<?php

use SomeThingElse\HW_Api;
use SomeThingElse\HW_API_OBJECT;
use SomeThingElse\hw_api_attribute;
use SomeThingElse\HW_API_Error;
use SomeThingElse\{
    HW_API_Content,
    HW_API_Reason,
};

new HW_API();
HW_API_Object::test();
function testingParamType(HW_API_ATTRIBUTE $param) {}
function testingReturnType(): HW_API_Error {}
$anon = new class extends HW_API_Content {
    public HW_API_Reason $prop;
};
