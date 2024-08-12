<?php

// Not our target.
function _() {}

// OK.
class __ {}
interface ____ {}
trait ___ {}
enum _H_ {}

class ClassName {}
interface InterfaceName {}
Trait TraitName {}
enum EnumName {}

// Not OK - deprecated in PHP >= 8.4.
class _ {}
INTERFACE _ {}
trait _ {}
enum /*comment*/_ {}

// This deprecation also applies to namespaced OO structures!
namespace Foo\Bar;

class _ {}
interface _ {}
Trait _ {}
eNuM /*comment*/_ {}

// Live coding, this has to be the last test in the file.
class
